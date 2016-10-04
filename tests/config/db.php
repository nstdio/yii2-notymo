<?php
return [
    'class'    => 'yii\db\Connection',
    'dsn'      => 'mysql:host=localhost;dbname=' . Yii::getAlias("@testDbName"),
    'username' => 'root',
    'password' => '',
    'charset'  => 'utf8',
];
