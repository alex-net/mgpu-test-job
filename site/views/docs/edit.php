<?php

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;

$this->title = $doc->isNewRecord ? 'Новый документ' : 'Редактировать документ';

$this->params['breadcrumbs'][] = ['label' => 'Документы', 'url' => ['index']];
if ($doc->isNewRecord) {
    $this->params['breadcrumbs'][] = 'Новый документ';
} else {
    $this->params['breadcrumbs'][] = "Редактирование документа '{$doc->title}'";
}


$f = ActiveForm::begin();

echo $f->field($doc, 'title');
echo $f->field($doc, 'body')->textarea();
echo Html::submitButton('Сохранить', ['class' => 'btn btn-primary']);


ActiveForm::end();