<?php
use nstdio\yii2notymo\tests\fixtures\DeviceTokenFixture;

$data = [];
$security = Yii::$app->getSecurity();
$range = range(0, 10);

foreach ($range as $i) {
    $nulls = mt_rand() % 13 === 0;
    $data[] = [
        'user_id'    => $i + 1,
        'apns_token' => $nulls ? DeviceTokenFixture::apnsTokenGenerator() : null,
        'gcm_token'  => $nulls ? $security->generateRandomString() : null,
    ];
}

return $data;
