<?php
	session_start();

	if(isset($_SESSION['loggedUserId'])) {
		require_once 'database.php';
		
		$expenseCategoryQuery = $db -> prepare(
		"SELECT ec.expense_category
		FROM expense_categories ec NATURAL JOIN user_expense_category uec
		WHERE uec.user_id = :loggedUserId");
		$expenseCategoryQuery -> execute([':loggedUserId'=> $_SESSION['loggedUserId']]);
		
		$expenseCategoriesOfLoggedUser = $expenseCategoryQuery -> fetchAll();
		
		$paymentMethodQuery = $db -> prepare(
		"SELECT pm.payment_method
		FROM payment_methods pm NATURAL JOIN user_payment_method upm
		WHERE upm.user_id = :loggedUserId");
		$paymentMethodQuery -> execute([':loggedUserId'=> $_SESSION['loggedUserId']]);
		
		$paymentMethodsOfLoggedUser = $paymentMethodQuery -> fetchAll();
		
		$_SESSION['expenseAdded'] = false;
		
		if(isset($_POST['expenseAmount'])) {
			
			if(!empty($_POST['expenseAmount'])) {
					
				$positiveValidation = true;
				
				$expenseAmount = number_format($_POST['expenseAmount'], 2, '.', '');
				$amount = explode('.', $expenseAmount);
					
				if(!is_numeric($expenseAmount) || strlen($expenseAmount) > 9 || $expenseAmount < 0 || !(isset($amount[1]) && strlen($amount[1]) == 2)) {
						
					$_SESSION['expenseAmountError'] = "Enter valid positive amount - maximum 6 integer digits and 2 decimal places.";
					$positiveValidation = false;
				}
				
				$expenseComment = $_POST['expenseComment'];
				
				if(!empty($expenseComment) && !preg_match('/^[A-ZĄĘÓŁŚŻŹĆŃa-ząęółśżźćń 0-9]+$/', $expenseComment)) {
					
					$_SESSION['commentError'] = "Comment can contain up to 100 characters - only letters and numbers allowed.";
					$positiveValidation = false;
				}
				
				
				$_SESSION['formExpenseAmount'] = $expenseAmount;
				$_SESSION['formExpenseDate'] = $_POST['expenseDate'];
				$_SESSION['formExpensePaymentMethod'] = $_POST['expensePaymentMethod'];
				$_SESSION['formExpenseCategory'] = $_POST['expenseCategory'];
				$_SESSION['formExpenseComment'] = $expenseComment;
			
				if($positiveValidation == true) {

					$addExpenseQuery = $db->prepare(
					"INSERT INTO expenses
					VALUES (NULL, :userId, :expenseAmount, :expenseDate,
					(SELECT payment_method_id FROM payment_methods
					WHERE payment_method=:expensePaymentMethod),
					(SELECT category_id FROM expense_categories
					WHERE expense_category=:expenseCategory),
					:expenseComment)");
					$addExpenseQuery -> execute([':userId' => $_SESSION['loggedUserId'], ':expenseAmount' => $expenseAmount, ':expenseDate' => $_POST['expenseDate'], ':expensePaymentMethod' => $_POST['expensePaymentMethod'], ':expenseCategory' => $_POST['expenseCategory'], ':expenseComment' => $expenseComment]);
					
					$_SESSION['expenseAdded'] = true;
				}
			} else {
				
					$_SESSION['emptyFieldError'] = "Please fill in all required fields.";
					$_SESSION['expenseAmountError'] = "Amount of an expense required.";
			}
		}
	} else {
		
		header ("Location: landing.php");
		exit();
	}
?>

<!DOCTYPE html>
<!--
    Insert expense.php
-->

<html lang="en">

<head>

	<meta charset="utf-8">
	<title>Budget</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	
	<meta http-equiv="X-Ua-Compatible" content="IE=edge">
	
	<script src="js/budget.js"></script>

	<script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
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
			<p>John Doe</p>
		</div>
		<ul>
			<li><a href="#">Profile</a></li>
			<li>
        
        <?php
          $userStartDate = date('Y-m-d');
		      $userEndDate = date('Y-m-d');
                  
          echo '<a href="home.php?userStartDate='.$userStartDate.'&userEndDate='.$userEndDate.'">Home</a>';
        ?>
      </li>
			<li><a href="budget.php">Budget</a></li>
      <li><a href="expense.php" class="active">Expense</a></li>
			<li>
        
        <?php
          $userStartDate = date('Y-m-01');
          $userEndDate = date('Y-m-t');
                  
          echo '<a href="summary.php?userStartDate='.$userStartDate.'&userEndDate='.$userEndDate.'&period=month">Statistics</a>';
        ?>
      </li>
      <li><a href="#">Notes</a></li>
      <li><a href="calendar.php">Calendar</a></li>
      <li><a href="settings.php">Settings</a></li>
      <li><a href="logout.php">Log Out</a></li>
		</ul>
        <div class="logo">
            <img src="images/Logo3.png" alt="Logo">
          </div>
	</div>

		<div class="box">
			<form class="budget-form" method="post">

			
				<h3>ADD EXPENSE</h3>
			

				<?php
					if(isset($_SESSION['emptyFieldError'])) {
								
						echo '<div class="text-danger">'.$_SESSION['emptyFieldError'].'</div>';
						unset($_SESSION['emptyFieldError']);
					}
				?>

					<div class="column">
							<div class="amount-box">
								<div class="amount">
								<span class="">Amount</span>
								</div>

										<input class="amountinput" type="number" name="expenseAmount" step="0.01" value="<?php
												if(isset($_SESSION['formExpeseAmount'])) {
													
													echo $_SESSION['formExpeseAmount'];
													unset($_SESSION['formExpeseAmount']);
												}
											?>">
							</div>
								
										<?php
											if(isset($_SESSION['expenseAmountError'])) {
												
												echo '<div class="text-danger">'.$_SESSION['expenseAmountError'].'</div>';
												unset($_SESSION['expenseAmountError']);
											}
										?>
								

							<div class="date-box">
								<div class="date">
								<span class="">Date</span>
								</div>
								
										<?php
										if(!isset($_SESSION['formExpenseDate'])) {
											
												echo "<script>$(document).ready(function(){getCurrentDate();})</script>";
											}
										?>
										
										<input class="dateinput" type="date" name="expenseDate" id="dateInput" value="<?php
											if(isset($_SESSION['formExpenseDate'])) {
												
												echo $_SESSION['formExpenseDate'];
												unset($_SESSION['formExpenseDate']);
											}
										?>" required>
							</div>
								

							<div class="method-box">
								<div class="method">
								<span class="">Payment Method</span>
								</div>

								<select class="methodinput" name="expensePaymentMethod">

										<?php
											foreach ($paymentMethodsOfLoggedUser as $payment_method) {
											
												if(isset($_SESSION['formExpensePaymentMethod']) && $_SESSION['formExpensePaymentMethod'] == $payment_method['payment_method']) {
													
													echo '<option selected>'.$payment_method['payment_method'].'</option>';
													unset($_SESSION['formExpensePaymentMethod']);
												} else {
													
													echo '<option>'.$payment_method['payment_method'].'</option>';
												}
											}
										?>

								</select>
							</div>
						

							<div class="category-box">
								<div class="category">
								<span class="">Category</span>
								</div>

								<select class="categoryinput" name="expenseCategory">

											<?php
												foreach ($expenseCategoriesOfLoggedUser as $category) {
												
													if(isset($_SESSION['formExpenseCategory']) && $_SESSION['formExpenseCategory'] == $category['expense_category']) {
														
														echo '<option selected>'.$category['expense_category']."</option>";
														unset($_SESSION['formExpenseCategory']);
													} else {
														
														echo "<option>".$category['expense_category']."</option>";
													}
												}
											?>

								</select>
							</div>
							
					
					
							<div class="comments-box">
								<div class="comments">
								<span class="">Commments (optional)</span>
								</div>

										<textarea class="commentsinput" name="expenseComment" rows="5"><?php
												if(isset($_SESSION['formExpenseComment'])) {
													
													echo $_SESSION['formExpenseComment'];
													unset($_SESSION['formExpenseComment']);
												}
											?></textarea>
							</div>
							
										<?php
											if(isset($_SESSION['commentError'])) {
												
												echo '<div class="text-danger">'.$_SESSION['commentError'].'</div>';
												unset($_SESSION['commentError']);
											}
										?>


							
						
					</div>

					<div class="button-container">
									<button class="saveButton" type="submit">
										<i class="icon-floppy"></i> Save
									</button>
								<a data-toggle="modal" data-target="#discardExpenseModal">
									<button id="cancelButton" class="cancelButton">
										<i class="icon-cancel-circled"></i> Cancel
									</button>
								</a>
					</div>

			</form>	
			</div>
         

		  				

			<?php
				if($_SESSION['expenseAdded']){
					
					echo "<script>$(document).ready(function(){ $('#expenseAdded').modal('show'); });</script>

					<div class='success'>
								<div class='modal-header'>
									<h3 class='modal-title'>New Expense Added</h3>
								</div>
								
							</div>"; 
				}
			?>
			
			<div id="popup" class="popup">
				<div class="popup-content">
					<h3>Quit adding expense?</h3>
					<p>Your changes will not be saved.</p>
					<button id="confirmBtn">Confirm</button>
					<button id="cancelPopupBtn">Cancel</button>
				</div>
			</div>
	
	<script>
		
		function openPopup() {
		document.getElementById('popup').style.display = "block";
		}

		function closePopup() {
		document.getElementById('popup').style.display = "none";
		}

		document.getElementById('cancelButton').addEventListener('click', openPopup);
		document.getElementById('cancelPopupBtn').addEventListener('click', closePopup);

		const confirmBtn = document.getElementById('confirmBtn');

		confirmBtn.addEventListener('click', function() {
			<?php
          		$userStartDate = date('Y-m-d');
			  $userEndDate = date('Y-m-d');
			  echo "window.location.href = 'home.php?userStartDate=".$userStartDate."&userEndDate=".$userEndDate."';";
		?>
		//window.location.href = 'home.php';
		});
	</script>
	
	<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
	<script src="js/bootstrap.min.js"></script>
</body>

</html>