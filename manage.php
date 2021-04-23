<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">  
<html>  
<head>
    <title>查看用户信息</title>
    <link href="./css/bootstrap.css" rel="stylesheet">
    <link href="./css/navbar.css" rel="stylesheet">
    <script type="text/javascript" src=".\javascript\page.js"></script>
    <?php
        require_once ('./includes/getdata.inc.php');
        if(!isset($_SESSION['user_accounts'])){
            echo "<script language='JavaScript' type='text/javascript'>
            alert('请先登录');
          	window.location.href='login.php';
            </script>";
            exit();
        }
        if (count($_POST)>0) {
            require ('./includes/check_manage.php');
            switch($type){
                case 'show':
                    echo "<script language='JavaScript' type='text/javascript'>window.location.href='show.php';</script>";
                    break;
                case 'edit':
                    echo "<script language='JavaScript' type='text/javascript'>window.location.href='edit.php';</script>";
                    break;
                case 'dele':
                    echo "<script language='JavaScript' type='text/javascript'>window.history.back();alert('已删除');window.location.href=window.location.href;</script>";
                    break;
            }
        }
        require_once ('./includes/getdata.inc.php');
        require_once('./includes/connection.inc.php');
        //load data
        $allArr=$_SESSION['all_user'];
    ?>
    <script language="JavaScript" type="text/javascript">
		//api
        function addRow(id,account,passwords){
            var sScript;
            sScript="<tr>";
            sScript=sScript+"<td align='center' style='padding:10px;'>"+id+"</td>";
            sScript=sScript+"<td align='center'>"+account+"</td>";
            sScript=sScript+"<td align='center'>"+passwords+"</td>";
            sScript=sScript+"<td align='center'>";
            sScript=sScript+"<input class='btn btn-default' style='margin: 0 3px;' type='submit' name='show"+id+"' value='查看'>";
            sScript=sScript+"<input class='btn btn-default' type='submit' name='edit"+id+"' value='编辑'>";
            sScript=sScript+"<input class='btn btn-default' type='submit' name='dele"+id+"' value='删除'></td>";
            sScript=sScript+"</tr>";
            content.insertAdjacentHTML("beforeEnd",sScript);            
		}
    </script>
    <style>
    td
    {
        width:100px;
    }
    #btns{
        width: 100%;
        margin-top: 20px;
    }
    #btns table{
        width: 100%;
    }
    #barcon{
        text-align: center;
        margin:0 auto;
    }
    </style>
</head>
<body>
<div id="container">
    <?php include('./includes/menu.inc.php'); ?>
    <br>
    <form id="manageForm" method="post" action="">
        <?php if(count($allArr)>0){?>
        <table frame="box" border="1" col="4" cellspacing="0" cellpadding="5" style="width:100%;">
            <thead style="text-align: center;">
                <tr>
                    <th style="text-align: center; width:25%;">ID</th>
                    <th style="text-align: center; width:25%;">帐号</th>
                    <th style="text-align: center; width:25%;">密码</th>
                    <th style="text-align: center; width:25%;">操作</th>    
                </tr>
              <tbody id="content">
              </tbody>
            </thead>
        </table>
        <?php }?>
    </form>
    <div id="btns">
        <table id="atable">
            <tr><td><div id="barcon" name="barcon"></div></td></tr>            
        </table>
    </div>
    <?php
        //load label
        foreach($allArr as $arr){
            echo "\n<script language='JavaScript' type='text/javascript'>addRow('"
                    .$arr['id']."','".$arr['accounts']."','".$arr['passwords']
                    ."');</script>";
        }
        echo "\n";
    ?>
    <script language="JavaScript" type="text/javascript">
		var numInfo=<?php echo count($allArr);?>;
    	if(numInfo>0){
    		goPage(1,10,numInfo);
    	}
    </script>
    </div>
</body>
</html>