<?php require_once('../Connections/hos.php'); 

function doctorname($doctor){
    global $hos;
    mysql_select_db($database_hos, $hos);
    $query_rs_doctor = "SELECT name from doctor where code='".$doctor."'";
    $rs_doctor = mysql_query($query_rs_doctor, $hos) or die(mysql_error());
    $row_rs_doctor = mysql_fetch_assoc($rs_doctor);    
    
    $doctorname= $row_rs_doctor['name'];
    
    mysql_free_result($rs_doctor);
    return $doctorname;
}
?>