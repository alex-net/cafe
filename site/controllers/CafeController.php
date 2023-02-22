<?php

namespace app\controllers;

use yii\rest\Controller;
use yii\helpers\ArrayHelper;
use yii\filters\VerbFilter;

use app\models\Dish;
use app\models\Order;
use app\models\Cook;

class CafeController extends Controller
{

    public function behaviors()
    {
        return [
            [
                'class' => VerbFilter::class,
                'actions' => [
                    'menu' => ['get'],
                    'new-order' => ['post'],
                    'add-dish-to-order' => ['post'],
                    'good-povars' => ['post'],
                ],
            ]
        ];
    }
    /**
     *  Список блюд в меню ...
     */
    public function actionMenu()
    {
        $list = ArrayHelper::map(Dish::find()->select(['id', 'name'])->asArray()->all(), 'id', 'name');
        return $this->asJson($list);
    }

    /**
     * создание нового заказа (открытия чека)
     */
    public function actionNewOrder()
    {
        $order = new Order();
        if ($order->save()) {
            return $this->asJson([
                'ok' => true,
                'oid' => $order->id,
            ]);
        }
    }

    /**
     * добавление позиции из меню в чек
     */
    public function actionAddDishToOrder()
    {
        $post = $this->request->post();
        // проверка параметров
        if (empty($post['oid']) || empty($post['did'])) {
            throw new \yii\web\BadRequestHttpException("Пустые входные параметры");
        }

        // ищем заказ
        $order = Order::find()->where(['id' => intval($post['oid'])])->limit(1)->one();
        if (!$order) {
            throw new \yii\web\BadRequestHttpException("Заказ не найден");
        }

        // ищем блюдо
        $dish = Dish::find()->where(['id' => intval($post['did'])])->limit(1)->one();
        if (!$dish) {
            throw new \yii\web\BadRequestHttpException("Не найдено блюдо ");
        }

        $result = $order->addDish($dish, intval($post['count'] ?? 1));

        return $this->asJson(['ok' => true, 'count' => $result]);
    }


    public function actionGoodPovars()
    {
        return $this->asJson(Cook::goodPovarList()->asArray()->all());
    }

}