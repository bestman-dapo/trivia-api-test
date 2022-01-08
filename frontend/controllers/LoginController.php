<?php

namespace frontend\controllers;

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo "This request method is not allowed";
    exit();
}


// include "../vendor/autoload.php";
use \Firebase\JWT\JWT;

class LoginController extends \yii\rest\Controller
{
    public function actionIndex()
    {
        // return $this->render('index');
    require '../config/dbh.php';
    $data = json_decode(file_get_contents("php://input"));
      
    $emailOrUsername = $data->emailOrUsername;
    $usersPassword = $data->password;
    $clients_token = $data->clients_token;


    if (empty($emailOrUsername) || empty($usersPassword)) {
        $errorMsg = "empty field(s)";
        return $errorMsg;
        exit();
    } else{
        $sql = "SELECT email FROM users WHERE clients_token = ?";
        $stmt = mysqli_stmt_init($conn);

        if (!mysqli_stmt_prepare($stmt, $sql)) {
            echo "Your request cannot be processed at the moment";
            exit();
        }else {
            mysqli_stmt_bind_param($stmt, "s", $clients_token);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            $result_row = mysqli_num_rows($result);

            // echo $result_row;
            // exit();

            if ($result_row == 1){
                $row = mysqli_fetch_assoc($result);
                $clients_email = $row['email'];

                // echo $clients_email;
                // exit();

                $sql = "SELECT * FROM users WHERE username=? OR email=?;";
                $stmt = mysqli_stmt_init($conn);
                if (!mysqli_stmt_prepare($stmt, $sql)) {
                    echo "Your request cannot be processed at the moment";
                    exit();
                }else{
                        mysqli_stmt_bind_param($stmt, "ss", $emailOrUsername, $emailOrUsername);
                        mysqli_stmt_execute($stmt);
                        $result = mysqli_stmt_get_result($stmt);
                        $row = mysqli_fetch_assoc($result);
                        $row_result = mysqli_num_rows($result);
                        if ($row_result == 1) {
                            $pwdcheck = password_verify($usersPassword, $row['password_hash']);
                            if ($pwdcheck !== true) {
                                echo $row['password_hash'];
                                exit();
                            }elseif($pwdcheck == true){
                                $user_token = $row['clients_token'];
                                $current_user = $row['username'];
                                $id = $row['ID'];
                        
                                if ($user_token != $clients_email) {
                                    echo "unauthorized client";
                                    exit();
                                }else {

                                    $secret_key = $clients_token;
                                    $issuer_claim = "THE_ISSUER"; // this can be the servername
                                    $audience_claim = "THE_AUDIENCE";
                                    $issuedat_claim = time(); // issued at
                                    $notbefore_claim = $issuedat_claim + 10; //not before in seconds
                                    $expire_claim = $issuedat_claim + 3600; // expire time in seconds
                                    $token = array(
                                        "iss" => $issuer_claim,
                                        "aud" => $audience_claim,
                                        "iat" => $issuedat_claim,
                                        "nbf" => $notbefore_claim,
                                        "exp" => $expire_claim,
                                        "data" => array(
                                            "id" => $id,
                                            "username" => $current_user
                                    ));

                                    http_response_code(200);

                                    $jwt = JWT::encode($token, $secret_key);
                                    echo json_encode(
                                        array(
                                            "message" => "Successful login.",
                                            "username" => $current_user,
                                            "id" => $id,
                                            "access_token" => $jwt,
                                            "expireAt" => $expire_claim
                                        ));
                                
                                    // header ("location:http://localhost/tester.php?message=success");
                                }
                        
                            }else {
                                http_response_code(400);
                                exit();
                            }
                            }else{
                                echo "This user cannot be found on the server";
                                exit();
                            }
                    }
            }else {
                echo "unauthorized Client";
            }

        }


        
    }

    }

}
