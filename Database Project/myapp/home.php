<?php
session_start();
$dbservername='localhost';
$dbname='test';
$dbusername='root';
$dbpassword='asdf1234';


$conn = new PDO("mysql:host=$dbservername; dbname=$dbname", $dbusername, $dbpassword);
$conn->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);

$uname=$_SESSION['Username'];
$sql="SELECT * from users where username='$uname'";
$result = $conn->query($sql);
$row=$result->fetch(PDO::FETCH_ASSOC);
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
    <a class="nav-link active" aria-current="page" href="home.php">Home</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" href="manager.php">Shop</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" href="my_order.php">My Order</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" href="shop_order.php">Shop Order</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" href="logout.php">Logout</a>
  </li>
  </ul>
<div class="content-group">
  <div class="content content1">
        <ul class="list-group list-group-flush">
          <h2><li class="list-group-item">Profile</li></h2>
            <li class="list-group-item">Account:
            <?php
            echo $row['username'];
            ?></li><br>
            <li class="list-group-item">Phone:
            <?php
            echo $row['phone'];
            ?></li>
          </ul>
          <br>
          <h2>Shop List</h2>
              <form action="" method="post">
                <label>shop&nbsp<input type="text" name="sname">
                </label><br>
                <label>city&nbsp
                    <select name="city">
                        <option>Taipei</opntion>
                        <option>Hsinchu</opntion>
                        <option>Taichung</opntion>
                        <option>Tainan</opntion>
                    </select>
                </label><br>
                <label>price&nbsp
                    <input type="number" name="pricemin">&nbsp~
                    <input type="number" name="pricemax">
                </label><br>
                <label>amount&nbsp
                    <select name="amount">
                        <option>all</opntion>
                        <option>售完(0)</opntion>
                        <option>稀少(1~99)</opntion>
                        <option>充足(100以上)</opntion>
                    </select>
                </label><br>
                <br>
                <div class="d-grid gap-2 d-md-block">
                <button name="search" class="btn btn-primary">search</button>

                <div class="custom-control custom-switch">
                  <input type="checkbox" class="custom-control-input" name="myshop" value="yes">
                  <label class="custom-control-label" for="myshop">Only show the shop I work at</label>
                </div>

              <br></form>
              
              <?php include'search.php'?>
              </div>
    </form>
  </body>
</html>
