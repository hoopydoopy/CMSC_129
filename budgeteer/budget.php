<?php
		session_start();

		if(isset($_SESSION['loggedUserId'])) {
			
			require_once 'database.php';	
			
			$incomeCategoryQuery = $db -> prepare(
			"SELECT ic.income_category
			FROM income_categories ic NATURAL JOIN user_income_category uic
			WHERE uic.user_id = :loggedUserId");
			$incomeCategoryQuery -> execute([':loggedUserId'=> $_SESSION['loggedUserId']]);
			
			$incomeCategoriesOfLoggedUser = $incomeCategoryQuery -> fetchAll();
			
			$_SESSION['incomeAdded'] = false;
			
			if(isset($_POST['incomeAmount'])) {
				
				if(!empty($_POST['incomeAmount'])) {
						
					$positiveValidation = true;
					
					$incomeAmount = number_format($_POST['incomeAmount'], 2, '.', '');
					$amount = explode('.', $incomeAmount);
						
					if(!is_numeric($incomeAmount) || strlen($incomeAmount) > 9 || $incomeAmount < 0 || !(isset($amount[1]) && strlen($amount[1]) == 2)) {
							
						$_SESSION['incomeAmountError'] = "Enter valid positive amount - maximum 6 integer digits and 2 decimal places.";
						$positiveValidation = false;
					}
					
					$incomeComment = $_POST['incomeComment'];
					
					if(!empty($incomeComment) && !preg_match('/^[A-ZĄĘÓŁŚŻŹĆŃa-ząęółśżźćń 0-9]+$/', $incomeComment)) {
						
						$_SESSION['commentError'] = "Comment can contain up to 100 characters - only letters and numbers allowed.";
						$positiveValidation = false;
					}
					
					$_SESSION['formIncomeAmount'] = $incomeAmount;
					$_SESSION['formIncomeDate'] = $_POST['incomeDate'];
					$_SESSION['formIncomeCategory'] = $_POST['incomeCategory'];
					$_SESSION['formIncomeComment'] = $incomeComment;
				
					if($positiveValidation == true) {

						$addIncomeQuery = $db -> prepare(
						"INSERT INTO incomes
						VALUES (NULL, :userId, :incomeAmount, :incomeDate,
						(SELECT category_id FROM income_categories
						WHERE income_category=:incomeCategory),
						:incomeComment)");
						$addIncomeQuery -> execute([':userId' => $_SESSION['loggedUserId'], ':incomeAmount' => $incomeAmount, ':incomeDate' => $_POST['incomeDate'], ':incomeCategory' => $_POST['incomeCategory'], ':incomeComment' => $incomeComment]);
						
						$_SESSION['incomeAdded'] = true;
					}
				} else {
					
						$_SESSION['emptyFieldError'] = "Please fill in all required fields.";
						$_SESSION['incomeAmountError'] = "Amount of an income required.";
				}
			}
		} else {

			header ("Location: landing.php");
			exit();
		}
	?>

	<!DOCTYPE html>
	<!--
		Insert income.php
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
		
		<link rel="preconnect" href="https://fonts.googleapis.com">
		<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
		<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;300;400;600&display=swap" rel="stylesheet">
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css">

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
                  
          echo '<a href="home.php?userStartDate='.$userStartDate.'&userEndDate='.$userEndDate.'" class="active">Home</a>';
        ?>
      </li>
			<li><a href="budget.php">Budget</a></li>
      <li><a href="expense.php">Expense</a></li>
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
							<h3>ADD BUDGET</h3>


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

									<input class="amountinput" type="number" name="incomeAmount" step="0.01" value="<?php
										if(isset($_SESSION['formIncomeAmount'])) {
													
											echo $_SESSION['formIncomeAmount'];
											unset($_SESSION['formIncomeAmount']);
										}
									?>">
								</div>


											<?php
												if(isset($_SESSION['incomeAmountError'])) {
															
													echo '<div class="text-danger">'.$_SESSION['incomeAmountError'].'</div>';
													unset($_SESSION['incomeAmountError']);
												}
											?>

								
								<div class="date-box">
									<div class="date">
										<span class="">Date</span>
									</div>
											<?php
											if(!isset($_SESSION['formIncomeDate'])) {
												
													echo "<script>$(document).ready(function(){getCurrentDate();})</script>";
												}
											?>
											<input class="dateinput" type="date" id="dateInput" name="incomeDate" value="<?php
												if(isset($_SESSION['formIncomeDate'])) {
													
													echo $_SESSION['formIncomeDate'];
													//unset($_SESSION['formIncomeDate']);
												}
											?>" required>
								</div>

								<div class="category-box">
									<div class="category">
										<span class="input-group-text">Category</span>
									</div>
									<select class="categoryinput" name="incomeCategory">
										<?php
											foreach ($incomeCategoriesOfLoggedUser as $category) {
											
												if(isset($_SESSION['formIncomeCategory']) && $_SESSION['formIncomeCategory'] == $category['income_category']) {
													
													echo '<option selected>'.$category['income_category']."</option>";
													unset($_SESSION['formIncomeCategory']);
												} else {
													
													echo "<option>".$category['income_category']."</option>";
												}
											}
										?>
									</select>
								</div>		
					


								<div class="comments-box">
										<div class="comments">
											<span class="input-group-text">Commments (optional)</span>
										</div>
										<textarea class="commentsinput" name="incomeComment" maxlength="100" rows="5"><?php
												if(isset($_SESSION['formIncomeComment'])) {
													
													echo $_SESSION['formIncomeComment'];
													unset($_SESSION['formIncomeComment']);
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
				if($_SESSION['incomeAdded']){
					
					echo "<script>$(document).ready(function(){ $('#incomeAdded').modal('show'); });</script>


				
						
							<div class='success'>
								<div class='modal-header'>
									<h3 class='modal-title'>New Income Added</h3>
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
				</div>

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
		window.location.href = 'home.php';
		});
	</script>
	<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
	<script src="js/bootstrap.min.js"></script>
	</body>

	</html>