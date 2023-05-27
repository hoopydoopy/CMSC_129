<?php
	session_start();
		
	if(isset($_SESSION['loggedUserId'])) {
		
		
	} else{
        header('Location: login.php');
		exit();
    }
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Calendar</title>
        <script src="js/budget.js"></script>
	  <link rel="stylesheet" type="text/css" href="homestyle.css">
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.1.0/fullcalendar.min.css">
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.2.7/semantic.min.css">
      <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.2.7/semantic.min.js"></script>
      <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
      <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.17.1/moment.min.js"></script>
      <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.1.0/fullcalendar.min.js"></script>

</head>
<body>

<div class="navbar">
			<div class="profile">
				<img src="images/profile picture.png" alt="Profile Picture">
				<p>John Doe</p>
			</div>
			<ul>
				<li><a href="#">Profile</a></li>
				<li>
                    <!--<a href="home.php" class="active">Home</a>-->
                    <?php
                    $userStartDate = date('Y-m-d');
                    $userEndDate = date('Y-m-d');
                            
                    echo '<a href="home.php?userStartDate='.$userStartDate.'&userEndDate='.$userEndDate.'">Home</a>';
                    ?>
                </li>
				<li><a href="budget.php">Budget</a></li>
				<li><a href="expense.php">Expense</a></li>
				<li>
					<!--<a href="balance.php">Statistics</a>-->
					<?php
					$userStartDate = date('Y-m-01');
					$userEndDate = date('Y-m-t');
							
					echo '<a class="dropdown-item" href="summary.php?userStartDate='.$userStartDate.'&userEndDate='.$userEndDate.'&period=month">Statistics</a>';
					?>
				</li>
				<li><a href="#">Notes</a></li>
				<li><a href="calendar.php" class="active">Calendar</a></li>
				<li><a href="settings.php">Settings</a></li>
				<li><a href="logout.php">Log Out</a></li>
			</ul>
			<div class="logo">
				<img src="images/Logo3.png" alt="Logo">
			</div>
		</div>

        
        <div class="ui container">

        <div class="ui container">
        <div class="ui grid">
        <div class="ui sixteen column">
            <div id="calendar"></div>
        </div>
        </div>
        </div>

        </div>

        <script>
            $(document).ready(function() {

        $('#calendar').fullCalendar({
        header: {
            left: 'prev,next',
            center: 'title',
            //right: 'month,basicWeek,basicDay'
            right:''
        },
        //defaultDate: '2022-12-12',
        //defaultDate: 'getDate()',
        //navLinks: true, // can click day/week names to navigate views
        //editable: true,
        //eventLimit: true, // allow "more" link when too many events
        });

        });
        </script>

</body>

</html>