<?php
namespace nstdio\yii2notymo\provider;

use yii\base\InvalidConfigException;
use yii\base\Object;
use yii\db\Query;

/**
 * Class DataProvider
 *
 * @package nstdio\yii2notymo\provider
 * @author  Edgar Asatryan <nstdio@gmail.com>
 */
abstract class DataProvider extends Object
{
    /**
     * @var string The table name where device tokens stored.
     */
    public $table;

    /**
     * @var string
     */
    public $identifier;

    /**
     * @var string iOS device token table field name.
     */
    public $apns;

    /**
     * @var string Android device token table field name.
     */
    public $gcm;

    /**
     * @var Query
     */
    protected $query;

    /**
     * @param $identifiers
     *
     * @return array
     */
    public function getTokens($identifiers)
    {
        $this->query->from($this->table);

        $select = [];
        if ($this->apns !== null) {
            $select[] = $this->apns;
        }

        if ($this->gcm !== null) {
            $select[] = $this->gcm;
        }

        if (!empty($select)) {
            $this->query->select($select);
        }

        if (!empty($identifiers)) {
            $this->query->andWhere([$this->identifier => $identifiers]);
        }

        return $this->query->all();
    }

    public function init()
    {
        if (!$this->table) {
            throw new InvalidConfigException("You must specify table.");
        }

        if (!$this->identifier) {
            throw new InvalidConfigException("You must specify identifier.");
        }

        if (!$this->apns && !$this->gcm) {
            throw new InvalidConfigException("At least one of apns or gcm properties must be initialized.");
        }
    }
}