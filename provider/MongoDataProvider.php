<?php
namespace nstdio\yii2notymo\provider;

use yii\mongodb\Query;

/**
 * Class MongoDataProvider
 *
 * @package nstdio\yii2notymo\provider
 * @author  Edgar Asatryan <nstdio@gmail.com>
 */
class MongoDataProvider extends DataProvider
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
}
