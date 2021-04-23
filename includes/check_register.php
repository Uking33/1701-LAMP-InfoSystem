<?php
    require_once('connection.inc.php');
    $user=$_POST['user'];  
    $password1=$_POST['password1'];
    $password2=$_POST['password2'];
    $codeNum=$_POST['codeNum'];
    
    function onBack(){
        echo"<script type='text/javascript'>
                back();
            </script>";
    }
    
    if($user == ""){
        echo"<script type='text/javascript'>
                alert('请输入用户名');
            </script>";
        onBack();
        return;
    }  
    if($password1 == "" || $password2 == ""){
        echo"<script type='text/javascript'>
                alert('请输入密码');
            </script>";
        onBack();
        return;
    }
    if($password1 != $password2){
        echo"<script type='text/javascript'>
                alert('密码不一致');
            </script>"; 
        onBack();
        return;
    }
    if($codeNum != $_SESSION["codeNum"]){
        echo"<script type='text/javascript'>
                alert('验证码错误');
            </script>";
        onBack();
        return;
    }
    $conn = dbConnect('read');
    if(findUser($conn, $user, $password1)){
        echo"<script type='text/javascript'>
                alert('帐号已存在');
            </script>";
        onBack();
    }
    else{
        $conn = dbConnect('write');
        registerUser($conn, $user, $password1);
        echo"<script type='text/javascript'>
                alert('注册成功');
            </script>";
        onBack();
        onBack();
    }
?>  