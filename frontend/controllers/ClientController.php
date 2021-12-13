<?php

namespace frontend\controllers;

use phpDocumentor\Reflection\Types\Parent_;
use Yii;
use yii\base\Model;
use common\models\User;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\rest\Response;
use frontend\models\Candidate;

class ClientController extends \yii\rest\Controller
{
    
    public function actionIndex()
    {   
        Yii::$app->request->enableCsrfValidation = false;
        $post = Yii::$app->request->post();
        $db = Yii::$app->db;
       
            $client = $post['access_token'];
            $username = $post['username'];
            $category = $post['category'];
            $quizamount = $post['quizamount'];
            $duration = $post['duration'];

            $candidate = $post['candidate'];
            $time_started = date("Y-m-d H:i:s");
            $quizid = rand(10, 1000);
                // $time_ended = date("Y-m-d H:i:s");
            // $client = str_split($client, 43)[0];

            // echo '<pre>';
            // echo $client;
            // echo '</pre';
            
            $db = Yii::$app->db;
            $users = $db->createCommand( sql: "SELECT * FROM user WHERE username = :username")
            ->bindValue(':username',  $username)
            ->queryAll();

            // echo '<pre>';
            // echo $users[0]['verification_token'];
            // echo '</pre';

            if ($client == $users[0]['verification_token']) {

                

                $users = $db->createCommand()->insert('candidate', [
                'candidate' => $candidate,
                'category' => $category,
                'client' => $client,
                'duration' => $duration,
                'time_start' => $time_started,
                'quizid' => $quizid,
                ])->execute();


                $fields = [
                    "category" => $category,
                ];
                $fileds_string = json_encode($fields) ;
                
                $headers = array(
                  "Content-Type: application/json"
                );
                
                $ch = curl_init();
                // curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                curl_setopt($ch, CURLOPT_URL, "https://opentdb.com/api.php?amount=$quizamount");
                curl_setopt($ch, CURLOPT_POST, true);
                // curl_setopt($ch, CURLOPT_POSTFIELDS, $fileds_string);
                
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                
                $result = curl_exec($ch);

                
                return $result;
                
                
                

               
            };
        
            
            
        
    }
    

        

    protected function verbs() {
        $verbs = parent::verbs();
        // $verbs['index'] = ['POST']; //methods you need in action
        //or
        $verbs['index'][] = 'POST'; //just add the 'POST' to "GET" and "HEAD"
        $verbs['index'][] = 'GET'; //just add the 'POST' to "GET" and "HEAD"
        // $verbs['index'][] = 'DELETE'; //just add the 'POST' to "GET" and "HEAD"
        // $verbs['index'][] = 'PUT'; //just add the 'POST' to "GET" and "HEAD"
        return $verbs;
     }


  
    
    
}
