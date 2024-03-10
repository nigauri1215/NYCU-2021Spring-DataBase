<?php
session_start();
$_SESSION['manager'] = false;
$dbservername = 'localhost';
$dbname='test';
$dbusername='root';
$dbpassword='asdf1234';
try {
    if (!isset($_POST['sname']) || !isset($_POST['price']) || !isset($_POST['amount'])) {
        header("Location: shop_unreg.php");
        exit();
    }
    if (empty($_POST['sname']) || empty($_POST['price'])) 
        throw new Exception('Please input each box!!');
    $sname = $_POST['sname'];
    $city=$_POST['city'];
    $price = $_POST['price'];
    $amount = $_POST['amount'];
    $conn = new PDO("mysql:host=$dbservername; dbname=$dbname", $dbusername, $dbpassword);
    $conn->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
    $stmt = $conn->prepare("select * from shop where shopname=:shopname");
    $stmt->execute(array('shopname' => $sname));
    $shop=$stmt->fetch();

    $uname=$_SESSION['Username'];
    $stmt2 = $conn->prepare("select * from users where username=:username");
    $stmt2->execute(array('username' =>$uname));
    $user=$stmt2->fetch();

    
    if ($stmt->rowCount() == 0) {
        $stmt = $conn->prepare("insert into shop (shopname ,city, price, amount,user_id) values (:shopname, :city, :price, :amount,:user_id)");
        $stmt->execute(array(
            'shopname' => $sname,
            'city' => $city,
            'price' => $price,
            'amount' => $amount,
            'user_id'=>$user['user_id']
        ));
        $_SESSION['manager']=true;
        $_SESSION['shopname']=$sname;
        $stmt2 = $conn->prepare("select shop_id from shop where user_id=:user_id");
        $stmt2->execute(array('user_id' =>$user['user_id']));
        $sid=$stmt2->fetch();
        
        $stmt2 = $conn->prepare("insert into employees (user_id ,shop_id) values (:user_id, :shop_id)");
        $stmt2->execute(array(
            'user_id'=>$user['user_id'],
            'shop_id'=>$sid[0]
        ));
        echo <<< EOT
            <!DOCTYPE html>
            <html>
            <body>
            <script>
                alert("Register shop successfully!");
                window.location.replace("shop_reg.php");
            </script>
            </body>
            </html>
        EOT;
        exit();
    }
    else throw new Exception("Failed to create a new shop.");
}
catch (Exception $e) {
    $msg = $e->getMessage();
    echo <<< EOT
        <!DOCTYPE html>
        <html>
        <body>
        <script>
            alert("$msg");
            window.location.replace("shop_unreg.php");
        </script>
        </body>
        </html>
    EOT;
}
?>