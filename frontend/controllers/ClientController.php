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
        // Yii::$app->request->enableCsrfValidation = false;
        // $post = Yii::$app->request->post();
        // $db = Yii::$app->db;
       
        //     $client = $post['access_token'];
        //     $username = $post['username'];
        //     $category = $post['category'];
        //     $quizamount = $post['quizamount'];
        //     $duration = $post['duration'];

        //     $candidate = $post['candidate'];
        //     $time_started = date("Y-m-d H:i:s");
        //     $quizid = rand(10, 1000);
        //         // $time_ended = date("Y-m-d H:i:s");
        //     // $client = str_split($client, 43)[0];
            

            
            
        //     $db = Yii::$app->db;
        //     $users = $db->createCommand( sql: "SELECT * FROM user WHERE username = username")
        //     ->bindValue('username',  $username)
        //     ->queryAll();

            

        //     if ($client == $users[0]['verification_token']) {

                

        //         $users = $db->createCommand()->insert('candidate', [
        //         'candidate' => $candidate,
        //         'category' => $category,
        //         'client' => $client,
        //         'duration' => $duration,
        //         'time_start' => $time_started,
        //         'quizid' => $quizid,
        //         ])->execute();


        //         $fields = [
        //             "category" => $category,
        //         ];
        //         $fileds_string = json_encode($fields) ;
                
        //         $headers = array(
        //           "Content-Type: application/json"
        //         );
                
        //         $ch = curl_init();
        //         // curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        //         curl_setopt($ch, CURLOPT_URL, "https://opentdb.com/api.php?amount=$quizamount");
        //         curl_setopt($ch, CURLOPT_POST, true);
        //         // curl_setopt($ch, CURLOPT_POSTFIELDS, $fileds_string);
                
        //         curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                
        //         $result = curl_exec($ch);

                
        //         var_dump($result) ;
                
                
                

               
        //     };
        header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/json; charset=UTF-8");
        header("Access-Control-Allow-Methods: POST");
        header("Access-Control-Max-Age: 3600");
        header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
            
        require '../config/dbh.php';
        // session_start();
        $data = json_decode(file_get_contents("php://input"));
        
        $usersName = $data->usersName;
        $usersPassword = $data->password;
        $mail = $data->mail;
        $passwordRepeat = $data->passwordRepeat;
        $appUrl = $data->app_url;
        $created_at = date("Y-m-d H:i:s");
        $clients_token = bin2hex(openssl_random_pseudo_bytes(20));
        
        $role = 1;

        if (empty($usersName) || empty($usersPassword) || empty($mail) || empty($passwordRepeat)){
            header("Location: ../index.php?error=emptyfields&usersName=".$usersName."&mail=".$mail);
            exit();
        }elseif (!filter_var($mail, FILTER_VALIDATE_EMAIL) && !preg_match("/^[a-zA-Z0-9]*$/",$usersName)){
            header("Location: ../index.php?error=invalidmail&usersName");
            exit();
        }elseif (!filter_var($mail, FILTER_VALIDATE_EMAIL)){
            header("Location: ../index.php?error=invalidmail&usersName=".$usersName);
            exit();
        }elseif (!preg_match("/^[a-zA-Z0-9]*$/",$usersName)){
            header("Location: ../index.php?error=invalidname&mail=".$mail);
            exit();
        }elseif ($usersPassword !== $passwordRepeat){
            header("Location: ../index.php?error=passwordcheck&usersName=".$usersName."&mail=".$email);
            exit();
        }else{
            // echo $clients_token; exit;
            $sql = "INSERT INTO users (username, password_hash, email, role, created_at, clients_token, app_url) VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt = mysqli_stmt_init($conn);
            if (!mysqli_stmt_prepare($stmt, $sql)) {
                header("location: ../index.php?error=sqlerror");
                exit();
            }else {
                $hashedPwd = password_hash($usersPassword, PASSWORD_DEFAULT);

                mysqli_stmt_bind_param($stmt, "sssssss", $usersName, $hashedPwd, $mail, $role, $created_at, $clients_token, $appUrl);
                mysqli_stmt_execute($stmt);
                // $_SESSION['clients_token'] = $clients_token;
                echo nl2br("This is your Client's Token: " .$clients_token. " \n Copy it before closing browser or refreshing. It will be used for authorization");


                session_destroy();
                exit();
            }
        }
        mysqli_stmt_close($stmt);
        mysqli_close($conn);
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
