<?php
	session_start();

	$startDate = 00-00-00;
	$endDate = 00-00-00;

	if(isset($_SESSION['loggedUserId'])) {
		
		require_once 'database.php';

    $month = date('m');
		$year = date('Y');

		$startDate = date('Y-m-01', mktime(0, 0, 0, $month, 1, $year));

		$endDate = date('Y-m-t', mktime(0, 0, 0, $month, 1, $year));


		
		if(isset($_GET['userStartDate'])) {
			
			if($_GET['userStartDate'] > $_GET['userEndDate']) {
				
				$startDate = $_GET['userEndDate'];
				$endDate = $_GET['userStartDate'];
			} else {
				
				$startDate = $_GET['userStartDate'];
				$endDate = $_GET['userEndDate'];
			}

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

		header ("Location: login.php");
		exit();
	}
?>

<!DOCTYPE html>
<html>
<head>
	<title>Statistics-Budgeteer</title>
	  <link rel="stylesheet" type="text/css" href="summary1.css">
    
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

<!--Week, Month, Year Button -->

<div class="container">
      <div class="center-div" >
      
      
        <div class="date-buttons">
              <?php
                $today = date('Y-m-d');
                
                $userStartDate = date('Y-m-d', strtotime('last Sunday', strtotime($today)));
                $userEndDate = date('Y-m-d', strtotime('next Saturday', strtotime($userStartDate)));
              ?>
              <button class="weekButton" onclick="location.href='summary.php?userStartDate=<?php echo $userStartDate; ?>&userEndDate=<?php echo $userEndDate; ?>&period=week'">Week</button>

              <?php
                $userStartDate = date('Y-m-01');
                $userEndDate = date('Y-m-t');
              ?>
              <button class="monthButton" onclick="location.href='summary.php?userStartDate=<?php echo $userStartDate; ?>&userEndDate=<?php echo $userEndDate; ?>&period=month'">Month</button>
        
              <?php
                $userStartDate = date('Y-01-01');
                $userEndDate = date('Y-12-31');
              ?>
              <button class="yearButton" onclick="location.href='summary.php?userStartDate=<?php echo $userStartDate; ?>&userEndDate=<?php echo $userEndDate; ?>&period=year'">Year</button>
        </div>

        <script>
          function updateDateRange(startDate, endDate, timePeriod) {
            var url = 'summary.php?userStartDate=' + startDate + '&userEndDate=' + endDate + '&timePeriod=' + timePeriod;
            window.location.href = url;
          }
        </script>
        
        <div class="date-range">
            <?php
            echo "<span class='date' id ='result'>".date('M j, Y', strtotime($startDate))."</span>  -  <span class='date' id ='result'>".date('M j, Y', strtotime($endDate))."</span>";
            ?>
        </div>
        <div class="test">

            <!-- -->
            <form action="summary.php" method="GET" >
              <input class="date-range-input" type="date" name="userStartDate" required>
              <input class="date-range-input" type="date" name="userEndDate" required>
              <button type="submit" class="date-range-saveBtn">Save</button>
            </form>

            <?php
              $startDate = $_GET['userStartDate'] ?? $userStartDate;
              $endDate = $_GET['userEndDate'] ?? $userEndDate;
            ?>

            </div> 
  </div>
            
      </div>

  

  <!--Week, Month, Year Button -->

    <!--Custom Button -->

  
      
    <!--Custom Button -->

  <div class="container-upper">

  <div class="grossIncome">
        <div class="box-label">Gross Income </div>

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
                  
          echo'<div class="stat-amount"><center>₱ ';
          echo $totalIncomes;
          echo'</center></div>';

        ?>


  </div>

      <div class="totalExpense">
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
                        $transExpenses++;
                      }
                    }

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
                      $prevStartDate = date('Y-m-01', strtotime('-1 month', strtotime($userStartDate)));
                      $prevEndDate = date('Y-m-d', strtotime('last day of previous month', strtotime($userStartDate)));
                      $prevPeriodLabel = 'previous month';
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
                echo '<div class="stat-amount"><center>';
                echo "<tr class=\"summary\"><td class=\"total\">₱ </td><td class=\"sum\">{$totalExpenses}</td></tr>";
                echo '</center></div>';
                echo "<div class='change-per'><tr style='font-size:small;' class=\"summary\"><td class=\"indicator\"></td><td class=\"indicator\">{$percentageChange}% {$changeIndicator} from {$prevPeriodLabel}</td></tr></div>";
                  ?>
      </div>


        <div class="netSavings">
              <div class="box-label">Net Savings </div>
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
                    echo '<div class="stat-amount"><center>₱ '.$balance.'</center></div>';
                  ?>
        </div>

        <div class="taxes">
          <div class="box-label">Taxes </div>

          <?php
            $totalIncomes = 0;
            foreach ($incomesOfLoggedUser as $incomes) {
                            
              echo "<tr class=\"summary\">
                  
              <!-- <td class=\"category\">{$incomes['income_category']}</td><td class=\"sum\">{$incomes['income_amount']} ₱</td> -->
                                      
                                      
              </tr>";
              //echo nl2br("=============");

              $totalIncomes += $incomes['income_amount'];
            }

            
            $taxRate = 0;
            $taxValue = 0;

            if ($totalIncomes > 0 && $totalIncomes <= 250000) {
                $taxRate = 0;
            } elseif ($totalIncomes > 250000 && $totalIncomes <= 400000) {
                $taxRate = 0.15;
            } elseif ($totalIncomes > 400000 && $totalIncomes <= 800000) {
                $taxRate = 0.2;
            } elseif ($totalIncomes > 800000 && $totalIncomes <= 2000000) {
                $taxRate = 0.25;
            } elseif ($totalIncomes > 2000000 && $totalIncomes <= 8000000) {
                $taxRate = 0.3;
            } elseif ($totalIncomes > 8000000) {
                $taxRate = 0.35;
            }
            $taxValue = $totalIncomes * $taxRate;
            echo'<div class="stat-amount"><center>₱';
            echo $taxValue;
            echo'</center></div>';

          ?>
          
        </div>

      
        
  </div>


  <div class="container-chart">


          
            
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
            
          
          
          <?php
            if(!empty($incomesOfLoggedUser)) {
              
              echo '<div class="incomeChart"><div id="piechart1"></div></div>';
            }
          
            if(!empty($expensesOfLoggedUser)) {
              
              echo '<div class="expenseChart"><div id="piechart2"></div></div>';
            }
          ?>

          <!-- End of Statistics -->
 

      
  </div>


  <div class="container-lower">

      <div class="exp-to-inc">
        <div class="box-label">Expense to Income Ratio </div>

        <?php
          $eiRatio = 0;
          if ($totalExpenses != 0) {
            $eiRatio = round(($totalExpenses  / $totalIncomes) * 100);
          }
          echo'<div class="ratio"><center>';
          echo $eiRatio.'%';
          echo'</center></div>';

        ?>
        
      </div>

      <div class="savings-to-exp">
        <div class="box-label">Savings to Expense Ratio </div>

        <?php
          $siRatio = 0;
          if ($totalExpenses != 0) {
            $siRatio = round(($totalIncomes / $totalExpenses) * 100);
          }
          echo'<div class="ratio"><center>';
          echo $siRatio.'%';
          echo'</center></div>';

        ?>
        
      </div>

      <div class="exp-history">
        <div class="box-label">Expense History </div>
        
            <!-- Expenses -->
            	
            <br>
            
              <?php
								$totalExpenses = 0;
								
								foreach ($expensesOfLoggedUser as $expenses) {
									
									
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
										
										echo "<div class='exp'>
                    <tr>
                      
                      <div class='expAmount'> ₱ 
                        <td class=\"amount\" style='color: red;'>{$categoryExpense['expense_amount']}  </td> 
                        </div>
                      <div class='expPayment'>
                        <td class=\"payment\">{$categoryExpense['payment_method']}</td> 
                        </div> 
                      <div class='expCategory'>
                        <td class=\"date\">{$expenses['expense_category']}</td>
                        </div> 
                      <div class='expDate'>
                        <td class=\"date\">{$categoryExpense['expense_date']}</td>
                      </div>
                      <div class='expComment'> 
                        <td class=\"comment\">{$categoryExpense['expense_comment']}<br></td>	
                        </div>  
                      
              </tr> </div>";
                    //echo nl2br("=============");
									}
                  echo'<br>';
								}
								
								//echo "<tr class=\"summary\"><td class=\"total\">TOTAL</td><td class=\"sum\">{$totalExpenses} ₱</td></tr>";
							?>
          
      </div>

      <div class="inc-history">
        <div class="box-label">Income History </div>
       
            <br>
            
            
            <?php
								$totalIncomes = 0;
								
								foreach ($incomesOfLoggedUser as $incomes) {
									
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
										
										echo "<div class='inc'>
                    <tr>
                    
                    <div class='incAmount'>₱ 
                      <td class=\"amount\">{$categoryIncome['income_amount']}  </td>
                      </div>
                      <div class='incCategory'>
                      <td class=\"comment\">{$incomes['income_category']}<br></td>
                      </div>
                    <div class='incDate'>
                      <td class=\"date\">{$categoryIncome['income_date']}</td> 
                      </div>
                    
                    <div class='incComment'>
                      <td class=\"comment\">{$categoryIncome['income_comment']}<br></td>
                      </div>
										</tr></div>";
                    
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

<!-- 
<div class="box small-box">
        <div class="box-label">Total Transactions (expenses) </div>
        <?php
        //$totalTrans=$transIncomes + $transExpenses;
        //echo"<center> $totalTrans </center>";
        echo"<center> $transExpenses </center>";
        ?>

    </div>
  <div class="box small-box">
      <div class="box-label">Total Entries (incomes)</div>
        <?php
        //$totalTrans=$transIncomes + $transExpenses;
        //echo"<center> $totalTrans </center>";
        echo"<center> $transIncomes </center>";
        ?>

  </div>

-->

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