<?php
namespace nstdio\yii2notymo\tests\fixtures;

use yii\test\ActiveFixture;

/**
 * Class DeviceToken
 *
 * @package nstdio\yii2notymo\tests\fixtures
 * @author  Edgar Asatryan <nstdio@gmail.com>
 */
class DeviceTokenFixture extends ActiveFixture
{
    public $tableName = 'device_token';

    private static $alphabet = "0123456789abcdef";
    private static $lastPos = 15;

    public static function apnsTokenGenerator()
    {
        $token = '';
        for ($i = 0; $i < 8; $i++) {
            for ($j = 0; $j < 8; $j++) {
                $token .= self::$alphabet[mt_rand(0, self::$lastPos)];
            }
            $token .= ' ';
        }

        return "<" . rtrim($token) . ">";
    }
}