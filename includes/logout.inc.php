<?php 
    if(!isset($_SESSION)) session_start();
    unset($_SESSION['user_id']);
    unset($_SESSION['user_accounts']);
    unset($_SESSION['user_info']);
    echo "<script language='JavaScript' type='text/javascript'>
          	window.location.href='../login.php';
        </script>";
?>