<?php
namespace frontend\models;
use \yii\db\ActiveRecord;

class Address extends ActiveRecord{
    public static function tableName()
    {
        return 'address';
    }
    public function rules()
    {
        return [
            [['name','province', 'city','area','detailed_address','tel',], 'required'],
            [['status'],'safe'],
        ];
    }
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => '收货人',
            'member_id' => '用户ID',
            'province' => '省',
            'city' => '市',
            'area' => '县',
            'detailed_address' => '详细地址',
            'tel' => '手机号码',
            'status' => '状态（1默认地址，0非默认）\')',
        ];
    }
    //建立和member的关系--
    public function getMember()
    {
        return $this->hasOne(Member::className(),['member_id'=>'id']);
    }

}