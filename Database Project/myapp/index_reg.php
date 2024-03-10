<?php
session_start();
$_SESSION['Authenticated']=false;
$_SESSION['shopreg']=false;
?>
<!DOCTYPE html>
<html>
<head>
  <link rel="stylesheet" href="bootstrap.css">
</head>
  <script src="check.js"></script>

  <body>
  <ul class="nav nav-tabs">
  <li class="nav-item">
  <a class="nav-link" href="index.php">Login</a>
  </li>
  <li class="nav-item">
    <a class="nav-link active" aria-current="page" href="index_reg.php">Register</a>
  </li>
</ul>
  <h1>Create Account</h1>
    <form action="register.php" name="form1" method="post">
      User Name:
      <input type="text" name="uname" oninput="check_name(this.value);"><label id="msg"></label><br>
      Password:
      <input type="password" name="pwd" oninput="check_pwd(this.value);"><label id="msg2"></label><br>
      Comfirm Password:
      <input type="password" name="compwd" oninput="com_pwd();"><label id="msg3"></label><br>
      Phone number:
      <input type="text" name="phone" oninput="check_phone(this.value);"><label id="msg4"></label><br>
      <input type="submit" value="Create Account">
    </form>
  </body>
</html>