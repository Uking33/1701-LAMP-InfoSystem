<?php
    require_once ('./includes/getdata.inc.php');
    if(!isset($_SESSION['user_accounts'])){
        echo "<script language='JavaScript' type='text/javascript'>
        alert('请先登录');
      	window.location.href='login.php';
        </script>";
        exit();
    }
    //load province xml
    require_once ('./includes/provinceLoad.inc.php');
    //load data
    $user=$_SESSION['user_accounts'];
    //post
    if(isset($_SESSION['success'])){
        echo "<script language='JavaScript' type='text/javascript'>
                window.location.href='show.php';
            </script>";
        unset($_SESSION['success']);
    }
    if($_SESSION['user_accounts']=='system')
        $infoArr=$_SESSION['user_info'];
    if (count($_POST)>0) {
        if(isset($_POST['save']))
            require('./includes/check_edit.php');
        if($_SESSION['user_accounts']=='system'){
            if(isset($_POST['dele'.$infoArr['id']])){
                require ('./includes/check_manage.php');
                switch($type){
                    case 'dele':
                        echo "<script language='JavaScript' type='text/javascript'>
                            window.history.back();
                            alert('已删除');
                            window.history.back();
                            window.location.href=window.location.href;
                            </script>";
                        break;
                }
            }
        }
    }
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">  
<html>  
<head>
    <?php header("Content-Type: text/html; charset=UTF-8");?>

    <title>修改个人信息</title>
    <link href="./css/bootstrap.css" rel="stylesheet">
    <link href="./css/navbar.css" rel="stylesheet">
    <script language="JavaScript" type="text/javascript">
        //---area---
    	var provinceJsArr=<?php echo urldecode($provinceJsonArr);?>; 
        function changeProvince(){
            var sltProvince=document.editForm.province;  
            var sltCity=document.editForm.city;
            var sltDistrict=document.editForm.district;
            //get
            var cities=provinceJsArr[sltProvince.selectedIndex]; 
            //clear
            sltCity.length=1;
            sltDistrict.length=1;
            //fill
            for(var i=1;i<cities.length;i++){  
            	sltCity[i]=new Option(cities[i][0],cities[i][0]);  
            }
        }
        function changeCity(){
            var sltProvince=document.editForm.province;  
            var sltCity=document.editForm.city;
            var sltDistrict=document.editForm.district;
            //get
            var districts=provinceJsArr[sltProvince.selectedIndex][sltCity.selectedIndex]; 
            //clear
            sltDistrict.length=1;
            //fill
            for(var i=1;i<districts.length;i++){
            	sltDistrict[i]=new Option(districts[i],districts[i]);  
            }
        }

		//birth
        function changeYear(){
            var sltYear=document.editForm.birth_year;  
            var sltMonth=document.editForm.birth_month;
            var sltday=document.editForm.birth_day;
            sltMonth.value = 1; 
            sltday.length=0;
            for(var i=1;i<=31;i++){
            	sltday[i-1]=new Option(i,i);  
            }
        }
        
        function changeMonth(){
            var sltYear=document.editForm.birth_year;  
            var sltMonth=document.editForm.birth_month;
            var sltday=document.editForm.birth_day;
            sltday.length=0;
            var day = new Date(sltYear[sltYear.selectedIndex].text, sltMonth[sltMonth.selectedIndex].text, 0); 
            var daycount = day.getDate();
            for(var i=1;i<=daycount;i++){
            	sltday[i-1]=new Option(i,i);  
            }
        }
		
        //add
        function Length(str) {  
      	  var len = 0;  
      	  for (var i=0; i<str.length; i++) {  
      	    if (str.charCodeAt(i)>127 || str.charCodeAt(i)==94) {  
      	       len += 2;  
      	     } else {  
      	       len ++;  
      	     }  
      	   }  
      	  return len;  
      	}
    	var infoJsArr=<?php echo json_encode(array_keys($_SESSION['user_info']));?>;
        function addRow(){
            var str=prompt("请输入要增加的条目名","");
            if(Length(str)>10){
                alert("输入超过10个字符/5个中文");
                return;
            }
                
            var result=false;
            for(var i=0;i<infoJsArr.length;i++){
         	   	if(infoJsArr[i]==str){
             	   	result=true;
             	   	break;
     		   	}
     		}
            if(str){
                if(result){
                    alert("您已添加过\""+str+"\"条目");
                    return;
                }
                addNum++;
                infoJsArr.push(str);
                var sScript="<div id='new"+addNum+"' name='new"+addNum+"'class='form-group row'>";
                sScript=sScript+"<label class='col-md-1'></label>";
                sScript=sScript+"<label class='col-md-2 col-form-label'>"+str+":</label>";
                sScript=sScript+"<input name='addName"+addNum+"' type='hidden' value='"+str+"'/>";
                sScript=sScript+"<div class='col-md-5'>"+"<input maxlength='20' name='addValue"+addNum+"' type='text' class='form-control'/>"+"</div>";
                sScript=sScript+"<div class='col-md-1' style='padding-left: 0;'>"+"<input type='button' value='删' class='btn btn-default' onclick='delRow("+addNum+")'/>";
                sScript=sScript+"</div></div>";
                content.insertAdjacentHTML("beforeEnd",sScript); 
            }
            else{
                alert("您尚未输入");
            }                
		} 
        function delRow(num){
            infoJsArr.splice(num+12,1);
        	var el = document.getElementById("new"+num);
       	 	el.parentNode.removeChild(el);
            addNum--;
        }

        //head pic
        function compress(img) {
        	var initSize = img.src.length;
            var width = img.width;
            var height = img.height;
            //如果图片大于四百万像素，计算压缩比并将大小压至400万以下
            var ratio;
            if ((ratio = width * height / 4000000)>1) {
                ratio = Math.sqrt(ratio);
                width /= ratio;
                height /= ratio;
            }else {
                ratio = 1;
            }
            var canvas = document.createElement('canvas');
            canvas.width = width;
            canvas.height = height;
			//铺底色
            var ctx = canvas.getContext('2d');
            ctx.fillStyle = "#fff";
            ctx.fillRect(0, 0, canvas.width, canvas.height);
            //如果图片像素大于100万则使用瓦片绘制
            var count;
            var tcanvas = document.createElement('canvas');
            var tctx = tcanvas.getContext('2d');
            if ((count = width * height / 1000000) > 1) {
                count = ~~(Math.sqrt(count)+1); //计算要分成多少块瓦片
				//计算每块瓦片的宽和高
                var nw = ~~(width / count);
                var nh = ~~(height / count);
                tcanvas.width = nw;
                tcanvas.height = nh;
                for (var i = 0; i < count; i++) {
                    for (var j = 0; j < count; j++) {
                        tctx.drawImage(img, i * nw * ratio, j * nh * ratio, nw * ratio, nh * ratio, 0, 0, nw, nh);
                        ctx.drawImage(tcanvas, i * nw, j * nh, nw, nh);
                    }
                }
            } else {
                ctx.drawImage(img, 0, 0, width, height);
            }
            //进行最小压缩
            var ndata = canvas.toDataURL("image/jpeg", 0.1);
            tcanvas.width = tcanvas.height = canvas.width = canvas.height = 0;
            return ndata;
        }
        function headPicClick(){
        	document.getElementById('file_head').click();
        }
        function headPicChange() { 
            //init
            var file=document.getElementById("file_head");
            var img_brower=document.getElementById("pic_head");
            var post=document.getElementById("post_head");
        	//check
        	var img_id=file.value; 
        	var img_type=img_id.substring(img_id.indexOf(".")).toLowerCase(); 
        	if(img_type!=".bmp"&&img_type!=".png"&&img_type!=".gif"&&img_type!=".jpg"&&img_type!=".jpeg"){
       			alert("不是指定图片格式,重新选择"); 
            	document.getElementById('movie_img').value=""; 
            	return;
        	}
            if(typeof FileReader == "undefined"){
                alert("您的浏览器不支持FileReader对象！");
            }

            var reader = new FileReader();
            reader.readAsDataURL(file.files[0]);
            reader.onload = function (e) {
                var result = this.result;
                var img = new Image();
                img.src = result;
                var maxsize=65*1024;
                //<=65kb
                if (result.length <= maxsize) {
                    img = null;
                	img_brower.src=data;
                	post.value=data;
                    return;
                } 
                //>65kb complete
                if (img.complete) {
                    callback();
                } else {
                    img.onload = callback;
                }
                function callback() {
                    var data = compress(img);
                    img = null;
                	img_brower.src=data;
                	post.value=data;
                }
            };
            return true;
        }

        //limit
        function ValidateNumber(e, pnumber){
        	if (!/^\d+$/.test(pnumber)){    
        		e.value = /^\d+/.exec(e.value);
        	}    
        	return false;
    	}
        function ValidatePhone(e, pnumber){
        	if (!/^[\d-]+$/.test(pnumber)){    
        		e.value = /^[\d-]+/.exec(e.value);
        	}    
        	return false;
    	}
    </script>    
    <style type="text/css">
    		#content{
    			width:100%;
    			margin:0 auto;
    		}
    		label{
    			margin-bottom: 0;
    			line-height:34px;
    		}
    		#btns{
    			margin:0 auto;
    			width: 30%;
    		}
    		.form-group label{
    			margin-right: 5px;
    			text-align: right;
    		}
    		#touxiang{
    			line-height: 100px;
    		}
    		.tiao .col-md-2{
    			padding-right: 10px;
    		}
    </style>
</head>  
<body>
<div id="container">
    <?php include('./includes/menu.inc.php'); ?>
    <br>
    <form name="editForm" method="post" action="">
        <div id="content">
            <?php if($_SESSION['user_accounts']=='system'){?>
            <div class="form-group row" style="text-align:center;">
                <label style="font-size:20px;">ID:<?php echo $infoArr['id'];?></label>
            </div>
            <br>
            <?php }?>
            <div class="form-group row">
                <label class="col-md-1"></label>
                <label for="name" id="touxiang" class="col-md-2 col-form-label" >头像:</label>
                <img id="pic_head"  class="img-circle" onclick='headPicClick()' style="width: 100px; height: 100px; CURSOR: hand" src="
                <?php echo getPic('头像');?>"  title="点击更换头像"/>
                <input type="hidden" name="post_head" id="post_head" value=""/>   
                <input type="file" name="file_head" id="file_head" onchange="headPicChange()" style="filter:alpha(opacity=0);opacity:0;width: 0;height: 0;"/>   
            </div>
             <div class="form-group row">
                <label class="col-md-1"></label>
                <label for="name" class="col-md-2 col-form-label" >名称:</label>
                <div class="col-md-6">
                <input name="name" class="form-control" placeholder="请输入你的名字" type="text" maxlength="20" <?php echo "value='".getWords('名称')."'"?>>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-md-1"></label>
                <label for="age" class="col-md-2 col-form-label" >年龄:</label>
                <div class="col-md-6">
                <input name="age" class="form-control" placeholder="请输入你的年龄(岁)" type="text" maxlength="3" onkeyup="return ValidateNumber(this,value)" <?php echo "value='".getWords('年龄')."'"?>>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-md-1"></label>
                <label for="height" class="col-md-2 col-form-label" >身高:</label>
                <div class="col-md-6">
                <input name="height" class="form-control" type="text" placeholder="请输入你的身高(cm)" maxlength="3" onkeyup="return ValidateNumber(this,value)" <?php echo "value='".getWords('身高')."'"?>>
                </div>
           </div>
            <div class="form-group row">
                <label class="col-md-1"></label>
                <label for="weight" class="col-md-2 col-form-label" >体重:</label>
                <div class="col-md-6">
                <input name="weight" class="form-control" type="text" placeholder="请输入你的体重(kg) "maxlength="3" onkeyup="return ValidateNumber(this,value)" <?php echo "value='".getWords('体重')."'"?>>
                </div>
          </div>
            <div class="form-group row">
                <label class="col-md-1"></label>
                <label for="sex" class="col-md-2 col-form-label">性别:</label>
                <div class="col-md-6">
                    <label style='float:left;margin-left:8%;'><input name="sex" type="radio" value="m"<?php if(getWords('性别')=='m') echo 'checked="true"'?>/> 男</label> 
                    <label style='float:left;margin-left:22%;'><input name="sex" type="radio" value="f"<?php if(getWords('性别')=='f') echo 'checked="true"'?>/> 女</label> 
                    <label style='float:left;margin-left:22%;'><input name="sex" type="radio" value="s"<?php if(getWords('性别')=='s') echo 'checked="true"'?>/> 保密</label> 
                </div>
            </div>
             <div class="form-group row">
                <label class="col-md-1"></label>
                <label for="birth" class="col-md-2 col-form-label tiao">生日:</label>
                <div class="col-md-2" >
                    <select name="birth_year" onChange="changeYear()" class="form-control">
                        <?php
                            $date=getWords('生日');
                            if(strlen($date)==8){
                                $year=substr($date,0,4);
                                for ($i=date('Y');$i>=date('Y')-100;$i--){
                                    echo "<option value=\"$i\"";
                                    if((int)$year==$i)
                                        echo " selected=\"selected\"";
                                    echo ">$i</option>";
                                }
                            }
                            else{
                                for ($i=date('Y');$i>=date('Y')-100;$i--)
                                    echo "<option value=\"$i\">$i</option>";
                            }
                        ?>
                    </select>
                </div> 
                <div class="col-md-2" style="padding-left: 0;">
                <select name="birth_month" onChange="changeMonth()" class="form-control"> 
                    <?php 
                        $date=getWords('生日');
                        if(strlen($date)==8){
                            $month=substr($date,4,2);
                            for ($i=1;$i<=12;$i++){
                                echo "<option value=\"$i\"";
                                if((int)$month==$i)
                                    echo " selected=\"selected\"";
                                echo ">$i</option>";
                            }
                        }
                        else{
                            for ($i=1;$i<=12;$i++)
                                echo "<option value=\"$i\">$i</option>";
                        }
                    ?>
                </select> 
                </div>
                <div class="col-md-2" style="padding-left: 0;">
                <select name="birth_day" class="form-control">
                    <?php
                        $date=getWords('生日');
                        if(strlen($date)==8){
                            $day=substr($date,6,2);
                            $dayMax=cal_days_in_month(CAL_GREGORIAN, (int)substr($date,4,2), (int)substr($date,0,4));
                            for ($i=1;$i<=$dayMax;$i++){
                                echo "<option value=\"$i\"";
                                if((int)$day==$i)
                                    echo " selected=\"selected\"";
                                echo ">$i</option>";
                            }
                        }
                        else{
                            for ($i=1;$i<=31;$i++)
                                echo "<option value=\"$i\">$i</option>";
                        }
                    ?>
                </select> 
                </div>
           </div>
           <div class="form-group row">
                <label class="col-md-1"></label>
                <label for="area" class="col-md-2 col-form-label tiao">区域:</label>
               
                <div class="col-md-2">
                <select name="province" onChange="changeProvince()" class="form-control">
                    <option value="0">省份</option> 
                    <?php
                        $p1=getWords('区域');
                        $p2=getWords('城市');
                        $p3=getWords('地区');
                        if($p1!=""){
                            for($i=1;$i<count($provinceArr);$i++){
                                echo "<option value=\"".$provinceArr[$i][0]."\"";
                                if($p1==$provinceArr[$i][0]){
                                    echo " selected=\"selected\"";
                                    $i1=$i;
                                }
                                echo ">".$provinceArr[$i][0]."</option>";
                            }
                        }
                        else{
                            for($i=1;$i<count($provinceArr);$i++)
                                echo "<option value=\"".$provinceArr[$i][0]."\">".$provinceArr[$i][0]."</option>";
                        }
                    ?>
                </select> 
                </div>
                <div class="col-md-2" style="padding-right: 10px;padding-left: 0;">
                <select name="city" onChange="changeCity()" class="form-control">
                    <option value="0">城市</option>
                    <?php
                        if($p1!="" && $p2==""){
                            for($i=1;$i<count($provinceArr[$i1]);$i++){
                                echo "<option value=\"".$provinceArr[$i1][$i][0]."\">"
                                    .$provinceArr[$i1][$i][0]."</option>";
                            }
                        }
                        elseif($p2!=""){
                            for($i=1;$i<count($provinceArr[$i1]);$i++){
                                echo "<option value=\"".$provinceArr[$i1][$i][0]."\"";
                                if($p2==$provinceArr[$i1][$i][0]){
                                    echo " selected=\"selected\"";
                                    $i2=$i;
                                }
                                echo ">".$provinceArr[$i1][$i][0]."</option>";
                            }
                        }
                    ?>
                </select> 
                </div>
                <div class="col-md-2" style="padding-right: 10px;padding-left: 0;">
                <select name="district" class="form-control"> 
                    <option value="0">地区</option> 
                    <?php
                        if($p2!="" && $p3==""){
                            for($i=1;$i<count($provinceArr[$i1][$i2]);$i++){
                                echo "<option value=\"".$provinceArr[$i1][$i2][$i]."\">"
                                    .$provinceArr[$i1][$i2][$i]."</option>";
                            }
                        }
                        elseif($p3!=""){
                            for($i=1;$i<count($provinceArr[$i1][$i2]);$i++){
                                echo "<option value=\"".$provinceArr[$i1][$i2][$i]."\"";
                                if($p3==$provinceArr[$i1][$i2][$i]){
                                    echo " selected=\"selected\"";
                                    $i3=$i;
                                }
                                echo ">".$provinceArr[$i1][$i2][$i]."</option>";
                            }
                        }
                    ?>
                </select> 
                </div>
                </div>
            <div class="form-group row">
                <label class="col-md-1"></label>
                <label for="phone" class="col-md-2 col-form-label">手机:</label>
                <div class="col-md-6">
                <input name="phone" type="text" class="form-control" maxlength="14" onkeyup="return ValidatePhone(this,value)" <?php echo "value='".getWords('手机')."'"?>>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-md-1"></label>
                <label for="qq" class="col-md-2 col-form-label">QQ:</label>
                <div class="col-md-6">
                <input name="qq" type="text" class="form-control" maxlength="11" onkeyup="return ValidateNumber(this,value)" <?php echo "value='".getWords('QQ')."'"?>>
                </div>
           </div>
            <div class="form-group row">
                <label class="col-md-1"></label>
                <label for="email" class="col-md-2 col-form-label">邮箱:</label>
                <div class="col-md-6">
                <input name="email" type="text" class="form-control" maxlength='20' <?php echo "value='".getWords('邮箱')."'"?>>
                </div>
            </div>

            
	        <?php
	            $i=0;
	            $notKey=array('头像','名称','年龄','身高','体重','性别','生日','区域','城市','地区','手机','QQ','邮箱');
	            foreach($_SESSION['user_info'] as $name=>$value){
	                if(!in_array($name, $notKey)){
	                    $i++;
	                    $index=(string)$i;

	                	echo "<div id='new".$index."' name='new".$index."'class='form-group row'>"        
                        ."<label class='col-md-1'></label>"
	                	."<label class='col-md-2 col-form-label'>".$name.":</label>"
	                	."<input name='addName".$index."' type='hidden' value='".$name."'/>"
	                	."<div class='col-md-5'><input maxlength='20' name='addValue".$index."' type='text'  class='form-control' value='".$value."'/></div>"
	                	."<div class='col-md-1' style='padding-left: 0;'><input type='button' value='删'  class='btn btn-default' onclick='delRow(".$index.")'/>"
	                	."</div></div>";
	                }
	            }

	            echo "<script language='JavaScript' type='text/javascript'>
	    			var addNum=".$i.";
	            </script>";
	        ?>
        </div>
        <br>
        <?php 
            if($_SESSION['user_accounts']=='system'){?>
            <div id="btns" class="row" style='width:50%'>
            	<span class="col-md-2">
                    <input name="save" class="btn btn-default " type="submit" value="保存">
                </span>
                <span class="col-md-1"></span>
                <span class="col-md-2">
                    <input name="add"  class="btn btn-default " type="button" value="增加" onclick="addRow()">
                </span>
                <span class="col-md-1"></span>
                <?php echo "<span class='col-md-2'><input name='dele".$infoArr['id']."' class='btn btn-default' type='submit' value='删除'></span>";?>
                <span class="col-md-1"></span>
                <span class='col-md-2'><input name='back' class='btn btn-default' type='button' value='返回' onclick='window.history.back();'></span>
            </div>
        <?php }
            else{
        ?>
        <div id="btns" class="row">
        	<span class="col-md-3">
                <input name="save" class="btn btn-default " type="submit" value="保存">
            </span>
            <span class="col-md-1"></span>
            <span class="col-md-3">
                <input name="add"  class="btn btn-default " type="button" value="增加" onclick="addRow()">
            </span>
        </div>
        <?php }?>
    </form>
    </div>
</body>  
</html>