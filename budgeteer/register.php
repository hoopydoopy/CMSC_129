<?php
	session_start();
	
	if(isset($_SESSION['loggedUserId'])) {
	header('Location: home.php');
	exit();
	}
	
	$_SESSION['successfulRegistration'] = false;
	
	if(isset($_POST['email'])) {
		$positiveValidation = true;
		
		$userName = $_POST['userName'];
		if((strlen($userName) < 2) || (strlen($userName) > 20)) {
			
			$positiveValidation = false;
			$_SESSION['nameError'] = "Name needs to be between 2 to 20 characters.";
		}
		/*
		if(!preg_match('/^[A-ZĄĘÓŁŚŻŹĆŃa-ząęółśżźćń]+$/', $userName)) {
			
			$positiveValidation = false;
			$_SESSION['nameError'] = "Name must contain letters only, special characters not allowed.";
		}
		*/
		$email = $_POST['email'];
		$emailCheck = filter_var($email, FILTER_SANITIZE_EMAIL);
		
		if(filter_var($emailCheck, FILTER_VALIDATE_EMAIL) == false || $emailCheck != $email) {
			
			$positiveValidation = false;
			$_SESSION['emailError'] = "Please enter a valid e-mail adress";
		}
		
		$password1 = $_POST['password'];
		$password2 = $_POST['passwordConfirm'];
		
		if(strlen($password1) < 8 || strlen($password1) > 50) {
			
			$positiveValidation = false;
			$_SESSION['passwordError'] = "Password needs to be between 8 to 50 characters.";
		}
		
		if($password1 != $password2) {
			
			$positiveValidation = false;
			$_SESSION['passwordError'] = "Password you have entered does not match.";
		}
		
		$passwordHash = password_hash($password1, PASSWORD_DEFAULT);
		
		$_SESSION['formName']=$userName;
		$_SESSION['formEmail']=$email;
		$_SESSION['formPassword1']=$password1;
		$_SESSION['formPassword2']=$password2;
		
		require_once 'database.php';
				
		$checkEmailQuery = $db->prepare(
		"SELECT user_id
		FROM users
		WHERE email = :email");
		
		$checkEmailQuery -> execute([':email' => $email]);

		$isEmailUsed = $checkEmailQuery -> rowCount();
		
		if($isEmailUsed) {
			
			$positiveValidation = false;
			$_SESSION['emailError'] = "An account with this e-mail adress already exists.";
		}
				/*VALUES(NULL, :userName, :email, :passwordHash)")*/;
		if($positiveValidation == true) {
			
			$addUserQuery = $db->prepare(
			"INSERT INTO users
			VALUES(NULL, :userName, :email, :passwordHash)");
			$addUserQuery->execute([':userName'=> $userName, ':passwordHash'=> $passwordHash,':email' => $email]);
			
			$getUserId = $db->prepare(
			"SELECT user_id
			FROM users
			WHERE email = :email");
			$getUserId -> execute([':email' => $email]);
			$result = $getUserId -> fetch();
			$userId = $result['user_id'];
			
			$assignIncomeCategoriesToUser = $db->prepare(
			"INSERT INTO user_income_category
			VALUES($userId, 1),($userId, 2),($userId, 3),($userId, 4)");
			$assignIncomeCategoriesToUser -> execute();
			
			$assignExpenseCategoriesToUser = $db->prepare(
			"INSERT INTO user_expense_category
			VALUES($userId, 1),($userId, 2),($userId, 3),($userId, 4),($userId, 5),($userId, 6),($userId, 7),($userId, 8),($userId, 9),($userId, 10),($userId, 11),($userId, 12),($userId, 13),($userId, 14),($userId, 15),($userId, 16),($userId, 17)");
			$assignExpenseCategoriesToUser -> execute();
			
			$assignPaymentMethodsToUser = $db->prepare(
			"INSERT INTO user_payment_method
			VALUES($userId, 1),($userId, 2),($userId, 3)");
			$assignPaymentMethodsToUser -> execute();
			
			$_SESSION['successfulRegistration'] = true;
		}
	}
?>

<!DOCTYPE html>

<html lang="en">

<head>

	<meta charset="utf-8">
	<title>Register-Budgeteer</title>
	<meta name="keywords" content="expense manager, budget planner, expense tracker, budgeting app, money manager, money management, personal finance management software, finance manager, saving planner">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	
	<meta http-equiv="X-Ua-Compatible" content="IE=edge">
	
	<link rel="stylesheet" href="css/bootstrap.min.css">
	<link rel="stylesheet" href="css/main.css">
	<link rel="stylesheet" href="css/fontello.css">
	<link href="https://fonts.googleapis.com/css2?family=Baloo+Paaji+2:wght@400;500;700&family=Fredoka+One&family=Roboto:wght@400;700;900&family=Varela+Round&display=swap" rel="stylesheet">
	<script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
	<link rel="stylesheet" type="text/css" href="style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;300;400;600&display=swap" rel="stylesheet">	
</head>

<body>
<!--
	<header>
	
		<h1 class="mt-3 mb-1" id="title">
			<a id="homeButton" href="landing.php" role="button"><span id="logo">Budgeteer</span>.io</a>
		</h1>
		
		<p id="subtitle">Take Control of Your Finances</p>
		
	</header> -->
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

			<h1>Welcome to Budgeteer.io!</h1>
			<h2>Sign in to start managing your budget.</h2>
			<img src="images/11124.jpg" alt="Image">
		</div>
		<div class="divider"></div>
		<div class="right-section">
			<form method="post">
				<?php
					if(isset($_SESSION['badAttempt'])) {
									
						echo '<div class="text-danger px-2">The name or password you have entered is incorrect.</div>';
						unset($_SESSION['badAttempt']);
					}
				?>

			<h2>Create an account or <a href = "login.php"> login.<a>
				
			</h2>

			<input class="form-control  userInput" type="text" name="userName" placeholder="Name" value="<?php
								if(isset($_SESSION['formName'])) {
									
									echo $_SESSION['formName'];
									unset($_SESSION['formName']);
								}
							?>" required>
			<?php
				if(isset($_SESSION['nameError'])) {
								
				echo '<div class="text-danger">'.$_SESSION['nameError'].'</div>';
				unset($_SESSION['nameError']);
							}
			?>

			<input class="form-control  userInput" type="text" name="email" placeholder="Email Address" value="<?php
				if(isset($_SESSION['formEmail'])) {
									
				echo $_SESSION['formEmail'];
				unset($_SESSION['formEmail']);
				}
			?>" required>
						
			<?php
				if(isset($_SESSION['emailError'])) {
								
					echo '<div class="text-danger">'.$_SESSION['emailError'].'</div>';
					unset($_SESSION['emailError']);
				}
			?>
		
			<input class="form-control  userInput" type="password" id="password1" name="password" placeholder="Password" value="<?php
				if(isset($_SESSION['formPassword1'])) {
									
				echo $_SESSION['formPassword1'];
				unset($_SESSION['formPassword1']);
				}
			?>" required>

			<?php
				if(isset($_SESSION['passwordError'])) {
								
				echo '<div class="text-danger">'.$_SESSION['passwordError'].'</div>';
				unset($_SESSION['passwordError']);
							}
			?>

			<input class="form-control  userInput" type="password" id="password2" name="passwordConfirm" placeholder="Confirm Password" required>

			<div>
				<input type="checkbox" onclick="showPassword()"> Show password
			</div>
			<input class="mt-3" type="submit" value="Sign up" data-toggle="modal" data-target="#dateModal">
			</form>
		</div>
		</div>
<!--
	<main>
		
		<section class="container-fluid square my-4 py-4">
			
			<form class="col-sm-10 col-md-8 col-lg-6 mx-auto my-2 py-3" method="post">
				
				<div class="row justify-content-around">
				
					<div class="col-sm-8">
					
						<div class="input-group mt-3">
							<div class="input-group-prepend px-1 pt-1 inputIcon">
								<i class="icon-user"></i>
							</div>
							<input class="form-control  userInput" type="text" name="userName" placeholder="Name" value="<?php
								if(isset($_SESSION['formName'])) {
									
									echo $_SESSION['formName'];
									unset($_SESSION['formName']);
								}
							?>" required>
						</div>
						
						<?php
							if(isset($_SESSION['nameError'])) {
								
								echo '<div class="text-danger">'.$_SESSION['nameError'].'</div>';
								unset($_SESSION['nameError']);
							}
						?>
						
						<div class="input-group mt-3">
							<div class="input-group-prepend px-1 pt-1 inputIcon">
								<i class="icon-mail-alt"></i>
							</div>
							<input class="form-control  userInput" type="email" name="email" placeholder="Email Address" value="<?php
								if(isset($_SESSION['formEmail'])) {
									
									echo $_SESSION['formEmail'];
									unset($_SESSION['formEmail']);
								}
							?>" required>
						</div>
						
						<?php
							if(isset($_SESSION['emailError'])) {
								
								echo '<div class="text-danger">'.$_SESSION['emailError'].'</div>';
								unset($_SESSION['emailError']);
							}
						?>
						
						<div class="input-group mt-3">
							<div class="input-group-prepend px-1 pt-1 inputIcon">
							<i class="icon-lock"></i>
							</div>
							<input class="form-control  userInput" type="password" id="password1" name="password" placeholder="Password" value="<?php
								if(isset($_SESSION['formPassword1'])) {
									
									echo $_SESSION['formPassword1'];
									unset($_SESSION['formPassword1']);
								}
							?>" required>
						</div>
						
						<?php
							if(isset($_SESSION['passwordError'])) {
								
								echo '<div class="text-danger">'.$_SESSION['passwordError'].'</div>';
								unset($_SESSION['passwordError']);
							}
						?>
						
						<div class="input-group mt-3">
							<div class="input-group-prepend px-1 pt-1 inputIcon">
								<i class="icon-lock"></i>
							</div>
							<input class="form-control  userInput" type="password" id="password2" name="passwordConfirm" placeholder="Confirm Password" required>
							
						</div>
						
						<div id="passwordCheck">
							<input class="mt-3" type="checkbox" onclick="showPassword()"> Show Password
						</div>
						
						<button class="btn btn-lg mt-3 mb-2 signButton" type="submit" data-toggle="modal" data-target="#dateModal">
							<i class="icon-user-plus"></i> Sign up
						</button>
						
					</div>
					
				</div>
				
			</form>
			
		</section>
		
		<div class="modal fade" id="registration" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
		  <div class="modal-dialog" role="document">
			<div class="modal-content">
			  <div class="modal-body">
				<h3 class="modal-title">Thankyou!</h3>
			  </div>
			</div>
		  </div>
		</div>
		
		<?php
			if($_SESSION['successfulRegistration'] == true) {
				
				echo "<script>$(document).ready(function(){ $('#registrationModal').modal('show'); });</script>

					<div class='modal fade' id='registrationModal' role='dialog'>
						<div class='modal-dialog col'>
							<div class='modal-content'>
								<div class='modal-header'>
									<h3 class='modal-title'>Successful Registration</h3>
									<a href='login.php'>
										<button type='button' class='close'>&times;</button>
									</a>
								</div>
														
								<div class='modal-body'>
									<p>Thank you for registration! You can now login.</p>
								</div>
								<div class='modal-footer'>
									<a href='login.php'>
										<button type='button' class='btn btn-success'>Sign in</button>
									</a>
								</div>
							</div>
						</div>
					</div>"; 
			}
		?>
		
	</main>-->

	<?php
			if($_SESSION['successfulRegistration'] == true) {
				
				echo "<script>$(document).ready(function(){ $('#registrationModal').modal('show'); });</script>

					<div class='modal fade' id='registrationModal' role='dialog'>
						<div class='modal-dialog col'>
							<div class='modal-content'>
								<div class='modal-header'>
									<h3 class='modal-title'>Successful Registration</h3>
									<a href='login.php'>
										<button type='button' class='close'>&times;</button>
									</a>
								</div>
														
								<div class='modal-body'>
									<p>Thank you for registration! You can now sign in.</p>
								</div>
								<div class='modal-footer'>
									<a href='login.php'>
										<button type='button' class='btn btn-success'>Sign in</button>
									</a>
								</div>
							</div>
						</div>
					</div>"; 
			}
		?>
	
	<!--<footer>
	
		<div class="col my-2 footer">
			2023 &copy; Budgeteer.io
		</div>
		
	</footer> -->
	
	<script src="js/bootstrap.min.js"></script>
	<script src="js/budget.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
	
</body>

</html>