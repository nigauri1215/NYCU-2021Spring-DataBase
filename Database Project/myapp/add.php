<?php
session_start();
$_SESSION['Authenticated'] = false;
$_SESSION['manager']=false;
$dbservername = 'localhost';
$dbname='test';
$dbusername='root';
$dbpassword='asdf1234';

if (empty($_POST['add'])) {
    echo <<< EOT
        <!DOCTYPE html>
        <html>
        <body>
        <script>
            alert("不能是空值!");
            window.location.replace("shop_reg.php");
        </script>
        </body>
        </html>
    EOT;
}

$conn = new PDO("mysql:host=$dbservername;dbname=$dbname",$dbusername, $dbpassword);
$conn->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);

//目前使用者
$uname=$_SESSION['Username'];


//目前店面
$sname=$_SESSION['shopname'];
$sql="SELECT * FROM shop WHERE shopname='$sname'";
$stmtsp = $conn->query($sql);
$shop=$stmtsp->fetch();

//想add的員工
$ename=$_POST['add'];
$stmt = $conn->prepare("select username,user_id from users where username=:username");
$stmt->execute(array('username' => $ename));
$add=$stmt->fetch();
//目前員工列表
$sql3="SELECT * FROM employees";
$stmte = $conn->query($sql3);

if($stmt->rowCount()==0){
    echo <<< EOT
    <!DOCTYPE html>
    <html>
    <body>
    <script>
        alert("員工不存在>:(");
        window.location.replace("shop_reg.php");
    </script>
    </body>
    </html>
    EOT;
    exit();
}
if($add['username']==$uname){
    echo <<< EOT
        <!DOCTYPE html>
        <html>
        <body>
        <script>
            alert("不能加自己^^...");
            window.location.replace("shop_reg.php");
        </script>
        </body>
        </html>
    EOT;
    exit();
}
else{
    while($emplo=$stmte->fetch()){
        if($emplo['user_id']==$add['user_id'] && $emplo['shop_id']==$shop['shop_id']){
                echo <<< EOT
                <!DOCTYPE html>
                <html>
                <body>
                <script>
                    alert("員工已經在這間店工作了!");
                    window.location.replace("shop_reg.php");
                </script>
                </body>
                </html>
            EOT;
            exit();
        }
    }
    if($exist="false"){
        $stmt = $conn->prepare("insert into employees (user_id,shop_id) values (:user_id,:shop_id)");
        $stmt->execute(array(
            'user_id' => $add['user_id'],
            'shop_id' => $shop['shop_id']
        ));
        echo <<< EOT
                <!DOCTYPE html>
                <html>
                <body>
                <script>
                    alert("成功雇用!:D");
                    window.location.replace("shop_reg.php");
                </script>
                </body>
                </html>
            EOT;

    }

}



?>