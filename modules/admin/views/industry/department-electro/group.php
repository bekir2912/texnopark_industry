<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$page = Yii::$app->request->get('id') ? 'Редактировать' : 'Добавить';

$this->title = "Электро сборка группировка: " . $page." операцию  " . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Sizing', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;



?>

<div class="content-wrapper">
    <section class="content-header">
        <h1><?=$this->title;?></h1>

        <ol class="breadcrumb">
            <li><a href="<?=Yii::$app->urlManager->createUrl(['/admin/'])?>"><i class="fa fa-dashboard"></i> Главная</a></li>
            <li class="active"><?=$this->title;?></li>
        </ol>
    </section>
    <section class="content">
        <?php $form = ActiveForm::begin(); ?>
        <div class="box box-info color-palette-box">
            <div class="box-body">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="col-sm-4">
                            <?=$form->field($model, 'model_id')->dropDownList( $models , ['class'=>'select-drop form-control','prompt'=>'- Выберите модель -', 'options'=>[$models->model_id? $models->model_id: $model->model_id=>["Selected"=>true]]])->label('Модель');?>
                            <?php if($department->model_id){?>
                                <b>Предедущая модель: </b> <?=  $department->model_id? $model->nameModel($department->model_id) : '--' ?>
                            <?php }?>
                        </div>
                        <div class="col-sm-4">
                            <?= $form->field($model, 'line_id')->dropDownList($lines, ['class'=>'select-drop form-control','prompt'=>'- Выберите линию -'])->label('Линия');?>
                        </div>
                        <div class="col-sm-4">
                            <?= $form->field($model, 'current_operation')->textInput(['maxlength' => true, 'value' => $department->current_operation ? $department->current_operation : ($model->id ?$model->current_operation :  \Faker\Provider\Uuid::uuid()) , 'readonly' => true ])->label('Операция <span class="required-field">*</span>') ?>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <?php  if(!$model->id){  ?>
                            <div class="col-sm-4">
                                <?= $form->field($model, 'amount')->textInput(['maxlength' => true, 'value' =>  $department->amount ? $department->amount : $model->amount])->label('Кол-во <span class="required-field">*</span>') ?>
                            </div>
                        <?php }else {?>
                            <div class="col-sm-4">
                                <?= $form->field($model, 'amount')->textInput(['maxlength' => true, 'value' =>  $department->amount ? $department->amount : $model->amount, 'readonly' => true])->label('Кол-во <span class="required-field">*</span>') ?>
                            </div>
                        <?php }?>
                        <div class="col-sm-4">
                            <?= $form->field($model, 'number_poddon')->textInput(['maxlength' => true, 'value' => $department->number_poddon ? $department->number_poddon : $model->number_poddon])->label('Номер поддона <span class="required-field">*</span>'); ?>
                            <?php if($department->number_poddon){?>
                                <b>Предедущий поддон: </b> <?=  $department->number_poddon? $department->number_poddon : '--' ?>
                            <?php }?>
                        </div>
                        <?php if($department->department_id == 10){?>
                            <div class="col-sm-4">
                                <?= $form->field($model, 'status')->dropDownList([ "1" => "Готов", "0" => "В ожидании"], ['disabled'=>true]); ?>
                                <?=$form->field($model, 'status')->hiddenInput(['value'=>1])->label(false);?>
                            </div>
                        <?php } else {?>
                            <div class="col-sm-4">
                                <?= $form->field($model, 'status')->dropDownList([ "0" => "В ожидании", "1" => "Готов"] ); ?>
                            </div>
                        <?php }?>
                    </div>
                    <?php if($department->department_id == 10|| $model->to_user) {?>

                        <div class="col-sm-12">
                            <div class="col-sm-4">
                                <?= $form->field($model, 'to_user')->textInput(['maxlength' => true])->label() ?>
                            </div>
                        </div>
                    <?php }?>
                </div>

                <?=$form->field($model, 'user_id')->hiddenInput(['value'=> Yii::$app->user->identity->id ? Yii::$app->user->identity->id : 1])->label(false);?>
                <?=$form->field($model, 'previous_department_id')->hiddenInput([ 'value'=> 0])->label(false);?>
                <?=$form->field($model, 'department_id')->hiddenInput([ 'value'=> 0])->label(false);?>

                <?=Html::submitButton('<i class="fa fa-check-square-o"></i> Сохранить', ['class'=>'btn btn-primary pull-right']);?>
            </div>
        </div>

        <?php ActiveForm::end();?>
    </section>
</div>
