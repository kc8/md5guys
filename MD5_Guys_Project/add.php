<?php
/*

*/
	require "./session.php";
	if (returnLoggedin() == "false")
	{
		header ("Location: ./index.php");
	}

	if (getRole() == "other")
	{
		session_start();
		$_SESSION['error'] = "You are not authorized to use this funtion. Please login as an authorized user.";
		header('Location: ./search.php');
	}
	$parameter = $_POST['parameter'];
	$link = mysqli_connect("localhost", "finalproject", "jung2016", "FinalProject")
		or die('Failure to Connect to Database');
	if($parameter == "employee") {
		employee_add();
	}
	elseif($parameter == "department") {
		department_add();
	}
	elseif ($parameter == "project") {
		project_add();
	}
	else {
		echo "An incorrect option was selected. Please go back <a href='./insert.php'>Back</a>";
	}
	/*
		@para will clean any plantext
		@return returns clean new data
		A function that can take data and help cleanes our database.
		Uses built in php functions

	*/
	function clean_input($value) {
		$value = trim($value);
		$value = stripcslashes($value);
		$value = htmlspecialchars($value);
		return $value; //we have cleansed our keys with built in PHP functions to
	}

	/*
		emplyee add; adds an employee into the employee database aloung with a
		photo.
	*/
	function employee_add() {
		//declare variables
		global $link;
		$employee = array();
		$photoText = array();
		$image = array();
		$joindate = array();
		$department= array();
		$project = array();
		$salary = array();
		$fileCount = count($_FILES['photo']);

		/*
			the following loops through two arrays to get each image file
			each indivually image from the two arrays is added to the image
			array. This array at a certain index will hold a photo, and
			then be inserted into the database (see below)
		*/
		foreach($_FILES as $key => $value) {
			$curPhoto = $key; //not being used
				foreach($_FILES as $key => $value) {
						$filee = $_FILES[$key]['tmp_name'];
						$imageTemp = $_FILES['image']['tmp_name']; //not being used
						$fp = fopen($filee, 'r');
						$imageFile = fread($fp, filesize($filee));
						$imageFile = addslashes($imageFile);
						fclose($fp);
						$image[] = $imageFile;
				}
			}
		//the following puts post values into arrays that are more managable
		foreach($_POST as $key => $value) {
			if(preg_match('/fullname/', $key)) {
				$employee[] = $value;
			}
			if(preg_match('/photo/', $key)) {
				$photoText[] = $value;
			}
			if(preg_match('/joindate/', $key)) {
				$joindate[] = $value;
			}
			if(preg_match('/department/', $key)) {
				$department[] = $value;
			}
			if(preg_match('/project/', $key)) {
				$project[] = $value;
			}
			if(preg_match('/salary/', $key)) {
				$salary[] = $value;
			}
		}

		$num = count($employee);
		//for loop each id and
		for($i = 0; $i < $num; $i++) {
			//sanatize input, never trust users.
			$tempEmployee = clean_input($employee[$i]);
			$tempDepartment = clean_input($department[$i]);
			$tempProject = clean_input($project[$i]);
			$tempSalary = clean_input($salary[$i]);
			$actualSalary = str_replace(',','',$tempSalary); //strip the commas from the input, we will add them back on display
			$sql = "INSERT INTO tblEmployee VALUES(NULL, '$tempEmployee', '$image[$i]', '$joindate[$i]', '$tempDepartment', '$tempProject', '$actualSalary');";
			$result = mysqli_query($link, $sql) or die("Query failed. Please go back <a href='./insert.php'>Back</a>");
		}
		header("Location: ./insert.php");
	}

	/*
		departmetn_add() adds new rows into the department table in the database
	*/
	function department_add() {
		global $link;
		$department = array();
		foreach($_POST as $key => $value){
			if(preg_match('/department/', $key)){
				$department[] = $value;
			}
		}
		$num = count($department);
		//for loop each id and
		for($i = 0; $i < $num; $i++) {
			$tempDepartment = clean_input($department[$i]); //clean input, never trust users
			$sql = "INSERT INTO tblplDepartment VALUES(NULL, '$tempDepartment');";
			$result = mysqli_query($link, $sql) or die("Query failed. Please go back <a href='./insert.php'>Back</a>");
		}
		header("Location: ./insert.php");
	}

	/*
		project_add() adds new projects to the database
	*/
	function project_add() {
		global $link;
		$project = array();
		foreach($_POST as $key => $value){
			if(preg_match('/project/', $key)){
				$project[] = $value;
			}
		}
		$num = count($project);
		for($i = 0; $i < $num; $i++) {
			$tempProject = clean_input($project[$i]);
			$sql = "INSERT INTO tblplProject VALUES(NULL, '$tempProject');";
			$result = mysqli_query($link, $sql) or die("Query failed. Please go back <a href='./insert.php'>Back</a>");
		}
		header("Location: ./insert.php");
	}
mysqli_close($link);

?>
