<?php

namespace app\models;

use yii\db\ActiveRecord;


class Order extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%orders}}';
    }

    /**
     * добавление блюда в заказ .
     *
     * @param      Dish  $dish   The dish
     * @param      int   $count  The count
     */
    public function addDish(Dish $dish, int $count = 1)
    {
        if ($this->isNewRecord) {
            return false;
        }
        // поиск указанной позиции в заказе ...
        $dishBinder = OrderDish::find()->where([
            'oid' => $this->id,
            'did' => $dish->id,
        ])->one();
        if ($dishBinder) {
            \Yii::info('das1', $count);
            $dishBinder->count += $count;
        } else {
            \Yii::info('das2');
            $dishBinder = new OrderDish([
                'oid' => $this->id,
                'did' => $dish->id,
                'count' => $count,
            ]);
        }
        $dishBinder->save();
        \Yii::info($dishBinder->errors, 'errs');
        if ($dishBinder->count <= 0) {
            return -1;
        }

        return $dishBinder->count;
    }
}