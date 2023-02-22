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
            'cid' => $this->integer()->notNull()->comment('Ссылка на повара'),
        ], "COMMENT 'Список блюд (меню)' ");
        $this->addForeignKey('fk-cook-link', '{{%dishs}}', ['cid'], '{{%cooks}}', ['id'], 'cascade', 'cascade');
        $this->createIndex('dishs_cid_ind', '{{%dishs}}', ['cid']);

        $this->createTable('{{%orders}}', [
            'id' => $this->primaryKey()->comment('Ключик заказа'),
            'date' => $this->timestamp()->notNull()->defaultExpression('current_timestamp')->comment('Дата заказа'),
        ], "COMMENT 'Заказы' ");

        $this->createTable('{{%orders_dishs}}', [
            'id' => $this->primaryKey()->comment('Ключик'),
            'oid' => $this->integer()->notNull()->comment('ссылка на заказ'),
            'did' => $this->integer()->notNull()->comment('сылка на заказанное блюдо'),
            'count' => $this->integer()->notNull()->defaultValue(1)->comment('Число блюд в заказе'),
        ], "COMMENT 'список заказанных блюд '");
        $this->addForeignKey('fk-order-link', '{{%orders_dishs}}', ['oid'], '{{%orders}}', ['id'], 'cascade', 'cascade');
        $this->addForeignKey('fk-dish-link', '{{%orders_dishs}}', ['did'], '{{%dishs}}', ['id'], 'cascade', 'cascade');
        $this->createIndex('od_oid_ind', '{{%orders_dishs}}', ['oid']);
        $this->createIndex('od_did_ind', '{{%orders_dishs}}', ['did']);
        $this->createIndex('od_unic', '{{%orders_dishs}}', ['oid', 'did'], true);
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
