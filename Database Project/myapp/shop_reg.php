<?php
session_start();
$dbservername='localhost';
$dbname='test';
$dbusername='root';
$dbpassword='asdf1234';

$conn = new PDO("mysql:host=$dbservername;dbname=$dbname",
$dbusername, $dbpassword);
$conn->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);

$uname=$_SESSION['Username'];
$stmt = $conn->prepare("select user_id from users where username=:username");
$stmt->execute(array('username' => $uname));
$uid=$stmt->fetch();

$sname=$_SESSION['shopname'];
$sql="SELECT * from shop where shopname='$sname'";
$result2 = $conn->query($sql);
$row2=$result2->fetch();
$shop_id=$row2['shop_id'];

$stmt2=$conn->prepare("select username,phone ,user_id from users where user_id in (select user_id from employees where shop_id=:shop_id)");
$stmt2->execute(array('shop_id' =>$shop_id));

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
    <a class="nav-link active" aria-current="page" href="shop_reg.php">Shop</a>
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
        <div>
            <h2>My Shop</h2>
            <b>shop</b>
            <?php 
            echo $row2['shopname'];
            ?><br>
            <b>city</b>
            <?php 
            echo $row2['city'];
            ?><br>
            <b>mask price</b>
            <form action="edit.php" method="post">
              <input type="number" name="maskprice" value="<?php echo $row2['price'] ?>">
              <input type="submit" class="btn btn-primary" value="edit">
            </form>
            <b>mask amount</b>
            
            <form action="edit2.php" method="post">
              <input type="number" name="maskamount" value="<?php echo $row2['amount'] ?>">
              <input type="submit" class="btn btn-primary" value="edit">
            </form>
            <br>
        </div>
        <div>
            <h2>Employee</h2>
            <form action="add.php" method="post">
            <input type="text" placeholder="type account" name="add">
            <input type="submit" class="btn btn-primary" value="add"><br>
            </form>
            <table>
                    <tr>
                        <th>Account</th>
                        <th>Phone</th>
                        <th></th>
                    </tr>
                    <?php
                    while($table=$stmt2->fetch()) 
                    { 
                      if($uid[0]!=$table[2]){
                      ?>
                        <tr><td><?php echo $table[0];?></td><td><?php echo $table[1]; ?></td><td>
                        <form action="delete.php?id=<?php echo $table[2] ?>" method="post">
                          <input type="submit" class="btn btn-danger" value="delete">
                        </form>
                        </td><tr>
                    <?php    
                      }
                    } 
                    ?>
                </table>
          </div>
    </form>
  
  </body>
</html>
