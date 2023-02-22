<?php

namespace app\models;

use yii\db\ActiveRecord;
use yii\db\Expression;
use yii\validators\DateValidator;

class Cook extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%cooks}}';
    }

    public static function goodPovarList($from, $to)
    {
        $query = static::find()->alias('c')->select(['c.id', 'c.fio', 'count' => new Expression('count(b.id)')])
            ->leftJoin(['d' => '{{%dishs}}'], 'd.cid = c.id')
            ->leftJoin(['b' => '{{%orders_dishs}}'], 'b.did = d.id')
            ->groupBy('c.id')->orderBy(new Expression('count(b.id) desc'));
        if ($from || $to) {
            $validator = new DateValidator(['format' => 'php:Y-m-d']);
            $where = [];
            if ($from && $validator->validate($from)) {
                $where[] = ['>=', 'o.date', $from];
            }
            if ($to && $validator->validate($to)) {
                $where[] = ['<=', 'o.date', $to];
            }
            if ($where) {
                $query->leftJoin(['o' => '{{%orders}}'], 'o.id = b.oid');
                array_unshift($where, 'and');
                $query->where($where);
            }
        }
        return $query;
    }
}
