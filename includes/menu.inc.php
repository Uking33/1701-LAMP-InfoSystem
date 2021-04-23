<?php $currentPage = basename($_SERVER['SCRIPT_FILENAME']);?>


<nav class="navbar navbar-default" role="navigation">
    <div class="container-fluid">
        <div class="navbar-header">
            <a class="navbar-brand" href="welcome.php">主页</a>
        </div>
        <div>
            <ul class="nav navbar-nav">
                <?php if($_SESSION['user_accounts']=='system'){
                        $user='管理员';?>
                    <li <?php if ($currentPage=='manage.php'||$currentPage=='manage.php#'){echo "class='active'";} ?>>
                        <a href='manage.php'>查看用户</a></li>
                    <li><a href='./includes/logout.inc.php'>注销</a></li>     
                <?php }else{?>
                    <li <?php if ($currentPage=='show.php'||$currentPage=='show.php#'){echo "class='active'";} ?>>
                        <a href='show.php'>个人信息</a></li>
                    <li <?php if ($currentPage=='edit.php'||$currentPage=='edit.php#'){echo "class='active'";} ?>>
                        <a href='edit.php'>修改信息</a></li>
                    <li><a href='./includes/logout.inc.php'>注销</a></li>
                <?php }?>
            </ul>
        </div>
    </div>
</nav>