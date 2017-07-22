<?php

namespace backend\models;
use yii\helpers\ArrayHelper;


/**
 * This is the model class for table "brand".
 *
 * @property integer $id
 * @property string $name
 * @property string $intro
 * @property string $logo
 * @property integer $sort
 * @property integer $status
 */
class Brand extends \yii\db\ActiveRecord
{
    //建立和商品表的关系
    public function getGoods()
    {
        return $this->hasMany(Goods::className(),['brand_id'=>'id']);
    }
    //获取品牌分类选项
    public static function getBrandOptions()
    {
        return ArrayHelper::map(Brand::find()->all(),'id','name');
    }
    public $imgFile;//保存文件上传对象
    public $code;//验证码
    public static function getStatusOptions($del_options=true){
        $status_options=[
            -1=>'删除',0=>'隐藏',1=>'正常'
        ];
        if($del_options){
            unset($status_options['-1']);
        }
        return $status_options;
    }


    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'brand';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['intro'], 'string'],
            [['sort', 'status'], 'integer'],
            [['name'], 'string', 'max' => 50],
            [['logo'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => '名称',
            'intro' => '简介',
            'logo' => 'LOGO图片',
            'sort' => '排序',
            'status' => '状态',
        ];
    }
}
