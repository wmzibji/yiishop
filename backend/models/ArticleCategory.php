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
class ArticleCategory extends \yii\db\ActiveRecord
{
    public $code;//验证码
    //建立和文章表的关系
    public function getArticle()
    {
        return $this->hasMany(Article::className(),['article_category_id'=>'id']);//hasMany 返回多个对象 用数组封装
    }
    //获取文章分类选项
    public static function getCategoryOptions()
    {
        return ArrayHelper::map(ArticleCategory::find()->all(),'id','name');
    }
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
        return 'article_category';
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
            'sort' => '排序',
            'status' => '状态',
        ];
    }
}
