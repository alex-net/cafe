<?php

namespace app\models;

use yii\db\ActiveRecord;
use yii\db\Expression;

class Cook extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%cooks}}';
    }

    public static function goodPovarList()
    {
        return static::find()->alias('c')->select(['c.id', 'c.fio', 'count' => new Expression('count(b.id)')])
            ->leftJoin(['d' => '{{%dishs}}'], 'd.cid = c.id')
            ->leftJoin(['b' => '{{%orders_dishs}}'], 'b.did = d.id')
            ->groupBy('c.id')->orderBy(new Expression('count(b.id) desc'));
    }
}
