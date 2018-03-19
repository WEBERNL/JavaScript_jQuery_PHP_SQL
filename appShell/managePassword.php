<?php

require("config.php");


$id = $_POST['id'];
$currentPassword = $_POST['currentPassword'];
$updatedPassword = $_POST['updatedPassword'];
$hash = md5($updatedPassword);

// validating the user's current password
$query = "SELECT * FROM users WHERE id = :id AND raw_password = :currentPassword";
$query_params = array(':id' => $id, ':currentPassword' => $currentPassword);


try{
    $stmt = $db->prepare($query); 
    $stmt->execute($query_params); 
    $row = $stmt->fetch(); // this fetch method returns a row of data from the database that includes column name(s) and column value(s)
    $resultData = $row;

    if($resultData) { // if this data exists, this indicates that the "current password" entered by the user was accurate

        // updating the database to reflect the user's updated password
        $sql = "UPDATE users SET raw_password = :updatedPassword, password = :hash WHERE id = :id;";
        $sql_params = array(':updatedPassword' => $updatedPassword, ':hash' => $hash, ':id' => $id);

        try {  
            $stmt = $db->prepare($sql); 
            $stmt->execute($sql_params);
            exit();

        } catch(PDOException $ex) { 	       	
            http_response_code(500);
            echo json_encode(array(
                    'error' => array(
                    'msg' => 'Error on update password: ' . $ex->getMessage(),
                    'code' => $ex->getCode(),
                ),
            ));
            exit();
        } 
    }else{
        http_response_code(500); // since this code indicates an error, the error message appears in the "method called at "manage password" form submission" error section of appShell.js 
        echo json_encode(array(
            'error' => array(	
            'msg' => 'Current password error',
            ),
        ));
        exit();

    }	  
 } catch(PDOException $ex){ // note that PDOException is a class, and $ex is being established as an instance of the class
    http_response_code(500); // since this code indicates an error, the error message appears in the "method called at "manage password" form submission" error section of appShell.js 
    echo json_encode(array('errorMsg' => 'Error on update password: ' . $ex->getMessage(), 'errorCode' => $ex->getCode()));
    exit();	
}
?>