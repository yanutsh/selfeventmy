<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "yii_user".
 *
 * @property int $id
 * @property string $username Имя
 * @property string|null $avatar
 * @property int $work_form_id
 * @property int|null $isexec Исолнитель. 1-да
 * @property int $isconfirm Докум. подтверждены
 * @property int $sex_id
 * @property string|null $birthday
 * @property string $phone
 * @property int|null $phone_confirm 1-подтвержден
 * @property string $email E-mail
 * @property int|null $email_confirm 1-подтвержден
 * @property string $password Пароль
 * @property string|null $auth_key
 * @property string|null $password_reset_token
 * @property int|null $status
 * @property string|null $created_at
 * @property int|null $updated_at
 * @property string|null $verification_token
 * @property bool $push_notif Пуш-уведомления
 * @property bool $show_notif Видимость анкеты
 * @property bool $email_notif Получать письма
 * @property bool $info_notif Информация о сервисе 
 * @property string|null $myself О себе
 * @property float $reyting
 * @property int $blk Удаление/Блокировка
 * @property string|null $blk_date
 *
 * @property Album[] $albums
 * @property Chat[] $chats
 * @property Dialog[] $dialogs
 * @property ExecCategory[] $execCategories
 * @property Order[] $orders
 * @property Review[] $reviews
 * @property Review[] $reviews0
 * @property WorkForm $workForm
 * @property Sex $sex
 * @property UserCategory[] $userCategories
 * @property UserCity[] $userCities
 * @property FsCity[] $cities
 * @property UserDoc[] $userDocs
 * @property UserEducation[] $userEducations
 */
class User2 extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'yii_user';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['username', 'work_form_id', 'phone', 'email', 'password'], 'required'],
            [['work_form_id', 'isexec', 'isconfirm', 'sex_id', 'phone_confirm', 'email_confirm', 'status', 'updated_at', 'blk'], 'integer'],
            [['birthday', 'created_at', 'blk_date'], 'safe'],
            [['push_notif', 'show_notif', 'email_notif', 'info_notif'], 'boolean'],
            [['myself'], 'string'],
            [['reyting'], 'number'],
            [['username', 'avatar', 'email', 'password', 'auth_key', 'password_reset_token', 'verification_token'], 'string', 'max' => 255],
            [['phone'], 'string', 'max' => 25],
            [['email'], 'unique'],
            [['work_form_id'], 'exist', 'skipOnError' => true, 'targetClass' => WorkForm::className(), 'targetAttribute' => ['work_form_id' => 'id']],
            [['sex_id'], 'exist', 'skipOnError' => true, 'targetClass' => Sex::className(), 'targetAttribute' => ['sex_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => 'Имя',
            'avatar' => 'Avatar',
            'work_form_id' => 'Work Form ID',
            'isexec' => 'Исолнитель. 1-да',
            'isconfirm' => 'Докум. подтверждены',
            'sex_id' => 'Sex ID',
            'birthday' => 'Birthday',
            'phone' => 'Phone',
            'phone_confirm' => '1-подтвержден',
            'email' => 'E-mail',
            'email_confirm' => '1-подтвержден',
            'password' => 'Пароль',
            'auth_key' => 'Auth Key',
            'password_reset_token' => 'Password Reset Token',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'verification_token' => 'Verification Token',
            'push_notif' => 'Пуш-уведомления',
            'show_notif' => 'Видимость анкеты',
            'email_notif' => 'Получать письма',
            'info_notif' => 'Информация о сервисе ',
            'myself' => 'О себе',
            'reyting' => 'Reyting',
            'blk' => 'Удаление/Блокировка',
            'blk_date' => 'Blk Date',
        ];
    }

    /**
     * Gets query for [[Albums]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAlbums()
    {
        return $this->hasMany(Album::className(), ['user_id' => 'id']);
    }

    /**
     * Gets query for [[Chats]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getChats()
    {
        return $this->hasMany(Chat::className(), ['exec_id' => 'id']);
    }

    /**
     * Gets query for [[Dialogs]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDialogs()
    {
        return $this->hasMany(Dialog::className(), ['user_id' => 'id']);
    }

    /**
     * Gets query for [[ExecCategories]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getExecCategories()
    {
        return $this->hasMany(ExecCategory::className(), ['user_id' => 'id']);
    }

    /**
     * Gets query for [[Orders]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOrders()
    {
        return $this->hasMany(Order::className(), ['user_id' => 'id']);
    }

    /**
     * Gets query for [[Reviews]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getReviews()
    {
        return $this->hasMany(Review::className(), ['from_user_id' => 'id']);
    }

    /**
     * Gets query for [[Reviews0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getReviews0()
    {
        return $this->hasMany(Review::className(), ['for_user_id' => 'id']);
    }

    /**
     * Gets query for [[WorkForm]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getWorkForm()
    {
        return $this->hasOne(WorkForm::className(), ['id' => 'work_form_id']);
    }

    /**
     * Gets query for [[Sex]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSex()
    {
        return $this->hasOne(Sex::className(), ['id' => 'sex_id']);
    }

    /**
     * Gets query for [[UserCategories]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUserCategories()
    {
        return $this->hasMany(UserCategory::className(), ['user_id' => 'id']);
    }

    /**
     * Gets query for [[UserCities]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUserCities()
    {
        return $this->hasMany(UserCity::className(), ['user_id' => 'id']);
    }

    /**
     * Gets query for [[Cities]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCities()
    {
        return $this->hasMany(FsCity::className(), ['id' => 'city_id'])->viaTable('yii_user_city', ['user_id' => 'id']);
    }

    /**
     * Gets query for [[UserDocs]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUserDocs()
    {
        return $this->hasMany(UserDoc::className(), ['user_id' => 'id']);
    }

    /**
     * Gets query for [[UserEducations]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUserEducations()
    {
        return $this->hasMany(UserEducation::className(), ['user_id' => 'id']);
    }
}
