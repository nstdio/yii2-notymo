<?php

use yii\db\Migration;

/**
 * Handles the creation of table `device_token`.
 */
class m161002_171521_create_device_token_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('device_token', [
            'id'         => $this->primaryKey(),
            'user_id'    => $this->integer(),
            'apns_token' => $this->string(),
            'gcm_token'  => $this->string(),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('device_token');
    }
}
