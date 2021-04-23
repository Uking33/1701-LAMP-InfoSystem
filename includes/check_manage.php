<?php
    require_once('connection.inc.php');
    $type="";
    $id="";
    foreach ($_POST as $key=>$value) {
        $str=substr($key,0,4);
        if($str=='show' || $str=='edit' || $str=='dele'){
            $type=substr($key,0,4);
            $id=substr($key,4);
            break;
        }
    }
    switch($type){
        case 'show':
        case 'edit':
            showItem($id);
            break;
        case 'dele':
            delItem($id);
            break;
    }
    function showItem($id){
        $conn = dbConnect('read');
        getUserInfo($conn,$id);
    }
    function delItem($id){
        $conn = dbConnect('write');
        deleteUser($conn,$id);
        foreach ($_SESSION['all_user'] as $key=>$arr){
            if($arr['id']==$id){
                unset($_SESSION['all_user'][$key]);
                break;
            }
                
        }
    }
?>  