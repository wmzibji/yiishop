<?php

namespace backend\models;

use Yii;
use yii\bootstrap\Html;

/**
 * This is the model class for table "goods".
 *
 * @property integer $id
 * @property string $name
 * @property string $sn
 * @property string $logo
 * @property integer $goods_category_id
 * @property integer $brand_id
 * @property string $market_price
 * @property string $shop_price
 * @property integer $stock
 * @property integer $is_on_sale
 * @property integer $status
 * @property integer $sort
 * @property integer $create_time
 * @property integer $view_times
 */
class Goods extends \yii\db\ActiveRecord
{
    //建立和品牌表的关系
    public function getBrand()
    {
        return $this->hasOne(Brand::className(),['id'=>'brand_id']);//hasMany 返回多个对象 用数组封装
    }
    //建立和商品分类表的关系
    public function getGoodsCategory()
    {
        return $this->hasOne(GoodsCategory::className(),['id'=>'goods_category_id']);//hasMany 返回多个对象 用数组封装
    }
    //建立和 goods_intro 商品详情表的关系
    public function getGoodsIntro()
    {
        return $this->hasOne(GoodsIntro::className(),['goods_id'=>'id']);
    }
    //建立和 goods_gallery 商品图片表的关系
    public function getGoodsGallery()
    {
        return $this->hasMany(GoodsGallery::className(),['goods_id'=>'id']);
    }
    //获取图片轮播数据
    public function getPics()
    {
        $images = [];
        foreach ($this->goodsGallery as $img){
            $images[] = Html::img($img->path);
        }
        return $images;
    }
    //状态
    public static $status_options=[0=>'回收站',1=>'正常'];
    //是否在售
    public static $sale_options=[0=>'下架',1=>'在售'];

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'goods';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['goods_category_id', 'brand_id', 'stock', 'is_on_sale', 'status', 'sort', 'create_time', 'view_times'], 'integer'],
            [['market_price', 'shop_price'], 'number'],
            [['name', 'sn'], 'string', 'max' => 100],
            [['logo'], 'string', 'max' => 255],
            ['goods_category_id','required'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => '商品名称',
            'sn' => '货号',
            'logo' => 'LOGO图片',
            'goods_category_id' => '商品分类id',
            'brand_id' => '品牌分类',
            'market_price' => '市场价格',
            'shop_price' => '商品价格',
            'stock' => '库存',
            'is_on_sale' => '是否在售',
            'status' => '状态',
            'sort' => '排序',
            'create_time' => '添加时间',
            'view_times' => '浏览次数',
        ];
    }
}
