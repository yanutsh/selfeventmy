<?php
/**
 * @link https://github.com/himiklab/yii2-ipgeobase-component
 * @copyright Copyright (c) 2014-2018 HimikLab
 * @license http://opensource.org/licenses/MIT MIT
 */

namespace himiklab\ipgeobase;

use yii\base\BaseObject;

/**
 * Информация о конкретном IP-адресе.
 *
 * @author HimikLab
 * @package himiklab\ipgeobase
 */
class IpData extends BaseObject
{
    public $ip;
    public $country;
    public $city;
    public $region;
    public $lat;
    public $lng;

    public function __construct(array $data)
    {
        parent::__construct();

        foreach ($data as $fieldName => $fieldValue) {
            if (property_exists($this, $fieldName)) {
                $this->$fieldName = $fieldValue;
            }
        }
    }
}
