<?php
/**
 * Created by PhpStorm.
 * User: or
 * Date: 2017/7/31
 * Time: 15:40
 */

namespace backend\controllers;


use backend\filters\RbacFilter;
use yii\web\Controller;

class BaseController extends Controller
{
    public function behaviors()
    {
        return [
            'myFilter' => [
                'class' => RbacFilter::className(),
                'nockeck'=>[
                    'brand/s-upload',
                    'user/login',
                    'user/logout',
                ]
            ]
        ];
    }

}