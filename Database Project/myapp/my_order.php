<?php
session_start();
$dbservername='localhost';
$dbname='test';
$dbusername='root';
$dbpassword='asdf1234';


$conn = new PDO("mysql:host=$dbservername; dbname=$dbname", $dbusername, $dbpassword);
$conn->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);

$uname=$_SESSION['Username'];
$stmt=$conn->prepare("select * from orders where orderer=:orderer");
$stmt->execute(array('orderer' =>$uname));
?>

<!DOCTYPE html>
<html>
<head>
  <link rel="stylesheet" href="bootstrap.css">
  <style>
table {
  font-family: arial, sans-serif;
  border-collapse: collapse;
  width: 100%;
}
</style>
</head>
  <body>
  <ul class="nav nav-tabs">
  <li class="nav-item">
    <a class="nav-link" href="home.php">Home</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" href="manager.php">Shop</a>
  </li>
  <li class="nav-item">
    <a class="nav-link active" aria-current="page" href="my_order.php">My Order</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" href="shop_order.php">Shop Order</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" href="logout.php">Logout</a>
  </li>
  </ul>
    
  </body>
</html>

<div class="d-grid gap-2 d-md-block">
        <h2>Order List</h2>
            <form action="" method="post">
                <label>Status&nbsp
                    <select name="status">
                        <option>All</opntion>
                        <option>Not finished</opntion>
                        <option>Finished</opntion>
                        <option>Cancelled</opntion>
                    </select>
                </label>
                <br>
                <br>
                <div class="custom-control custom-switch">
                  <button name="search_order" class="btn btn-primary">search</button>
                  <br>
                </div>

            <br></form>
              
              <?php include'search_order.php'?>
    </div>