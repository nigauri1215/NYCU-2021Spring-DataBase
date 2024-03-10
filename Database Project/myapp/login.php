<?php
session_start();
$_SESSION['Authenticated'] = false;
$dbservername = 'localhost';
$dbname='test';
$dbusername='root';
$dbpassword='asdf1234';
try {
    if (!isset($_POST['uname']) || !isset($_POST['pwd'])) {
        header("Location: index.php");
        exit();
    }
    if (empty($_POST['uname']) || empty($_POST['pwd'])) throw new Exception('Please input user name and password.');
    $uname = $_POST['uname'];
    $pwd = $_POST['pwd'];
    $conn = new PDO("mysql:host=$dbservername; dbname=$dbname", $dbusername, $dbpassword);
    # set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
    $stmt = $conn->prepare("select username, password, salt from users where username=:username");
    $stmt->execute(array('username' => $uname));
    if ($stmt->rowCount() == 1) {
        $row = $stmt->fetch();
        if ($row['password'] == hash('sha256', $row['salt'] . $_POST['pwd'])) {
            $_SESSION['Authenticated'] = true;
            $_SESSION['Username'] = $row[0];
            header("Location: home.php?page=1");
            exit();
        }
        else throw new Exception('Login failed,password is wrong. <:(');
    }
    else throw new Exception('Login failed,so such user. >:(');
}
catch (Exception $e) {
    $msg = $e->getMessage();
    session_unset();
    session_destroy();
    echo <<< EOT
        <!DOCTYPE html>
        <html>
        <body>
        <script>
            alert("$msg");
            window.location.replace("index.php");
        </script>
        </body>
        </html>
    EOT;
}
?>