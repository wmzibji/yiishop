<?php
namespace backend\models;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
class ArticleDetail extends ActiveRecord
{
    //建立和文章表的关系
    public function getArticle()
    {
        return $this->hasMany(Article::className(),['id'=>'article_id']);//hasMany 返回多个对象 用数组封装
    }
    //获取文章详情
//    public static function getArticleDetail()
//    {
//        return ArrayHelper::map(ArticleDetail::find()->all(),'id','name');
//    }
    public static function tableName()
    {
        return 'article_detail';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['content'], 'string'],
            ['article_id', 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'article_id' => '文章ID',
            'content' => '文章内容',
        ];
    }
}