<?php
	require("config.php");
   
    
    /* assessing if the value entered in the "username" input element is a username or email address by creating variables and then querying the username and email columns in the database;
       selecting user data (using input parameters) and assigning the query result to a PHP variable (called "resultData") */
    $username = $_POST['username'];
    $email = $_POST['username'];
    $password = $_POST['password'];
    $password = md5($password);
  
    
    $query = "SELECT id, username, name, email FROM users WHERE (username = :username AND password = :password) OR (email = :email AND password = :password)";
    $query_params = array(':username' => $username, ':password' => $password, ':email' => $email, ':password' => $password);
    
 
    try{
        $stmt = $db->prepare($query); 
        $stmt->execute($query_params); 
        $row = $stmt->fetch(); // this fetch method returns a row of data from the database that includes column name(s) and column value(s)
        $resultData = $row;
         
        if($resultData) {
            if(empty($_POST['rememberToken'])){
                echo json_encode($resultData); // this echo statement is rendered in appShell.js since it was appShell.js that called login.php
                exit();	
            } else {
                // inserting token data (using input parameters) in database
                $id = $resultData['id'];
                $token = $_POST['rememberToken'];


                $sql = "UPDATE users SET remember_token = :token WHERE id = :id";
                $sql_params = array(':token' => $token, ':id' => $id);
               
                try{
                    $stmt = $db->prepare($sql); 
                    $stmt->execute($sql_params);
               
                    echo json_encode($resultData); // this echo statement is rendered in appShell.js since it was appShell.js that called login.php
                    exit();

                }catch(PDOException $ex) { 	       	
                    http_response_code(500); // since this code indicates an error, the error message appears in the "method called at login" error section of appShell.js 
                    echo json_encode(array(
                            'error' => array(
                            'msg' => 'Error on updating remember me option' . $ex->getMessage(),
                            'code' => $ex->getCode(),
                        ),
                    ));
                    exit();
                }
            } 
        } else {          
                http_response_code(500); // since this code indicates an error, the error message appears in the "method called at login" error section of appShell.js 
		        echo json_encode(array(
			        'error' => array(	
			        'msg' => 'Login credential error',
                    ),
                ));
                exit();
		}
   
    }catch(PDOException $ex){ // note that PDOException is a class, and $ex is being established as an instance of the class
        http_response_code(500); // since this code indicates an error, the error message appears in the "method called at login" error section of appShell.js 
        echo json_encode(array('errorMsg' => 'Error on login: ' . $ex->getMessage(), 'errorCode' => $ex->getCode()));
        exit();	
    }

    
?>