<?php
namespace backend\models;
use backend\components\SphinxClient;
use yii\base\Model;
use yii\db\ActiveQuery;
use yii\helpers\ArrayHelper;

class GoodsSearchForm extends Model
{
    public $name;
    public $sn;
    public $brand_id;
    public $goods_category_id;
    public $minPrice;
    public $maxPrice;

    public function rules()
    {
        return [
            ['name','string','max'=>50],
            ['sn','string'],
            ['minPrice','double'],
            ['maxPrice','double'],

        ];
    }

    public function search(ActiveQuery $query)
    {
        //加载表单提交的数据
        $this->load(\Yii::$app->request->get());
        if($this->name){
//            $query->andWhere(['like','name',$this->name]);
            //使用coreseek进行中文分词搜索
            $coreseek = new SphinxClient();
            $coreseek->SetServer ( '127.0.0.1', 9312);
            $coreseek->SetArrayResult ( true );
            $coreseek->SetMatchMode ( SPH_MATCH_ALL);
            $coreseek->SetLimits(0, 1000);
            $res = $coreseek->Query($this->name, 'goods');
            if(isset($res['matches'])){
                $ids =ArrayHelper::getColumn($res['matches'],'id');
                //拼接sql
                $query->where(['in','id',$ids]);
            }else{
                $query->where(['id'=>0]);
                return ;
            }



        }
        if($this->sn){
            $query->andWhere(['like','sn',$this->sn]);
        }
//        if($this->goods_category_id){
//            $query->andWhere(['like','goods_category_id',$this->goods_category_id]);
//        }
//        if($this->brand_id){
//            $query->andWhere(['like','brand_id',$this->brand_id]);
//        }
        /*if($this->sn){
            $query->andWhere(['like','sn',$this->sn]);
        }*/
        if($this->maxPrice){
            $query->andWhere(['<=','shop_price',$this->maxPrice]);
        }
        if($this->minPrice){
            $query->andWhere(['>=','shop_price',$this->minPrice]);
        }
    }
}