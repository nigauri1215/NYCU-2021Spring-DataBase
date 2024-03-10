<?php
$dbservername = 'localhost';
$dbname='test';
$dbusername='root';
$dbpassword='asdf1234';
try {
    if (!isset($_REQUEST['uname']) || empty($_REQUEST['uname']))
    {
        echo 'FAILED';
        exit();
    }
    $uname=$_REQUEST['uname'];
    $conn = new PDO("mysql:host=$dbservername;dbname=$dbname", 
    $dbusername, $dbpassword);
    # set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $stmt=$conn->prepare("select username from users where username=:username");
    $stmt->execute(array('username' => $uname));
    if ($stmt->rowCount()==0){
        echo 'YES';
    }
    else {
        echo 'NO';
    }
}
catch(Exception $e)
{ 
    echo 'FAILED2';
}
?>