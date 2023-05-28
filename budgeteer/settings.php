<?php 
    session_start();

	if(isset($_SESSION['loggedUserId'])) {
        require_once 'database.php';

		if(isset($_POST['username'])) {
		
			$email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
      		$username = filter_input(INPUT_POST, 'username');
			$password = filter_input(INPUT_POST, 'password');
		
			$userQuery = $db -> prepare(
			"SELECT user_id, password, username
			FROM users
			WHERE username = :username");
			$userQuery->execute([':username'=> $username]);
			
			$user = $userQuery -> fetch();

			if($user && password_verify($password, $user['password'])) {
			//if($user && $password) {
				
				$_SESSION['loggedUserId'] = $user['user_id'];
				$_SESSION['username'] = $user['username'];
				unset($_SESSION['badAttempt']);
				
			} else {
				
				$_SESSION['badAttempt'] = "";
				header ('Location: login.php');
				exit();
			}
    	}
    }

	/*else {
		
		header ("Location: landing.php");
		exit();
    }*/
?>

<!DOCTYPE html>
<html>
<head>

	<meta charset="utf-8">
	<title>MyBudget - Your Personal Finance Manager</title>
	<meta name="description" content="Track your income and expenses - avoid overspending!">
	<meta name="keywords" content="expense manager, budget planner, expense tracker, budgeting app, money manager, money management, personal finance management software, finance manager, saving planner">
	<meta name="author" content="Magdalena Słomiany">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	
	<meta http-equiv="X-Ua-Compatible" content="IE=edge">
	
	<!--
		<link rel="stylesheet" href="css/bootstrap.min.css">
	<link rel="stylesheet" href="css/main.css">	
	<link rel="stylesheet" href="css/fontello.css">
	-->
	
	<link rel="stylesheet" href="homestyle.css">
	<link href="https://fonts.googleapis.com/css2?family=Baloo+Paaji+2:wght@400;500;700&family=Fredoka+One&family=Roboto:wght@400;700;900&family=Varela+Round&display=swap" rel="stylesheet">
	
</head>
<body>

<div class="navbar">
		<div class="profile">

			<img src="images/profile pic.png" alt="Profile Picture">
			<p>John Doe</p>
		</div>
		<ul>
			<li>
        <?php
          $userStartDate = date('Y-m-d');
		      $userEndDate = date('Y-m-d');
                  
          echo '<a href="home.php?userStartDate='.$userStartDate.'&userEndDate='.$userEndDate.'">Home</a>';
        ?>
      </li>
			<li><a href="budget.php">Budget</a></li>
      <li><a href="expense.php">Expense</a></li>
			<li>
        <?php
          $userStartDate = date('Y-m-01');
          $userEndDate = date('Y-m-t');
                  
          echo '<a href="summary.php?userStartDate='.$userStartDate.'&userEndDate='.$userEndDate.'.&period=month">Statistics</a>';
        ?>
      </li>
      <li><a href="#">Notes</a></li>
      <li><a href="calendar.php">Calendar</a></li>
      <li><a href="settings.php" class="active">Settings</a></li>
      <li><a href="logout.php">Log Out</a></li>
		</ul>
        <div class="logo">
            <img src="images/Logo3.png" alt="Logo">
          </div>
	</div>

	<?php
       /*$servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "my_budget";
		// Connect to MySQL
		//$conn = mysqli_connect("localhost", "user@gmail.com", "budgeteer", "my_budget");
        $conn = new mysqli($servername, $username, $password, $dbname);

		// Check connection
		if (!$conn) {
		    die("Connection failed: " . mysqli_connect_error());
		}

		// Get user's current information
        
		$user_id = $_SESSION['loggedUserId']; // Assume user is logged in
        //$user_id = 13; 
		$sql = "SELECT username, email, password FROM users WHERE user_id = '$user_id'";
		$result = mysqli_query($conn, $sql);

		if (mysqli_num_rows($result) > 0) {
			$row = mysqli_fetch_assoc($result);
			$current_name = $row['username'];
			$current_password = $row['password'];
			$current_email = $row['email'];
		} else {
			echo "Error: User not found";
		}

		// Handle form submission
		if ($_SERVER['REQUEST_METHOD'] == 'POST') {
			// Get user input
			$new_name = $_POST['username'];
			$new_password = $_POST['password'];
			$new_email = $_POST['email'];

			// Validate input (not shown)

			// Update database
			$sql = "UPDATE users SET username = '$new_name', password = '$new_password', email = '$new_email' WHERE id = '$user_id'";
			if (mysqli_query($conn, $sql)) {
				echo "Profile updated successfully";
			} else {
				echo "Error updating profile: " . mysqli_error($conn);
			}

			// Update current information
			$current_name = $new_name;
			$current_password = $new_password;
			$current_email = $new_email;
		}*/
	?>
	<?php
	$servername = "localhost";
	$username = "root";
	$password = "";
	$dbname = "my_budget";

	// Connect to MySQL
	$conn = new mysqli($servername, $username, $password, $dbname);

	// Check connection
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}

	// Get user's current information
	//session_start();
	$user_id = $_SESSION['loggedUserId']; // Assume user is logged in
	$sql = "SELECT username, email, password FROM users WHERE user_id = '$user_id'";
	$result = $conn->query($sql);

	if ($result->num_rows > 0) {
		$row = $result->fetch_assoc();
		$current_name = $row['username'];
		$current_password = $row['password'];
		$current_email = $row['email'];
	} else {
		echo "Error: User not found";
	}

	// Handle form submission
	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		// Get user input
		$new_password = $_POST['password'];

		// Validate input (not shown)

		// Update database
		$sql = "UPDATE users SET password = '$new_password' WHERE user_id = '$user_id'";
		if ($conn->query($sql) === TRUE) {
			echo "Password updated successfully";
			$current_password = $new_password; // Update current information
		} else {
			echo "Error updating password: " . $conn->error;
		}
	}
	?>

	<form method="post">
		<label for="name">UserName:</label>
		<input type="text" name="name" value="<?php echo $current_name; ?>"><br>

		<label for="password">Password:</label>
		<input type="text" name="password" value="<?php echo $current_password; ?>"><br>

		<label for="email">Email:</label>
		<input type="email" name="email" value="<?php echo $current_email; ?>"><br>

		<input type="submit" value="Save Changes">
	</form>
</body>
</html>