<?php ob_start();
include('header.php'); 

$username = $_POST['username'];
$password = hash('sha512', $_POST['password']);

require('db.php');

$sql = "SELECT user_id FROM users WHERE username = :username AND password = :password";

$cmd = $conn->prepare($sql);
$cmd->bindParam(':username', $username, PDO::PARAM_STR, 50);
$cmd->bindParam(':password', $password, PDO::PARAM_STR, 128);
$cmd->execute();
$users = $cmd->fetchAll();

$count = $cmd->rowCount();
$conn = null;

if ($count == 0) {
	echo 'Invalid Login';
	//exit();	
}
else {
	session_start(); // access the existing user session created on the server

	foreach  ($users as $user) {
		$_SESSION['user_id'] = $user['user_id'];
		header('location:beers.php');
	}
}

include('footer.php');
ob_flush(); ?>
