<?php

use yii\db\Migration;

/**
 * Class m230222_092115_cafe_init
 */
class m230222_092115_cafe_init extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%cooks}}', [
            'id' => $this->primaryKey()->comment('Ключи повара'),
            'fio' => $this->string(70)->notNull()->comment('ФИО повара'),
        ], "COMMENT 'Повара' ");

        $this->createTable('{{%dishs}}', [
            'id' => $this->primaryKey()->comment('Ключик еды'),
            'name' => $this->string(100)->notNull()->comment('Наименование блюда'),
            'descr' => $this->text()->comment('Описание блюда'),
        ], "COMMENT 'Список блюд (меню)' ");

        $this->createTable('{{%cooks_dishs}}', [
            'cid' => $this->integer()->notNull()->comment('ссылка на повара'),
            'did' => $this->integer()->notNull()->comment('ссылка на еду'),
        ], "COMMENT 'Связь поравов и блюд, которые они готовят' ");
        $this->addForeignKey('fk-cook-link', '{{%cooks_dishs}}', ['cid'], '{{%cooks}}', ['id'], 'cascade', 'cascade');
        $this->addForeignKey('fk-dish-link-cook', '{{%cooks_dishs}}', ['did'], '{{%dishs}}', ['id'], 'cascade', 'cascade');
        $this->createIndex('cdb_cid_ind', '{{%cooks_dishs}}', ['cid']);
        $this->createIndex('cdb_did_ind', '{{%cooks_dishs}}', ['did']);

        $this->createTable('{{%orders}}', [
            'id' => $this->primaryKey()->comment('Ключик заказа'),
            'date' => $this->timestamp()->notNull()->defaultExpression('current_timestamp')->comment('Дата заказа'),
        ], "COMMENT 'Заказы' ");

        $this->createTable('{{%orders_dishs}}', [
            'oid' => $this->integer()->notNull()->comment('ссылка на заказ'),
            'did' => $this->integer()->notNull()->comment('сылка на заказанное блюдо'),
        ], "COMMENT 'список заказанных блюд '");
        $this->addForeignKey('fk-order-link', '{{%orders_dishs}}', ['oid'], '{{%orders}}', ['id'], 'cascade', 'cascade');
        $this->addForeignKey('fk-dish-link-order', '{{%orders_dishs}}', ['did'], '{{%dishs}}', ['id'], 'cascade', 'cascade');
        $this->createIndex('od_oid_ind', '{{%orders_dishs}}', ['oid']);
        $this->createIndex('od_did_ind', '{{%orders_dishs}}', ['did']);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m230222_092115_cafe_init cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230222_092115_cafe_init cannot be reverted.\n";

        return false;
    }
    */
}
