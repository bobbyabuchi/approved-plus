<?php
require dirname(dirname(dirname(__FILE__))) .'/ezekcuij/ykiv/settings.php';

# Database Connection -------------------------------------------------
$servername = DBHOST;
$username = DBUSER;
$password = DBPASS;
$db = DBNAME;

// Create connection
$connect_db = new mysqli($servername, $username, $password, $db);

// Check connection
if ($connect_db->connect_error) {
  die("Connection failed: " . $connect_db->connect_error);
}else{
	$flash_messageX = '<b class="bg-dark card-header row text-white">Ready</b>';
}

# FUNCTIONS ---------------------------------------------------------------------------------------------

# Database related messages 
function flashMessage($message='Hmmmmm...we must have missed something!'){
	echo "<span class='card card-body bg-warning'>{$message}</span>";
}
?>