<?php

require("config.php");


$id = $_POST['id'];
$username = $_POST['username'];
$name = $_POST['name'];
$email = $_POST['email'];


// verifying that username doesn't already exist in database EXCEPT for this user
$query = "SELECT * FROM users WHERE username = :username";
$query_params = array(':username' => $username);

    try { 
        $stmt = $db->prepare($query); 
        $stmt->execute($query_params); 
        $row = $stmt->fetch();
            if ($row){
                if($row["id"] !== $id){
                http_response_code(500);
                echo json_encode(array(
                        'error' => array(
                            'msg' => 'This username is already registered',
                        ),
        
                ));
                exit();
                }   
                
            } 
    } catch(PDOException $ex){ 
        http_response_code(500);
        echo json_encode(array(
            'error' => array(	
            'msg' => 'Error on select checking for dupes: ' . $ex->getMessage(),
            'code' => $ex->getCode(),
            ),
        ));
        exit();
    } 

   


// after verification, updating username, name, and email in database
$sql = "UPDATE users SET username = :username, name = :name, email = :email WHERE id = :id";

$sql_params = array(
':username' => $username, 
':name' => $name,
':email' => $email,
':id' => $id 
); 	



    try {  
    $stmt = $db->prepare($sql); 
    $stmt->execute($sql_params);

    } catch(PDOException $ex) { 	       	
    http_response_code(500);
    echo json_encode(array(
            'error' => array(
            'msg' => 'Error on update user: ' . $ex->getMessage(),
            'code' => $ex->getCode(),
        ),
    ));
    exit();
    } 	  


// selecting data (using input parameter) and assigning the query result to a PHP variable (called "resultData"), then encoding the PHP variable using PHP's json_encode() function
$query = "SELECT id, username, name, email FROM users WHERE id = :id";
$query_params = array(':id' => $id);

    try { 
        $stmt = $db->prepare($query); 
        $stmt->execute($query_params);
        $row = $stmt->fetch(); // this fetch method returns a row of data from the database that includes column name(s) and column value(s)
        $resultData = $row;
        
        echo json_encode($resultData); // this echo statement is rendered in appShell.js since it was appShell.js that called manageAccount.php
        exit();
        
    } catch(PDOException $ex){ // note that PDOException is a class, and $ex is being established as an instance of the class
        http_response_code(500);
        echo json_encode(array(
            'error' => array(	
            'msg' => 'Error on select user: ' . $ex->getMessage(),
            'code' => $ex->getCode(),
            ),
        ));
        exit();
    } 


?>
