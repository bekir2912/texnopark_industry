<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\industry\DepartmentSizingSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="department-sizing-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'user_id') ?>

    <?= $form->field($model, 'department_id') ?>

    <?= $form->field($model, 'model_id') ?>

    <?= $form->field($model, 'current_operation') ?>

    <?php // echo $form->field($model, 'amount') ?>

    <?php // echo $form->field($model, 'time_expire') ?>

    <?php // echo $form->field($model, 'is_defect') ?>

    <?php // echo $form->field($model, 'status') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
