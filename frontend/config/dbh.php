<?php
       
            $hostName = "localhost";
            $userName = "root";
            $password = "";
            $dbName = "triviatester";

            $conn = mysqli_connect($hostName, $userName, $password, $dbName);
            
            if (!$conn) {
                die("Connection failed: ".mysqli_connect_error());
            }