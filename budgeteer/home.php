<?php
	session_start();
  $startDate = 00-00-00;
	$endDate = 00-00-00;

	require_once 'database.php';
	
	if(!isset($_SESSION['loggedUserId'])) {
		
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
			/*if($user && $password) {*/
				
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
?>

<?php 
   /*
    $startDate = 00-00-00;
    $endDate = 00-00-00;
    
    if(isset($_SESSION['loggedUserId'])) {
		
      require_once 'database.php';
      
      if(isset($_GET['userStartDate'])) {
        
        if($_GET['userStartDate'] > $_GET['userEndDate']) {
          
          $startDate = $_GET['userEndDate'];
          $endDate = $_GET['userStartDate'];
        } else {
          
          $startDate = $_GET['userStartDate'];
          $endDate = $_GET['userEndDate'];
        }
        
        $expensesQuery = $db -> prepare(
        "SELECT e.category_id, ec.expense_category, SUM(e.expense_amount) AS expense_amount
        FROM expenses e NATURAL JOIN expense_categories ec
        WHERE e.user_id=:loggedUserId AND e.expense_date BETWEEN :startDate AND :endDate
        GROUP BY e.category_id
        ORDER BY expense_amount DESC");
        $expensesQuery -> execute([':loggedUserId'=> $_SESSION['loggedUserId'], ':startDate'=> $startDate, ':endDate'=> $endDate]);
        
        $expensesOfLoggedUser = $expensesQuery -> fetchAll();
        
        $incomesQuery = $db -> prepare(
        "SELECT i.category_id, ic.income_category, SUM(i.income_amount) AS income_amount
        FROM incomes i NATURAL JOIN income_categories ic
        WHERE i.user_id=:loggedUserId AND i.income_date BETWEEN :startDate AND :endDate
        GROUP BY i.category_id
        ORDER BY income_amount DESC");
        $incomesQuery -> execute([':loggedUserId'=> $_SESSION['loggedUserId'], ':startDate'=> $startDate, ':endDate'=> $endDate]);
        
        $incomesOfLoggedUser = $incomesQuery -> fetchAll();
        
        echo "<script>
            var incomes = ".json_encode($incomesOfLoggedUser).";
            var expenses = ".json_encode($expensesOfLoggedUser)."
          </script>";
        
      } 
    }*/
  ?>

<!DOCTYPE html>
<html>
<head>
	<title>Homepage</title>
	  <link rel="stylesheet" type="text/css" href="homestyle.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css">

    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
</head>

<body onload="drawChart(incomes, expenses)" onresize="drawChart(incomes, expenses)">

  
	<div class="navbar">
		<div class="profile">
			<img src="images/profile pic.png" alt="Profile Picture">
			<p>John Doe</p>
		</div>
		<ul>
			<li><a href="#">Profile</a></li>
			<li><a href="home.php" class="active">Home</a></li>
			<li><a href="budget.php">Budget</a></li>
      <li><a href="expense.php">Expense</a></li>
			<li>
        <!--<a href="balance.php">Statistics</a>-->
        <?php
          $userStartDate = date('Y-m-01');
          $userEndDate = date('Y-m-t');
                  
          echo '<a class="dropdown-item" href="balance.php?userStartDate='.$userStartDate.'&userEndDate='.$userEndDate.'">Statistics</a>';
        ?>
      </li>
      <li><a href="#">Notes</a></li>
      <li><a href="#">Calendar</a></li>
      <li><a href="settings.php">Settings</a></li>
      <li><a href="logout.php">Log Out</a></li>
		</ul>
        <div class="logo">
            <img src="images/Logo3.png" alt="Logo">
          </div>
	</div>
    <div class="container">
        <div class="calendar-slider">
          <div class="slider-nav">
            <i class="fas fa-chevron-left"></i>

              <span class="date" id ="result">

                <script>
                function dateToWords() {
                  const options = { month: 'long', day: 'numeric', year: 'numeric' };
                  const dateObj = new Date();
                  const dateString = dateObj.toLocaleDateString('en-US', options);
                  return dateString;
                }

                  const dateElement = document.getElementById('result');
                  const currentDate = dateToWords();
                  dateElement.innerHTML = currentDate;
                </script>

              </span>

            <i class="fas fa-chevron-right"></i>
          </div>
        </div>
        <div class="box big-box">
          <div class="box-label">Overview</div>
          
          <?php
          $categories = array("Food", "Entertainment", "Transportation", "Utilities", "Shopping");
          ?>
          
          <div class="category-list">
              <!--<h3>Categories</h3>
               
              <div class="category-boxes">
                  <?php foreach ($categories as $category) { ?>
                      <div class="category-box">
                          <div class="category-label"><?php echo $category; ?></div>
                          <div class="category-circle"></div>
                          <div class="category-amount">$1000</div>
                      </div>
                  <?php } ?>
              </div>
             
              <form method="post" action="add_category.php">
                  <input type="text" name="new_category" placeholder="Add new category">
                  <button type="submit">Add</button>
              </form>
               -->
          </div>
          <!-- Content for big box here -->

            <?php
            /*
              $totalIncomes = 0;
              $totalExpenses = 0;
                      
              foreach ($incomesOfLoggedUser as $incomes) {
                $totalIncomes += $incomes['income_amount'];
                
              }
              foreach ($expensesOfLoggedUser as $expenses) {
                $totalExpenses += $expenses['expense_amount'];
              }
                $balance = $totalIncomes - $totalExpenses;
                echo '<div id="balance">BALANCE:&emsp;'.$balance.'</div>';
                
            ?>

            <?php
                if(!empty($incomesOfLoggedUser)) {
                  
                  echo '<div class="col-sm-8 col-lg-6 mt-4 mb-2 pt-2 pb-4 mx-auto box"><div id="piechart1"></div></div>';
                }
              
                if(!empty($expensesOfLoggedUser)) {
                  
                  echo '<div class="col-sm-8 col-lg-6 my-3 pt-2 pb-4 mx-auto box"><div id="piechart2"></div></div>';
                }*/
            ?>


        </div>
        <div class="box small-box">
          <div class="box-label">Transactions</div>
          <!-- Content for top small box here -->
        </div>

      </div>

      <script src="js/budget.js"></script>
      <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
      <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
      <script src="js/bootstrap.min.js"></script>
      <script src="js/jquery-3.4.1.min.js"></script>
</body>

</html>