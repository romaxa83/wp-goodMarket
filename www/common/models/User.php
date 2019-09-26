<?php

namespace common\models;

use backend\modules\users\roles\models\AuthAssignment;
use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
use yii\helpers\Json;

/**
 * User model
 *
 * @property integer $id
 * @property string $username
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $verification_token
 * @property string $email
 * @property string $auth_key
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $password write-only password
 */
class User extends ActiveRecord implements IdentityInterface {

    const STATUS_DELETED = 0;
    const STATUS_INACTIVE = 9;
    const STATUS_ACTIVE = 10;

    private $settings;

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return '{{%user}}';
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors() {
        return [
            TimestampBehavior::className(),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            ['status', 'default', 'value' => self::STATUS_INACTIVE],
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_INACTIVE, self::STATUS_DELETED]],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentity($id) {
        return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentityByAccessToken($token, $type = null) {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username) {
        return static::findOne(['username' => $username, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token) {
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
    public static function isPasswordResetTokenValid($token) {
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
    public function getId() {
        return $this->getPrimaryKey();
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey() {
        return $this->auth_key;
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey) {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password) {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password) {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey() {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken() {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    public function generateEmailVerificationToken() {
        $this->verification_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken() {
        $this->password_reset_token = null;
    }

    public function getSettings($entity = null) {
        if ($entity && $this->settings) {
            if ($this->hasEntity($entity)) {

                return JSON::decode($this->settings)[$entity];
            }
            return null;
        }
        return $this->settings;
    }

    /*
     * метод добавляет настройки пользователю
     * принимает 3 параметра:
     * - сущьность (для которой будет применяться настройка ,к примеру article-для статей)
     * - тип настройки (пример hide-col - прячет колонки)
     * - значение (пример title)
     */
    public function addSetting($entity,$type,$value)
    {
        if($this->settings){
            if($this->hasEntity($entity)){
                if($this->hasType($entity,$type)){
                    $this->addValue($entity,$type,$value);
                }
            }else{
                $this->newSetting($entity,$type,$value);
            }
        } else {
            $this->settings = JSON::encode($this->createSettings($entity,$type,$value));
        }
        $this->update();

    }

    public function removeSetting($entity,$type,$value)
    {
        $this->deleteValue($entity,$type,$value);
        $this->update();
    }

    private function createSettings($entity,$type,$value)
    {
        return [$entity => [$type => [$value]]];
    }

    //проверка наличия сущьности
    private function hasEntity($entity) {
        return array_key_exists($entity, JSON::decode($this->settings));
    }

    public function getFullName()
    {
        return ucfirst($this->first_name) .' '.ucfirst($this->last_name);
    }

    public function getUsername()
    {
        return ucfirst($this->username);
    }

    public function getRole()
    {
        return $this->hasOne(AuthAssignment::className(), ['user_id' => 'id']);
    }

    public function getRoleName() {

        if (isset($this->role->item_name)){
            return $this->role->item_name;
        }else{
            return 'Нет роли';
        }
    }

    public function validatePhone($attribute, $params)
    {
        if (strlen(preg_replace("/[^0-9]/", '', $this->phone)) != 12){
            $this->addError($attribute, 'Введите корректно номер телефона (380*********)');
        }
    }

    private function deleteValue($entity, $type, $value) {
        if (in_array($value, JSON::decode($this->settings)[$entity][$type])) {
            $temp = JSON::decode($this->settings);
            unset($temp[$entity][$type][array_search($value, $temp[$entity][$type])]);
            $temp[$entity][$type] = array_values($temp[$entity][$type]);
            return $this->settings = JSON::encode($temp);
        }
        return $this->settings;
    }

}
