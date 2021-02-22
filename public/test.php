<?php
    
try {
    $conn_p = new PDO("pgsql:host=127.0.0.1;port=5432;dbname=crm", "postgres", "root");
	var_dump($conn_p);
	echo 'test';
} catch(PDOException $e) {  
    echo $e->getMessage();
}
