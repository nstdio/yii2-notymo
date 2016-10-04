<?php
namespace nstdio\yii2notymo\provider;

use Yii;
use yii\base\InvalidConfigException;
use yii\db\ActiveQuery;

/**
 * Class SQLDataProvider
 *
 * @package nstdio\notymo
 * @author  Edgar Asatryan <nstdio@gmail.com>
 */
class SQLDataProvider extends DataProvider
{
    /**
     * @var ActiveQuery
     */
    protected $query;

    /**
     * DataProvider constructor.
     *
     * @param array $config
     *
     */
    public function __construct($config = [])
    {
        parent::__construct($config);

        $this->query = new ActiveQuery('yii\db\ActiveRecord');
    }

    public function init()
    {
        if (Yii::$app->get('db', false) === null) {
            throw new InvalidConfigException("You must configure database to use this provider.");
        }

        parent::init();
    }

    /**
     * @param $userId
     *
     * @return array|\yii\db\ActiveRecord[]
     */
    public function getTokens($userId)
    {
        $this->query->from($this->table);

        $this->addSelectIfNotNull($this->apns);
        $this->addSelectIfNotNull($this->gcm);

        return $this->query
            ->andWhere([$this->identifier => $userId])
            ->asArray()
            ->all(Yii::$app->getDb());
    }

    private function addSelectIfNotNull($field)
    {
        if ($field !== null) {
            $this->query
                ->addSelect($field)
                ->andWhere(['not', [$field => null]])
                ->andWhere(['not', [$field => '']]);
        }
    }
}