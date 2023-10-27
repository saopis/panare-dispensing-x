<?php 
while(list($xVarName, $xVarvalue) = each($_GET)) {
     ${$xVarName} = $xVarvalue;
}
 
 
while(list($xVarName, $xVarvalue) = each($_POST)) {
     ${$xVarName} = $xVarvalue;
}
 
while(list($xVarName, $xVarvalue) = each($_FILES)) {
     ${$xVarName."_name"} = $xVarvalue['name'];
     ${$xVarName."_type"} = $xVarvalue['type'];
     ${$xVarName."_size"} = $xVarvalue['size'];
     ${$xVarName."_error"} = $xVarvalue['error'];
     ${$xVarName} = $xVarvalue['tmp_name'];
}

function urlExists($url=NULL)
    {
        if($url == NULL) return false;
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $data = curl_exec($ch);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch); 
        if($httpcode>=200 && $httpcode<300){
            return true;
        } else {
            return false;
        }
    }

//กรณีแยกก้อนฐานข้อมูล
$database_kohrx = "dispensing";
?>