<?php

namespace app\commands;

use yii\console\Controller;
use Yii;

class DbInitController extends Controller
{
    /**
     * генерация данных
     *
     * @param      int   $cooksCount      Число поваров
     * @param      int   $dishsCount     Число блюд
     * @param      int   $ordersCount    число заказов
     * @param      int   $dishsPerOrder  число блюд на заказ
     * @param      int   $dishsOneType   количество блюд в одной позиции заказа
     */
    public function actionIndex($cooksCount = 5, $dishsCount = 15, $ordersCount = 100, $dishsPerOrder = 7, $dishsOneType = 5)
    {
        $db = Yii::$app->db;

        // зачиска таблиц
        $this->stdout('Cброс таблиц: ');
        foreach (['{{%cooks}}', '{{%orders}}'] as $tbl) {
            $db->createCommand()->delete($tbl)->execute();
            $this->stdout(" $tbl");
        }
        // сброс автонкремента
        $this->stdout("\nCброс автоинкремента: ");
        foreach (['{{%cooks}}', '{{%dishs}}', '{{%orders}}'] as $tbl) {
            $db->createCommand("ALTER TABLE $tbl AUTO_INCREMENT = 1")->execute();
            $this->stdout(" $tbl");
        }


        // заполнение поваров ...
        $this->stdout("\nГенерация поваров ($cooksCount): ");
        $items = [];
        for ($i = 0; $i < $cooksCount; $i++) {
            $items[] = ['fio' => 'Повар ' . ($i + 1)];
            $this->stdout('.');
        }
        $db->createCommand()->batchInsert('{{%cooks}}', ['fio'], $items)->execute();


        // заполнение блюд ...
        $this->stdout("\nГенерация блюд ($dishsCount): ");
        $items = [];
        for ($i = 0; $i < $dishsCount; $i++) {
            $items[] = [
                'name' => 'Блюдоо ' . ($i + 1),
                'cid' => rand(1, $cooksCount),
            ];
            $this->stdout('.');
        }
        $db->createCommand()->batchInsert('{{%dishs}}', ['name', 'cid'], $items)->execute();


        // заполнение заказов ..
        $this->stdout("\nГенерация заказов ($ordersCount): ");
        $items = [];
        $start = mktime(0, 0, 0, 1, 1, 2019);
        for ($i = 0; $i < $ordersCount; $i++) {
            $start += rand(4 , 500) * 3600;
            $items[] = [
                'date' => date('Y-m-d H:i:s', $start),
            ];
            $this->stdout('.');
        }
        $db->createCommand()->batchInsert('{{%orders}}', ['date'], $items)->execute();


        // заполнение связок  заказ - еда )
        $this->stdout("\nГенерация связов заказ-блюда ($ordersCount*[1,$dishsPerOrder][1,$dishsOneType]): ");
        $items = [];
        for ($i = 0; $i < $ordersCount; $i++) {
            $oId = $i + 1;
            $dishCount = rand(1, $dishsPerOrder);
            for ($j = 0; $j < $dishCount; $j++) {
                $dId = rand(1, $dishsCount);
                if (isset($items["$oId-$dId"])) {
                    $items["$oId-$dId"]['count'] += rand(1, $dishsOneType);
                } else {
                    $items["$oId-$dId"] = [
                        'oid' => $oId,
                        'did' => $dId,
                        'count' => rand(1, $dishsOneType),
                    ];
                }


                $this->stdout('.');
            }
        }
        $db->createCommand()->batchInsert('{{%orders_dishs}}', ['oid', 'did', 'count'], $items)->execute();
        $this->stdout("\n");
    }
}