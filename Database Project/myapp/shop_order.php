<?php
session_start();
$dbservername='localhost';
$dbname='test';
$dbusername='root';
$dbpassword='asdf1234';


$conn = new PDO("mysql:host=$dbservername; dbname=$dbname", $dbusername, $dbpassword);
$conn->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);

$uname=$_SESSION['Username'];

$stmt2=$conn->prepare("select shop_id from users join employees on users.user_id=employees.user_id where username=:username");
$stmt2->execute(array('username' =>$uname));
$sid=$stmt2->fetch();

if(!isset($sid[0])){
    echo <<< EOT
            <!DOCTYPE html>
            <html>
            <body>
            <script>
                alert("You are not an employee!");
                window.location.replace("home.php");
            </script>
            </body>
            </html>
        EOT;
        exit();
}
$stmt=$conn->prepare("select user_id from users where username=:username");
$stmt->execute(array('username' =>$uname));
$uid=$stmt->fetch();

$stmt3="SELECT shopname from shop join employees on shop.shop_id=employees.shop_id where employees.user_id='{$uid[0]}'";
$result=$conn->query($stmt3);
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
    <a class="nav-link" href="my_order.php">My Order</a>
  </li>
  <li class="nav-item">
    <a class="nav-link active"  aria-current="page" href="shop_order.php">Shop Order</a>
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
                <label>Shop&nbsp
                    <select name="shopname">
                    <option>All</option>
                    <?php while($shop=$result->fetch())
                    {
                       echo '<option>',$shop[0],'</opntion>';
                    }
                    ?>
                    </select>
                </label>
                <br>
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
              
              <?php include'search_shop_order.php'?>
</div>