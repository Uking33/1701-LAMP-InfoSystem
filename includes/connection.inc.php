<?php
    //init
    if(!isset($_SESSION)) session_start();
    //connect db
    function dbConnect($usertype){
        $host = 'localhost';
        $user = 'root';
        $pwd = '';
        $db = 'user';
        if ($usertype != 'read' && $usertype != 'write') {
            exit('Unrecognized connection type');
        }
        //create database
        $conn=new mysqli($host, $user, $pwd) or die ('Cannot connect');// or die ('Cannot open database');
        $sql="CREATE DATABASE IF NOT EXISTS $db default character set utf8 COLLATE utf8_general_ci";
        if ($conn->query($sql) === TRUE){
            $sql="GRANT ALL PRIVILEGES ON $db.* to $user@$host identified by '$pwd'";
            if (!$conn->query($sql))
                echo "Error GRANT database: " . $conn->error;
        }
        else {
            echo $sql;
            echo "Error creating database: " . $conn->error;
        }
        mysqli_close($conn);
        //connect
        $conn=new mysqli($host, $user, $pwd, $db) or die ('Cannot open database');
        if ($usertype != 'read')
            mysql_query("set character set 'utf8'");
        if ($usertype != 'write')
            mysql_query("set names 'utf8'");
        mysql_select_db($db); //open db
        //create table
        $sql = "create table if not exists user_all(user_id integer,user_accounts varchar(20), user_passwords varchar(20))" ;
        $conn->query($sql) or die(mysqli_error($conn));
        //init system
        $sql = "create table if not exists user_all(user_id integer,user_accounts varchar(20), user_passwords varchar(20))" ;
        $conn->query($sql) or die(mysqli_error($conn));
        if(!findUser($conn,'system','123456')){
            $sql = "insert into user_all (user_id,user_accounts,user_passwords) values ('0','system','123456')" ;
            $result = $conn->query($sql) or die(mysqli_error($conn));
        }
        
        return $conn;
    }
    //---sql find---
    function findUser($conn,$user, $passwords){//find user
        $sql = "SELECT * FROM user_all where user_accounts='$user'";
        $result = $conn->query($sql) or die(mysqli_error($conn));
        $num = $result->num_rows;
        if($num!=0)
            return true;
        else
            return false;
    }
    function getCount($conn,$db){//get count
        $sql = "SELECT * FROM $db";
        $result = $conn->query($sql) or die(mysqli_error($conn));
        $num = $result->num_rows;
        if($num>=0)
            return $num;
        else
            return -1;
    }
    function getMaxID($conn,$table,$idName){//get max id
        $sql = "SELECT max($idName) FROM $table";
        $result = $conn->query($sql) or die(mysqli_error($conn));
        $num = $result->num_rows;
        if($num!=0){
            foreach ($result as $row){
                return (int)$row["max($idName)"];
            }
        }
        else
            return -1;
    }
    function insertInfo($conn,$id,$name,$type,$data){
        $sql = "SELECT * FROM user_$id where name='$name'";
        $result = $conn->query($sql) or die(mysqli_error($conn));
        $exsits = $result->num_rows;
        switch($type){
            case "words":
                if($exsits)
                    $sql="update user_$id set words='$data' where name='$name';";
                    else
                        $sql="insert into user_$id(name,type,words) values ('$name','$type','$data')";
                    break;
            case "pics":
                if($exsits)
                    $sql="update user_$id set pics=$data where name='$name';";
                    else
                        $sql="insert into user_$id(name,type,pics) values ('$name','$type',$data)";
                    break;
        }
        $result = $conn->query($sql) or die(mysqli_error($conn));
    }
    
    function deleteInfo($conn,$id,$nameIndex,$name){
        $sql = "delete from user_$id where $nameIndex='$name'";
        $result = $conn->query($sql) or die(mysqli_error($conn));
    }
    function deleteUser($conn,$id){
        $sql = "delete from user_all where user_id='$id'";
        $result = $conn->query($sql) or die(mysqli_error($conn));
        $sql = "DROP TABLE IF EXISTS user_$id";
        $result = $conn->query($sql) or die(mysqli_error($conn));
    }

    //---api---
    function checkUser($conn, $user, $passwords){//login check
        $sql = "SELECT user_passwords FROM user_all where user_accounts='$user'";
        $result = $conn->query($sql) or die(mysqli_error($conn));
        $num = $result->num_rows;
        if($num==1){
            foreach ($result as $row){
                if($row['user_passwords'] == $passwords)
                    return 1;
                else
                    return 0; 
            }
        }
        else
            return -1;
    }
    function getInfo($conn, $user){//login get info
        //get id
        $sql = "SELECT user_id FROM user_all where user_accounts='$user'";
        $result = $conn->query($sql) or die(mysqli_error($conn));
        $num = $result->num_rows;
        if($num==1){
            foreach ($result as $row){
                $_SESSION['user_id']=$row['user_id'];
            }
        }
        else return false;
        //get else
        $infoArr=array();
        $id=$_SESSION['user_id'];
        $sql = "SELECT * FROM user_$id";
        $result = $conn->query($sql) or die(mysqli_error($conn));
        $num = $result->num_rows;
        if($num>0){
            foreach ($result as $row){
                switch($row['type']){
                    case 'words':
                        $infoArr[$row['name']]=$row['words'];
                        break;
                    case 'pics':
                        $infoArr[$row['name']]='data:image/jpeg;base64,'.base64_encode($row['pics']);
                        break;
                }
            }
        }
        $_SESSION['user_info']=$infoArr;
        return true;        
    }
    function getAll($conn){//login get all info
        //get id
        $sql = "SELECT * FROM user_all where user_accounts!='system'";
        $result = $conn->query($sql) or die(mysqli_error($conn));
        $num = $result->num_rows;
        $_SESSION['all_user']=array();
        if($num>=1){
            foreach ($result as $row){
                $arr=array();
                $arr['id']=$row['user_id'];
                $arr['accounts']=$row['user_accounts'];
                $arr['passwords']=$row['user_passwords'];
                array_push($_SESSION['all_user'],$arr);
            }
        }
        else return false;
        return true;        
    }
    function getUserInfo($conn,$id){
        //get else
        $infoArr=array();
        $sql = "SELECT * FROM user_$id";
        $result = $conn->query($sql) or die(mysqli_error($conn));
        $num = $result->num_rows;
        if($num>0){
            $infoArr['id']=$id;
            foreach ($result as $row){
                switch($row['type']){
                    case 'words':
                        $infoArr[$row['name']]=$row['words'];
                        break;
                    case 'pics':
                        $infoArr[$row['name']]='data:image/jpeg;base64,'.base64_encode($row['pics']);
                        break;
                }
            }
        }
        $_SESSION['user_info']=$infoArr;
    }
    function registerUser($conn,$user, $passwords){//register
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
        return 1;
    }
?>