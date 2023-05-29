<?php
	session_start();

	if(!isset($_SESSION['loggedUserId'])) {
		
		header('Location: login.php');	
		exit();
	}

	/*if(isset($_SESSION['loggedUserId'])) {
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
	
	<link rel="stylesheet" href="expensestyle.css">
	<link href="https://fonts.googleapis.com/css2?family=Baloo+Paaji+2:wght@400;500;700&family=Fredoka+One&family=Roboto:wght@400;700;900&family=Varela+Round&display=swap" rel="stylesheet">
	
</head>
<body>

<div class="navbar">
		<div class="profile">

			<img src="images/profile pic.png" alt="Profile Picture">
			<?php
				$servername = "localhost";
				$username = "root";
				$password = "";
				$dbname = "my_budget";
				
				$conn = new mysqli($servername, $username, $password, $dbname);

				// Check connection
				if (!$conn) {
					die("Connection failed: " . mysqli_connect_error());
				}
			
				$user_id = $_SESSION['loggedUserId'];
				$sql = "SELECT first_name FROM users WHERE user_id = '$user_id'";
				$result =  mysqli_query($conn, $sql);
				if (mysqli_num_rows($result) > 0) {
					$row = mysqli_fetch_assoc($result);
					$current_fname = $row['first_name'];
				}

				echo'<p>';
				echo $current_fname;
				echo'</p>';
			?>
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
		$servername = "localhost";
		$username = "root";
		$password = "";
		$dbname = "my_budget";

		/*$new_username ='';
		$new_email = '';
		$new_first_name ='';
		$new_last_name = '';
		$password = '';
		$new_password = '';
		$confirm_new_password = '';*/

		// Connect to MySQL
		$conn = new mysqli($servername, $username, $password, $dbname);

		// Check connection
		if (!$conn) {
		    die("Connection failed: " . mysqli_connect_error());
		}

		$user_id = $_SESSION['loggedUserId']; // Assume user is logged in
		//$sql = "SELECT first_name, last_name, username, email, password FROM users WHERE user_id = '$user_id'";
		$sql = "SELECT * FROM users WHERE user_id = '$user_id'";
		
		$result =  mysqli_query($conn, $sql);

		if (mysqli_num_rows($result) > 0) {
			$row = mysqli_fetch_assoc($result);
			$current_name = $row['username'];
			$current_fname = $row['first_name'];
			$current_lname = $row['last_name'];
			$current_password = $row['password'];
			$current_email = $row['email'];
		} else {
			echo "Error: User not found";
		}

		
	?>

	<div class="box">
					
		<form class="budget-form" method="post">
			<?php
			//$current_name = $current_fname = $current_lname = $current_email = '';

				if ($_SERVER['REQUEST_METHOD'] === 'POST') {
					$new_username = $_POST['username'];
					$new_email = $_POST['email'];
					$new_fname = $_POST['first_name'];
					$new_lname = $_POST['last_name'];

					if (mysqli_num_rows($result) > 0) {
						$row = mysqli_fetch_assoc($result);
						if (isset($row['username'])) {
							$current_name = $row['username'];
						}
						if (isset($row['first_name'])) {
							$current_fname = $row['first_name'];
						}
						if (isset($row['last_name'])) {
							$current_lname = $row['last_name'];
						}
						if (isset($row['email'])) {
							$current_email = $row['email'];
						}
					} else {
						echo "Error: User not found";
					}


					    // Update the fields that are provided
					$updates = [];

					if (!empty($new_username)) {
						$updates[] = "username = '$new_username'";
					}

					if (!empty($new_email)) {
						$updates[] = "email = '$new_email'";
					}

					if (!empty($new_fname)) {
						$updates[] = "first_name = '$new_fname'";
					}

					if (!empty($new_lname)) {
						$updates[] = "last_name = '$new_lname'";
					}

					if (!empty($updates)) {
						$updateSql = "UPDATE users SET " . implode(", ", $updates) . " WHERE user_id = '$user_id'";
						if (mysqli_query($conn, $updateSql)) {
							echo "Profile Updated Successfully";
						} else {
							echo "Error Updating Profile: " . mysqli_error($conn);
						}

						$current_name = $new_username;
						$current_fname = $new_fname;
						$current_lname = $new_lname;
						$current_email = $new_email;
					}
				}
			?>

		<h3>SETTINGS</h3>

			<div class="column">
					<div class="amount-box">
						<div class="amount"> <span class="">User Name</span> </div>


						<input class="amountinput" type="text" name="username" value="<?php echo $current_name; ?>"disabled data-original-value="<?php echo $current_name; ?>"><br>
					</div>
				</div>
			
			<div class="column">
				<div class="amount-box">
					<div class="amount"> <span class="">Email</span> </div>

					<input class="amountinput" type="email" name="email" value="<?php echo $current_email; ?>"disabled data-original-value="<?php echo $current_email; ?>"><br>
					
					
				</div>
			</div>

			<div class="column">
				<div class="amount-box">
					<div class="amount"> <span class="">First Name</span> </div>

					<input class="amountinput" type="text" name="first_name" value="<?php echo $current_fname; ?>"disabled data-original-value="<?php echo $current_fname; ?>"><br>
					
				</div>
			</div>

			<div class="column">
				<div class="amount-box">
					<div class="amount"> <span class="">Last Name</span> </div>	

					<input class="amountinput" type="text" name="last_name" value="<?php echo $current_lname; ?>"disabled data-original-value="<?php echo $current_lname; ?>"><br>
					
			<button class="editButton" type="button" onclick="enableInputField(this)">Edit</button>
			<br>
			<button class="cancelButton" type="button" onclick="cancelEdit(this)" style="display: none;">Cancel</button>
			<input class="saveButton" type="submit" value="Save" style="display: none;">

				</div>
				
			</div>
			
		</form>
		
		<script>

		function enableInputField(button) {
			var form = button.closest('form');
			var inputs = form.querySelectorAll('input[type="text"], input[type="email"]');
			var saveButton = form.querySelector('.saveButton');
			var cancelButton = form.querySelector('.cancelButton');

			for (var i = 0; i < inputs.length; i++) {
			inputs[i].disabled = false;
			}

			saveButton.style.display = 'inline-block';
			cancelButton.style.display = 'inline-block';
			button.style.display = 'none';
		}

		function cancelEdit(button) {
			var form = button.closest('form');
			var inputs = form.querySelectorAll('input[type="text"], input[type="email"]');
			var saveButton = form.querySelector('.saveButton');
			var editButton = form.querySelector('.editButton');
			var cancelButton = form.querySelector('.cancelButton');

			for (var i = 0; i < inputs.length; i++) {
			inputs[i].disabled = true;
			inputs[i].value = inputs[i].getAttribute('data-original-value'); // Reset input value to the original value
			}

			saveButton.style.display = 'none';
			cancelButton.style.display = 'none';
			editButton.style.display = 'inline-block';
		}

		</script>
		

	</div>


</body>
</html>

<!--<form method="post">
		<label for="name">UserName:</label>
		<input type="text" name="username" value="<?php echo $current_name; ?>"><br>

		<label for="email">Email:</label>
		<input type="email" name="email" value="<?php echo $current_email; ?>"><br>

		<label for="first_name">First Name:</label>
		<input type="text" name="first_name" value="<?php echo $current_fname; ?>"><br>

		<label for="last_name">Last Name:</label>
		<input type="text" name="last_name" value="<?php echo $current_lname; ?>"><br>

		<label for="password">Current Password:</label>
		<input type="password" name="password"><br>

		<label for="new_password">New Password:</label>
		<input type="password" name="new_password"><br>

		<label for="confirm_new_password">Confirm New Password:</label>
		<input type="password" name="confirm_new_password"><br>

		<input type="submit" value="Save Changes">
	</form>-->
<?php
	/*if ($_SERVER['REQUEST_METHOD'] === 'POST') {
				$new_username = $_POST['username'];
				$new_email = $_POST['email'];
				$new_first_name = $_POST['first_name'];
				$new_last_name = $_POST['last_name'];
				$password = $_POST['password'];
				$new_password = $_POST['new_password'];
				$confirm_new_password = $_POST['confirm_new_password'];

				$sql = "UPDATE users SET first_name = ?, last_name = ?, username= ?, email= ?, password= ?  WHERE user_id = '$user_id'";
				
				$stmt->bind_param("sssssi", $new_username, $new_first_name, $new_last_name, $new_email, $new_password);

				$stmt->execute();

				if ($stmt->affected_rows > 0) {
					// Update successful
					$message = "User information updated successfully!";
				} else {
					// Update failed
					$message = "Failed to update user information!";
				}
			
				// Close the statement
				$stmt->close();
				*/
				/*if (!empty($new_username)) {
					$sql = "UPDATE users SET username = '$new_username' WHERE user_id = '$user_id'";
					// Execute the SQL query to update the username
				}

				if (!empty($new_email)) {
					$sql = "UPDATE users SET email = '$new_email' WHERE user_id = '$user_id'";
					// Execute the SQL query to update the email
				}
		
				if (!empty($new_first_name)) {
					$sql = "UPDATE users SET first_name = '$new_first_name' WHERE user_id = '$user_id'";
					// Execute the SQL query to update the first name
				}
		
				if (!empty($new_last_name)) {
					$sql = "UPDATE users SET last_name = '$new_last_name' WHERE user_id = '$user_id'";
					// Execute the SQL query to update the last name
				}
		
				// Handle password update if necessary
				if (!empty($new_password) && !empty($confirm_new_password) && $new_password === $confirm_new_password) {
					// Verify the current password and update the new password
				}
			}*/

			/*if (mysqli_query($conn, $sql)) {
				echo "User Name updated successfully";
			} else {
				echo "Error updating User Name: " . mysqli_error($conn);
			}*/
			//$sql = "SELECT * FROM users WHERE id = '$user_id';
			// Update current information
			/*$current_name = $new_username;
			$current_fname = $new_first_name;
			$current_lname = $new_last_name;
			$current_password ='';
			$current_email = $new_email;*/
			?>


<!--<form class="budget-form" method="post">
			<h3>SETTINGS</h3> 
			<?php
				/*if ($_SERVER['REQUEST_METHOD'] == 'POST') {
					$new_username = $_POST['username'];

					$sql = "UPDATE users SET username = '$new_username' WHERE user_id = '$user_id'";

					if (mysqli_query($conn, $sql)) {
						echo "User Name updated successfully";
					} else {
						echo "Error updating User Name: " . mysqli_error($conn);
					}

					// Update current information
					$current_name = $new_username;
				}*/

			?>
			
			<div class="column">
				<div class="amount-box">
					<div class="amount"> <span class="">User Name</span> </div>

					<input class="amountinput" type="text" name="username" value="<?php echo $current_name; ?>"><br>
					<input class="saveButton" type="submit" value="Save">

				</div>
			</div>
		</form>

	-->