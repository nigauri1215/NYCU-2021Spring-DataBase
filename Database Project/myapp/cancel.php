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
        //改訂單,finish time
        date_default_timezone_set('Asia/Taipei');
        $time=date("Y-m-d H:i:s");
        $stmt2 = $conn->prepare("update orders set status='Cancelled',end='{$time}',finisher='{$uname}' where order_id=:order_id");
        $stmt2->execute(array('order_id'=>$oid));
        //mask數量加回shop
        $stmt3 = $conn->prepare("update shop set amount=amount+{$myorder[1]} where shop_id=:shop_id");
        $stmt3->execute(array('shop_id'=>$myorder[0]));


        echo <<< EOT
        <!DOCTYPE html>
        <html>
        <body>
        <script>
            alert("Cancel your order successfully!");
            window.location.replace("my_order.php");
        </script>
        </body>
        </html>
        EOT;
        exit();
    }
    else throw new Exception('Fail to cancel an order!');
}
catch (Exception $e) {
    $msg = $e->getMessage();
    echo <<< EOT
        <!DOCTYPE html>
        <html>
        <body>
        <script>
            alert("$msg");
            window.location.replace("my_order.php");
        </script>
        </body>
        </html>
    EOT;
}

?>