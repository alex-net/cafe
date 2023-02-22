<?php

namespace app\models;

use yii\db\ActiveRecord;

/**
 * класс связка заказа с блюдами (позиция в заказе)
 * @property $oid integer Ссылка на заказ
 * @property $did integer Ссылка на блюдо
 * @property $count integer Количество блюд в позиции
 */
class OrderDish extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%orders_dishs}}';
    }

    public function rules()
    {
        return [
            ['oid', 'exist', 'targetClass' => Order::class, 'targetAttribute' => 'id'],
            ['did', 'exist', 'targetClass' => Dish::class, 'targetAttribute' => 'id'],
            ['count', 'integer'],
            ['count', 'default', 'value' => 1],
        ];
    }

    public function beforeSave($ins)
    {
        if (!parent::beforeSave($ins)) {
            return false;
        }
        if ($this->count <= 0 && !$this->isNewRecord) {
            $this->delete();
            return false;
        }

        return true;
    }

}