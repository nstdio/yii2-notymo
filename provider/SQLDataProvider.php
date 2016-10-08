<?php
namespace nstdio\yii2notymo\provider;

use Yii;
use yii\base\InvalidConfigException;
use yii\db\Query;

/**
 * Class SQLDataProvider
 *
 * @package nstdio\notymo
 * @author  Edgar Asatryan <nstdio@gmail.com>
 */
class SQLDataProvider extends DataProvider
{
    /**
     * DataProvider constructor.
     *
     * @param array $config
     *
     */
    public function __construct($config = [])
    {
        parent::__construct($config);

        $this->query = new Query();
    }

    public function init()
    {
        if (Yii::$app->get('db', false) === null) {
            throw new InvalidConfigException("You must configure database to use this provider.");
        }

        parent::init();
    }
}
