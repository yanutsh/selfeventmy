<?php
/**
 * @link https://github.com/himiklab/yii2-ipgeobase-component
 * @copyright Copyright (c) 2014-2018 HimikLab
 * @license http://opensource.org/licenses/MIT MIT
 */

namespace himiklab\ipgeobase;

use Yii;
use yii\base\Component;
use yii\base\Exception;
use yii\db\Query;
use yii\httpclient\Client;

/**
 * Компонент для работы с базой IP-адресов сайта IpGeoBase.ru,
 * он Реализует поиск географического местонахождения IP-адреса,
 * выделенного RIPE локальным интернет-реестрам (LIR-ам).
 * Для Российской Федерации и Украины с точностью до города.
 *
 * @author HimikLab
 * @package himiklab\ipgeobase
 */
class IpGeoBase extends Component
{
    const XML_URL = 'http://ipgeobase.ru:7020/geo?ip=';
    const ARCHIVE_URL = 'http://ipgeobase.ru/files/db/Main/geo_files.zip';
    const ARCHIVE_IPS_FILE = 'cidr_optim.txt';
    const ARCHIVE_CITIES_FILE = 'cities.txt';

    const DB_IP_INSERTING_ROWS = 20000; // максимальный размер (строки) пакета для INSERT запроса
    const DB_IP_TABLE_NAME = '{{%geobase_ip}}';
    const DB_CITY_TABLE_NAME = '{{%geobase_city}}';
    const DB_REGION_TABLE_NAME = '{{%geobase_region}}';

    /** @var bool $useLocalDB Использовать ли локальную базу данных */
    public $useLocalDB = false;

    /** @var string Имя Вашего компонента подключения к БД. */
    public $db = 'db';

    /**
     * Определение географического положения по IP-адресу.
     * @param string $ip
     * @param boolean $asArray
     * @return array|IpData ('ip', 'country', 'city', 'region', 'lat', 'lng') или false если ничего не найдено.
     * @throws Exception
     */
    public function getLocation($ip, $asArray = true)
    {
        if ($this->useLocalDB) {
            $ipDataArray = $this->fromDB($ip) + ['ip' => $ip];
        } else {
            $ipDataArray = $this->fromSite($ip) + ['ip' => $ip];
        }

        if ($asArray) {
            return $ipDataArray;
        }

        return new IpData($ipDataArray);
    }

    /**
     * Тест скорости получения данных из БД.
     * @param integer $iterations
     * @return float IP/second
     * @throws Exception
     */
    public function speedTest($iterations)
    {
        $ips = [];
        for ($i = 0; $i < $iterations; ++$i) {
            $ips[] = mt_rand(0, 255) . '.' . mt_rand(0, 255) . '.' . mt_rand(0, 255) . '.' . mt_rand(0, 255);
        }

        $begin = microtime(true);
        foreach ($ips as $ip) {
            $this->getLocation($ip);
        }
        $time = microtime(true) - $begin;

        if ($time !== 0 && $iterations !== 0) {
            return $iterations / $time;
        }

        return 0.0;
    }

    /**
     * Метод создаёт или обновляет локальную базу IP-адресов.
     * @throws Exception
     */
    public function updateDB()
    {
        if (($fileName = $this->getArchive()) === false) {
            throw new Exception('Ошибка загрузки архива.');
        }
        $zip = new \ZipArchive;
        if ($zip->open($fileName) !== true) {
            @unlink($fileName);
            throw new Exception('Ошибка распаковки.');
        }

        $this->generateIpTable($zip);
        $this->generateCityTables($zip);
        $zip->close();
        @unlink($fileName);
    }

    /**
     * @param string $ip
     * @return array
     * @throws Exception
     */
    protected function fromSite($ip)
    {
        $xmlData = $this->getRemoteContent(self::XML_URL . urlencode($ip));
        $ipData = (new \SimpleXMLElement($xmlData))->ip;
        if (isset($ip->message)) {
            return [];
        }

        return [
            'country' => (string)$ipData->country,
            'city' => isset($ipData->city) ? (string)$ipData->city : null,
            'region' => isset($ipData->region) ? (string)$ipData->region : null,
            'lat' => isset($ipData->lat) ? (string)$ipData->lat : null,
            'lng' => isset($ipData->lng) ? (string)$ipData->lng : null
        ];
    }

    /**
     * @param string $ip
     * @return array
     */
    protected function fromDB($ip)
    {
        $dbIpTableName = self::DB_IP_TABLE_NAME;
        $dbCityTableName = self::DB_CITY_TABLE_NAME;
        $dbRegionTableName = self::DB_REGION_TABLE_NAME;
        $ip = ip2long($ip);

        $result = (new Query())
            ->select([
                't_ip.country_code AS country', 't_region.name AS region', 't_city.name AS city',
                't_city.latitude AS lat', 't_city.longitude AS lng'
            ])
            ->from(['t_ip' => (new Query())
                ->from($dbIpTableName)
                ->where(['<=', 'ip_begin', $ip])
                ->orderBy(['ip_begin' => SORT_DESC])
            ])
            ->leftJoin(['t_city' => $dbCityTableName], 't_city.id = t_ip.city_id')
            ->leftJoin(['t_region' => $dbRegionTableName], 't_region.id = t_city.region_id')
            ->where(['>=', 't_ip.ip_end', $ip])
            ->one();

        if ($result !== false) {
            return $result;
        }

        return [];
    }

    /**
     * Метод производит заполнение таблиц городов и регионов используя
     * данные из файла self::ARCHIVE_CITIES.
     * @param $zip \ZipArchive
     * @throws \yii\db\Exception
     */
    protected function generateCityTables($zip)
    {
        $citiesArray = explode("\n", $zip->getFromName(self::ARCHIVE_CITIES_FILE));
        array_pop($citiesArray); // пустая строка

        $cities = [];
        $uniqueRegions = [];
        $regionId = 1;
        foreach ($citiesArray as $city) {
            $row = explode("\t", $city);

            $regionName = iconv('WINDOWS-1251', 'UTF-8', $row[2]);
            if (!isset($uniqueRegions[$regionName])) {
                // новый регион
                $uniqueRegions[$regionName] = $regionId++;
            }

            $cities[$row[0]][0] = $row[0]; // id
            $cities[$row[0]][1] = iconv('WINDOWS-1251', 'UTF-8', $row[1]); // name
            $cities[$row[0]][2] = $uniqueRegions[$regionName]; // region_id
            $cities[$row[0]][3] = $row[4]; // latitude
            $cities[$row[0]][4] = $row[5]; // longitude
        }

        // города
        Yii::$app->{$this->db}->createCommand()->truncateTable(self::DB_CITY_TABLE_NAME)->execute();
        Yii::$app->{$this->db}->createCommand()->batchInsert(
            self::DB_CITY_TABLE_NAME,
            ['id', 'name', 'region_id', 'latitude', 'longitude'],
            $cities
        )->execute();

        // регионы
        $regions = [];
        foreach ($uniqueRegions as $regionUniqName => $regionUniqId) {
            $regions[] = [$regionUniqId, $regionUniqName];
        }
        Yii::$app->{$this->db}->createCommand()->truncateTable(self::DB_REGION_TABLE_NAME)->execute();
        Yii::$app->{$this->db}->createCommand()->batchInsert(
            self::DB_REGION_TABLE_NAME,
            ['id', 'name'],
            $regions
        )->execute();
    }

    /**
     * Метод производит заполнение таблиц IP-адресов используя
     * данные из файла self::ARCHIVE_IPS.
     * @param $zip \ZipArchive
     * @throws \yii\db\Exception
     */
    protected function generateIpTable($zip)
    {
        $ipsArray = explode("\n", $zip->getFromName(self::ARCHIVE_IPS_FILE));
        array_pop($ipsArray); // пустая строка

        $i = 0;
        $values = [];
        Yii::$app->{$this->db}->createCommand()->truncateTable(self::DB_IP_TABLE_NAME)->execute();
        foreach ($ipsArray as $ip) {
            $row = explode("\t", $ip);
            $values[++$i] = [$row[0], $row[1], $row[3], ($row[4] !== '-' ? $row[4] : 0)];

            if ($i === self::DB_IP_INSERTING_ROWS) {
                Yii::$app->{$this->db}->createCommand()->batchInsert(
                    self::DB_IP_TABLE_NAME,
                    ['ip_begin', 'ip_end', 'country_code', 'city_id'],
                    $values
                )->execute();

                $i = 0;
                $values = [];
                continue;
            }
        }

        // оставшиеся строки не вошедшие в пакеты
        Yii::$app->{$this->db}->createCommand()->batchInsert(
            self::DB_IP_TABLE_NAME,
            ['ip_begin', 'ip_end', 'country_code', 'city_id'],
            $values
        )->execute();
    }

    /**
     * Метод загружает архив с данными с адреса self::ARCHIVE_URL.
     * @return boolean|string путь к загруженному файлу или false если файл загрузить не удалось.
     * @throws Exception
     */
    protected function getArchive()
    {
        if (($fileData = $this->getRemoteContent(self::ARCHIVE_URL)) === false) {
            return false;
        }

        $fileName = Yii::getAlias('@runtime') . DIRECTORY_SEPARATOR . substr(strrchr(self::ARCHIVE_URL, '/'), 1);
        if (@file_put_contents($fileName, $fileData) !== false) {
            return $fileName;
        }

        return false;
    }

    /**
     * Метод возвращает содержимое документа полученного по указанному url.
     * @param string $url
     * @return string
     * @throws Exception
     */
    protected function getRemoteContent($url)
    {
        $response = (new Client())
            ->createRequest()
            ->setMethod('GET')
            ->setUrl($url)
            ->send();
        if (!$response->isOk) {
            throw new Exception("URL {$url} doesn't exist");
        }

        return $response->content;
    }
}
