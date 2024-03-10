<?php
session_start();
$_SESSION['Authenticated']=false;
?>
<!DOCTYPE html>
<html>
<head>
  <link rel="stylesheet" href="bootstrap.css">
</head>
  <body>
  <ul class="nav nav-tabs">
  <li class="nav-item">
    <a class="nav-link active" aria-current="page" href="index.php">Login</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" href="index_reg.php">Register</a>
  </li>
</ul>
  <h1>Login</h1>
    <form action="login.php" method="post">
      User Name:
      <input type="text" name="uname"><br>
      Password:
      <input type="password" name="pwd"><br>
      <input type="submit" value="Login">
    </form>
  
  </body>
</html>