

<?php
	require("config.php");


	// verifying that username doesn't already exist in database
	$username = $_POST['username'];
	$password = $_POST['password'];
	$name = $_POST['name'];
	$email = $_POST['email'];
	$hash = md5($password);
	
    $query = "SELECT * FROM users WHERE username = :username";
	$query_params = array(':username' => $username);

    try { 
        $stmt = $db->prepare($query); 
		$stmt->execute($query_params);
		$row = $stmt->fetch(); // this fetch method returns a row of data from the database that includes column name(s) and column value(s)
		if($row) { 
			http_response_code(500);
			echo json_encode(array(
					'error' => array(
						'msg' => 'This username is already registered',
					),
	
			));
			exit();
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
	
   
   
	
	// after verifying uniqueness, inserting username and other credentials in database
	$sql = 'INSERT INTO users (name, username, email, raw_password, password) 
			VALUES (:name, :username, :email, :rawPassword, :password)';
	
	$sql_params = array(
		':username' => $username, 
		':name' => $name, 
		':email' => $email, 
		':rawPassword' => $password, 		
		':password' => $hash
	); 	
		
	
	
	try {  
            $stmt = $db->prepare($sql); 
            $stmt->execute($sql_params); 
    } catch(PDOException $ex) { 	       	
			http_response_code(500);
			echo json_encode(array(
					'error' => array(
					'msg' => 'Error on insert of additional user: ' . $ex->getMessage(),
					'code' => $ex->getCode(),
				),
			));
			exit();
    } 	  

	// selecting data (using input parameter) and assigning the query result to a PHP variable (called "outData"), then encoding the PHP variable using PHP's json_encode() function
	$query = "SELECT id, username, name, email FROM users WHERE username = :userName";
    $query_params = array(':userName' => $username);

    try { 
        $stmt = $db->prepare($query); 
        $stmt->execute($query_params); 
		$row = $stmt->fetch(); // this fetch method returns a row of data from the database that includes column name(s) and column value(s)
		$outData = $row;
		 
		echo json_encode($outData); // this echo statement is rendered in appShell.js since it was appShell.js that called signup.php
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