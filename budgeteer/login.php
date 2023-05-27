<?php
	
	session_start();
	

	if(isset($_SESSION['loggedUserId'])) {
		
		header('Location: home.php');	
		exit();
	}

?>

<?php
	// set the start and end date for home
	$userStartDate = date('Y-m-d');
	$userEndDate = date('Y-m-d');
	
?>

<!DOCTYPE html>

<html lang="en">

<head>

	<meta charset="utf-8">
	<title>Login-Budgeteer</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	
	<meta http-equiv="X-Ua-Compatible" content="IE=edge">
	
	<link rel="stylesheet" type="text/css" href="style.css">
	<link rel="stylesheet" href="css/bootstrap.min.css">
	<link rel="stylesheet" href="css/main.css">
	<link rel="stylesheet" href="css/fontello.css">
	<link href="https://fonts.googleapis.com/css2?family=Baloo+Paaji+2:wght@400;500;700&family=Fredoka+One&family=Roboto:wght@400;700;900&family=Varela+Round&display=swap" rel="stylesheet">
	
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;300;400;600&display=swap" rel="stylesheet">
	
</head>

<body>

	<nav class="navbar">
			<div class="logo" >
			<a href="landing.php"> 
            <img src="images/logo2.png">
			</a>
        	</div>
		<h1 class="website-name">Budgeteer.io</h1>
	</nav>
	<div class="container">

		<div class="left-section">

			<h1>We missed you!</h1>
			<h2>Login to manage your budget.</h2>
			<img src="images/v19_80.png" alt="Image">
		</div>

		<div class="divider"></div>

		<div class="right-section">

			<!--<form method="post" action="home.php">-->
			<form method="post" action="home.php?userStartDate=<?php echo $userStartDate; ?>&userEndDate=<?php echo $userEndDate; ?>">
				<?php
					if(isset($_SESSION['badAttempt'])) {
									
						echo '<div class="text-danger px-2">The name or password you have entered is incorrect. Please try again.</div>';
						unset($_SESSION['badAttempt']);
					}
				?>

				<h2>Sign in or <a href = "register.php"> create an account.<a></h2>
					
				<input class="form-control  userInput" type="text" id="loginInput" name="username" placeholder="User Name" required>
				<input class="form-control  userInput" type="password" id="password1" name="password" placeholder="Password" required>
			

				<div>
					<input type="checkbox" onclick="showPassword()"> Show password
				</div>

				
				<input class="mt-3" type="submit" value="Login" data-toggle="modal" data-target="#dateModal">
				

				<!--
					
					<div>
				<button class="mt-3 button" type="submit" data-toggle="modal" data-target="#dateModal">
								Login
				</button>
				</div>
				
				-->
			</form>
		</div>
		
	</div>
					
	
	<script src="js/budget.js"></script>
	<script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
	<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
	<script src="js/bootstrap.min.js"></script>
	
</body>

</html>