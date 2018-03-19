<?php
	require("config.php");


    $token = $_POST['rememberToken'];
   
    $query = "SELECT id, name, username , email FROM users WHERE remember_token = :token";
    $query_params = array(':token' => $token);
    
 
    try{
        $stmt = $db->prepare($query); 
        $stmt->execute($query_params); 
    
        $row = $stmt->fetch(); // this fetch method returns a row of data from the database that includes column name(s) and column value(s)
        $resultData = $row;
        

        if($resultData) {
            echo json_encode($resultData); // this echo statement is rendered in appShell.js since it was appShell.js that called automaticLogin.php
            exit();	
        } else {              
            http_response_code(500); // since this code indicates an error, the error message appears in the "method called at automaticLogin" error section of appShell.js 
            echo json_encode(array(
                'error' => array(	
                'msg' => 'Login to continue',
                ),
            ));
            exit();
		}
   
    }catch(PDOException $ex){ // note that PDOException is a class, and $ex is being established as an instance of the class
        http_response_code(500); // since this code indicates an error, the error message appears in the "method called at automaticLogin" error section of appShell.js 
        echo json_encode(array('errorMsg' => 'Error on login: ' . $ex->getMessage(), 'errorCode' => $ex->getCode()));
        exit();	
    }

    
?>