<?php
session_start();
$dbservername = 'localhost';
$dbname='test';
$dbusername='root';
$dbpassword='asdf1234';
try {
    $conn = new PDO("mysql:host=$dbservername; dbname=$dbname", $dbusername, $dbpassword);
    $conn->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
    $uid=$_REQUEST['id'];
    
    $stmt2 = $conn->prepare("delete from employees where user_id=:user_id");
    $stmt2->execute(array('user_id'=>$uid));
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