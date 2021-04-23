<?php
    require_once ('./includes/getdata.inc.php');
    if(!isset($_SESSION['user_accounts'])){
        echo "<script language='JavaScript' type='text/javascript'>
        alert('请先登录');
      	window.location.href='login.php';
        </script>";
        exit();
    }
    if(isset($_SESSION['user_accounts'])){
        //load text
        if($_SESSION['user_accounts']=='system'){
            $user='管理员';
        }
        else{
            $user=($n=getWords('名称'))==""?$_SESSION['user_accounts']:$n;
        }
    }
    function helloWord1($user){
        //week
        $week=date('l');
        switch($week){
            case 'Monday':
                $week = '星期一';
                break;
            case 'Tuesday':
                $week = '星期二';
                break;
            case 'Wednesday':
                $week = '星期三';
                break;
            case 'Thursday':
                $week = '星期四';
                break;
            case 'Friday':
                $week = '星期五';
                break;
            case 'Saturday':
                $week = '星期六';
                break;
            case 'Sunday':
                $week = '星期天';
                break;
        }
        $time;
        $welcome="&nbsp;&nbsp;&nbsp;今天是".date("Y")."年".date("n")."月".date("d")."日  $week";
        return $welcome;
    }
    function helloWord2($user){
        date_default_timezone_set('PRC');
        //time
        $time=date('G');
        if($time>=0 && $time<6){
            $time="半夜好,".$user."。夜猫子，要注意身体哦！";
        }
        elseif($time>=6 && $time<8){
            $time="早上好,".$user."。早起的鸟儿有虫吃！";
        }
        elseif($time>=8 && $time<12){
            $time="早上好,".$user."。今天的阳光真灿烂啊！";
        }
        elseif($time>=12 && $time<14){
            $time="中午好,".$user."。午休时间。您要保持睡眠哦！";
        }
        elseif($time>=14 && $time<18){
            $time="下午好,".$user."。祝您下午工作愉快！";
        }
        elseif($time>=18 && $time<22){
            $time="晚上好,".$user."。忙碌了一天，是时候放松啦！";
        }
        elseif($time>=22 && $time<24){
            $time="晚上好,".$user."。您应该休息了！";
        }
        return $time;
    }
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">  
<html>  
<head>
    <?php header("Content-Type: text/html; charset=UTF-8");?>
    <title>个人主页</title>
    <link href="./css/bootstrap.css" rel="stylesheet">
    <link href="./css/navbar.css" rel="stylesheet">
    <style type="text/css">
        #huanying {
            text-align: center;
        }
    </style>
</head>  
<body>
    <div id="container" >
        <?php include('./includes/menu.inc.php'); ?>
        <div class="jumbotron">
            <p><?php echo helloWord1($user);?></p>
            <p id="huanying"><?php echo helloWord2($user);?></p>
        </div>
    </div>
</body>  
</html>