<?php
session_start();
$dbservername = 'localhost';
$dbname='test';
$dbusername='root';
$dbpassword='asdf1234';
try{
    $conn = new PDO("mysql:host=$dbservername; dbname=$dbname", $dbusername, $dbpassword);
    $conn->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);

    $oid=$_REQUEST['id'];
    $uname=$_SESSION['Username'];

    $stmt=$conn->prepare("select shop_id,order_num,status from orders where order_id=:order_id");
    $stmt->execute(array('order_id'=>$oid));
    $myorder = $stmt->fetch();

    if($myorder[2]=='Not Finished'){
        //改訂單
        date_default_timezone_set('Asia/Taipei');
        $time=date("Y-m-d H:i:s");
        $stmt2 = $conn->prepare("update orders set status='Finished',end='{$time}',finisher='{$uname}' where order_id=:order_id");
        $stmt2->execute(array('order_id'=>$oid));

        echo <<< EOT
        <!DOCTYPE html>
        <html>
        <body>
        <script>
            alert("The order is done!");
            window.location.replace("my_order.php");
        </script>
        </body>
        </html>
        EOT;
        exit();
    }
    else throw new Exception('Fail to finish an order!:(');

} 
catch (Exception $e) {
    $msg = $e->getMessage();
    echo <<< EOT
        <!DOCTYPE html>
        <html>
        <body>
        <script>
            alert("Fail to finish an order!");
            window.location.replace("my_order.php");
        </script>
        </body>
        </html>
    EOT;
}
?>