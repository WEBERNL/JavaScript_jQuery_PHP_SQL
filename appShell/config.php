<?php
	
    // These variables define the connection information for your MySQL database 
    $username = "root"; 
    $password = ""; 
    $host = "localhost"; 
    $dbname = "appshell"; 
    
    // Sample syntax to connect to a database:
    // $connection = new PDO('mysql:host=localhost;dbname=mydb;charset=utf8', 'root', 'root');
    $options = array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'); 
    try { 
	    $db = new PDO("mysql:host=$host; dbname=$dbname; charset=utf8", $username, $password, $options); 
	}     
    catch(PDOException $ex){
		//header("http/1.0 503 Service Unavailable");		
		//logmsg("Config.php : Failed to connect to the database: "  . $ex->getMessage());
	    echo "Failed to connect to the database: " . $ex->getMessage();
	}
	
	
	 
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 
    $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC); 
    
?>