<?php
    require_once('connection.inc.php');
    $conn = dbConnect('read');
    $user=$_POST['user'];  
    $password=$_POST['password'];
    $codeNum=$_POST['codeNum'];
    if($user == ""){
        echo"<script type='text/javascript'>
                alert('请输入用户名');
                back();
            </script>";
        return;
    }  
    if($password == ""){
        echo"<script type='text/javascript'>
                alert('请输入密码');
                back();
            </script>";
        return;
    }
    
    if($codeNum != $_SESSION["codeNum"]){
        echo"<script type='text/javascript'>
                alert('验证码错误');
                back();
            </script>";
        return;
    }  
    switch(checkUser($conn,$user,$password)){
        case 1:
            $_SESSION['user_accounts']=$user;
            if($user!='system') getInfo($conn,$user);
            else getAll($conn);
            echo"<script type='text/javascript'>
                    alert('登录成功');
  	                next('welcome.php');
                </script>";
            break;
        case 0:
            echo"<script type='text/javascript'>
                    alert('密码错误');
                    back();
                </script>";
            break;
        case -1: 
            echo"<script type='text/javascript'>
                    alert('找不到帐号');
                    back();
                </script>";
            break;
    }
?>  