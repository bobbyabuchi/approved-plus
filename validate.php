<?php
if (!isset($_SESSION['contact']) AND !isset($_SESSION['savedteam'])) {
	# code...
	$flash_message = "<span class='text-danger'>Please log in.</span>";
	header('Location: sign-in.php');
	exit();
}
?>