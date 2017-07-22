<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "goods_intro".
 *
 * @property integer $goods_id
 * @property string $count
 */
class GoodsIntro extends \yii\db\ActiveRecord
{
    //建立和 goods商品表的关系
    public function getGoods()
    {
        return $this->hasMany(Goods::className(),['id'=>'goods_id']);
    }
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'goods_intro';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['goods_id'], 'integer'],
            [['content'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'goods_id' => '商品id',
            'content' => '商品描述',
        ];
    }
}
