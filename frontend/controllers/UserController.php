<?php

namespace frontend\controllers;
use common\models\User;
use Yii;

class UserController extends \yii\rest\ActiveController
{

   public $modelClass = User::class;

   
    // public function actionIndex()
    // {
    //     return $this->render('index');
    // }
    protected function verbs() {
        $verbs = parent::verbs();
        // $verbs['index'] = ['POST']; //methods you need in action
        //or
        $verbs['index'][] = 'POST'; //just add the 'POST' to "GET" and "HEAD"
        $verbs['index'][] = 'GET'; //just add the 'POST' to "GET" and "HEAD"
        $verbs['index'][] = 'DELETE'; //just add the 'POST' to "GET" and "HEAD"
        $verbs['index'][] = 'PUT'; //just add the 'POST' to "GET" and "HEAD"
        return $verbs;
     }
}
