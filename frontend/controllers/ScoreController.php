<?php

namespace frontend\controllers;
use \Firebase\JWT\JWT;
use \Spatie\Async\Pool;
// require "./includes/dbh.php";
include "../../vendor/autoload.php";

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo "This request method is not allowed";
    exit();
}

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

class ScoreController extends \yii\rest\Controller
{
    
    // public $modelClass = Score::class;
    public function actionIndex()
    {
        $data = json_decode(file_get_contents("php://input"));

    $user_id = $data->users_id;
    $quiz_id = $data->quiz_id;
    $score = $data->score;
    $status = "";

    $pool = Pool::create();

    $pool->add(function ()  {
        @ob_end_clean();
        ignore_user_abort(true);
        header("Connection: close");
        ob_start();
        // $news = "happyyyy";
        // echo $news;
    
            $data = json_decode(file_get_contents("php://input"));
            $user_id = $data->users_id;
            $quiz_id = $data->quiz_id;
            $score = $data->score;
            $time_ended = date("Y-m-d H:i:s");
            $status = null;
    
            if (empty($user_id) || empty($quiz_id) || empty($score)) {
                $response = header("HTTP/1.1 400 BAD REQUEST");
                echo "Missing parameters";
                echo $response;
                exit();
            } else {
                $response = header("HTTP/1.1 200 OK");
                echo $response;
            }
        $size = ob_get_length();
        header("Content-length: $size");
        ob_end_flush();
        flush();
    })
    ->then(function () {
        require "../config/dbh.php";
        $data = json_decode(file_get_contents("php://input"));
        $user_id = $data->users_id;
        $quiz_id = $data->quiz_id;
        $score = $data->score;
        $time_ended = date("Y-m-d H:i:s");
        $status = null;
    
        if (empty($user_id) || empty($quiz_id) || empty($score)) {
            echo "Missing parameters";
            exit();
        } else {
    
            $sql = "SELECT * FROM triviaquiz WHERE users_id = ? AND quizid = ?";
            $stmt = mysqli_stmt_init($conn);
            if (!mysqli_stmt_prepare($stmt, $sql)) {
                exit();
            }else {
                mysqli_stmt_bind_param($stmt, "ss", $user_id, $quiz_id);
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);
                if ($row = mysqli_fetch_assoc($result)) {
                    $time_start = $row['time_start'];
                    $duration = $row['duration'];
                    $quiz_taken= $row['ID'];
    
                    $time_difference = strtotime($time_ended) - strtotime($time_start);
                    $time_difference = $time_difference/60;
                    $time_diff = floatval($time_difference);
                    $durationExt = $duration + 0.5;
                    // echo $durationExt;
                    // echo $time_diff;
                    
                    // exit();
                    if ($durationExt < $time_diff) {
                        // echo "to much";
                        // exit();
    
                        $status = 0;
    
                        $sql = "INSERT INTO triviascore (score, status, triviaquiz_id, user_id) VALUES (?, ?, ?, ?)";
                            $stmt = mysqli_stmt_init($conn);
                    
                            if (!mysqli_stmt_prepare($stmt, $sql)) {
                                exit();
                            }else {
                                mysqli_stmt_bind_param($stmt, "ssss", $score, $status, $quiz_taken, $user_id);
                                mysqli_stmt_execute($stmt);
                            }
    
                            $sql = "UPDATE triviaquiz SET time_ended = ? WHERE quizid = ?";
                            $stmt = mysqli_stmt_init($conn);
                    
                            if (!mysqli_stmt_prepare($stmt, $sql)) {
                                exit();
                            }else {
                                // echo "i am here";
                                // echo $quiz_taken;
                                // exit();
                                mysqli_stmt_bind_param($stmt, "ss", $time_ended, $quiz_id);
                                mysqli_stmt_execute($stmt);
                            }    
    
                    }else if ($durationExt >= $time_diff){
                        
                        $status = 1;
    
                        $sql = "INSERT INTO triviascore (score, status, triviaquiz_id, user_id) VALUES (?, ?, ?, ?)";
                            $stmt = mysqli_stmt_init($conn);
                    
                            if (!mysqli_stmt_prepare($stmt, $sql)) {
                                exit();
                            }else {
                                // echo "i am here";
                                // echo $quiz_taken;
                                // exit();
                                mysqli_stmt_bind_param($stmt, "ssss", $score, $status, $quiz_taken, $user_id);
                                mysqli_stmt_execute($stmt);
                            }
                        $sql = "UPDATE triviaquiz SET time_ended = ? WHERE quizid = ?";
                            $stmt = mysqli_stmt_init($conn);
                    
                            if (!mysqli_stmt_prepare($stmt, $sql)) {
                                exit();
                            }else {
                                // echo "i am here";
                                // echo $quiz_taken;
                                // exit();
                                mysqli_stmt_bind_param($stmt, "ss", $time_ended, $quiz_id);
                                mysqli_stmt_execute($stmt);
                            }
                        }
                    }
    
                }
            
        }
    
        // print("success");
    })
    ->catch(function (Throwable $exception) {
        echo $exception;
    });
    
    
    $pool->wait();

        // return $this->render('index');
    }

    
}
