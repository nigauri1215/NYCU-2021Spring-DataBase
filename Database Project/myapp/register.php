<?php
session_start();
$_SESSION['Authenticated'] = false;
$dbservername = 'localhost';
$dbname='test';
$dbusername='root';
$dbpassword='asdf1234';
try {
    if (!isset($_POST['uname']) || !isset($_POST['pwd']) || !isset($_POST['phone'])) {
        header("Location: index_reg.php");
        exit();
    }
    if (empty($_POST['uname']) || empty($_POST['pwd']) || empty($_POST['phone'])) 
        throw new Exception('Please input each box!!');
    $uname = $_POST['uname'];
    $pwd = $_POST['pwd'];
    $phone = $_POST['phone'];
    $conn = new PDO("mysql:host=$dbservername; dbname=$dbname", $dbusername, $dbpassword);
    $conn->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
    $stmt = $conn->prepare("select username from users where username=:username");
    $stmt->execute(array('username' => $uname));
    if ($stmt->rowCount() == 0) {
        $salt = strval(rand(1000, 9999));
        $hashvalue = hash('sha256', $salt . $pwd);
        $stmt = $conn->prepare("insert into users (username, password, salt, phone) values (:username, :password, :salt, :phone)");
        $stmt->execute(array(
            'username' => $uname,
            'password' => $hashvalue,
            'salt' => $salt,
            'phone' => $phone
        ));
        echo <<< EOT
            <!DOCTYPE html>
            <html>
            <body>
            <script>
                alert("Create an account successfully!:) Plz login.");
                window.location.replace("index.php");
            </script>
            </body>
            </html>
        EOT;
        exit();
    }
    else throw new Exception("Failed to create a new account.");
}
catch (Exception $e) {
    $msg = $e->getMessage();
    echo <<< EOT
        <!DOCTYPE html>
        <html>
        <body>
        <script>
            alert("$msg");
            window.location.replace("index_reg.php");
        </script>
        </body>
        </html>
    EOT;
}
?>