<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use app\assets\AdminAsset;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use app\models\user\User;
use app\models\Notification;
use app\models\Category;
use app\models\shipment\Shipment;
use app\models\Settings;

AdminAsset::register($this);

$requestedRoute = explode('/', Yii::$app->requestedRoute);
$requestedRoute = $requestedRoute[0];

$controller = Yii::$app->controller->id;
$action = Yii::$app->controller->action->id;

//echo  Yii::$app->user->identity->id;
$user = null;
if (!Yii::$app->user->isGuest) {
    $user = User::find()->with('image', 'moderatorAccess', 'moderatorAccess.moderator')->where(['id'=>Yii::$app->user->identity->id])->one();
    $notifications = Notification::find()->where(['status_admin'=>0])->all();
}

$accesses = array();

if ($user && $user->moderatorAccess) {
    foreach ($user->moderatorAccess as $v) {
        if ($v && $v->moderator) {
            $accesses[] = $v->moderator->url;
        }
    }
}

$shipments = Shipment::find()->where(['status'=>0])->count();

$currency = Settings::findOne(['type'=>'currency']);
if (!$currency) {
    $currency = new Settings;
}
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body class="hold-transition skin-blue sidebar-mini sidebar-collapse">
<?php $this->beginBody() ?>
    <div class="wrapper">
        <?php if (($controller != 'default') || (($controller == 'default') && ($action != 'index'))) {?>
            <header class="main-header">
                <a href="<?=Yii::$app->urlManager->createUrl(['/'])?>" class="logo">
                    <!-- <span class="logo-mini"><img src="/assets_files/img/texno_logo.png" width="50"/></span>
                    <span class="logo-lg"><img src="/assets_files/img/texno_logo.png" width="50"/></span> -->
                    <span class="logo-mini"><b>T</b>P</span>
                    <span class="logo-xs"><b>TEXNO</b>PARK <span style="font-size: 10px">INDUSTRY</span></span>
                </a>
                <nav class="navbar navbar-static-top">
                    <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </a>

                    <div class="navbar-custom-menu">
                        <ul class="nav navbar-nav">

                            <li class="dropdown user user-menu">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                    <img src="<?=$user ? $user->getPhoto('100x100') : '';?>" class="user-image" alt="User Image">
                                    <span class="hidden-xs">?????? ??????????????</span>
                                </a>
                                <ul class="dropdown-menu">
                                    <li class="user-header">
                                        <img src="<?=$user ? $user->getPhoto('100x100') : '';?>" class="img-circle" alt="User Image">
                                        <p>?????? ??????????????<br/><?=$user ? $user->name : '';?></p>
                                    </li>
                                    <li class="user-footer">
                                        <div class="pull-left">
                                            <a href="<?=Yii::$app->urlManager->createUrl(['/admin/default/profile'])?>" class="btn btn-default btn-flat">????????????????????</a>
                                        </div>
                                        <div class="pull-right">
                                            <a href="<?=Yii::$app->urlManager->createUrl(['/main/log-out'])?>" class="btn btn-default btn-flat">??????????</a>
                                        </div>
                                    </li>
                                </ul>
                            </li>

                        </ul>
                    </div>
                </nav>
            </header>

            <aside class="main-sidebar">
                <section class="sidebar">
                    <div class="user-panel" style="height:70px">
                        <div class="pull-left info">
                            <p><?=$user ? $user->name : '';?></p>
                            <a href="javascript:;"><i class="fa fa-circle text-success"></i> ??????????????????????????</a>
                        </div>
                    </div>

                    <!-- <form action="#" method="get" class="sidebar-form">
                        <div class="input-group">
                            <input type="text" name="q" class="form-control" placeholder="??????????...">
                            <span class="input-group-btn">
                                <button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i>
                                </button>
                            </span>
                        </div>
                    </form> -->

                    <ul class="sidebar-menu" data-widget="tree">
                        <li class="header">????????</li>

                        <li <?=(($controller == 'default') && ($action == 'dashboard')) ? 'class="active"' : '';?>>
                            <a href="<?=Yii::$app->urlManager->createUrl(['/admin/default/dashboard'])?>">
                                <i class="fa fa-home"></i> <span>??????????????</span>
                            </a>
                        </li>

                        <?php if ($user && (($user->role == User::ROLE_ADMIN )
                                ||  in_array('department-stamping', $accesses)
                                ||  in_array('department-paiting', $accesses)
                                ||  in_array('department-mechanical', $accesses)
                                ||  in_array('department-test', $accesses)
                                ||  in_array('department-sizing', $accesses)
                                ||  in_array('department-electro', $accesses)
                                ||  in_array('department-gp', $accesses)
                                ||  in_array('department-plastic', $accesses)
                                ||  in_array('department-regulator', $accesses)
                                ||  in_array('department-printing', $accesses)
                                ||  in_array('department-forming', $accesses)
                                ||  in_array('department-checking', $accesses)
                            )) {?>
                            <li class="treeview<?=( $controller == 'industry/department-stamping' || $controller == 'industry/department-paiting' || $controller == 'industry/department-plastic' || $controller == 'industry/department-regular'|| $controller == 'industry/department-printing' || $controller == 'industry/department-forming' || $controller == 'industry/department-checking' ||  $controller == 'industry/department-mechanical' || $controller == 'industry/department-test' || $controller == 'industry/department-sizing' || $controller == 'industry/department-electro'|| $controller == 'industry/department-gp' || $controller == 'industry/department-regulator') && $action == 'report' ? ' active' : '';?>">
                                <a href="#">
                                    <i class="fa fa-file"></i> <span>????????????????????</span>
                                    <span class="pull-right-container">
                                        <i class="fa fa-angle-left pull-right"></i>
                                    </span>
                                </a>
                                <ul class="treeview-menu">
                                    <?php if ($user->role == User::ROLE_ADMIN ||  in_array('department-stamping', $accesses)) {?>
                                        <li><a href="<?=Url::to(['/admin/industry/department-stamping/report'])?>"><i class="fa fa-circle-o"></i> ?????????????????? </a></li>
                                    <?php }?>
                                    <?php if ($user->role == User::ROLE_ADMIN || in_array('department-paiting', $accesses)) {?>
                                        <li><a href="<?=Url::to(['/admin/industry/department-paiting/report', 'sort' => '-id'])?>"><i class="fa fa-circle-o"></i> ????????????????</a></li>
                                    <?php }?>
                                    <?php if ($user->role == User::ROLE_ADMIN || in_array('department-mechanical', $accesses)) {?>
                                        <li><a href="<?=Url::to(['/admin/industry/department-mechanical/report', 'sort' => '-id'])?>"><i class="fa fa-circle-o"></i> ???????????????????????? ????????????</a></li>
                                    <?php }?>
                                    <?php if ($user->role == User::ROLE_ADMIN || in_array('department-test', $accesses)) {?>
                                        <li><a href="<?=Url::to(['/admin/industry/department-test/report', 'sort' => '-id'])?>"><i class="fa fa-circle-o"></i> ???????? ???? ????????????</a></li>
                                    <?php }?>

                                    <?php if ($user->role == User::ROLE_ADMIN || in_array('department-sizing', $accesses)) {?>
                                        <li><a href="<?=Url::to(['/admin/industry/department-sizing/report', 'sort' => '-id'])?>"><i class="fa fa-circle-o"></i> ????????????????????</a></li>
                                    <?php }?>

                                    <?php if ($user->role == User::ROLE_ADMIN || in_array('department-electro', $accesses)) {?>
                                        <li><a href="<?=Url::to(['/admin/industry/department-electro/report', 'sort' => '-id'])?>"><i class="fa fa-circle-o"></i> ??????????????????????????</a></li>
                                    <?php }?>
                                    <?php if ($user->role == User::ROLE_ADMIN || in_array('department-gp', $accesses)) {?>
                                        <li><a href="<?=Url::to(['/admin/industry/department-gp/report'])?>"><i class="fa fa-circle-o"></i> ????</a></li>
                                    <?php }?>

                                    <?php if ($user->role == User::ROLE_ADMIN || in_array('department-plastic', $accesses)) {?>
                                        <li><a href="<?=Url::to(['/admin/industry/department-plastic/report', 'sort' => '-id'])?>"><i class="fa fa-circle-o"></i> ?????????????? ????????. ??????????????</a></li>
                                    <?php }?>
                                    <?php if ($user->role == User::ROLE_ADMIN || in_array('department-regulator', $accesses)) {?>
                                        <li><a href="<?=Url::to(['/admin/industry/department-regulator/report', 'sort' => '-id'])?>"><i class="fa fa-circle-o"></i> ???????????? ???????????????? ????????????????????</a></li>
                                    <?php }?>
                                    <?php if ($user->role == User::ROLE_ADMIN || in_array('department-printing', $accesses)) {?>
                                        <li><a href="<?=Url::to(['/admin/industry/department-printing/report', 'sort' => '-id'])?>"><i class="fa fa-circle-o"></i> ???????????? ????????????????</a></li>
                                    <?php }?>
                                    <?php if ($user->role == User::ROLE_ADMIN || in_array('department-forming', $accesses)) {?>
                                        <li><a href="<?=Url::to(['/admin/industry/department-forming/report', 'sort' => '-id'])?>"><i class="fa fa-circle-o"></i> ???????????????? AUQ-G6</a></li>
                                    <?php }?>
                                    <?php if ($user->role == User::ROLE_ADMIN || in_array('department-checking', $accesses)) {?>
                                        <li><a href="<?=Url::to(['/admin/industry/department-checking/report', 'sort' => '-id'])?>"><i class="fa fa-circle-o"></i> ???????????????? ?????????? ???? ????????????</a></li>
                                    <?php }?>

                                </ul>
                            </li>
                        <?php }?>

                        



                        <?php if ($user && (($user->role == User::ROLE_ADMIN)
                                || in_array('department-mechanical', $accesses)
                                || in_array('department-test', $accesses)
                                || in_array('department-sizing', $accesses)
                                || in_array('department-electro', $accesses))) {?>
                            <li class="treeview<?=( $controller == 'industry/department-mechanical' || $controller == 'industry/department-test' || $controller == 'industry/department-sizing' || $controller == 'industry/department-electro')  && $action != 'report'? ' active' : '';?>">
                                <a href="#">
                                    <i class="fa fa-industry"></i> <span>????????????</span>
                                    <span class="pull-right-container">
                                        <i class="fa fa-angle-left pull-right"></i>
                                    </span>
                                </a>
                                <ul class="treeview-menu">
                                    <?php if ($user->role == User::ROLE_ADMIN || in_array('department-mechanical', $accesses)) {?>
                                        <li><a href="<?=Url::to(['/admin/industry/department-mechanical', 'sort' => '-id'])?>"><i class="fa fa-circle-o"></i> ???????????????????????? ????????????</a></li>
                                    <?php }?>
                                    <?php if ($user->role == User::ROLE_ADMIN || in_array('department-test', $accesses)) {?>
                                        <li><a href="<?=Url::to(['/admin/industry/department-test', 'sort' => '-id'])?>"><i class="fa fa-circle-o"></i> ???????? ???? ????????????</a></li>
                                    <?php }?>

                                    <?php if ($user->role == User::ROLE_ADMIN || in_array('department-sizing', $accesses)) {?>
                                        <li><a href="<?=Url::to(['/admin/industry/department-sizing', 'sort' => '-id'])?>"><i class="fa fa-circle-o"></i> ????????????????????</a></li>
                                    <?php }?>

                                    <?php if ($user->role == User::ROLE_ADMIN || in_array('department-electro', $accesses)) {?>
                                        <li><a href="<?=Url::to(['/admin/industry/department-electro', 'sort' => '-id'])?>"><i class="fa fa-circle-o"></i> ??????????????????????????</a></li>
                                    <?php }?>

                                </ul>
                            </li>
                        <?php }?>

                        <?php if ($user && (($user->role == User::ROLE_ADMIN)
                                || in_array('department-stamping', $accesses )
                                || in_array('department-paiting', $accesses )
                                || in_array('department-plastic', $accesses )
                                || in_array('department-regulator', $accesses )
                                || in_array('department-printing', $accesses )
                                || in_array('department-forming', $accesses )
                                || in_array('department-checking', $accesses ))) {?>
                            <li class="treeview<?=($controller == 'industry/department-stamping' || $controller == 'industry/department-paiting' || $controller == 'industry/department-plastic' || $controller == 'industry/department-regular'|| $controller == 'industry/department-printing' || $controller == 'industry/department-forming' || $controller == 'industry/department-checking') && $action != 'report' ? ' active' : '';?>">
                                <a href="#">
                                    <i class="fa fa-industry"></i> <span>??????. ????????????</span>
                                    <span class="pull-right-container">
                                        <i class="fa fa-angle-left pull-right"></i>
                                    </span>
                                </a>
                                <ul class="treeview-menu">
                                    <?php if ($user->role == User::ROLE_ADMIN || in_array('department-stamping', $accesses)) {?>
                                        <li><a href="<?=Url::to(['/admin/industry/department-stamping', 'sort' => '-id'])?>"><i class="fa fa-circle-o"></i> ??????????????????</a></li>
                                    <?php }?>
                                    <?php if ($user->role == User::ROLE_ADMIN || in_array('department-paiting', $accesses)) {?>
                                        <li><a href="<?=Url::to(['/admin/industry/department-paiting', 'sort' => '-id'])?>"><i class="fa fa-circle-o"></i> ????????????????</a></li>
                                    <?php }?>
                                    <?php if ($user->role == User::ROLE_ADMIN || in_array('department-plastic', $accesses)) {?>
                                        <li><a href="<?=Url::to(['/admin/industry/department-plastic', 'sort' => '-id'])?>"><i class="fa fa-circle-o"></i> ?????????????? ????????. ??????????????</a></li>
                                    <?php }?>
                                    <?php if ($user->role == User::ROLE_ADMIN || in_array('department-regulator', $accesses)) {?>
                                        <li><a href="<?=Url::to(['/admin/industry/department-regulator', 'sort' => '-id'])?>"><i class="fa fa-circle-o"></i> ???????????? ???????????????? ????????????????????</a></li>
                                    <?php }?>
                                    <?php if ($user->role == User::ROLE_ADMIN || in_array('department-printing', $accesses)) {?>
                                        <li><a href="<?=Url::to(['/admin/industry/department-printing', 'sort' => '-id'])?>"><i class="fa fa-circle-o"></i> ???????????? ????????????????</a></li>
                                    <?php }?>
                                    <?php if ($user->role == User::ROLE_ADMIN || in_array('department-forming', $accesses)) {?>
                                        <li><a href="<?=Url::to(['/admin/industry/department-forming', 'sort' => '-id'])?>"><i class="fa fa-circle-o"></i> ???????????????? AUQ-G6</a></li>
                                    <?php }?>
                                    <?php if ($user->role == User::ROLE_ADMIN || in_array('department-checking', $accesses)) {?>
                                        <li><a href="<?=Url::to(['/admin/industry/department-checking', 'sort' => '-id'])?>"><i class="fa fa-circle-o"></i> ???????????????? ?????????? ???? ????????????</a></li>
                                    <?php }?>
                                </ul>
                            </li>
                        <?php }?>


                        <?php if ($user && (($user->role == User::ROLE_ADMIN) || in_array('buffer-zone', $accesses))) {?>
                            <li <?=($controller == 'industry/buffer-zone') ? 'class="active"' : '';?>>
                                <a href="<?=Yii::$app->urlManager->createUrl(['/admin/industry/buffer-zone'])?>">
                                    <i class="fa fa-angle-double-right"></i> <span>???????????????? ????????</span>
                                </a>
                            </li>
                        <?php }?>


                        <?php if ($user && (($user->role == User::ROLE_ADMIN) || in_array('department-gp', $accesses))) {?>
                            <li <?=($controller == 'industry/department-gp') ? 'class="active"' : '';?>>
                                <a href="<?=Yii::$app->urlManager->createUrl(['/admin/industry/department-gp'])?>">
                                    <i class="fa fa-star"></i> <span>?????????? ????</span>
                                </a>
                            </li>
                        <?php }?>

                        <?php if ($user && ($user->role == User::ROLE_ADMIN) || in_array('b-plan', $accesses)) {?>
                            <li <?=(($controller == 'industry/b-plan')) ? 'class="active"' : '';?>>
                                <a href="<?=Yii::$app->urlManager->createUrl(['/admin/industry/b-plan'])?>">
                                    <i class="fa  fa-check-square-o"></i> <span>?????????? ????????????????????????</span>
                                </a>
                            </li>
                        <?php }?>




                        <?php if ($user && ($user->role == User::ROLE_ADMIN)|| in_array('all-deffect', $accesses)) {?>
                            <li <?=(($controller == 'industry/all-deffect')) ? 'class="active"' : '';?>>
                                <a href="<?=Yii::$app->urlManager->createUrl(['/admin/industry/all-deffect'])?>">
                                    <i class="fa fa-remove"></i> <span>?????????????????? ??????????????????</span>
                                </a>
                            </li>
                        <?php }?>



                        <?php if ($user && ($user->role == User::ROLE_ADMIN)|| in_array('detail', $accesses)) {?>
                            <li <?=($controller == 'industry/detail')  ? 'class="active"' : '';?>>
                                <a href="<?=Yii::$app->urlManager->createUrl(['/admin/industry/detail'])?>">
                                    <i class="fa fa-cogs"></i> <span>????????????</span>
                                </a>
                            </li>
                        <?php }?>


                        <?php if ($user && (($user->role == User::ROLE_ADMIN)
                                || in_array('department', $accesses)
                                || in_array('deffect', $accesses)
                                || in_array('line', $accesses)
                                || in_array('product-model', $accesses)
                                || in_array('category', $accesses))) {?>
                            <li class="treeview<?=(($controller == 'industry/deffect') || ($controller == 'industry/department')|| ($controller == 'industry/line')|| ($controller == 'industry/product-model')) ? ' active' : '';?>">
                                <a href="#">
                                    <i class="fa fa-list"></i> <span>????????????????????</span>
                                    <span class="pull-right-container">
                                        <i class="fa fa-angle-left pull-right"></i>
                                    </span>
                                </a>
                                <ul class="treeview-menu">
                                    <?php if ($user->role == User::ROLE_ADMIN  || in_array('b-plan', $accesses) ) {?>
                                        <li><a href="<?=Yii::$app->urlManager->createUrl(['/admin/industry/deffect'])?>"><i class="fa fa-circle-o"></i> ??????????????</a></li>
                                    <?php }?>
                                    <?php if ($user->role == User::ROLE_ADMIN || in_array('department', $accesses)) {?>
                                        <li><a href="<?=Yii::$app->urlManager->createUrl(['/admin/industry/department'])?>"><i class="fa fa-circle-o"></i> ????????????</a></li>
                                    <?php }?>
                                    <?php if ($user->role == User::ROLE_ADMIN || in_array('line', $accesses)) {?>
                                        <li><a href="<?=Yii::$app->urlManager->createUrl(['/admin/industry/line'])?>"><i class="fa fa-circle-o"></i> ??????????</a></li>
                                    <?php }?>
                                    <?php if ($user->role == User::ROLE_ADMIN || in_array('b-plan', $accesses)) {?>
                                        <li><a href="<?=Yii::$app->urlManager->createUrl(['/admin/industry/product-model'])?>"><i class="fa fa-circle-o"></i> ????????????</a></li>
                                    <?php }?>

                                    <?php if ($user->role == User::ROLE_ADMIN || in_array('category?type=unit', $accesses)) {?>
                                        <li><a href="<?=Yii::$app->urlManager->createUrl(['/admin/category/', 'type'=>'unit'])?>"><i class="fa fa-circle-o"></i> ????. ??????????????????</a></li>
                                    <?php }?>


                                </ul>
                            </li>
                        <?php }?>

                        <!-- <?php if ($user && (($user->role == User::ROLE_ADMIN) || in_array('settings/contacts', $accesses) || in_array('settings/logo', $accesses))) {?>
                            <?php $admin_settings = ['settings'];?>
                            <li class="treeview<?=in_array($controller, $admin_settings) ? ' active' : '';?>">
                                <a href="#">
                                    <i class="fa fa-cogs"></i> <span>??????????????????</span>
                                    <span class="pull-right-container">
                                        <i class="fa fa-angle-left pull-right"></i>
                                    </span>
                                </a>
                                <ul class="treeview-menu">
                                    <?php if ($user && (($user->role == User::ROLE_ADMIN) || in_array('settings/contacts', $accesses))) {?>
                                        <li><a href="<?=Yii::$app->urlManager->createUrl(['/admin/settings/contacts'])?>"><i class="fa fa-circle-o"></i> ????????????????</a></li>
                                    <?php }?>
                                    <?php if ($user && (($user->role == User::ROLE_ADMIN) || in_array('settings/logo', $accesses))) {?>
                                        <li><a href="<?=Yii::$app->urlManager->createUrl(['/admin/settings/logo'])?>"><i class="fa fa-circle-o"></i> ??????????????</a></li>
                                    <?php }?>
                                </ul>
                            </li>
                        <?php }?> -->



                        <?php if($user && (($user->role == User::ROLE_ADMIN))) {?>
                            <li <?=($controller == 'moderator') ? 'class="active"' : '';?>>
                                <a href="<?=Yii::$app->urlManager->createUrl(['/admin/moderator'])?>">
                                    <i class="fa fa-user"></i> <span>????????????????????</span>
                                </a>
                            </li>
                        <?php }?>





                        <!-- <li <?=(($controller == 'default') && (($action == 'change-password') || ($action == 'profile'))) ? ' active' : '';?>>
                            <a href="<?=Yii::$app->urlManager->createUrl(['/admin/default/profile'])?>">
                                <i class="fa fa-pencil"></i> <span>?????? ??????????????</span>
                            </a>
                        </li> -->

                        <li>
                            <a href="<?=Yii::$app->urlManager->createUrl(['/main/log-out'])?>">
                                <i class="fa fa-sign-out"></i>
                                <span>??????????</span>
                            </a>
                        </li>
                    </ul>
                </section>
            </aside>
        <?php }?>

        <?=$content;?>

        <?php if (($controller != 'default') || (($controller == 'default') && ($action != 'index'))) {?>
            <footer class="main-footer text-center">
                <?=date('Y');?> Texnopark
            </footer>
            <div class="control-sidebar-bg"></div>
        <?php }?>
    </div>

<?php $this->endBody() ?>

</body>
</html>
<?php $this->endPage() ?>