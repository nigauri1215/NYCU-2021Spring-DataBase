<?php
session_start();
$dbservername='localhost';
$dbname='test';
$dbusername='root';
$dbpassword='asdf1234';

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
<script src="check.js"></script>
  <body>
  <ul class="nav nav-tabs">
    <li class="nav-item">
        <a class="nav-link" href="home.php">Home</a>
    </li>
    <li class="nav-item">
        <a class="nav-link active" aria-current="page" href="shop_unreg.php">Shop</a>
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
  <div class="content content2">
        <h2>Register Shop</h2>
        <p>註冊</p>
        <form action="register_shop.php" method="post">
            shop
            <input type="text" name="sname" ><br>
            city
            <select name="city">
                    <option>Taipei</opntion>
                    <option>Hsinchu</opntion>
                    <option>Taichung</opntion>
                    <option>Tainan</opntion>
            </select><br>
            mask price
            <input type="number" name="price" oninput="check_price(this.value);"><label id="msg2"></label><br>
            mask amount
            <input type="number" name="amount" oninput="check_amount(this.value);"><label id="msg3"></label><br>
            <input class="btn btn-primary" type="submit" value="register">
            </div>
        </form>
    </div>

  
  </body>
</html>
