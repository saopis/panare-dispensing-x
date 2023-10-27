<?php
function vn2hn($vn){
	if($vn!=""){
    global $hos;
    mysql_select_db($database_hos, $hos);
    $query_rs_doctor = "SELECT hn from vn_stat where vn='".$vn."'";
    $rs_doctor = mysql_query($query_rs_doctor, $hos) or die(mysql_error());
    $row_rs_doctor = mysql_fetch_assoc($rs_doctor);    
    
    $f_hn= $row_rs_doctor['hn'];
    
    mysql_free_result($rs_doctor);
    return $f_hn;
	}
}
function ptname($hn){
	if($hn!=""){
    global $hos;
    mysql_select_db($database_hos, $hos);
    $query_rs_doctor = "SELECT concat(pname,fname,' ',lname) as ptname from patient where hn='".$hn."'";
    $rs_doctor = mysql_query($query_rs_doctor, $hos) or die(mysql_error());
    $row_rs_doctor = mysql_fetch_assoc($rs_doctor);    
    
    $ptname= $row_rs_doctor['ptname'];
    
    mysql_free_result($rs_doctor);
    return $ptname;
	}
}
function ptnameVn($vn){
	if($vn!=""){
    global $hos;
    mysql_select_db($database_hos, $hos);
    $query_rs_doctor = "SELECT concat(pname,fname,' ',lname) as ptname from vn_stat v left outer join patient p on p.hn=v.hn where vn='".$vn."'";
    $rs_doctor = mysql_query($query_rs_doctor, $hos) or die(mysql_error());
    $row_rs_doctor = mysql_fetch_assoc($rs_doctor);    
    
    $ptname2= $row_rs_doctor['ptname'];
    
    mysql_free_result($rs_doctor);
    return $ptname2;
	}
}
function vnVstdate($vn){
	if($vn!=""){
    global $hos;
    mysql_select_db($database_hos, $hos);
    $query_rs_doctor = "SELECT vstdate from vn_stat v where vn='".$vn."'";
    $rs_doctor = mysql_query($query_rs_doctor, $hos) or die(mysql_error());
    $row_rs_doctor = mysql_fetch_assoc($rs_doctor);    
    
    $f_vstdate= $row_rs_doctor['vstdate'];
    
    mysql_free_result($rs_doctor);
    return $f_vstdate;
	}
}
function doctorname($code){
	if($code!=""||$code!=NULL){
    global $hos;
    mysql_select_db($database_hos, $hos);
    $query_rs_doctor = "SELECT name from doctor where code='".$code."'";
    $rs_doctor = mysql_query($query_rs_doctor, $hos) or die(mysql_error());
    $row_rs_doctor = mysql_fetch_assoc($rs_doctor);    
    
    $doctorname= $row_rs_doctor['name'];
    
    mysql_free_result($rs_doctor);
    return $doctorname;
	}
}

function drugname($icode){
	if($icode!=""){
    global $hos;
    mysql_select_db($database_hos, $hos);
    $query_rs_doctor = "SELECT concat(name,' ',strength) as drugname from drugitems where icode='".$icode."'";
    $rs_doctor = mysql_query($query_rs_doctor, $hos) or die(mysql_error());
    $row_rs_doctor = mysql_fetch_assoc($rs_doctor);    
    
    $drugname= $row_rs_doctor['drugname'];
    
    mysql_free_result($rs_doctor);
    return $drugname;
	}
}
function drp_cause($code){
	if($code!=""){
    global $hos;
    mysql_select_db($database_hos, $hos);
    $query_rs_doctor = "SELECT * from drp_cause where drp_cause_id='".$code."'";
    $rs_doctor = mysql_query($query_rs_doctor, $hos) or die(mysql_error());
    $row_rs_doctor = mysql_fetch_assoc($rs_doctor);    
    
    $name= $row_rs_doctor['std_code'];
    
    mysql_free_result($rs_doctor);
    return $name;
	}
}
function drp_cause_name($code){
	if($code!=""){
    global $hos;
    mysql_select_db($database_hos, $hos);
    $query_rs_doctor = "SELECT * from drp_cause where drp_cause_id='".$code."'";
    $rs_doctor = mysql_query($query_rs_doctor, $hos) or die(mysql_error());
    $row_rs_doctor = mysql_fetch_assoc($rs_doctor);    
    
    $name= $row_rs_doctor['drp_cause_name'];
    
    mysql_free_result($rs_doctor);
    return $name;
	}
}
function interventionname($code){
	if($code!=""){
    global $hos;
    mysql_select_db($database_hos, $hos);
    $query_rs_doctor = "SELECT * from drp_intervention_type where drp_intervention_type_id='".$code."'";
    $rs_doctor = mysql_query($query_rs_doctor, $hos) or die(mysql_error());
    $row_rs_doctor = mysql_fetch_assoc($rs_doctor);    
    
    $name= $row_rs_doctor['drp_intervention_type_name'];
    
    mysql_free_result($rs_doctor);
    return $name;
	}
}
function outcomename($code){
	if($code!=""){
    global $hos;
    mysql_select_db($database_hos, $hos);
    $query_rs_doctor = "SELECT * from drp_outcome_type where drp_outcome_type_id='".$code."'";
    $rs_doctor = mysql_query($query_rs_doctor, $hos) or die(mysql_error());
    $row_rs_doctor = mysql_fetch_assoc($rs_doctor);    
    
    $name= $row_rs_doctor['drp_outcome_type_name'];
    
    mysql_free_result($rs_doctor);
    return $name;
	}
}
function diag($code){
	if($code!=""){
    global $hos;
    mysql_select_db($database_hos, $hos);
    $query_rs_doctor = "SELECT name from icd101 where code='".$code."'";
    $rs_doctor = mysql_query($query_rs_doctor, $hos) or die(mysql_error());
    $row_rs_doctor = mysql_fetch_assoc($rs_doctor);    
    
    $name= $row_rs_doctor['name'];
    
    mysql_free_result($rs_doctor);
    return $name;
	}
}

function getChw(){
    global $hos;
    mysql_select_db($database_hos, $hos);
    $query_rs = "SELECT h.chwpart from opdconfig t left outer join hospcode h on h.hospcode=t.hospitalcode";
    $rs = mysql_query($query_rs, $hos) or die(mysql_error());
    $row_rs = mysql_fetch_assoc($rs);    
    
    $name= $row_rs['chwpart'];
    
    mysql_free_result($rs);
    return $name;
	
}
function getHospname(){
    global $hos;
    mysql_select_db($database_hos, $hos);
    $query_rs = "SELECT hospitalname from opdconfig ";
    $rs = mysql_query($query_rs, $hos) or die(mysql_error());
    $row_rs = mysql_fetch_assoc($rs);    
    
    $name= $row_rs['hospitalname'];
    
    mysql_free_result($rs);
    return $name;
	
}
function pttypename($pttype){
    global $hos;
    mysql_select_db($database_hos, $hos);
    $query_rs = "SELECT name from pttype where pttype='".$pttype."' ";
    $rs = mysql_query($query_rs, $hos) or die(mysql_error());
    $row_rs = mysql_fetch_assoc($rs);    
    
    $name= $row_rs['name'];
    
    mysql_free_result($rs);
    return $name;
	
}
function respondentname($cc){
	if($cc!=""){	
    global $hos;
    mysql_select_db($database_hos, $hos);
    $query_rs = "SELECT respondent from ".$database_kohrx.".kohrx_adr_check_respondent where id='".$cc."' ";
    $rs = mysql_query($query_rs, $hos) or die(mysql_error());
    $row_rs = mysql_fetch_assoc($rs);    
    
    $name= $row_rs['respondent'];
    
    mysql_free_result($rs);
    return $name;
	
	}
	
}
function answername($cc){
	if($cc!=""){	
    global $hos;
    mysql_select_db($database_hos, $hos);
    $query_rs = "SELECT answer from ".$database_kohrx.".kohrx_adr_check_answer where id='".$cc."' ";
    $rs = mysql_query($query_rs, $hos) or die(mysql_error());
    $row_rs = mysql_fetch_assoc($rs);    
    
    $name= $row_rs['answer'];
    
    mysql_free_result($rs);
    return $name;
	
	}
	
}
function name_error_type($cc){
	if($cc!=""){	
    global $hos;
    mysql_select_db($database_hos, $hos);
    $query_rs = "SELECT type_thai as name from ".$database_kohrx.".kohrx_med_error_error_type where id='".$cc."' ";
    $rs = mysql_query($query_rs, $hos) or die(mysql_error());
    $row_rs = mysql_fetch_assoc($rs);    
    
    $name= $row_rs['name'];
    
    mysql_free_result($rs);
    return $name;
	
	}
	
}
function name_error_cause($cc){
	if($cc!=""){	
    global $hos;
    mysql_select_db($database_hos, $hos);
    $query_rs = "SELECT name from ".$database_kohrx.".kohrx_med_error_error_cause where id='".$cc."' ";
    $rs = mysql_query($query_rs, $hos) or die(mysql_error());
    $row_rs = mysql_fetch_assoc($rs);    
    
    $name= $row_rs['name'];
    
    mysql_free_result($rs);
    return $name;
	
	}
	
}
function name_error_subtype($cc){
	if($cc!=""){	
    global $hos;
    mysql_select_db($database_hos, $hos);
    $query_rs = "SELECT sub_name as name from ".$database_kohrx.".kohrx_med_error_error_sub_cause where id='".$cc."' ";
    $rs = mysql_query($query_rs, $hos) or die(mysql_error());
    $row_rs = mysql_fetch_assoc($rs);    
    
    $name= $row_rs['name'];
    
    mysql_free_result($rs);
    return $name;
	
	}
	
}

?>