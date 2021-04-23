<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">  
<html>  
<head>
    <?php header("Content-Type: text/html; charset=UTF-8");?>
    <title>用户注册</title>

    <link href="./css/bootstrap.css" rel="stylesheet">
<link href="./css/signin.css" rel="stylesheet">
    <script language="JavaScript" type="text/javascript">
        function back(){
        	window.history.back();
        }
    	function ReCodeNum(){
    		setTimeout(function(){document.getElementById('codePic').setAttribute('src','./includes/code_num.php');},500);
    	}
        function ReCodeNumFast(){
        	document.getElementById('codePic').attributes['src'].value="./includes/code_num.php";
    	}
    </script>
    <?php
        if (isset($_POST['registerBtn'])) {
            require ('./includes/check_register.php');
        }
    ?>
</head>  
<body>
<div class="container">

    <form id="loginForm" class="form-signin" method="post" action="">
        <div id="content">

                <h2 class="form-signin-heading">注册</h2>
                <label for="inputEmail" class="sr-only">请输入用户名:</label>
                <input name="user" id="user" type="text" class="form-control" placeholder="请输入用户名" value="<?php if(isset($user)) echo $user;?>">
                <br>
       
       
                <label for="password1"  class="sr-only">请输入密码:</label>
                <input name="password1" id="password1" class="form-control" type="password" class="formbox" placeholder="请输入密码">
                <br>
       
          
                <label for="password2" class="sr-only">请再输入密码:</label>
                <input name="password2" id="password2" class="form-control" type="password" class="formbox" placeholder="请输入密码">
                <br>
   
                <label for="codeNum" class="sr-only">验证码</label>
                <input type="text" id="codeNum" name="codeNum" maxlength="4"  class="form-controlyanzheng" height='30px'  placeholder="请输入验证码"/>
                <img width="100px" src="./includes/code_num.php" id="codePic" title="点击更换验证码" align="absmiddle" onclick='ReCodeNum()'/>
                <br><br>
       
                <input name="registerBtn" class="btn btn-lg btn-primary btn-block" type="submit" value="注册"">
                <br>
                <a id="zhuce" href="./login.php" onclick="window.history.back();">返回</a>
       
        </div>
    </form>
    </div>
</body>  
</html>  