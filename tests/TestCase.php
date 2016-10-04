<?php

namespace nstdio\yii2notymo\tests;

use Yii;
use yii\di\Container;
use yii\helpers\ArrayHelper;

/**
 * This is the base class for all yii framework unit tests.
 */
abstract class TestCase extends \PHPUnit_Framework_TestCase
{
    /**
     * Clean up after test.
     * By default the application created with [[mockApplication]] will be destroyed.
     */
    protected function tearDown()
    {
        parent::tearDown();
        $this->destroyApplication();
    }

    /**
     * Destroys application in Yii::$app by setting it to null.
     */
    protected function destroyApplication()
    {
        Yii::$app = null;
        Yii::$container = new Container();
    }

    /**
     * Populates Yii::$app with a new application
     * The application will be destroyed on tearDown() automatically.
     *
     * @param array  $config   The application configuration, if needed
     * @param string $appClass name of the application class to create
     */
    protected function mockApplication($config = [], $appClass = '\yii\console\Application')
    {
        new $appClass(ArrayHelper::merge([
            'id'            => 'testapp',
            'basePath'      => __DIR__,
            'vendorPath'    => dirname(__DIR__) . '/vendor',
            'controllerMap' => [
                'fixture' => [
                    'class'       => 'yii\console\controllers\FixtureController',
                    'namespace'   => 'nstdio\\yii2notymo\\tests\\fixtures',
                    'interactive' => false,
                ],
                'migrate' => [
                    'class'       => 'yii\console\controllers\MigrateController',
                    'interactive' => false,
                ],
            ],
        ], $config));

        $this->loadParams();
    }

    protected function migrateDown()
    {
        if (Yii::$app->get('db', false) === null || Yii::$app->getDb()->isActive === false) {
            $this->configureDatabase();
        }

        Yii::$app->runAction('migrate/down');
    }

    protected function configureDatabase()
    {
        $dbConfig = require "config/db.php";

        Yii::$app->set('db', $dbConfig);

        try {
            Yii::$app->getDb()->open();
        } catch (\Exception $e) {
            self::assertTrue(false, "Cannot open connection. Please create '" . Yii::getAlias("@testDbName") . "' database.");
        }
    }

    protected function loadParams()
    {
        if (Yii::$app !== null) {
            Yii::$app->params = require("config/params.php");
        }
    }
}