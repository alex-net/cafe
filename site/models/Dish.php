<?php

namespace app\models;

use yii\db\ActiveRecord;

class Dish extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%dishs}}';
    }
}