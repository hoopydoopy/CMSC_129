<?php
    session_start();

	if(isset($_SESSION['loggedUserId'])) {
        require_once 'database.php';

    }else {

		header ("Location: landing.php");
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

          echo '<a href="summary.php?userStartDate='.$userStartDate.'&userEndDate='.$userEndDate.'.&period=month" class="active">Statistics</a>';
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

	<?php
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "my_budget";
		// Connect to MySQL

        $conn = new mysqli($servername, $username, $password, $dbname);

		// Check connection
		if (!$conn) {
		    die("Connection failed: " . mysqli_connect_error());
		}

		// Get user's current information

		$user_id = $_SESSION['loggedUserId']; // Assume user is logged in
		$income_id = $_GET['income_id']; // fetches income_id of an income

		// query to fetch income_id, income_amount, and income_comment from incomes table
		$sql = "SELECT income_id, income_amount, income_comment FROM incomes WHERE income_id = '$income_id'";

		$result = mysqli_query($conn, $sql);

		// fetches the values of current_amount and current_comment
		if (mysqli_num_rows($result) > 0) {
			$row = mysqli_fetch_assoc($result);
			$current_amount = $row['income_amount'];
			$current_comment = $row['income_comment'];

		} else {
			echo "Error: User not found";
		}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get user input
    $new_name = $_POST['income_amount'];
    $new_password = $_POST['income_comment'];
    // Validate input (not shown)

    // Update database using prepared statements
	$sql = "UPDATE incomes SET income_amount = ?, income_comment = ? WHERE income_id = ?";
	$stmt = $conn->prepare($sql);
	$stmt->bind_param("ssi", $new_name, $new_password, $income_id);

	// heads to summary.php after executing
    if ($stmt->execute()) {
        header("Location: summary.php");
    } else { // profile failed to update
        echo "Error updating profile: " . $stmt->error;
    }

}
	?>
	<!-- form to get income and comments -->

	<div class="box">
		<form class="budget-form" method="post">
		<h3>EDIT INCOME</h3>
		<div class="column">
			<div class="amount-box">
				<div class="amount"> <span class="">Income Amount</span> </div>
				<input class="amountinput" type="number" step="0.01" name="income_amount" value = "<?php echo $current_amount; ?>" required><br>
				
			</div>
		</div>

		<div class="column">
			<div class="amount-box">
				<div class="amount"> <span class="">Comments</span> </div>
				<input class="amountinput" type="text" name="income_comment" value = "<?php echo $current_comment; ?>" ><br>
			</div>
			<br>
			<br>
			<input class="saveButton" type="submit" value="Save">
			<button id="cancelButton" class="cancelButton"> Cancel</button>
		</div>


		</form>
	</div>

</body>
</html>
<!--
	<form method="post">
		<label for="name">Income Amount:</label>
		<input type="text" name="income_amount" value = "<?php echo $current_amount; ?>" required><br>

		<label for="password">Comments:</label>
		<input type="text" name="income_comment" value = "<?php echo $current_comment; ?>"><br>

		<input type="submit" value="Save Changes">
	</form>
-->