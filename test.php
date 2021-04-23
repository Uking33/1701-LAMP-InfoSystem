<?php
    require_once('.\includes\connection.inc.php');
    testAddUserWithMoreDetails('test','123456',80);
    function testAddUserWithMoreDetails($user,$passwords,$num){
        $conn = dbConnect('write');
        if(findUser($conn, $user, $passwords)){
            echo"<script type='text/javascript'>
                    alert('帐号已存在');
                </script>";
            return;
        }
        //get id
        $id = getMaxID($conn,'user_all','user_id')+1;
        //insert accounts
        $sql = "insert into user_all (user_id,user_accounts,user_passwords) values ('$id','$user','$passwords')" ;
        $result = $conn->query($sql) or die(mysqli_error($conn));
        //create base info
        $sql = "create table if not exists user_$id("
        ."name varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,"
            ."type varchar(5),"
                ."words varchar(400) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,"
                    ."pics blob)";
        $conn->query($sql) or die(mysqli_error($conn));
        //insert headPic
        $headPic = "0x".bin2hex(file_get_contents('.\images\unhead.jpg'));
        insertInfo($conn,$id,'头像','pics',$headPic);
        //insert name
        insertInfo($conn,$id,'名称','words','');
        //insert age
        insertInfo($conn,$id,'年龄','words','');
        //insert height
        insertInfo($conn,$id,'身高','words','');
        //insert weight
        insertInfo($conn,$id,'体重','words','');
        //insert sex
        insertInfo($conn,$id,'性别','words','s');
        //insert birth
        $date=date("Ymd");
        insertInfo($conn,$id,'生日','words',$date);
        //insert province
        insertInfo($conn,$id,'区域','words','');
        //insert city
        insertInfo($conn,$id,'城市','words','');
        //insert district
        insertInfo($conn,$id,'地区','words','');
        //insert phone
        insertInfo($conn,$id,'手机','words','');
        //insert qq
        insertInfo($conn,$id,'QQ','words','');
        //insert email
        insertInfo($conn,$id,'邮箱','words','');
        //others
        for($i=1;$i<=$num;$i++)
            insertInfo($conn,$id,(string)$i,'words','');
        echo"<script type='text/javascript'>
                alert('test帐号创建成功');
            </script>";
    }
?>

