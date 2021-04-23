<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">  
<?php
    require_once ('./includes/getdata.inc.php');
    if(!isset($_SESSION['user_accounts'])){
        echo "<script language='JavaScript' type='text/javascript'>
            alert('请先登录');
          	window.location.href='login.php';
        </script>";
        exit();
    }
    //load data
    $user=$_SESSION['user_accounts'];
    $infoArr=$_SESSION['user_info'];
    //post
    if (count($_POST)>0) {
        require ('./includes/check_manage.php');
        switch($type){
            case 'dele':
                echo "<script language='JavaScript' type='text/javascript'>window.history.back();
                            alert('已删除');window.history.back();window.location.href=window.location.href;</script>";
                break;
        }
    }
?>
<html>  
<head>
    <title>个人信息</title>
    <link href="./css/bootstrap.css" rel="stylesheet">
    <link href="./css/navbar.css" rel="stylesheet">
    <script type="text/javascript" src=".\javascript\page.js"></script>
    <script language="JavaScript" type="text/javascript">
		//api
        function addRow(name,type,value){
            var sScript;
            switch(type){
            	case 'words':
                    sScript="<tr style='display:table-row;'>";
                    sScript=sScript+"<td>"+name+"</td>"
                    sScript=sScript+"<td>"+transferValue(name,value)+"</td>"
                    sScript=sScript+"</tr>";
                    content.insertAdjacentHTML("beforeEnd",sScript);
                	break;
            }
		}
		function transferValue(str,value){
			switch(str){
				case '年龄':
    				return value==''?'未填写':value+'岁';
				case '身高':
    				return value==''?'未填写':value+'cm';
				case '体重':
    				return value==''?'未填写':value+'kg';
    			case '性别':
        			switch (value){
            			case 'm':
                			return '男';
            			case 'f':
                			return '女';
            			case 's':
                			return '保密';
        			}
				case '生日':
					return value.substr(0,4)+"年"+value.substr(4,2)+"月"+value.substr(6,2)+"日";
				default:
    				return value==''?'未填写':value;
			}
		}
		function edit(){
			history.replaceState(null,'修改个人信息','edit.php');
          	window.location.href=window.location.href;
		}
		function del(){
			//lock
		}
		function backToManage(){
			var str=window.location.href;
			if(str[str.length-1]=='#')
				window.history.back();
			window.history.back();
		}
    </script>
    <style type="text/css">
        img{
            display: block;
            margin:0 auto;
        }
 
        #atable{
            margin:0 auto;
        }
    </style>
</head>

<body>
<div id="container">
    <?php include('./includes/menu.inc.php'); ?>
  
    <img id="pic_head"  class="img-info" style=" width: 100px; height: 100px; CURSOR: hand" src="
            <?php echo getPic('头像');?>" /> 
    <br>
    <table  class="table table-striped " style="width: 95%; margin:0 auto;text-align: center;">
        <thead style="text-align: center;">
            <tr>
                <th style="text-align: center; width:40%;"><label style="font-size:20px;">个人信息</label></th>
                <th style="text-align: center; width:60%;">
                <?php if($_SESSION['user_accounts']=='system'){?>
                <label style="font-size:20px;">ID:<?php echo $infoArr['id'];?></label>
                <?php }?>
                </th>
            </tr>
          <tbody id="content">
          </tbody>
        </thead>
    </table>
    <br>
    <br>
    <div id="btns">
        <table id="atable">
            <tr><td><div id="barcon" name="barcon"></div></td></tr>
            <?php 
                if($_SESSION['user_accounts']=='system'){
                    echo "<tr><td>&nbsp;</td></tr>";
                    echo "<tr><td align='center'>";
                    echo "<form id='manageForm' method='post' action=''>";
                    echo "<input name='dele".$infoArr['id']."' class='btn btn-default' type='submit' value='删除用户'>";
                    echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                    echo "<input name='back' class='btn btn-default' type='button' value='返回管理'
                    onclick='backToManage()'>";
                    echo "</form></td></tr>";
                }
               
            ?>
        </table>
    </div>
    <?php
        //load label
        $orderArr=array('头像','名称','年龄','身高','体重','性别','生日','区域','手机','QQ','邮箱');
        foreach($orderArr as $name){
            if(array_key_exists($name, $infoArr)){
                if($name=="头像")
                    continue;
                else
                    $type='words';
                $value=$infoArr[$name]; 
                if($name=='区域'){
                    $value.=$infoArr['城市'].$infoArr['地区'];
                }
                echo "\n    <script language='JavaScript' type='text/javascript'>addRow('"
                        .$name."','".$type."','".$value
                        ."');</script>";
            }
        }
        if($_SESSION['user_accounts']=='system')
            $notKey=array('头像','名称','年龄','身高','体重','性别','生日','区域','城市','地区','手机','QQ','邮箱','id');
        else
            $notKey=array('头像','名称','年龄','身高','体重','性别','生日','区域','城市','地区','手机','QQ','邮箱');
        $type='words';
        foreach($infoArr as $name=>$value){        
            if(!in_array($name, $notKey)){
                echo "\n    <script language='JavaScript' type='text/javascript'>addRow('"
                        .$name."','".$type."','".$value
                        ."');</script>";
            }
        }
    ?>
    <script language="JavaScript" type="text/javascript">
		var numInfo=<?php if($_SESSION['user_accounts']=='system') echo count($_SESSION['user_info'])-4; else echo count($_SESSION['user_info'])-3;?>;
		goPage(1,10,numInfo);
    </script>
     </div>
</body>
</html>