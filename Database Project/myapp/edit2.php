<?php
session_start();
$dbservername = 'localhost';
$dbname='test';
$dbusername='root';
$dbpassword='asdf1234';
try {
    $conn = new PDO("mysql:host=$dbservername; dbname=$dbname", $dbusername, $dbpassword);
    $conn->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
    $mamount=$_POST['maskamount'];
    $sname=$_SESSION['shopname'];

    $stmt2 = $conn->prepare("update shop set amount=:amount where shopname=:shopname");
    $stmt2->execute(array('shopname' =>$sname,'amount'=>$mamount));

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