<?php
use yii\grid\GridView;
use app\models\Document;
use yii\helpers\Html;
use yii\grid\ActionColumn;

$this->title = 'Документы';
$this->params['breadcrumbs'][] = 'Документы';

echo Html::a('Добавить документ', ['add'], ['class' => 'btn btn-primary']);

echo GridView::widget([
    'dataProvider' => Document::list(),
    'columns' => [
        [
            'attribute' => 'title',
            'format' => 'html',
            'value' => function ($m) {
                $title = $m->title;
                // Документы на последнем (статус Проверен) не реадктируем ...
                if ($m->isLastStatus) {
                    return $title;
                }
                return Html::a($title, ['edit', 'id' => $m->id], ['title' => 'Редактировать']);
            }
        ],
        'author_id',
        [
            'attribute' => 'status',
            'format' => 'html',
            'value' => function ($m) {
                $st = $m->statusDescr;
                if (!$m->isLastStatus) {

                   $st = Html::a($st, ['next-st', 'id' => $m->id], ['title' => 'Передвинуть на следующий шаг ' . $m->getStatusDescr($m->nextStatus)]);
                }
                return $st;// $m['status'];
            }
        ]

    ],
]);