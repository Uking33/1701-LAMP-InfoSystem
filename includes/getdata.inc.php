<?php 
    if(!isset($_SESSION)) session_start();
    function tr($str){//transfer
        return mb_convert_encoding ($str,'UTF-8','GB2312');
    }
    //load data
    function getWords($name){
        if(array_key_exists($name, $_SESSION['user_info']))
            return $_SESSION['user_info'][$name];
        else
            return "";
    }
    function getPic($name){
        if(array_key_exists($name, $_SESSION['user_info'])){
            //$type='image/jpeg';
            //$base64=base64_encode($_SESSION['user_info'][$name]);
            //return 'data:'.$type.';base64,'.$base64;
            return $_SESSION['user_info'][$name];
        }
        else
            return "";
    }
?>