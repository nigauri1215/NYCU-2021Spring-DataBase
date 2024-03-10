<?php
session_start();
# remove all session variables
session_unset(); 
# destroy the session 
session_destroy();
$_SESSION['Authenticated']=false;
$_SESSION['manager']=false;
?>
<!DOCTYPE html>
            <html>
            <body>
            <script>
                alert("You're loging out.");
                window.location.replace("index.php");
            </script>
</body>
</html>