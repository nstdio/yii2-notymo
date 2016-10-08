# yii2-notymo [![Build Status](https://travis-ci.org/nstdio/yii2-notymo.svg?branch=master)](https://travis-ci.org/nstdio/yii2-notymo) [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/nstdio/yii2-notymo/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/nstdio/yii2-notymo/?branch=master) [![Code Coverage](https://scrutinizer-ci.com/g/nstdio/yii2-notymo/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/nstdio/yii2-notymo/?branch=master)
The iOS and Android push notification extension for Yii2.

# Installation

The suggested installation method is via [composer](https://getcomposer.org/):
```
$ composer require nstdio/yii2-notymo: "dev-master"
```
or add
```
"nstdio/yii2-notymo": "dev-master"
```
to the `require` section of your `composer.json` file.

# Usage

## Defining as application component
```php
// web.php or console.php
'component' => [
    
    // ...
    
    'notymo'  => [
        'class'        => 'nstdio\yii2notymo\PushNotification',
        'push'         => [
            // If you dоn't want to use one of the services we can just skip them loading.
            // It's obvious that the skipped service is not necessary to configure.
            // 'skipApns' => true,
            // 'skipGcm'  => true,
            'apns' => [
                'live'        => true, // Whether to use live credentials.
                'cert'        => 'path/to/apns_live_cert.pem',
                'sandboxCert' => 'path/to/apns_sandbox_cert.pem',
            ],
            'gcm'  => [
                'apiKey' => 'api_key' // Here goes GCM Service API key. 
            ],
        ],
        'dataProvider' => [
            'class'      => 'nstdio\yii2notymo\provider\SQLDataProvider',
            'table'      => 'device_token', // The table from which the data will be obtained.
            'identifier' => 'user_id', // The identifier that defines the criteria for what data will be obtained. In this case, it is the column name from the table.
            'apns'       => 'apns_token', // The column name for APNS device tokens.
            'gcm'        => 'gcm_token', // The column name for GCM device tokens.
        ],
    ],
],

// For example SiteController.php
use nstdio\notymo\Message;
// ...

$userIds = [1, 2, 3, 4, 5];

/** @var \nstdio\yii2notymo\PushNotification $push */
$push = Yii::$app->notymo;

$msg = new Message();
$msg->setMessage("Test msg.");

$push->send($msg, $userIds); // Message will be sent to mentioned users.
```