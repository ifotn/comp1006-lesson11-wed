<?php 
$page_title = 'Saving your Registration...';
require_once('header.php');

// store the form inputs in variables
$username = $_POST['username'];
$password = $_POST['password'];
$confirm = $_POST['confirm'];
$ok = true;

// validation
if (empty($username)) {
	echo 'Username is required<br />';
	$ok = false;
}

if (empty($password)) {
	echo 'Password is required<br />';
	$ok = false;
}

if ($password != $confirm) {
	echo 'Passwords must match<br />';
	$ok = false;
}

// echo $ok;

// save if the form is ok
if ($ok == true) {
	require_once('db.php'); // connect

	$sql = "INSERT INTO users (username, password) VALUES (:username, :password)";

	// hash the password
	$hashed_password = hash('sha512', $password);

	$cmd = $conn->prepare($sql);
	$cmd->bindParam(':username', $username, PDO::PARAM_STR, 50);
	$cmd->bindParam(':password', $hashed_password, PDO::PARAM_STR, 128);
	$cmd->execute();

	$conn = null; // disconnect

	echo '<div class="jumbotron">Your registration has been saved.  Click to <a href="login.php" title="Login">Login <i class="fa fa-sign-in"></i></a> </div>';

}

require('footer.php'); ?>