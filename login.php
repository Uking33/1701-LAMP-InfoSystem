<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">  
<html>
<head>
    <?php header("Content-Type: text/html; charset=UTF-8");?>
    <title>用户系统--登录 </title> 
    
    <link href="./css/bootstrap.css" rel="stylesheet">
    <link href="./css/signin.css" rel="stylesheet">
    <script language="JavaScript" type="text/javascript">
        function back(){
        	window.history.back();
        }
        function next(url){
          	window.location.href=url;
        }
    	function ReCodeNum(){
    		setTimeout(function(){document.getElementById('codePic').setAttribute('src','./includes/code_num.php');},500);
        }
        function ReCodeNumFast(){
			document.getElementById('codePic').attributes['src'].value="./includes/code_num.php";
    	}
    </script>
    <?php
        if (isset($_POST['loginBtn'])) {
            require ('./includes/check_login.php');
        }
    ?>
</head>  
<body>
<div class="container">
    <form id="loginForm" class="form-signin" method="post" action="">
        <div id="content">
                <h2 class="form-signin-heading">请登录</h2>
                <label for="inputEmail" class="sr-only">用户名:</label>
                <input name="user" id="user" type="text"  class="form-control"  placeholder="用户名" value="<?php if(isset($user)) echo $user;?>">
                <br>
                <label for="password" class="sr-only">密码:</label>
                <input name="password" id="password" type="password" class="form-control" placeholder="密码">
           <br> 
                <label for="codeNum" class="sr-only">验证码</label>
                <input type="text" id="codeNum" name="codeNum" class="form-controlyanzheng" maxlength="4" height='30px'  placeholder="验证码"/>
                <img  width="100px" src="./includes/code_num.php" id="codePic" name="codePic" title="点击更换验证码" align="absmiddle" onclick='ReCodeNum()'/>
                <br><br>
        </div>
            <input name="loginBtn" type="submit" class="btn btn-lg btn-primary btn-block" value="登录">
            <br>
            <a id="zhuce" href="./register.php">注册</a>
    </form>
    </div>
</body>  
</html>