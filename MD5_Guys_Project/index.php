<?php
/*
	below requires session.php to check for login
	if the user is logged in there are sent to home.php
*/
require "./session.php";

if (returnLoggedin() == "true")
{
	header("Location: ./home.php");
}
?>

<!doctype html>
<html>
<head>
<link rel="stylesheet" type="text/css" href="./Project.css">

<meta charset="utf-8">
<title>Login</title>
</head>
<body>
<div id="wrapper">
<div id="header"><h1>MD5 Guys Login Page</h1>
		<img id="banner" src="./MD5_Guys_Banner.jpg" alt="MD5 Guys" width="400px" height="140px" />
</div>


<?php
/*
	MD5 Guys
	By: Johnathan Lamberson, Mike Bercume, Kyle Cooper
	Date: May 3, 2016
	Professor Jung
	CSCI 373
	Purpose of Page: This is the main page in which the user will be directed to
	It always prompts them for login
*/
	session_start();

	//If there is an error from login.php print it here
	if ($_SESSION[error] != NULL) 
	{
		$error = $_SESSION[error];
		echo "<div id='error'><p>$error</p></div>";
	}
	//if there is a message from logout.php print it here
	if ($_SESSION[logout] != NULL) 
	{
		$logoutmessage = $_SESSION['logout'];
		echo "<div id='logout'><p>$logoutmessage</p></div>";
	}
	/*Unset the Session Varables*/
	session_unset();
	/*Destroy the session*/
	session_destroy();
?>

<div id="login">
<h3>Please login to use the Employee Management System.</h3>


<form action="login.php" method="POST">
<input type="text" name="username" placeholder="Username" required/><br><br>
<input type="password" name="password" placeholder="Password" required/><br><br>
<input type="submit" name="login" value="Login" /><br>
</form>

</div>


</div>

</body>
</html>
