<?php
session_start();
$dbservername = 'localhost';
$dbname='test';
$dbusername='root';
$dbpassword='asdf1234';
try {
    $conn = new PDO("mysql:host=$dbservername; dbname=$dbname", $dbusername, $dbpassword);
    $conn->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
    $mprice=$_POST['maskprice'];
    $sname=$_SESSION['shopname'];

    $stmt2 = $conn->prepare("update shop set price=:price where shopname=:shopname");
    $stmt2->execute(array('shopname' =>$sname,'price'=>$mprice));

    header("Location: shop_reg.php");
}
catch (Exception $e) {
    $msg = $e->getMessage();
    echo <<< EOT
        <!DOCTYPE html>
        <html>
        <body>
        <script>
            alert("$msg");
            window.location.replace("shop_reg.php");
        </script>
        </body>
        </html>
    EOT;
}
?>