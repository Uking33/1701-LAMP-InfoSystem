<?php
    //random code number picture
    if(!isset($_SESSION)) session_start();
    getCode(4,60,20);
    function getCode($num,$w,$h)
    {
    	$code = "";
        for ($i = 0; $i < $num; $i++) {
        	$code .= rand(0, 9);
        }
        $_SESSION["codeNum"] = $code;
        header("Content-type: image/PNG");
        $im = imagecreate($w, $h);
        $black = imagecolorallocate($im, 0, 0, 0);
        $gray = imagecolorallocate($im, 200, 200, 200);
        $bgcolor = imagecolorallocate($im, 255, 255, 255);
        imagefill($im, 0, 0, $gray);
        imagerectangle($im, 0, 0, $w-1, $h-1, $black);

        $style = array ($black,$black,$black,$black,$black, $gray,$gray,$gray,$gray,$gray);
        imagesetstyle($im, $style);
        $y1 = rand(0, $h);
        $y2 = rand(0, $h);
        $y3 = rand(0, $h);
        $y4 = rand(0, $h);
        imageline($im, 0, $y1, $w, $y3, IMG_COLOR_STYLED);
        imageline($im, 0, $y2, $w, $y4, IMG_COLOR_STYLED);
        for ($i = 0; $i < 80; $i++){
            imagesetpixel($im, rand(0, $w), rand(0, $h), $black);
        }
        $strx = rand(3, 8);
        for ($i = 0; $i < $num; $i++) {
            $strpos = rand(1, 6);
            imagestring($im, 5, $strx, $strpos, substr($code, $i, 1), $black);
            $strx += rand(8, 12);
        }
        
        imagepng($im);
        imagedestroy($im);
    }
?>