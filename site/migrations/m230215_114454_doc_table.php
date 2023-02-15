<?php

use yii\db\Migration;

/**
 * Class m230215_114454_doc_table
 */
class m230215_114454_doc_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("create type doc_status as enum ('draft', 'verification', 'proved')");
        $this->createTable('{{%docs}}', [
            'id' => $this->primaryKey()->comment('Ключик'),
            'title' => $this->string(50)->notNull()->unique()->comment('Заголовок'),
            'author_id' => $this->integer()->notNull()->comment('Автор'),
            'body' => $this->text()->comment('Содержимое'),
            'status' => "doc_status default 'draft' not null",
        ]);
        $this->addCommentOnColumn('{{%docs}}', 'status', 'Статус документа');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%docs}}');
        $this->execute("drop type doc_status");
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230215_114454_doc_table cannot be reverted.\n";

        return false;
    }
    */
}
