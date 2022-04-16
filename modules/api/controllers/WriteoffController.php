<?php
namespace app\modules\api\controllers;

use Yii;
use yii\web\Response;
use yii\rest\Controller;
use yii\web\HttpException;
use yii\data\ActiveDataProvider;
use yii\filters\auth\HttpBearerAuth;
use yii\helpers\ArrayHelper;
use yii\web\UploadedFile;

use app\models\Category;
use app\models\writeoff\Writeoff;
use app\models\writeoff\WriteoffProduct;

class WriteoffController extends Controller {
    public $user;

    public function beforeAction($action) {
        $this->enableCsrfValidation = false;

        if (Yii::$app->request->headers->has('OPTIONS')) {
            throw new HttpException(200, 'OK');
        }

        if (!Yii::$app->user->isGuest) {
            $this->user = Yii::$app->user->identity;
        }

        return parent::beforeAction($action);
    }

    public function behaviors() {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => HttpBearerAuth::className(),
            'optional' => ['*']
        ];
        return $behaviors;
    }

    public $serializer = [
        'class' => 'yii\rest\Serializer',
        'collectionEnvelope' => 'data',
    ];

    public function actionIndex($stock_id = null) {
        $query = Writeoff::find();

        if ($stock_id) {
            $query->where(['stock_id'=>$stock_id]);
        }

        return new ActiveDataProvider([
            'query' => $query,
            'pagination' => false,
            'sort' => ['defaultOrder' => ['id'=>'desc']]
        ]);
    }

    public function actionView($id) {
        $model = Writeoff::findOne($id);
        return ['data'=>$model];
    }

    public function actionHistory($stock_id) {
        $writeoffs = ArrayHelper::map(Writeoff::find()->where(['stock_id'=>$stock_id])->all(), 'id', 'id');

        $query = WriteoffProduct::find()->where(['in', 'writeoff_id', $writeoffs]);

        return new ActiveDataProvider([
            'query' => $query,
            'pagination' => false,
            'sort' => ['defaultOrder' => ['id'=>'desc']]
        ]);
    }

    public function actionCreate() {
        $post = Yii::$app->request->post();

        $model = new Writeoff;
        $model->setAttributes($post);

        if (!$model->validate()) {
            Yii::$app->response->statusCode = 422;
            return ['errors'=>$model->errors];
        }

        if ($model->saveObject()) {
            return ['data' => $model];
        }
    }
}
?>