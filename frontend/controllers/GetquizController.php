<?php

namespace frontend\controllers;

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo "This request method is not allowed";
    exit();
}

use \Firebase\JWT\JWT;
include "../../vendor/autoload.php";

class GetquizController extends \yii\rest\Controller
{
    public function actionIndex()
    {
        // return $this->render('index');

        header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/json; charset=UTF-8");
        header("Access-Control-Allow-Methods: POST");
        header("Access-Control-Max-Age: 3600");
        header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

        $secret_key = "cbc85655209e078d601e4558b57c42b587202e3e";
$jwt = null;


$data = json_decode(file_get_contents("php://input"));

$authHeader = $_SERVER['HTTP_AUTHORIZATION'];
$time_start = date("Y-m-d H:i:s");
$time_ended = "";

$users_ID = $data->users_id;
$users_ID = number_format($users_ID);

$quiz_amount = $data->quiz_amount;

$duration = number_format($quiz_amount/2);

$quiz_id = rand(10, 10000);

$arr = explode(" ", $authHeader);

$jwt = $arr[1];

// echo $jwt;
// exit();
// echo $users_ID;
//     exit();
// $decoded = JWT::decode($jwt, $secret_key, array('HS256'));
// echo $decoded;
// exit();

if($jwt){
    

    try {
        
        include "../config/dbh.php";

        JWT::decode($jwt, $secret_key, array('HS256'));
        
        
        // Create and start a Quiz 
        // Create and start a Quiz 
        // Create and start a Quiz 

        $sql = "INSERT INTO triviaquiz (time_start, time_ended, duration, users_id, quizid) VALUES (?, ?, ?, ?, ?)";
        $stmt = mysqli_stmt_init($conn);
        
        if (!mysqli_stmt_prepare($stmt, $sql)) {
          
            echo "Request could nor be processed";
            exit();
        }else {

            if ( mysqli_stmt_bind_param($stmt, "sssss", $time_start, $time_ended, $duration, $users_ID, $quiz_id) && mysqli_stmt_execute($stmt)) {
              // fetch questions and answers from quiz endpoint
              // fetch questions and answers from quiz endpoint
              // fetch questions and answers from quiz endpoint

              $headers = array(
                "Content-Type: application/json"
              );
              
              $ch = curl_init();
              curl_setopt($ch, CURLOPT_URL, "https://opentdb.com/api.php?amount=10");
              curl_setopt($ch, CURLOPT_POST, true);
              
              curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
              
              $result = curl_exec($ch);
              
              echo "Quiz id: ".$quiz_id."\r\n";
              var_dump($result);

            } else {
              echo "invalid User Id";
            }
            
        }
    }
    catch (Exception $e){

        http_response_code(401);

        echo json_encode(array(
            "message" => "Access denied.",
            "error" => $e->getMessage()
        ));
    }

}
    }

}
