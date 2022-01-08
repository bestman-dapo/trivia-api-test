<?php
       
            $hostName = "localhost";
            $userName = "oladapo";
            $password = "Password_1234";
            $dbName = "triviatester";

            $conn = mysqli_connect($hostName, $userName, $password, $dbName);
            
            if (!$conn) {
                die("Connection failed: ".mysqli_connect_error());
            }