<?php
/*

*/
//require session.php to make sure user is not logged in already
require "./session.php";

$password = md5($_POST['password']); //create an md5 hash to compare against the database
$username = $_POST['username'];
//MySQL connection
$link = mysqli_connect("localhost", "finalproject", "jung2016", "FinalProject")
		or die('Failure to Connect to Database');
// call from session.php to see if the user is already logged in
if (returnLoggedin() == "true")
{
	header("Location: ./home.php");
}
//check to see if the username even has anything in it
if(($_POST['username'] != NULL) && ($_POST['password'] != NULL))
{
$sql = "SELECT * FROM tblUser WHERE Username='$username'";
$query = mysqli_query($link, $sql) or die("failed to query"); //. mysqli_connect_error()
		//count the number of rows in the query, if less than one login has failed
		if(mysqli_num_rows($query) < 1) {
			session_start();
			$_SESSION['error'] = "You have entered an invalid Password/Username. Please try again.";
			header("Location: ./index.php");
		}
		//loop through the query to see if the username and password match
		while($info = mysqli_fetch_assoc($query))
		{
			if (($info['Password'] == $password) && ($info['Username'] == $username))
			{
				session_start(); //the user has successfully been validated
				/*Store the values in the session*/
				$_SESSION['user'] = $username;
				$_SESSION['loggedin'] = "yes";
				$_SESSION['role'] = $info['userRole'];
				//take user to home.php
				header("Location: ./home.php");
			}
			else
			{ // the user has failed to be validated
				session_start();
				//write error to session in order to display on index.php
				$_SESSION['error'] = "You have entered an invalid Password/Username. Please try again.";
				//take user to index.php
				header("Location: ./index.php");
			}
		}
mysqli_close($link);
}
else
{ //if anything else happens, the user failed to login and is redirected to index.php
	session_start();
	//store error in session and take the user back to index.php
	$_SESSION['error'] = "An unknown error has occured. Please Login again.";
	header("Location: ./index.php");
}
//end of code
?>
