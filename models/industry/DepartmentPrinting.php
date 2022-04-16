<?php

namespace app\models\industry;

use app\models\industry\handbook\BDepartment;
use app\models\industry\handbook\ProductModel;
use app\models\user\User;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "b_department_printing".
 *
 * @property int $id
 * @property int|null $department_id
 * @property int|null $user_id
 * @property int $model_id
 * @property int|null $is_part
 * @property string|null $current_operation
 * @property string $number_poddon
 * @property int|null $model_part
 * @property int $amount
 * @property int $status
 * @property int|null $created_at
 * @property int|null $updated_at
 *
 * @property User $user
 * @property BModel $model
 */
class DepartmentPrinting extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'b_department_printing';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['department_id', 'user_id', 'model_id',  'is_defect',  'is_ckeck', 'amount', 'status', 'created_at', 'updated_at'], 'integer'],
            [['model_id', 'number_poddon', 'amount', 'status'], 'required'],
            ['current_operation', 'unique'],
            [['current_operation', 'number_poddon'], 'string', 'max' => 255],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
            [['model_id'], 'exist', 'skipOnError' => true, 'targetClass' => ProductModel::className(), 'targetAttribute' => ['model_id' => 'id']],
        ];
    }


    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if($insert){
                $this->dates = date('Y-m-d H:i');

                $this->articul = $this->model->articul;
            }
            return true;
        } else {
            return false;
        }
    }



    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'model_id' => 'Модель',
            'user_id' => 'Пользователь',
            'department_id' => 'Отдел',
            'part_model' => 'Часть',
            'current_operation' => 'Текущая операция',
            'number_poddon' => 'Номер поддона',
            'is_defect' => 'Дефект',
            'amount' => 'Кол-во',
            'status' => 'Статус',
            'created_at' => 'Дата создания',
            'updated_at' => 'Дата изменения',
        ];
    }

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],
                ],
//                 'value' => new Expression('NOW()'),
            ],
        ];
    }

    public function removeObject(){
        if($this->buffer){
            foreach ($this->buffer as $k => $buffer)
                $buffer->removeObject();
        }
        if($this->deffect){
            foreach ($this->deffect as $k => $defect)
                $defect->removeObject();
        }
        return $this->delete();
    }



    public  function nameModel($model_id){
        $model_name = ProductModel::findOne($model_id);
        return $model_name->name_ru;
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * Gets query for [[Model]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getModel()
    {
        return $this->hasOne(ProductModel::className(), ['id' => 'model_id']);
    }
    public function getDepartment()
    {
        return $this->hasOne(BDepartment::className(), ['id' => 'department_id']);
    }


    public function getDeffect()
    {
        return $this->hasMany(AllDeffect::className(), ['dep_id' => 'id']);
    }
    public function getBuffer()
    {
        return $this->hasMany(BufferZone::className(), ['dep_id' => 'id']);
    }
}
