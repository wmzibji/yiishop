<?php

namespace backend\models;



class Article extends \yii\db\ActiveRecord
{
    public $code;//验证码
    //建立和文章分类表的关系
    public function getArticleCategory()
    {
        return $this->hasOne(ArticleCategory::className(),['id'=>'article_category_id']);//hasMany 返回多个对象 用数组封装
    }
    //建立和文章分类模型（ArticleDetail）的关系    1对1
    //先定义get方法
    public function getArticleDetail()
    {
        //hasOne表示1对1  参数1 表示对应模型的完整类名
        //参数2 是一个数组 [k=>v]  k表示ArticleDetail的主键键名   v表示ArticleDetail在Article表的关联主键
        return $this->hasOne(ArticleDetail::className(),['article_id'=>'id']);//hasOne 返回一个对象
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


    public static function tableName()
    {
        return 'article';
    }

    public function rules()
    {
        return [
            [['intro'], 'string'],
            [['sort', 'status','article_category_id'], 'integer'],
            [['name'], 'string', 'max' => 250],
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
            'article_category_id' => '文章分类id',
            'sort' => '排序',
            'status' => '状态',
            'create_time' => '创建时间',
        ];
    }
}
