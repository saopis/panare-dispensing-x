<?php require_once('Connections/hos.php'); ?>

<?php
shell_exec('start calc');
?>
<?php
/*
  // Set headers
  header("Cache-Control: public");
  header("Content-Description: File Transfer");
  header("Content-Disposition: attachment; filename=batch-file.bat");
  header("Content-Type: application/bat");
  header("Content-Transfer-Encoding: ASCII");
    
  // Read the file from disk
  ?>
	 
  @Echo off
  echo.
  echo.
  echo.
  echo      Script wird geöffnet...
    Del batch-file.bat
  EXIT

<?php

$i;
while($i<=62) {
	echo "$i\n";
	$i++;
}


  //moderna
            for($i=175;$i<=(175+18);$i++){
             for($j=1;$j<=15;$j++){
				$sn="M1096720062022".str_pad(($i), 3, '0', STR_PAD_LEFT);
                $vaccine_number=$i;
                mysql_select_db($database_hos, $hos);
                $query_rs = "insert into dispensing.kohrx_vaccine_record (person_vaccine_id,vaccine_lot_no,vaccine_exp,vaccine_number,vaccine_dose_number,vaccine_receive_date,vaccine_receive_time,vaccine_order,vaccine_sn,url) value (117,'940913','14/07/2022','".$vaccine_number."','".$j."','2022-06-17','15:00:00','1','".$sn."','http://159.192.104.60/organization/pharmacy/service/dispensingx/vaccine_record.php')";
                $rs = mysql_query($query_rs, $hos) or die(mysql_error());
             }
        }    

  //pfizer
            for($i=5887;$i<=(5887+66);$i++){
             for($j=1;$j<=6;$j++){
				$sn="1096702062022".str_pad(($i), 4, '0', STR_PAD_LEFT);
                $vaccine_number=$i;
                mysql_select_db($database_hos, $hos);
                $query_rs = "insert into dispensing.kohrx_vaccine_record (person_vaccine_id,vaccine_lot_no,vaccine_exp,vaccine_number,vaccine_dose_number,vaccine_receive_date,vaccine_receive_time,vaccine_order,vaccine_sn,url) value (76,'1L085A','26/06/2022','".$vaccine_number."','".$j."','2022-06-02','16:00:00','1','".$sn."','http://159.192.104.60/organization/pharmacy/service/dispensingx/vaccine_record.php')";
                $rs = mysql_query($query_rs, $hos) or die(mysql_error());
             }
            } 
      	

        
      //pfizerเด็ก
            for($i=723;$i<=(723+19);$i++){
             for($j=1;$j<=10;$j++){
				$sn="1096717062022".str_pad(($i), 4, '0', STR_PAD_LEFT);
                $vaccine_number=$i;
                mysql_select_db($database_hos, $hos);
                $query_rs = "insert into dispensing.kohrx_vaccine_record (person_vaccine_id,vaccine_lot_no,vaccine_exp,vaccine_number,vaccine_dose_number,vaccine_receive_date,vaccine_receive_time,vaccine_order,vaccine_sn,url) value (76,'FR4268','28/07/2022','".$vaccine_number."','".$j."','2022-06-17','16:00:00','1','".$sn."','http://159.192.104.60/organization/pharmacy/service/dispensingx/vaccine_record.php')";
                $rs = mysql_query($query_rs, $hos) or die(mysql_error());
             }
        }
*/
	//covavax
            for($i=1;$i<=(1+9);$i++){
             for($j=1;$j<=10;$j++){
				$sn="1096702062022".str_pad(($i), 3, '0', STR_PAD_LEFT);
                $vaccine_number=$i;
                mysql_select_db($database_hos, $hos);
                $query_rs = "insert into dispensing.kohrx_vaccine_record (person_vaccine_id,vaccine_lot_no,vaccine_exp,vaccine_number,vaccine_dose_number,vaccine_receive_date,vaccine_receive_time,vaccine_order,vaccine_sn,url) value (124,'4302MF007','30/09/2022','".$vaccine_number."','".$j."','2022-06-02','16:00:00','1','".$sn."','http://159.192.104.60/organization/pharmacy/service/dispensingx/vaccine_record.php')";
                $rs = mysql_query($query_rs, $hos) or die(mysql_error());
             }
        }
     
?>
