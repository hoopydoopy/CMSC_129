<?php
	session_start();

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
			
		} else {
			
			header ("Location: home.php");
			exit();
		}
	} else {

		header ("Location: login.php");
		exit();
	}
?>

<!DOCTYPE html>
<html>
<head>
	<title>Statistics-Budgeteer</title>
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

<!--Week, Month, Year Button -->

  <div class="container">
      <div class="center-div">
      
      
    
          <?php
            $today = date('Y-m-d');
            
            $userStartDate = date('Y-m-d', strtotime('last Sunday', strtotime($today)));
            $userEndDate = date('Y-m-d', strtotime('next Saturday', strtotime($userStartDate)));
              
            //echo '<a class="button" href="summary.php?userStartDate='.$userStartDate.'&userEndDate='.$userEndDate.'">Week</a>';
            //echo '<button class="button" onclick="location.href=\'summary.php?userStartDate='.$userStartDate.'&userEndDate='.$userEndDate.'\'">Week</button>';
            //echo '<button class="button" onclick="updateDateRange(\''.$userStartDate.'\', \''.$userEndDate.'\', \'week\')">Week</button>';
            
          ?>
          <button class="button" onclick="location.href='summary.php?userStartDate=<?php echo $userStartDate; ?>&userEndDate=<?php echo $userEndDate; ?>&period=week'">Week</button>

          <?php
            $userStartDate = date('Y-m-01');
            $userEndDate = date('Y-m-t');
                      
            //echo '<a class="button" href="summary.php?userStartDate='.$userStartDate.'&userEndDate='.$userEndDate.'">Month</a>';
            //echo '<button class="button" onclick="location.href=\'summary.php?userStartDate='.$userStartDate.'&userEndDate='.$userEndDate.'\'">Month</button>';       
            //echo '<button class="button" onclick="updateDateRange(\''.$userStartDate.'\', \''.$userEndDate.'\', \'month\')">Month</button>';
  
          ?>
          <button class="button" onclick="location.href='summary.php?userStartDate=<?php echo $userStartDate; ?>&userEndDate=<?php echo $userEndDate; ?>&period=month'">Month</button>
          <?php
            $userStartDate = date('Y-01-01');
            $userEndDate = date('Y-12-31');
                      
            //echo '<a class="button" href="summary.php?userStartDate='.$userStartDate.'&userEndDate='.$userEndDate.'">Year</a>';
            //echo '<button class="button" onclick="location.href=\'summary.php?userStartDate='.$userStartDate.'&userEndDate='.$userEndDate.'\'">Year</button>';
            //echo '<button class="button" onclick="updateDateRange(\''.$userStartDate.'\', \''.$userEndDate.'\', \'year\')">Year</button>';
          ?>
          <button class="button" onclick="location.href='summary.php?userStartDate=<?php echo $userStartDate; ?>&userEndDate=<?php echo $userEndDate; ?>&period=year'">Year</button>

        <script>
          function updateDateRange(startDate, endDate, timePeriod) {
            var url = 'summary.php?userStartDate=' + startDate + '&userEndDate=' + endDate + '&timePeriod=' + timePeriod;
            window.location.href = url;
          }
        </script>
        <br>
        
        <?php
        echo'<br>';
        echo "<span class='date' id ='result'>".date('M j, Y', strtotime($startDate))."</span>  -  <span class='date' id ='result'>".date('M j, Y', strtotime($endDate))."</span>";
        ?>
      </div>
  </div>

  <!--Week, Month, Year Button -->

    <!--Custom Button -->

    <div>

      <!-- -->
      <form action="summary.php" method="GET">
        <input type="date" name="userStartDate" required>
        <input type="date" name="userEndDate" required>
        <button type="submit" class="button">Save</button>
      </form>

      <br>
      
      <?php
        $startDate = $_GET['userStartDate'] ?? $userStartDate;
        $endDate = $_GET['userEndDate'] ?? $userEndDate;
        
      // echo "<span class='date' id ='result'>".date('M j, Y', strtotime($startDate))."</span>  -  <span class='date' id ='result'>".date('M j, Y', strtotime($endDate))."</span>";
      ?>
    </div>  
      
    <!--Custom Button -->

  <div class="container">


  <div class="box small-box">
        <div class="box-label">Total Expenses </div>
        <?php
								$totalExpenses = 0;
                $transExpenses = 0;
								
								foreach ($expensesOfLoggedUser as $expenses) {
									
									echo "<tr class=\"summary\">
                  <!--<td class=\"category\">{$expenses['expense_category']}</td><td class=\"sum\"> {$expenses['expense_amount']} ₱</td>-->
	
									</tr>";

									$totalExpenses += $expenses['expense_amount'];
									
									$expensesTableRowsQuery = $db -> prepare(
									"SELECT e.expense_date, e.expense_amount, pm.payment_method, e.expense_comment
									FROM expenses e NATURAL JOIN payment_methods pm
									WHERE e.category_id=:expenseCategoryId AND e.user_id=:loggedUserId AND e.expense_date BETWEEN :startDate AND :endDate
									ORDER BY e.expense_date ASC");
									$expensesTableRowsQuery -> execute([':loggedUserId' => $_SESSION['loggedUserId'], ':expenseCategoryId' => $expenses['category_id'], ':startDate'=> $startDate, ':endDate'=> $endDate]);
									
									$expensesOfSpecificCategory = $expensesTableRowsQuery -> fetchAll();
									
									foreach ($expensesOfSpecificCategory as $categoryExpense) {
										
										//echo "<tr><td class=\"date\">{$categoryExpense['expense_date']}</td>| ₱ <td class=\"amount\">{$categoryExpense['expense_amount']} | </td><td class=\"payment\">{$categoryExpense['payment_method']}</td>| <td class=\"comment\">{$categoryExpense['expense_comment']}<br></td></tr>";
                    //echo nl2br("=============");
                    $transExpenses++;

									}
								}
                //$startDate = $_GET['userStartDate'] ?? $userStartDate;
                //$endDate = $_GET['userEndDate'] ?? $userEndDate;

                $userStartDate = $startDate;
                $userEndDate = $endDate;
                
               // Get expenses for the previous time period based on the selected button
            $prevExpenses = 0;
            $prevPeriodLabel = '';

            if (isset($_GET['period'])) {
              $period = $_GET['period'];

              switch ($period) {
                case 'week':
                  $prevStartDate = date('Y-m-d', strtotime('-1 week', strtotime($userStartDate)));
                  $prevEndDate = date('Y-m-d', strtotime('-1 day', strtotime($userEndDate)));
                  $prevPeriodLabel = 'previous week';
                  break;
                case 'month':
                  $prevStartDate = date('Y-m-01', strtotime('-1 month', strtotime($userStartDate)));
                  $prevEndDate = date('Y-m-d', strtotime('last day of previous month', strtotime($userStartDate)));
                  $prevPeriodLabel = 'previous month';
                  break;
                case 'year':
                  $prevStartDate = date('Y-m-d', strtotime('-1 year', strtotime($userStartDate)));
                  $prevEndDate = date('Y-m-d', strtotime('-1 day', strtotime('last day of previous year', strtotime($userStartDate))));
                  $prevPeriodLabel = 'previous year';
                  break;
                default:
                  $prevStartDate = '';
                  $prevEndDate = '';
                  $prevPeriodLabel = '';
                  break;
              }

              if (!empty($prevStartDate) && !empty($prevEndDate)) {
                $prevExpensesQuery = $db->prepare("
                  SELECT SUM(expense_amount) AS total_expenses
                  FROM expenses
                  WHERE user_id = :loggedUserId
                    AND expense_date BETWEEN :prevStartDate AND :prevEndDate
                ");
                $prevExpensesQuery->execute([
                  ':loggedUserId' => $_SESSION['loggedUserId'],
                  ':prevStartDate' => $prevStartDate,
                  ':prevEndDate' => $prevEndDate
                ]);

                $prevExpensesResult = $prevExpensesQuery->fetch(PDO::FETCH_ASSOC);
                if ($prevExpensesResult && $prevExpensesResult['total_expenses']) {
                  $prevExpenses = $prevExpensesResult['total_expenses'];
                  //echo $prevExpenses;
                  
                }
                
              }
              
              
            }

            // Calculate the percentage increase or decrease
            $percentageChange = 0;
            if ($prevExpenses != 0) {
              $percentageChange = round((($totalExpenses - $prevExpenses) / $prevExpenses) * 100);
            }

            // Determine if there's an increase or decrease
            $changeIndicator = '';
            if ($percentageChange > 0) {
              $changeIndicator = 'Increase';
            } elseif ($percentageChange < 0) {
              $changeIndicator = 'Decrease';
            } else {
              $changeIndicator = 'Change';
            }

            // Display the total expenses, percentage change, and change indicator
            echo '<center>';
            echo "<tr class=\"summary\"><td class=\"total\">₱ </td><td class=\"sum\">{$totalExpenses}</td></tr>";
            echo '</center>';
            echo "<tr class=\"summary\"><td class=\"indicator\"></td><td class=\"indicator\">{$percentageChange}% {$changeIndicator} from {$prevPeriodLabel}</td></tr>";
              ?>
  </div>

  <div class="box small-box">
        <div class="box-label">Total Income </div>

        <?php
          $totalIncomes = 0;
          $transIncomes = 0;
                    
          foreach ($incomesOfLoggedUser as $incomes) {
                      
                    echo "<tr class=\"summary\">
                        
                    <!-- <td class=\"category\">{$incomes['income_category']}</td><td class=\"sum\">{$incomes['income_amount']} ₱</td> -->
                                            
                                            
                    </tr>";
                    //echo nl2br("=============");

                    $totalIncomes += $incomes['income_amount'];
                                            
                    $incomesTableRowsQuery = $db -> prepare(
                    "SELECT income_date, income_amount, income_comment
                    FROM incomes
                    WHERE category_id=:incomeCategoryId AND user_id=:loggedUserId AND income_date BETWEEN :startDate AND :endDate
                    ORDER BY income_date ASC");
                    $incomesTableRowsQuery -> execute([':loggedUserId' => $_SESSION['loggedUserId'], ':incomeCategoryId' => $incomes['category_id'], ':startDate'=> $startDate, ':endDate'=> $endDate]);
                                            
                    $incomesOfSpecificCategory = $incomesTableRowsQuery -> fetchAll();
                                            
                    foreach ($incomesOfSpecificCategory as $categoryIncome) {
                                                
                        //echo "<tr><td class=\"date\">{$categoryIncome['income_date']}</td>| ₱ <td class=\"amount\">{$categoryIncome['income_amount']} | </td><td class=\"comment\">{$categoryIncome['income_comment']}</td>
                        //</tr>";
                            //echo nl2br("=============");
                            $transIncomes ++;
                    }
          }
                    
          // echo "<tr class=\"summary\"><td class=\"total\">TOTAL</td><td class=\"sum\">{$totalIncomes} ₱</td>  </tr>";
          echo'<center>';
          echo "<tr class=\"summary\"><td class=\"total\">₱ </td><td class=\"sum\">{$totalIncomes} </td>  </tr>";
          //echo"<br>Total Income Transaction: $transIncomes";
          echo'</center>';
        ?>

  </div>

  <div class="box small-box">
        <div class="box-label">Balance </div>
        <?php	
            $totalIncomes = 0;
            $totalExpenses = 0;
                    
            foreach ($incomesOfLoggedUser as $incomes) {
              $totalIncomes += $incomes['income_amount'];
              
            }
            foreach ($expensesOfLoggedUser as $expenses) {
              $totalExpenses += $expenses['expense_amount'];
            }
              $balance = $totalIncomes - $totalExpenses;
              echo '<center><div id="balance">₱ '.$balance.'</div></center>';
            ?>
  </div>

  <div class="box small-box">
    <div class="box-label">Largest Spending </div>

    <?php
      $largestExpense = 0;
      $largestExpenseItem = null;

      foreach ($expensesOfLoggedUser as $expenses) {

        $expensesTableRowsQuery = $db -> prepare(
          "SELECT e.expense_date, e.expense_amount, pm.payment_method, e.expense_comment
          FROM expenses e NATURAL JOIN payment_methods pm
          WHERE e.category_id=:expenseCategoryId AND e.user_id=:loggedUserId AND e.expense_date BETWEEN :startDate AND :endDate
          ORDER BY e.expense_date ASC");
          $expensesTableRowsQuery -> execute([':loggedUserId' => $_SESSION['loggedUserId'], ':expenseCategoryId' => $expenses['category_id'], ':startDate'=> $startDate, ':endDate'=> $endDate]);
          
          $expensesOfSpecificCategory = $expensesTableRowsQuery -> fetchAll();

          foreach ($expensesOfSpecificCategory as $categoryExpense) {
            $expenseAmount = $categoryExpense['expense_amount'];
    
            if ($expenseAmount > $largestExpense) {
              $largestExpense = $expenseAmount;
              $largestExpenseItem = $categoryExpense;
            }
    
            //echo "<tr><td class=\"date\">{$categoryExpense['expense_date']}</td>| ₱ <td class=\"amount\">{$categoryExpense['expense_amount']} | </td><td class=\"payment\">{$categoryExpense['payment_method']}</td>| <td class=\"comment\">{$categoryExpense['expense_comment']}<br></td></tr>";
          }
    
          // Display the largest expense if found
      }

      /*if ($largestExpenseItem) {
        echo "₱{$largestExpenseItem['expense_amount']} ({$largestExpenseItem['expense_date']})";
      }*/
      if ($largestExpenseItem) {
        $formattedAmount = '₱ ' . number_format($largestExpenseItem['expense_amount'], 2);
        $formattedDate = date('F j, Y', strtotime($largestExpenseItem['expense_date']));
        echo "<center>{$formattedAmount}</center>";
        echo"<br>on {$formattedDate}";
      }

    ?>
  </div>

  <div class="box small-box">
        <div class="box-label">Total Transactions </div>
        <?php
        $totalTrans=$transIncomes + $transExpenses;
        echo"<center> $totalTrans </center>";
        ?>

    </div>
    
  </div>


  <div class="container">

      <div class="box big-box">
          <div class="box-label">Expense Distribution</div>
          <!-- Content for big box here -->

          
          <!-- Statistics -->
			
          <div class="row col-sm-6 col-lg-4 justify-content-center mt-5 mb-2 mx-auto box">
            
            <?php	
            $totalIncomes = 0;
            $totalExpenses = 0;
                    
            foreach ($incomesOfLoggedUser as $incomes) {
              $totalIncomes += $incomes['income_amount'];
              
            }
            foreach ($expensesOfLoggedUser as $expenses) {
              $totalExpenses += $expenses['expense_amount'];
            }
              $balance = $totalIncomes - $totalExpenses;
              //echo '<center><div id="balance">BALANCE:&emsp;'.$balance.'</div></center>';
            ?>
            
          </div>
          
          <?php
            if(!empty($incomesOfLoggedUser)) {
              
              echo '<div class="col-sm-8 col-lg-6 mt-4 mb-2 pt-2 pb-4 mx-auto box"><div id="piechart1"></div></div>';
            }
          
            if(!empty($expensesOfLoggedUser)) {
              
              echo '<div class="col-sm-8 col-lg-6 my-3 pt-2 pb-4 mx-auto box"><div id="piechart2"></div></div>';
            }
          ?>

          <!-- End of Statistics -->
      </div>
  </div>


  <div class="container">

      <div class="box small-boxb">
        <div class="box-label">Expense History </div>
        
            <!-- Expenses -->
            	
            <br>
            <tr>
              <th class="">Date</th>
              <th class="">Amount</th>
              <th class="">Payment Method</th>
              <th class="">Description</th>
              <br>
              <br>
              <?php
								$totalExpenses = 0;
								
								foreach ($expensesOfLoggedUser as $expenses) {
									
									echo "<tr class=\"summary\">
                  <!--<td class=\"category\">{$expenses['expense_category']}</td><td class=\"sum\"> {$expenses['expense_amount']} ₱</td>-->
									
                  <td class=\"category\">Category: {$expenses['expense_category']}</td>
									<br>
									</tr>";
                  //echo nl2br("=============");
									
									$totalExpenses += $expenses['expense_amount'];
									
									$expensesTableRowsQuery = $db -> prepare(
									"SELECT e.expense_date, e.expense_amount, pm.payment_method, e.expense_comment
									FROM expenses e NATURAL JOIN payment_methods pm
									WHERE e.category_id=:expenseCategoryId AND e.user_id=:loggedUserId AND e.expense_date BETWEEN :startDate AND :endDate
									ORDER BY e.expense_date ASC");
									$expensesTableRowsQuery -> execute([':loggedUserId' => $_SESSION['loggedUserId'], ':expenseCategoryId' => $expenses['category_id'], ':startDate'=> $startDate, ':endDate'=> $endDate]);
									
									$expensesOfSpecificCategory = $expensesTableRowsQuery -> fetchAll();
									
									foreach ($expensesOfSpecificCategory as $categoryExpense) {
										
										echo "<tr><td class=\"date\">{$categoryExpense['expense_date']}</td>| ₱ <td class=\"amount\">{$categoryExpense['expense_amount']} | </td><td class=\"payment\">{$categoryExpense['payment_method']}</td>| <td class=\"comment\">{$categoryExpense['expense_comment']}<br></td>	
										</tr>";
                    //echo nl2br("=============");
									}
                  echo'<br>';
								}
								
								//echo "<tr class=\"summary\"><td class=\"total\">TOTAL</td><td class=\"sum\">{$totalExpenses} ₱</td></tr>";
							?>
          
      </div>

      <div class="box small-boxb">
        <div class="box-label">Income History </div>
       
            <br>
            <tr>
              <th class="">Date</th>
              <th class="">Amount</th>
            <br>        
            <br>
            </tr>
            
            <?php
								$totalIncomes = 0;
								
								foreach ($incomesOfLoggedUser as $incomes) {
									
									echo "<tr class=\"summary\">
                  
                  <!-- <td class=\"category\">{$incomes['income_category']}</td><td class=\"sum\">{$incomes['income_amount']} ₱</td> -->
									
									<td class=\"category\">Category: {$incomes['income_category']}</td>
                  <br>
									</tr>";
									//echo nl2br("=============");
									
									$totalIncomes += $incomes['income_amount'];
									
									$incomesTableRowsQuery = $db -> prepare(
									"SELECT income_date, income_amount, income_comment
									FROM incomes
									WHERE category_id=:incomeCategoryId AND user_id=:loggedUserId AND income_date BETWEEN :startDate AND :endDate
									ORDER BY income_date ASC");
									$incomesTableRowsQuery -> execute([':loggedUserId' => $_SESSION['loggedUserId'], ':incomeCategoryId' => $incomes['category_id'], ':startDate'=> $startDate, ':endDate'=> $endDate]);
									
									$incomesOfSpecificCategory = $incomesTableRowsQuery -> fetchAll();
									
									foreach ($incomesOfSpecificCategory as $categoryIncome) {
										
										echo "<tr><td class=\"date\">{$categoryIncome['income_date']}</td>| ₱ <td class=\"amount\">{$categoryIncome['income_amount']} | </td><td class=\"comment\" >{$categoryIncome['income_comment']}<br> </td>
										</tr>";
                    
                    //echo nl2br("=============");
									}
                  echo'<br>';
								}
								
								//echo "<tr class=\"summary\"><td class=\"total\">TOTAL</td><td class=\"sum\">{$totalIncomes} ₱</td>
						
								
								//</tr>";
							?>
      </div>

    </div>


    <script src="js/budget.js"></script>
    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/jquery-3.4.1.min.js"></script>

</body>

</html>

<?php
            /*if($balance > 0) {
              
              echo '<div class="ml-3 text-success" id="result">Great!  You Manage Your Finances Very Well!</div>';
            }
            if ($balance < 0){
              
              echo '<div class="ml-3 text-danger" id="result">Watch Out! You Are Getting Into Debt!!</div>';
            }*/
          ?>
<?php
// Get expenses for the previous month
                $prevMonthStartDate = date('Y-m-d', strtotime('first day of previous month'));
                $prevMonthEndDate = date('Y-m-d', strtotime('last day of previous month'));
                $prevMonthExpenses = 0;
              
                $prevMonthExpensesQuery = $db->prepare("
                  SELECT SUM(expense_amount) AS total_expenses
                  FROM expenses
                  WHERE user_id = :loggedUserId
                    AND expense_date BETWEEN :prevStartDate AND :prevEndDate
                ");
                $prevMonthExpensesQuery->execute([
                  ':loggedUserId' => $_SESSION['loggedUserId'],
                  ':prevStartDate' => $prevMonthStartDate,
                  ':prevEndDate' => $prevMonthEndDate
                ]);
              
                $prevMonthExpensesResult = $prevMonthExpensesQuery->fetch(PDO::FETCH_ASSOC);
                if ($prevMonthExpensesResult && $prevMonthExpensesResult['total_expenses']) {
                  $prevMonthExpenses = $prevMonthExpensesResult['total_expenses'];
                }
              
                  // Calculate the percentage increase or decrease
                $percentageChange = 0;
                if ($prevMonthExpenses != 0) {
                  $percentageChange = (($totalExpenses - $prevMonthExpenses) / $prevMonthExpenses) * 100;
                }
  ?>