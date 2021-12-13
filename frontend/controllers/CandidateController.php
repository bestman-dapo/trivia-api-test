<?php

namespace frontend\controllers;
use frontend\models\Candidate;
use Yii;
use yii\base\Model;

class CandidateController extends \yii\rest\Controller
{   
   

    public function actionIndex()
    {
        $post = Yii::$app->request->post();
        $db = Yii::$app->db;


        $candidate = $post['candidate'];
        $category = $post['category'];
        $access_token = $post['access_token'];
        $score = $post['score'];
        $time_ended = date("Y-m-d H:i:s");
        $quizid = $post['quizid'];

        $db->createCommand()->update('candidate', [
           'score' => $score,
           'time_ended' => $time_ended
        ],[
            'client' => $access_token,
            'candidate' => $candidate
        ])->execute();

        $time_difference = $db->createCommand( sql: "SELECT time_start, time_ended, TIMESTAMPDIFF()");
        
        $result = $db->createCommand( sql: "SELECT * FROM candidate WHERE candidate = :username AND client = :access_token AND quizid = :quizid")->
        bindValue(':username', $candidate)
        ->bindValue(':access_token', $access_token)
        ->bindValue(':quizid', $quizid)
        ->queryOne(); 
        
        $start_time = strtotime($result['time_start']) ;  
        $end_time = strtotime($result['time_ended']); 
        $interval =  $end_time - $start_time;
        
        return floor($interval/(60*60));
        // echo $interval;
        // echo $start_time;
        // echo $end_time;
        // if (!$result){
        //     echo "One of the parameters is incorrect";
        // } else {
        //     print_r($result);            
        // }
        
        
            

    }

    

    

}
