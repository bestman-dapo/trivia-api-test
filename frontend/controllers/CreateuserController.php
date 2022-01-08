<?php

namespace frontend\controllers;

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo "This request method is not allowed";
    exit();
}

class CreateuserController extends \yii\rest\Controller
{
    public function actionIndex()
    {
        // return $this->render('index');

        require '../config/dbh.php';
        // include ".../vendor/autoload.php";

        // header("Access-Control-Allow-Origin: *");
        // header("Content-Type: application/json; charset=UTF-8");
        // header("Access-Control-Allow-Methods: POST");
        // header("Access-Control-Max-Age: 3600");
        // header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

        $data = json_decode(file_get_contents("php://input"));

        
        $usersName = $data->user_name;
        $usersPassword = $data->password;
        $mail = $data->email;
        $passwordRepeat = $data->password_repeat;
        $appUrl = "";
        $created_at = date("Y-m-d H:i:s");
        $clients_token = $data->clients_token;
        // $_SESSION['clients_token'] = $clients_token;
        $role = 0;

        
        $sql = "SELECT email FROM users WHERE clients_token = ?";
            $stmt = mysqli_stmt_init($conn);
            if (!mysqli_stmt_prepare($stmt, $sql)) {
                header("location:javascript://history.go(-1)?error=unabletohandlethisrequest");
              
                exit();
            }else {
            
                mysqli_stmt_bind_param($stmt, "s", $clients_token);
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);
                $result_row = mysqli_num_rows($result);
                
                
                // echo $result;
                if ($result_row == 1) {
                    $row = mysqli_fetch_assoc($result);
                    $clients_token = $row['email'];
                    // exit();
                
                    if (empty($usersName) || empty($usersPassword) || empty($mail) || empty($passwordRepeat)){
                        header("Location: ../signup.php?error=emptyfields&usersName=".$usersName."&mail=".$mail);
                        exit();
                    }elseif (!filter_var($mail, FILTER_VALIDATE_EMAIL) && !preg_match("/^[a-zA-Z0-9]*$/",$usersName)){
                        header("Location: ../signup.php?error=invalidmail&usersName");
                        exit();
                    }elseif (!filter_var($mail, FILTER_VALIDATE_EMAIL)){
                        header("Location: ../signup.php?error=invalidmail&usersName=".$usersName);
                        exit();
                    }elseif (!preg_match("/^[a-zA-Z0-9]*$/",$usersName)){
                        header("Location: ../signup.php?error=invalidname&mail=".$mail);
                        exit();
                    }elseif ($usersPassword !== $passwordRepeat){
                        header("Location: ../signup.php?error=passwordcheck&usersName=".$usersName."&mail=".$email);
                        exit();
                    }else{
                        // echo $clients_token; exit;
                        $sql = "INSERT INTO users (username, password_hash, email, role, created_at, clients_token, app_url) VALUE (?, ?, ?, ?, ?, ?, ?)";
                        $stmt = mysqli_stmt_init($conn);
                        if (!mysqli_stmt_prepare($stmt, $sql)) {
                            header("location: ../signup.php?error=sqlerror");
                            exit();
                        }else {
                            $hashedPwd = password_hash($usersPassword, PASSWORD_DEFAULT);
            
                            mysqli_stmt_bind_param($stmt, "sssssss", $usersName, $hashedPwd, $mail, $role, $created_at, $clients_token, $appUrl);
                            mysqli_stmt_execute($stmt);
                            echo "User registered successful";
                            exit();
                        }
                    }
                    mysqli_stmt_close($stmt);
                    mysqli_close($conn);
                }else {
                    echo "unauthorized Client";
                }
                } 
    }

}
