<?php
session_start();

if (isset($_SESSION['loggedUserId'])) {
    require_once 'database.php';

} else {
    header("Location: landing.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>MyBudget - Your Personal Finance Manager</title>
    <meta name="description" content="Track your income and expenses - avoid overspending!">
    <meta name="keywords"
          content="expense manager, budget planner, expense tracker, budgeting app, money manager, money management, personal finance management software, finance manager, saving planner">
    <meta name="author" content="Magdalena Słomiany">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-Ua-Compatible" content="IE=edge">
    <link rel="stylesheet" href="homestyle.css">
    <link href="https://fonts.googleapis.com/css2?family=Baloo+Paaji+2:wght@400;500;700&family=Fredoka+One&family=Roboto:wght@400;700;900&family=Varela+Round&display=swap" rel="stylesheet">
</head>
<body>
<div class="navbar">
    <div class="profile">
        <img src="images/profile picture.png" alt="Profile Picture">
        <p>John Doe</p>
    </div>
    <ul>
        <li><a href="#">Profile</a></li>
        <li><a href="home.php">Home</a></li>
        <li><a href="budget.php">Budget</a></li>
        <li><a href="expense.php">Expense</a></li>
        <li>
            <?php
            $userStartDate = date('Y-m-01');
            $userEndDate = date('Y-m-t');
            echo '<a class="dropdown-item" href="balance.php?userStartDate=' . $userStartDate . '&userEndDate=' . $userEndDate . '">Statistics</a>';
            ?>
        </li>
        <li><a href="#">Notes</a></li>
        <li><a href="#">Calendar</a></li>
        <li><a href="settings.php" class="active">Settings</a></li>
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

$conn = new mysqli($servername, $username, $password, $dbname);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$user_id = $_SESSION['loggedUserId'];
$income_id = $_GET['income_id'];

$sql = "SELECT * FROM incomes WHERE user_id = '$user_id'";
$result = mysqli_query($conn, $sql);

if (isset($_GET['income_id'])){
    $id = $_GET['income_id'];

    $sql = "DELETE FROM incomes WHERE income_id = $id";
    $result = mysqli_query($conn, $sql);
    if ($result){
        echo "Income deleted Successfully!";
        header('location: summary.php');
    }
    else{
        die(mysqli_error($conn));
    }
}
?>

<form method="post">
    <label for="name">Expense Amount:</label>
    <input type="text" name="income_amount" value="<?php echo $current_name; ?>"><br>

    <form method="post">
    <label for="name">Expense Amount:</label>
    <input type="text" name="income_amount" value="<?php echo $current_name; ?>"><br>
    <input type="submit" value="Save Changes">
    </form>
</body>
</html>
