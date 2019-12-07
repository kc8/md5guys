<?php
require "./session.php";
if (getRole() == "other")
{
	session_start();
	$_SESSION['error'] = "You are not authorized to use this funtion. Please login as an authorized user.";
	header('Location: ./search.php');
}
?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Update Record</title>

        <link rel="stylesheet" type="text/css" href="./Project.css">
		<link rel="icon" type="image/x-icon" href="./favicon.ico">
	</head>
	<body>
		<nav>
		<ul id="nav">
			<li><a href="#">Home</a></li>
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
			printLogout();
			?>
		</div>
		<br />
			<div id="banner">
			<img src="./MD5_Guys_Banner.jpg" alt="MD5 Guys" width="400px" height="140px" />
			</div>
	</div>

<?php
$link = mysqli_connect("localhost", "finalproject", "jung2016", "FinalProject") or die("Cannot Connect to MySQL Server! Contact Administrator.");

if($_POST['modifyEmployee'] == NULL and $_POST['modifyProject'] == NULL and $_POST['modifyDepartment'] == NULL)
{
	echo "You have not selected anything to update! Please <a href='./search.php'>Go Back</a>";
}
else
{
	if($_POST['parameter'] == "employee")
	{
		modifyEmployee();
	}
	if($_POST['parameter'] == "department")
	{
		modifyDepartment();
	}
	if($_POST['parameter'] == "project")
	{
		modifyProject();
	}
}

function modifyEmployee()
{
	global $link;
	echo "<form enctype=multipart/form-data action='./updateDB.php' method=POST />";
	echo "<input type=hidden name=modify value='modifyEmployee'/>";
	echo "<table>";
	echo "<tr><th>ID</th><th>Name</th><th>Photo</th><th>Join Date</th><th>Department</th><th>Project</th><th>Salary</th></tr>";
foreach($_POST['modifyEmployee'] as $id)
{
	$sql = "SELECT * FROM tblEmployee WHERE emEmployeeID= '$id' ORDER BY emEmployeeID ASC";
	$query = mysqli_query($link, $sql) or die("failed to query". mysqli_connect_error());
		while($info = mysqli_fetch_assoc($query))
		{
			$id = $info['emEmployeeID'];
			echo "<tr>";
			echo "<td>$id<input type=hidden value=$id name=id$id /></td>";
			echo "<td> <input type=text name=name$id value='$info[emFullName]' /></td>";
			echo "<td><input type=file name=photo$id accept=image/jpg></td>";
			echo "<td><input type=date name=join$id value=$info[emJoinDate] /> </td>";
			echo "<td> <select name=department$id >";
			$dep = $info['emDepartmentID'] ;
			$departments = "SELECT * FROM tblplDepartment";
			$query = mysqli_query($link, $departments) or die("Query Failed");
			while ($array = mysqli_fetch_assoc($query))
			{
				if ($array['deDepartmentID'] == $dep)
				{
					echo "<option value=$array[deDepartmentID] selected=selected>$array[deDepartmentName]</option>";
				}
				else
				{
					echo "<option value=$array[deDepartmentID]>$array[deDepartmentName]</option>";
				}
			}
			echo "</select></td>";
			echo "<td><select name=project$id >";
			$proj = $info['emProjectID'] ;
			$projects = "SELECT * FROM tblplProject";
			$query1 = mysqli_query($link, $projects) or die("Query Failed");
			while ($array1 = mysqli_fetch_assoc($query1))
			{
				if ($array1['plProjectID'] == $proj)
				{
					echo "<option value=$array1[plProjectID] selected=selected>$array1[plProjectName]</option>";
				}
				else
				{
					echo "<option value=$array1[plProjectID]>$array1[plProjectName]</option>";
				}
			}
			echo "</select></td>";
			echo "<td>$<input type=text name=salary$id value=".number_format($info['emSalary'],2,'.',',')." /></td>";
			echo "</tr>";
		}
	}
	echo "<tr><td colspan=6><input type='hidden' value='updateEmployee' name='parameter' /><input type=submit value='Update Records' name='submit'/></td></tr>";
	echo "</table></form>";
}

function modifyDepartment()
{
	global $link;

	echo "<form action='./updateDB.php' method=POST />";
	echo "<table>";
	echo "<tr><th>ID</th><th>Name</th></tr>";

	foreach($_POST['modifyDepartment'] as $id)
	{
		$sql = "SELECT * FROM tblplDepartment WHERE deDepartmentID= '$id' ";
		$query = mysqli_query($link, $sql) or die("failed to query". mysqli_connect_error());
			while($info = mysqli_fetch_assoc($query))
			{

				echo "<tr><td><input type=hidden value=$id name=id$id />$id</td>";
				echo "<td><input type=text value='$info[deDepartmentName]'  name=department$id /></td></tr>";
			}

	}
	echo "<tr><td colspan=2 ><input type='hidden' value='updateDepartment' name='parameter' /><input type=submit name=submit value='Update Records'";
	echo "</table></form>";
}

function modifyProject()
{
	global $link;

	echo "<form action='./updateDB.php' method=POST />";
	echo "<table>";
	echo "<tr><th>ID</th><th>Name</th></tr>";

	foreach($_POST['modifyProject'] as $id)
	{
		$sql = "SELECT * FROM tblplProject WHERE plProjectID= '$id' ";
		$query = mysqli_query($link, $sql) or die("failed to query". mysqli_connect_error());
			while($info = mysqli_fetch_assoc($query))
			{

				echo "<tr><td><input type=hidden value=$id name=id$id />$id</td>";
				echo "<td><input type=text value='$info[plProjectName]'  name=project$id /></td></tr>";
			}

	}
	echo "<tr><td colspan=2 ><input type='hidden' value='updateProject' name='parameter' /><input type=submit name=submit value='Update Records'";
	echo "</table></form>";
}
mysqli_close($link);
?>
</div>
	</body>
</html>
