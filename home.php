<!DOCTYPE html>
<html>
<head>
	<title>Homepage</title>
	<link rel="stylesheet" type="text/css" href="homestyle.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css">
</head>
<body>
	<div class="navbar">
		<div class="profile">
			<img src="images/profile picture.png" alt="Profile Picture">
			<p>John Doe</p>
		</div>
		<ul>
			<li><a href="#">Profile</a></li>
			<li><a href="#" class="active">Home</a></li>
			<li><a href="#">Budget</a></li>
			<li><a href="#">Statistics</a></li>
      <li><a href="#">Notes</a></li>
      <li><a href="#">Calendar</a></li>
      <li><a href="#">Settings</a></li>
		</ul>
        <div class="logo">
            <img src="images/Logo3.png" alt="Logo">
          </div>
	</div>
    <div class="container">
        <div class="calendar-slider">
          <div class="slider-nav">
            <i class="fas fa-chevron-left"></i>
               <span class="date">April 2023</span>
            <i class="fas fa-chevron-right"></i>
          </div>
        </div>
        <div class="box big-box">
          <div class="box-label">Overview</div>
          <?php
          $categories = array("Food", "Entertainment", "Transportation", "Utilities", "Shopping");
          ?>
          
          <div class="category-list">
              <h3>Categories</h3>
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
          </div>
          <!-- Content for big box here -->
        </div>
        <div class="box small-box">
          <div class="box-label">Transactions</div>
          <!-- Content for top small box here -->
        </div>

      </div>
</body>
</html>
