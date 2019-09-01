<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%bank_account}}`.
 */
class m190831_165830_create_bank_account_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%bank_account}}', [
            'id' => $this->primaryKey(),
            'account'=> $this->integer()->notNull()->unique(),
            'shortcode'=>$this->integer()->notNull()->unique(),
            'api_key'=>$this->string()->notNull(),
            'secret_key'=>$this->string()->notNull(),
            'activity'=>$this->integer()->defaultValue(1)

        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%bank_account}}');
    }
}
