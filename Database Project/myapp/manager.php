<?php
session_start();
$_SESSION['manager']=false;
$dbservername = 'localhost';
$dbname='test';
$dbusername='root';
$dbpassword='asdf1234';

$conn = new PDO("mysql:host=$dbservername;dbname=$dbname",$dbusername, $dbpassword);
$conn->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);

$uname=$_SESSION['Username'];
$sql="SELECT * FROM users WHERE username='$uname'";
$getuser=$conn->query($sql);
$row=$getuser->fetch();
$id=$row['user_id'];

$sql2="SELECT * FROM shop WHERE user_id='$id'";
$result2 = $conn->query($sql2);
$row2=$result2->fetch();

if(!isset($row2['user_id'])){
    header("Location: shop_unreg.php");
    exit();
}
else{
    $_SESSION['manager']=true;
    $_SESSION['shopname']=$row2['shopname'];
    header("Location: shop_reg.php");
    exit();
}

?>