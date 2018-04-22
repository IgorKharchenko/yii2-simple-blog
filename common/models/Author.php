<?php

namespace common\models;

use common\components\db\ActiveRecord;
use yii\base\InvalidArgumentException;
use yii\web\IdentityInterface;

/**
 * Class Author
 * Автор поста.
 *
 * @property integer $id
 * @property string  $username
 * @property string  $password_hash
 * @property string  $password_reset_token
 * @property string  $email
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 */
class Author extends ActiveRecord implements IdentityInterface
{
    /**
     * {@inheritdoc}
     */
    public static function tableName ()
    {
        return '{{%author}}';
    }

    /**
     * Устанавливает токен сброса пароля на рандомный, если он не установлен.
     *
     * @param bool $insert
     *
     * @return bool
     *
     * @throws \yii\base\Exception в случае проблем с генерацией рандомной строки.
     */
    public function beforeSave ($insert)
    {
        if (empty($this->password_reset_token)) {
            $this->password_reset_token = \Yii::$app->getSecurity()->generateRandomString(32);
        }

        return parent::beforeSave($insert);
    }

    /**
     * {@inheritdoc}
     */
    public function rules (): array
    {
        return [
            [
                [
                    'full_name',
                    'username',
                    'password_hash',
                    'email',
                ],
                'required',
            ],
            [
                [
                    'full_name',
                    'username',
                    'password_hash',
                    'email',
                ],
                'string',
            ],
            [
                ['full_name'],
                'string',
                'max'     => 64,
                'message' => 'ФИО не дожно быть длиннее 64 символов.',
            ],
            [
                ['username'],
                'match',
                'pattern' => '/^[a-zA-Z\d._-]+$/',
                'message' => 'Никнейм может содержать только латинские символы, цифры и следующие символы: ._-',
            ],
            [
                ['username'],
                'string',
                'max'     => 24,
                'message' => 'Никнейм не должен быть длиннее 24 символов.',
            ],
            [
                [
                    'password_hash',
                    'password_reset_token',
                ],
                'string',
                'max' => 64,
            ],
            [
                ['email'],
                'unique',
                'message' => 'Этот адрес электронной почты уже используется.',
            ],
            [
                ['username'],
                'unique',
                'message' => 'Этот никнейм уже используется',
            ],
            [
                ['password_reset_token'],
                'unique',
            ],
            [
                ['email'],
                'email',
                'message' => 'Проверьте правильность введённого Вами адреса электронной почты.',
            ],
            [
                [
                    'created_at',
                    'updated_at',
                ],
                'integer',
            ],
            [
                [
                    'created_at',
                    'updated_at',
                ],
                'safe',
            ],
        ];
    }

    /**
     * Устанавливает пользователю пароль.
     *
     * @param string $password новый пароль.
     *
     * @return bool true если пароль установлен,
     *              false в случае, если пароль невалидный.
     */
    public function setPassword (string $password): bool
    {
        if (!$this->isPasswordValid($password)) {
            return false;
        }

        $this->password_hash = \Yii::$app->getSecurity()->generatePasswordHash($password);

        return true;
    }

    /**
     * Проверяет, валиден ли пароль и соответствует ли он хешу.
     *
     * @param string $password пароль.
     *
     * @return bool true если пароль установлен,
     *              false в случае, если пароль невалидный.
     */
    public function validatePassword (string $password)
    {
        if (!$this->isPasswordValid($password)) {
            return false;
        }

        return \Yii::$app->getSecurity()->validatePassword($password, $this->password_hash);
    }

    /**
     * Проверяет, валиден ли пароль.
     *
     * @param string $password пароль.
     *
     * @return bool true если пароль установлен,
     *              false в случае, если пароль невалидный.
     */
    private function isPasswordValid (string $password): bool
    {
        $strlen = strlen($password);
        if ($strlen < 7 || $strlen > 32) {
            return false;
        }

        $pattern = '/^[a-zA-Z\d^*._-]+$/';
        if (!preg_match_all($pattern, $password)) {
            return false;
        }

        return true;
    }

    /**
     * Возвращает id пользователя.
     *
     * @return int|null
     */
    public function getId() : ?int
    {
        return $this->id;
    }

    /**
     * Возвращает пользователя по id.
     *
     * @param int $id id пользователя.
     *
     * @return null|static
     *
     * @throws InvalidArgumentException в случае, если id невалидный.
     */
    public static function findIdentity($id)
    {
        if (!is_numeric($id) || $id <= 0) {
            throw new InvalidArgumentException('id должен быть числом больше 0.');
        }

        return self::findOne(['id' => $id]);
    }

    public function validateAuthKey ($authKey)
    {
        throw new InvalidArgumentException(__METHOD__ . ' is not implemented');
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new InvalidArgumentException(__METHOD__ . ' is not implemented');
    }

    public function getAuthKey()
    {
        throw new InvalidArgumentException(__METHOD__ . ' is not implemented');
    }
}
