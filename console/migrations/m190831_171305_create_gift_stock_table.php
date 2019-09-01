<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%gift_stock}}`.
 */
class m190831_171305_create_gift_stock_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%gift_stock}}', [
            'id' => $this->primaryKey(),
            'name'=>$this->string()->notNull(),
            'count'=>$this->integer()->notNull()->defaultValue(0)
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%gift_stock}}');
    }
}
