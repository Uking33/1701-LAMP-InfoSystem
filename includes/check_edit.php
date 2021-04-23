<?php 
    require_once('connection.inc.php');
    $conn = dbConnect('write');
    //---load---
    if(!isset($_SESSION['user_id']))
        $id=$_SESSION['user_info']['id'];
    else
        $id=$_SESSION['user_id'];
    $headPic=$_POST['post_head'];
    $name=$_POST['name'];
    $age=$_POST['age'];
    $height=$_POST['height'];
    $weight=$_POST['weight'];
    $sex=$_POST['sex'];
    $birth=(string)$_POST['birth_year'].sprintf('%02s', (string)$_POST['birth_month'])
            .sprintf('%02s', (string)$_POST['birth_day']);
    $province=$_POST['province'];
    $city=$_POST['city'];
    $district=$_POST['district'];
    $phone=$_POST['phone'];
    $qq=$_POST['qq'];
    $email=$_POST['email'];
    //---check---
    if($province!="0" && ($city=="0" || $district=="0")){
        echo"<script type='text/javascript'>
                alert('请把地区信息填写完整或空出');
                window.history.back();
            </script>";
        return;
    }
    if($qq!="" && (strlen($qq)<5 || strlen($qq)>11)){
        echo"<script type='text/javascript'>
                alert('qq号码位数不正确');
                window.history.back();
            </script>";
        return;
    }
    if($email!="") {
        $mode = '/\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*/';
        if(!preg_match($mode,$email)){
            echo"<script type='text/javascript'>
                alert('邮箱格式不正确');
                window.history.back();
            </script>";
            return;
        }
    }
    //---save---
    //head
    function scalePic($base64){
        $size = getimagesize($base64);
        $img = imagecreatefromstring (base64_decode(str_replace("data:image/jpeg;base64,","",$base64)));
        
        $width=$size[0];
        $height=$size[1];
        $n_w=100;
        $n_h=100;
        $new=imagecreatetruecolor($n_w, $n_h);
        //copy部分图像并调整
        imagecopyresampled($new,$img,0,0,0,0,$n_w,$n_h,$width,$height);
        //图像输出新图片、另存为
        ob_start();
        //echo $new;
        imagejpeg($new, null, 100);
        $newbase64 = ob_get_contents();
        ob_end_clean();        
        $newbase64= "data:image/jpeg;base64,".base64_encode($newbase64);
        imagedestroy($new);
        imagedestroy($img);
        return $newbase64;
    }
    if($headPic!=""){
        $headPic=scalePic($headPic);//scale       
        $_SESSION['user_info']['头像']=$headPic; 
        $headPic = "0x".bin2hex (base64_decode(str_replace("data:image/jpeg;base64,","",$headPic)));//0x
        //$headPic
        insertInfo($conn,$id,'头像','pics',$headPic);
    }
    //name
    if($name!=""){
        insertInfo($conn,$id,'名称','words',$name);
        $_SESSION['user_info']['名称']=$name;
    }
    //age
    if($age!=""){
        insertInfo($conn,$id,'年龄','words',$age);
        $_SESSION['user_info']['年龄']=$age;
    }
    //height
    if($height!=""){
        insertInfo($conn,$id,'身高','words',$height);
        $_SESSION['user_info']['身高']=$height;
    }
    //name
    if($weight!=""){
        insertInfo($conn,$id,'体重','words',$weight);
        $_SESSION['user_info']['体重']=$weight;
    }
    //sex
    insertInfo($conn,$id,'性别','words',$sex);
    $_SESSION['user_info']['性别']=$sex;
    //birth
    insertInfo($conn,$id,'生日','words',$birth);
    $_SESSION['user_info']['生日']=$birth;
    //province
    if($province!="0" && $city!="0" && $district!="0"){
        insertInfo($conn,$id,'区域','words',$province);
        insertInfo($conn,$id,'城市','words',$city);
        insertInfo($conn,$id,'地区','words',$district);
        $_SESSION['user_info']['区域']=$province;
        $_SESSION['user_info']['城市']=$city;
        $_SESSION['user_info']['地区']=$district;
    }
    //qq
    if($qq!=""){
        insertInfo($conn,$id,'QQ','words',$qq);
        $_SESSION['user_info']['QQ']=$qq;
    }
    //phone
    if($phone!=""){
        insertInfo($conn,$id,'手机','words',$phone);
        $_SESSION['user_info']['手机']=$phone;
    }
    //email
    if($email!=""){
        insertInfo($conn,$id,'邮箱','words',$email);
        $_SESSION['user_info']['邮箱']=$email;
    }
    //addition
    $i=0;
    $addOldArr=array();
    $addNewArr=array();
    $notKey=array('头像','名称','年龄','身高','体重','性别','生日','区域','城市','地区','手机','QQ','邮箱');
    foreach($_SESSION['user_info'] as $name=>$value){
        if(!in_array($name, $notKey))
            array_push($addOldArr,$name);
    }
    foreach($_POST as $key=>$name){
        //break condition
        if(strpos($key,'addName')===false)
            continue;
        //get POST
        $index=str_replace("addName","",$key);
        $value=$_POST['addValue'.$index];
        //record new
        array_push($addNewArr,$name);
        //Updata
        if((!in_array($name, $addOldArr)) || $_SESSION['user_info'][$name]!=$value){
            insertInfo($conn,$id,$name,'words',$value);
            $_SESSION['user_info'][$name]=$value;
        }
    }
    //delete old
    foreach($addOldArr as $key=>$name){
        if(!in_array($name, $addNewArr)){
            unset($_SESSION['user_info'][$name]);
            deleteInfo($conn,$id,'name',$name);
        }
    }
    //---end---
    $_SESSION['success']=true;
    echo"<script type='text/javascript'>
            alert('修改成功');
            window.history.back();
        </script>";
?>  