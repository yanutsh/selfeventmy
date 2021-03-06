<?php
namespace app\models;

use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
use app\models\City;


/**
 * @property int $id
 * @property string|null $photo
 * @property int $work_form_id
 * @property string $username Имя
 * @property int $sex_id
 * @property string|null $birthday
 * @property string $phone
 * @property int $phone_confirm 1-подтвержден
 * @property string $email E-mail
 * @property int $email_confirm 1-подтвержден
 * @property int $isexec
 * @property int $isprepayment
 * @property string $password Пароль
 * @property string|null $auth_key
 * @property string|null $password_reset_token
 * @property int $status
 * @property int $created_at
 * @property int $updated_at
 * @property string|null $verification_token
 *
 * @property Album[] $albums
 * @property StarRating $starRating
 * @property Chat[] $chats
 * @property Dialog[] $dialogs
 * @property ExecCategory[] $execCategories
 * @property Order[] $orders
 * @property Review[] $reviews
 * @property WorkForm $workForm
 * @property Sex $sex
 * @property UserCategory[] $userCategories
 * @property UserCity[] $userCities  // города предпринимательской деятельности
 * @property FsCity[] $cities
 * @property UserDoc[] $userDocs
  */

class User extends ActiveRecord implements IdentityInterface
{
    const STATUS_DELETED = 0;
    const STATUS_INACTIVE = 9;
    const STATUS_ACTIVE = 10;


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
            //['status', 'default', 'value' => self::STATUS_INACTIVE],
            ['status', 'default', 'value' => self::STATUS_ACTIVE],
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_INACTIVE, self::STATUS_DELETED]],            
        ];
    }

     public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => 'Контактное лицо',
            'myself' => 'О себе',            
            'blk'=> 'Признак блокировки аккаунта',
        ];
    }

    public function getDialogs()
    {
        return $this->hasMany(Dialog::className(), ['user_id' => 'id']);
    }

    public function getAlbums()
    {
        return $this->hasMany(Album::className(), ['user_id' => 'id']);
    }

    public function getChats()
    {
        return $this->hasMany(Chat::className(), ['exec_id' => 'id']);
    }

    public function getOrders()
    {
        return $this->hasMany(Order::className(), ['user_id' => 'id']);
    }

    public function getComplains()
    {
        return $this->hasMany(Complain::className(), ['for_user_id' => 'id']);
    }

    public function getReviews()
    {
        return $this->hasMany(Review::className(), ['from_user_id' => 'id']);
    }

    public function getCategory()
    {
         return $this->hasMany(Category::className(), ['id' => 'category_id'])->viaTable('yii_user_category', ['user_id' => 'id']);
    }

    public function getSubcategory()
    {
         return $this->hasMany(Subcategory::className(), ['id' => 'subcategory_id'])->viaTable('yii_user_category', ['user_id' => 'id']);
    }
    
    public function getWorkForm()
    {
        return $this->hasOne(WorkForm::className(), ['id' => 'work_form_id']);
    }

    public function getSex()
    {
        return $this->hasOne(Sex::className(), ['id' => 'sex_id']);
    }

     public function getUserAbonements()
    {
        return $this->hasMany(UserAbonement::className(), ['user_id' => 'id']);
    }
    
    public function getUserCities()
    {
        return $this->hasMany(UserCity::className(), ['user_id' => 'id']);
    }

    public function getCities()
    {
        return $this->hasMany(City::className(), ['id' => 'city_id'])->viaTable('yii_user_city', ['user_id' => 'id']);
    }

    public function getUserCategories()
    {
        return $this->hasMany(UserCategory::className(), ['user_id' => 'id']);
    }

    public function getUserDocs()
    {
        return $this->hasMany(UserDoc::className(), ['user_id' => 'id']);
    }

    public function getUserEducations()
    {
        return $this->hasMany(UserEducation::className(), ['user_id' => 'id']);
    }

    public function getStarRating()
    {
        return $this->hasOne(StarRating::className(), ['rating_id' => 'id']);
    }

    public function getVisitLogs()
    {
        return $this->hasMany(VisitLog::className(), ['user_id' => 'id']);
    }
    
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username]); //, 'status' => self::STATUS_ACTIVE]);
    }

    /* Finds user by email */
    public static function findByEmail($email)
    {
        return static::findOne(['email' => $email]); //, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
            'password_reset_token' => $token,
            'status' => self::STATUS_ACTIVE,
        ]);
    }

    /**
     * Finds user by verification email token
     *
     * @param string $token verify email token
     * @return static|null
     */
    public static function findByVerificationToken($token) {
        return static::findOne([
            'verification_token' => $token,
            'status' => self::STATUS_INACTIVE
        ]);
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return bool
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }

        $timestamp = (int) substr($token, strrpos($token, '_') + 1);
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        return $timestamp + $expire >= time();
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Generates new token for email verification
     */
    public function generateEmailVerificationToken()
    {
        $this->verification_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }
}
