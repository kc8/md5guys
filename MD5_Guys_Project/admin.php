
<!doctype html>
<html>
<head>
<link rel="stylesheet" type="text/css" href="./Project.css">

<meta charset="utf-8">
<title>Admin Page</title>
<script type="text/javascript">
	function validate() {
	
    var pass1 = document.getElementById("password").value;
    var pass2 = document.getElementById("password2").value;
    var ok = true;
    if (pass1 != pass2) {
        //alert("Passwords Do not match");
        document.getElementById("password").style.backgroundColor = "#ff4d4d";
        document.getElementById("password2").style.backgroundColor = "#ff4d4d";
		document.getElementById("passworderror").innerHTML = "Passwords do not match!";

        ok = false;
    }
    else {
        document.getElementById("password").style.backgroundColor = "green";
        document.getElementById("password2").style.backgroundColor = "green";
    }
    return ok;
}
</script>
</head>
<body>
<nav>
		<ul id="nav">
			<li><a href="home.php">Home</a></li>
			<li><a href="search.php">Search</a></li>
			<li><a href="insert.php">Add</a></li>
			<li><a href="logout.php">Logout</a></li>
		</ul>
	</nav>
  <div id="wrapper">
	<div id="header">
		<div id="sessiondata">
			<?php
			/*
				Requires seesion.php which makes sure the user is logged in
				otherwise they will be redriected to the login page
			*/
			require "./session.php";
			printLogout();
			?>
		</div>
		<br />
			<div id="banner">
			<img src="./MD5_Guys_Banner.jpg" alt="MD5 Guys" width="400px" height="140px" />
			</div>

	</div>
<?php
/*
	MD5 Guys
	By: Johnathan Lamberson, Mike Bercume, Kyle Cooper
	Date: May 3, 2016
	Professor Jung
	CSCI 373
	Purpose of Page: This is the admin page so that admin users can add new users
	to the users table and assign them roles (admin, empployee and user)
*/

function checkUser($newuser)
{
	global $link;
	$sql1 = "SELECT * FROM tblUser WHERE Username='$newuser'";
	$query = mysqli_query($link, $sql1) or die("failed to query"); //. mysqli_connect_error()
		//count the number of rows in the query, if less than one user does not exist
		if(mysqli_num_rows($query) < 1) 
		{
			return "true";
		}
}

//The statement below checks to see if the user is logged in
if(returnLoggedin() == "true")
{
		//The below statement check to see if the role is admin from the session.php page 
		if(getRole() == "admin")
		{
			//if admin, print the form to create accounts. 
			echo "<div id='create-user'>";
			echo "<h2> Create User Accounts</h2>";
			echo "<form action='./admin.php' method='POST' onsubmit='return validate()'>";
			echo "<input type='text' name='username' placeholder='Username' required/><br/><br/>";
			echo "<input type='password' name='password' id=password placeholder='Password' required/><br/><br/>";
			echo "<input type='password' placeholder='Confirm Password' id=password2 required><br/><br/>";
			echo "<div id='passworderror'></div><br/>";
			echo "<select name='role' required>
				<option value='employee'>Employee</option>
				<option value='admin'>Administrator</option>
				<option value='other'>Other</option>
			</select><br/><br/>";
			echo "<input type='submit' name='create' value='Create User'/><br/>";
			echo "</form>";
			echo "</div>";
			//the below if statemnet checks that $_POST is not empty and then
			//adds the data into the database
			if ($_POST != NULL)
			{
				//Post variables put into local variables
				$user = $_POST['username'];
				$password = md5($_POST['password']);
				$role = $_POST['role'];
				//DB connection
				$link = mysqli_connect("localhost", "finalproject", "jung2016", "FinalProject")
				or die('Failure to Connect to Database');
				
				if (checkUser($user) == "true")
				{
					if (strlen($_POST['password']) > 7)
					{
						//Query code
						$sql = "INSERT INTO tblUser VALUES(NULL, '$user', '$password', '$role');";
						//execute DB query 
						$result = mysqli_query($link, $sql) or die("Query failed");
						echo "<div id=logout>User created successfully.</div>";
					}
					else 
					{
						echo "<div id='error'>The password must be at least 8 charcters long. Please try a different password.</div>"; 	
					}
				}
				else
				{
					echo "<div id='error'>That user already exists, please choose a different username.</div>";	
				}
			}
		}
		else
		{
			//if not an admin, print the message below as an error
			echo "<div id='error'>You are not an administrator! Please login as an administrator to use this function</div>";
		}
}
else
{	
	//if not logged in, send to login page. 
	header("Location: ./index.php");
}
//End of PHP code.
?>

</div>
</body>
</html>
