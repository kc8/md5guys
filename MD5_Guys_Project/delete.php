<?php
/*
	Requires seesion.php which makes sure the user is logged in
	otherwise they will be redriected to the login page
*/
require "./session.php";
//Members of the Other role are not allowed to preform these functions
if (getRole() == "other")
{
	session_start();
	//if user is in the Other role, save an error to session and display on search.php
	$_SESSION['error'] = "You are not authorized to use this funtion. Please login as an authorized user.";
	header('Location: ./search.php');	
}
?>
<!DOCTYPE html>
<html>
	<head>
		<link rel="stylesheet" type="text/css" href="./Project.css">
		<link rel="icon" type="image/x-icon" href="./favicon.ico">
		<meta charset="utf-8">
		<title>Employee Search</title>
		<script>
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
			//prints admin page if user is admin and the name of the user who is logged in
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
	Purpose of Page: This page deletesan a selected row from the database. It will only
	function if the user selects a row to delete from search.php. If the deletion was
	unsucesul, then it informs the user and tells them to go back. If they
	are trying to delete a dependency, a table is generated showing them
	what needs to be removed.
*/

	/*
		This page deletes the selected box from the database
	*/
	$link = mysqli_connect("localhost", "finalproject", "jung2016", "FinalProject") or die('Failure to Connect to Database');
	$deleteData = $_POST["delete"]; //holds the idea that needs to be deleted
	$parameter = $_POST['parameter']; //holds the table that the id should be deleted from

	//checks to see what function to be called, to delete from
	if($parameter == "employee"){
		main_delete();
	}
	elseif($parameter == "department"){
		department_delete();
	}
	elseif ($parameter == "project"){
		project_delete();
	}
	else {
		echo "An incorrect option was select and nothing was deleted. Please go back <a href=./search.php>Back</a>";
	}

	/*
		The main delete deletes data from the employee table. It will remove the entire row from the database
	*/
	function main_delete() {
		global $link;
		global $deleteData;
		$query = "DELETE FROM tblEmployee WHERE emEmployeeID=$deleteData;";
		$result = mysqli_query($link, $query);
		if(!$result) {
			echo "Im sorry, the delete function was not succseful. Please go back: <a href=./search.php>Back</a>)";
		}
		else {
					$_SESSION['message'] = "Entry succesully removed"; //sets the message for confirmation to the user
					header("Location: ./search.php");
		}
	}

	/*
		deletes the selected id from the department table. Removes the entry row
		Since the main table is dependent on depertament table, it will not allow
		the user to delete data that is being used in the mployee table
		it then displays an error, and the shows the user who is still using these entries
	*/
	function department_delete() {
		global $link;
		global $deleteData;
		$query = "DELETE FROM tblplDepartment WHERE deDepartmentID=$deleteData;";
		$result = mysqli_query($link, $query);
		if(!$result) {
			echo "<div id='error'>Delete has failed.";
			echo " Listed below are the employees still in this department.</div>";
			$query = "SELECT emEmployeeID, emFullName, emPhoto, emJoinDate, deDepartmentName, plProjectName, emSalary
			FROM tblEmployee
			JOIN tblplDepartment
			ON emDepartmentID = deDepartmentID
			JOIN tblplProject
			ON emProjectID = plProjectID
			WHERE (deDepartmentID LIKE $deleteData);";
			$result = mysqli_query($link, $query) or die("query to display who is in Department failed");
			echo "<table>";
			echo "<th>Employee ID</th><th>Full Name</th><th>Photo</th><th>Join Date</th>";
			echo "<th>Department Name</th><th>Project Name</th><th>Salary</th>";
			while($info = mysqli_fetch_assoc($result)) {
				echo "<tr>";
				echo "<td>$info[emEmployeeID]";
				echo "<td>$info[emFullName]</td>";
				//below displays the output of the image correctly
				echo '<td><img src="data:image/jpeg;base64,'.base64_encode($info['emPhoto']).'" alt="No Image" height="60" width="60" /></td>';
				echo "<td>".date_format(date_create($info['emJoinDate']),'m/d/Y')."</td>"; //formats the date correctly
				echo "<td>$info[deDepartmentName]";
				echo "<td>$info[plProjectName]";
				//format the salary numbers correclty:
				echo "<td>$".number_format($info['emSalary'],2,'.',',')."</td>";
				echo "</tr>";
			}
			echo "</table>";
			echo "You must delete or change these entries before you can delete the department ";
			echo "Please Go back: <a href=./search.php>Back</a>";
		}
		else {
			$_SESSION['message'] = "Entry succesully removed";
			header("Location: ./search.php");
		}
	}

	/*
	deletes the selected id from the project table. Removes the entry row
	Since the main table is dependent on project table, it will not allow
	the user to delete data that is being used in the mployee table
	it then displays an error, and the shows the user who is still using these entries
	*/
	function project_delete() {
		global $link;
		global $deleteData;
		$query = "DELETE FROM tblplProject WHERE plProjectID=$deleteData;";
		$result = mysqli_query($link, $query);
		if(!$result) {
			echo "<div id='error'>Delete has failed.";
			echo " Below are the employees still working on this project:</div>";
			$query = "SELECT emEmployeeID, emFullName, emPhoto, emJoinDate, deDepartmentName, plProjectName, emSalary
			FROM tblEmployee
			JOIN tblplDepartment
			ON emDepartmentID = deDepartmentID
			JOIN tblplProject
			ON emProjectID = plProjectID
			WHERE (plProjectID LIKE $deleteData);";
			$result = mysqli_query($link, $query) or die("query to display who is in Project failed");
			echo "<table>";
			echo "<th>Employee ID</th><th>Full Name</th><th>Photo</th><th>Join Date</th>";
			echo "<th>Department Name</th><th>Project Name</th><th>Salary</th>";
			while($info = mysqli_fetch_assoc($result)) {
				echo "<tr>";
				echo "<td>$info[emEmployeeID]";
				echo "<td>$info[emFullName]</td>";
				echo '<td><img src="data:image/jpeg;base64,'.base64_encode($info['emPhoto']).'" alt="No Image" height="60" width="60" /></td>';
				echo "<td>".date_format(date_create($info['emJoinDate']),'m/d/Y')."</td>"; //formats the date correctly
				echo "<td>$info[deDepartmentName]";
				echo "<td>$info[plProjectName]";
			echo "<td>$".number_format($info['emSalary'],2,'.',',')."</td>";
				echo "</tr>";
			}
			echo "</table>";
			echo "You must delete or change these entires before you can delete the Project. ";
			echo "Please Go back: <a href=./search.php>Back</a>";;
		}
		else {
			$_SESSION['message'] = "Entry succesully removed";
			header("Location: ./search.php");
		}
	}
	mysqli_close();
?>
</body>
</html>
