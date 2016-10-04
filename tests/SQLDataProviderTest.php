<?php
namespace nstdio\yii2notymo\tests;


use nstdio\yii2notymo\provider\SQLDataProvider;
use Yii;

class SQLDataProviderTest extends TestCase
{
    public function setUp()
    {
        $this->mockApplication();

        $this->configureDatabase();

        Yii::$app->runAction('migrate');
        Yii::$app->runAction('fixture', ['*']);
    }

    public function tearDown()
    {
        $this->migrateDown();

        parent::tearDown();
    }

    /**
     * @expectedException \yii\base\InvalidConfigException
     */
    public function testWithNoDb()
    {
        Yii::$app->clear('db');

        new SQLDataProvider();
    }

    /**
     * @expectedException \yii\base\InvalidConfigException
     *
     */
    public function testNoTable()
    {
        new SQLDataProvider([
            'table' => null,
        ]);
    }

    /**
     * @expectedException \yii\base\InvalidConfigException
     */
    public function testNoIdentifier()
    {
        new SQLDataProvider([
            'table' => 'tbl',
        ]);
    }

    /**
     * @expectedException \yii\base\InvalidConfigException
     */
    public function testNoApnsAndGcm()
    {
        new SQLDataProvider([
            'table'      => 'tbl',
            'identifier' => 'user_id',
        ]);
    }

    public function testSuccessDb()
    {
        $dataProvider = new SQLDataProvider([
            'table'      => Yii::$app->params['table'],
            'identifier' => Yii::$app->params['identifier'],
            'apns'       => Yii::$app->params['apns'],
            'gcm'        => Yii::$app->params['gcm'],
        ]);

        $userIds = range(1, 5);
        $data = $dataProvider->getTokens($userIds);

        self::assertCount(count($userIds), $data);

        foreach ($data as $item) {
            self::assertArrayHasKey(Yii::$app->params['apns'], $item);
            self::assertArrayHasKey(Yii::$app->params['gcm'], $item);
        }
    }

    public function testOneFieldName()
    {
        $apnsTokenFieldName = 'apns_token';

        $dataProvider = new SQLDataProvider([
            'table'      => Yii::$app->params['table'],
            'identifier' => Yii::$app->params['identifier'],
            'apns'       => Yii::$app->params['apns'],
        ]);

        $userIds = range(1, 5);
        $data = $dataProvider->getTokens($userIds);

        foreach ($data as $item) {
            self::assertArrayHasKey($apnsTokenFieldName, $item);
            self::assertCount(1, $item);
        }
    }
}
