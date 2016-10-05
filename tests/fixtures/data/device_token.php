<?php
use nstdio\yii2notymo\tests\fixtures\DeviceTokenFixture;

$data = [];

foreach (range(0, 10) as $i) {
    $data[] = [
        'user_id'    => $i + 1,
        'apns_token' => mt_rand() % 7 === 0 ? DeviceTokenFixture::apnsTokenGenerator() : null,
        'gcm_token'  => mt_rand() % 3 === 0 ? Yii::$app->getSecurity()->generateRandomString() : null,
    ];
}

return $data;
