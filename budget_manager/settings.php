<?php 
    session_start();

	if(isset($_SESSION['loggedUserId'])) {
        require_once 'database.php';

    }else {
		
		header ("Location: index.php");
		exit();
    }
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
	
	<link rel="stylesheet" href="css/bootstrap.min.css">
	<link rel="stylesheet" href="css/main.css">
	<link rel="stylesheet" href="css/fontello.css">
	<link href="https://fonts.googleapis.com/css2?family=Baloo+Paaji+2:wght@400;500;700&family=Fredoka+One&family=Roboto:wght@400;700;900&family=Varela+Round&display=swap" rel="stylesheet">
	
</head>
<body>
	<header>
	
		<h1 class="mt-3 mb-1" id="title">
			<a id="homeButton" href="index.php" role="button"><span id="logo">Budgeteer</span>.io</a>
		</h1>
		
		<p id="subtitle">Take Control of Your Finances</p>
		
	</header>

    <main> 
    <section class="container-fluid square my-4 py-2">
			
			<nav class="navbar navbar-dark navbar-expand-lg">
			
				<button class="navbar-toggler bg-primary" type="button" data-toggle="collapse" data-target="#mainMenu" aria-controls="mainMenu" aria-expanded="false" aria-label="Navigation Toggler">
					<span class="navbar-toggler-icon"></span>
				</button>
				
				<div class="collapse navbar-collapse" id="mainMenu">
			
					<ul class="navbar-nav mx-auto">
					
						<li class="col-lg-2 nav-item">
							<a class="nav-link" href="menu.php"><i class="icon-home"></i> Home</a>
						</li>
						
						<li class="col-lg-2 nav-item">
							<a class="nav-link" href="income.php"><i class="icon-money-1"></i> Add Income</a>
						</li>
						
						<li class="col-lg-2 nav-item">
							<a class="nav-link" href="expense.php"><i class="icon-dollar"></i> Add Expense</a>
						</li>
						
						<li class="col-lg-2 nav-item dropdown">
							<a class="nav-link" href="#" role="button"><i class="icon-chart-pie"></i> View Balance</a>
							<div class="dropdown-menu bg-transparent border-0 m-0 p-0">
							
								<?php
									$userStartDate = date('Y-m-01');
									$userEndDate = date('Y-m-t');
									
									echo '<a class="dropdown-item" href="balance.php?userStartDate='.$userStartDate.'&userEndDate='.$userEndDate.'">Current Month</a>';
								?>
								<?php
									$userStartDate = date('Y-m-01', strtotime("last month"));
									$userEndDate = date('Y-m-t', strtotime("last month"));
									
									echo '<a class="dropdown-item" href="balance.php?userStartDate='.$userStartDate.'&userEndDate='.$userEndDate.'">Last Month</a>';
								?>
								<?php
									$userStartDate = date('Y-01-01');
									$userEndDate = date('Y-12-31');
									
									echo '<a class="dropdown-item" href="balance.php?userStartDate='.$userStartDate.'&userEndDate='.$userEndDate.'">Current Year</a>';
								?>
								<a class="dropdown-item" data-toggle="modal" data-target="#dateModal">Custom</a>
							
							</div>
						</li>
						
						<li class="col-lg-2 nav-item disabled">
							<a class="nav-link" href="settings.php" role="button"><i class="icon-cog-alt"></i> Settings</a>
							<div class="dropdown-menu bg-transparent border-0 m-0 p-0">
							
								<h6 class="dropdown-header">Profile settings</h6>
								<a class="dropdown-item" href="#">Name</a>
								<a class="dropdown-item" href="#">Password</a>
								<a class="dropdown-item" href="#">E-mail Adress</a>
								<div class="dropdown-divider"></div>
								<h6 class="dropdown-header">Category settings</h6>
								<a class="dropdown-item" href="#">Income</a>
								<a class="dropdown-item" href="#">Expense</a>
								<a class="dropdown-item" href="#">Payment Methods</a>
							
							</div>
						</li> 
						
						<li class="col-lg-2 nav-item">
							<a class="nav-link" href="logout.php"><i class="icon-logout"></i> Sign out</a>
						</li>
						
					</ul>
					
				</div>
			
			</nav>
			
		</section>
    </main>

	<?php
        $servername = "localhost";
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