<?php
session_start();
$dbservername = 'localhost';
$dbname='test';
$dbusername='root';
$dbpassword='asdf1234';
try {
    if ($_POST['amount']<=0) 
        throw new Exception('Order failed! Input should be a positive number!!');
    
    $conn = new PDO("mysql:host=$dbservername; dbname=$dbname", $dbusername, $dbpassword);
    $conn->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
    $sname=$_REQUEST['id'];
    $order_num=$_POST['amount'];
    $uname=$_SESSION['Username'];

    $stmt = $conn->prepare("select * from shop where shopname=:shopname");
    $stmt->execute(array('shopname' => $sname));
    $shop=$stmt->fetch();
    date_default_timezone_set('Asia/Taipei');
    $time=date("Y-m-d H:i:s");

    if ($shop['amount']>=$order_num) {
        $price=$shop['price'];
        $total=$price*$order_num;
        $stmt2 = $conn->prepare("insert into orders (status,start,orderer,order_num,order_price,total,shop_id) values (:status,:start,:orderer,:order_num,:order_price,:total,:shop_id)");
        $stmt2->execute(array(
            'status' => "Not Finished",
            'start'=>$time,
            'orderer' => $uname,
            'order_num'=>$order_num,
            'order_price'=>$price,
            'total' => $total,
            'shop_id'=>$shop['shop_id']
        ));
        $cur_num=$shop['amount']-$order_num;
        $stmt2 = $conn->prepare("update shop set amount='{$cur_num}' where shop_id=:shop_id");
        $stmt2->execute(array('shop_id'=>$shop['shop_id']));

        echo <<< EOT
            <!DOCTYPE html>
            <html>
            <body>
            <script>
                alert("Create an order successfully!");
                window.location.replace("home.php");
            </script>
            </body>
            </html>
        EOT;
        exit();
    }
    else throw new Exception("Failed to create new order. The shop doesn't have enough masks!!");
}
catch (Exception $e) {
    $msg = $e->getMessage();
    echo <<< EOT
        <!DOCTYPE html>
        <html>
        <body>
        <script>
            alert("$msg");
            window.location.replace("home.php");
        </script>
        </body>
        </html>
    EOT;
}
?>