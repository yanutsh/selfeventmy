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
 * @property string $password Пароль
 * @property string|null $auth_key
 * @property string|null $password_reset_token
 * @property int $status
 * @property int $created_at
 * @property int $updated_at
 * @property string|null $verification_token
 *
 * @property Order[] $orders
 * @property WorkForm $workForm
 * @property Sex $sex
 * @property UserCity[] $userCities  // города предпринимательской деятельности
 * @property FsCity[] $cities        // города
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
            'reyting' => 'Рейтинг',
            'blk'=> 'Признак блокировки аккаунта',
        ];
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

    public function getCategory()
    {
         return $this->hasMany(Category::className(), ['id' => 'category_id'])->viaTable('yii_exec_category', ['user_id' => 'id']);
    }

    /** Связь с таблицей WorkForm
     * Gets query for [[WorkForm]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getWorkForm()
    {
        return $this->hasOne(WorkForm::className(), ['id' => 'work_form_id']);
    }

    /** Связь с таблицей Sex
     * Gets query for [[Sex]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSex()
    {
        return $this->hasOne(Sex::className(), ['id' => 'sex_id']);
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
        return $this->hasMany(City::className(), ['id' => 'city_id'])->viaTable('yii_user_city', ['user_id' => 'id']);
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
     * {@inheritdoc}
     */
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
