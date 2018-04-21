<?php

namespace common\components\test;

use yii\base\InvalidArgumentException;

/**
 * Class DbHelper
 * В момент _bootstrap-а компонент Yii ещё не инициализирован,
 * поэтому надо вручную через PDO установить максимальное количество соединений к БД,
 * взяв соответствующий конфиг из папки config.
 *
 * @package common\tests
 */
class DbHelper
{
    /**
     * Увеличиваем максимальное количество одновременных соединений к СУБД,
     * чтобы она не загнулась при выполнении тестов.
     * Я не использовал здесь Yii::$app->db, потому что в момент выполнения _bootstrap
     * Yii ещё не инициализировался.
     * Если СУБД изменится - то переписать запрос под нужную.
     *
     * @param int $maxConnectionsAmount максимальное количество одновременных соединений к СУБД.
     *
     * @throws InvalidArgumentException в случае, если количество соединений <= 0.
     */
    public static function setMaxConnectionsToDb (int $maxConnectionsAmount)
    {
        $db = static::getDb();

        if ($maxConnectionsAmount <= 0) {
            throw new InvalidArgumentException('Max connections amount must be greater than 0.');
        }

        $stmt = $db->prepare('SET GLOBAL max_connections = :conn');
        $stmt->bindParam(':conn', $maxConnectionsAmount, \PDO::PARAM_INT);
        $stmt->execute();
    }

    /**
     * Устанавливает таймаут соединения с СУБД.
     * Нужно это для того, чтобы СУБД не падала с ошибкой "MySQL server has gone away".
     * Если СУБД изменится - то переписать запрос под нужную.
     *
     * @param int $waitTimeout
     *
     * @throws InvalidArgumentException в случае, если таймаут <= 0.
     */
    public static function setWaitTimeoutToDb (int $waitTimeout)
    {
        $db = static::getDb();

        if ($waitTimeout <= 0) {
            throw new InvalidArgumentException('Wait timeout must be greater than 0.');
        }

        $stmt = $db->prepare('SET GLOBAL wait_timeout = :timeout');
        $stmt->bindParam(':timeout', $waitTimeout, \PDO::PARAM_INT);
        $stmt->execute();
    }

    /**
     * Инициализирует PDO локальным конфигом test-local.php.
     *
     * @return \PDO
     */
    private static function getDb (): \PDO
    {
        $config = require __DIR__ . '/../../config/test-local.php';
        $dbConfig = $config['components']['db'];

        return new \PDO($dbConfig['dsn'], $dbConfig['username'], $dbConfig['password'], [
            'charset' => $dbConfig['charset'],
        ]);
    }
}