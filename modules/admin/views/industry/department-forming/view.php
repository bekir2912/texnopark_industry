<?php

use app\models\industry\AllDeffect;
use app\models\industry\BufferZone;
use app\models\user\User;
use yii\helpers\Html;
use yii\widgets\DetailView;

if($model->id){
    $defect = AllDeffect::find()->where(['dep_id' =>$model->id])->andWhere(['department_id' => 11])->one();
    $buffer = BufferZone::find()->where(['dep_id' =>$model->id])->one();
}


$this->title = 'Формовка AUQ-G6';
$this->params['breadcrumbs'][] = $this->title;

?>



<div class="content-wrapper">
    <section class="content-header">

        <h1><?=$this->title . ' № ' . $model->id;?></h1>

        <ol class="breadcrumb">
            <li><a href="<?=Yii::$app->urlManager->createUrl(['/admin/'])?>"><i class="fa fa-dashboard"></i> Главная</a></li>
            <li class="active"><?=$this->title;?></li>
        </ol>
    </section>
    <section class="content">
        <?php if (Yii::$app->session->hasFlash('forming_saved')) {?>
            <div class="alert alert-success text-center">
                <?=Yii::$app->session->getFlash('forming_saved');?>
            </div>
        <?php } ?>
        <?php if (Yii::$app->session->hasFlash('forming_setted')) {?>
            <div class="alert alert-success text-center">
                <?=Yii::$app->session->getFlash('forming_setted');?>
            </div>
        <?php }?>
        <?php if (Yii::$app->session->hasFlash('ckeck_status')) {?>
            <div class="alert alert-success text-center">
                <?=Yii::$app->session->getFlash('ckeck_status');?>
            </div>
        <?php }?>
        <div class="row">
            <div class="col-sm-12">
                <?php if ($model) {?>
                <div class="box box-info color-palette-box">
                    <div class="box-header">

                        <?php if ($model->status == 1 || $model->status == 2 ||$model->status == 0) { ?>
                            <a href="<?=Yii::$app->urlManager->createUrl(['/admin/industry/department-forming/stock', 'id'=>$model->id]);?>" class="btn btn-warning">Добавить на склад</a>
                        <?php }?>
                        <?php if ($model->is_ckeck == 0 &&  (Yii::$app->user->identity->is_part == User::ROLE_OTK || Yii::$app->user->identity->role == User::ROLE_ADMIN)) {?>
                            <a href="<?=Yii::$app->urlManager->createUrl(['/admin/industry/department-forming/check', 'id'=>$model->id]);?>" class="btn btn-success">Проверку прошел</a>
                        <?php }?>

                        <!--                        --><?php //if ($model->is_defect == 0 && !$defect) {?>
<!--                            <a href="--><?//=Yii::$app->urlManager->createUrl(['/admin/industry/all-deffect/create', 'id_department'=>$model->id, 'dep_name' => $model->department_id]);?><!--" class="btn btn-danger">Добавить в дефект</a>-->
<!--                        --><?php //}?>
<!---->
<!--                        --><?php //if ($model->is_defect == 1 || $defect) {?>
<!--                            <a href="--><?//=Yii::$app->urlManager->createUrl(['/admin/industry/all-deffect/view', 'id'=>$defect->id ]);?><!--" class="btn btn-warning">Посмотреть дефект</a>-->
<!--                        --><?php //}?>
                        <!---->
                        <!--                        --><?php //if ($model->status == 0 || empty($buffer->id)) {?>
                        <!--                            <a href="--><?//=Yii::$app->urlManager->createUrl(['/admin/industry/buffer-zone/create', 'department_id'=>$model->id, 'dep_name' => $model->department_id ]);?><!--" class="btn btn-primary">Добавить в Буфферную зону</a>-->
                        <!--                        --><?php //}?>
                        <!---->
                        <!--                        --><?php //if ($model->status == 1 && empty(!$buffer->id)) {?>
                        <!--                            <a href="--><?//=Yii::$app->urlManager->createUrl(['/admin/industry/buffer-zone/view', 'id'=>$buffer->id ]);?><!--" class="btn btn-primary">Посмотреть в Буфферой зоне</a>-->
                        <!--                        --><?php //}?>

                        <div class="btn-group"><button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
                                <span class="fa fa-cog"></span>
                            </button>
                            <ul class="dropdown-menu pull-left">
                                <li><a href="<?=Yii::$app->urlManager->createUrl(['/admin/industry/department-forming/index']);?>" class="dropdown-item">Все операции</a></li>
                                <?php if(Yii::$app->user->identity->is_edit == true  ) { ?>
                                    <li><a href="<?=Yii::$app->urlManager->createUrl(['/admin/industry/department-forming/create', 'id'=>$model->id]);?>" class="dropdown-item">Редактировать</a></li>
                                <?php } ?>
                                <?php if(Yii::$app->user->identity->is_remove == true  ) { ?>
                                    <li><a href="<?=Yii::$app->urlManager->createUrl(['/admin/industry/department-forming/remove', 'id'=>$model->id]);?>" class="dropdown-item" class="remove-object">Удалить</a></li>
                                <?php } ?>

                            </ul>
                        </div>

                        <div class="box-body">
                            <table class="table table-striped">
                                <tr>
                                    <td>ID:</td>
                                    <td><?=$model->id ? $model->id : '-';?></td>
                                </tr>
                                <tr>
                                    <td>Отдел:</td>
                                    <td><?=$model->department_id ? $model->department->name_ru : '-';?></td>
                                </tr>

                                <tr>
                                    <td>Модель:</td>
                                    <td><?=$model->model ? $model->model->name_ru : '-';?></td>
                                </tr>
                                <tr>
                                    <td>Артикул:</td>
                                    <td><?=$model->articul ? $model->articul : '-';?></td>

                                </tr>
                                <tr>
                                    <td>Пользатель:</td>
                                    <td><?=$model->user ? $model->user->name : '-';?></td>
                                </tr>
                                <tr>
                                    <td>Номер поддона:</td>
                                    <td><?=$model->number_poddon ? $model->number_poddon : '-';?></td>
                                </tr>
                                <tr>
                                    <td>Текущая оперция:</td>
                                    <td><?=$model->current_operation ? $model->current_operation : '-';?></td>
                                </tr>
                                <tr>
                                    <td>Кол-во:</td>
                                    <td><?=$model->amount ? ($model->amount) : '-';?></td>
                                </tr>
                                <tr>
                                    <td>Дефектная продукция:</td>
                                    <td>
                                        <?php
                                        if ($model->is_defect == 1) {
                                            echo '<span class="label label-danger">Да</span>';
                                        } else {
                                            echo '<span class="label label-success">Нет</span>';
                                        }
                                        ?>
                                    </td>
                                </tr>

<!--                                <tr>-->
<!--                                    <td>Часть:</td>-->
<!--                                    <td>-->
<!--                                        --><?php
//                                        if ($model->part_model == 1) {
//                                            echo '<span class="label label-primary">Нижняя</span>';
//                                        } else {
//                                            echo '<span class="label label-primary">Верхняя</span>';
//                                        }
//                                        ?>
<!--                                    </td>-->
<!--                                </tr>-->
                                <tr>
                                    <td>Проверка контроля качества:</td>
                                    <td>
                                        <?php
                                        if ($model->is_ckeck == 1) {
                                            echo '<span class="label label-success">Проверку прошел</span>';
                                        } else {
                                            echo '<span class="label label-warning">Ожидание проверки</span>';
                                        }
                                        ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Статус:</td>
                                    <td>
                                        <?php
                                        if ($model->status == 1) {
                                            echo '<small class="label label-success">Готов</small>';
                                        }elseif ($model->status == 2){
                                            echo '<small class="label label-primary">На проверке</small>';
                                        }elseif($model->status == 0) {
                                            echo '<small class="label label-warning">В ожидании </small>';
                                        }elseif($model->status == 3) {
                                            echo '<small class="label label-success">На складе </small>';
                                        }
                                        ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Дата изменения:</td>
                                    <td><?=$model->updated_at ? date('d.m.Y H:i',$model->updated_at) : '-';?></td>
                                </tr>

                                <tr>
                                    <td>Дата создания:</td>
                                    <td><?=$model->dates ? date_format(date_create($model->dates), 'd.m.Y H:i') : '-';?></td>
                                </tr>
<!--                                <tr>-->
<!--                                    <td>Дата создания:</td>-->
<!--                                    <td>--><?//=$model->created_at ? date('d.m.Y H:i',$model->created_at) : '-';?><!--</td>-->
<!--                                </tr>-->

                            </table>
                            <br/>
                        </div>
                    </div>
                    <?php } else {?>
                        <div class="alert alert-warning text-center">Данных нет</div>
                    <?php }?>
                </div>
            </div>
    </section>
</div>
