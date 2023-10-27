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
*/

            for($i=943;$i<=(943+334);$i++){
             for($j=1;$j<=6;$j++){
				$sn="1096714122021".str_pad(($i-407), 3, '0', STR_PAD_LEFT);
                $vaccine_number=$i;
                mysql_select_db($database_hos, $hos);
                $query_rs = "insert into dispensing.kohrx_vaccine_record (person_vaccine_id,vaccine_lot_no,vaccine_exp,vaccine_number,vaccine_dose_number,vaccine_receive_date,vaccine_receive_time,vaccine_order,vaccine_sn,url) value (76,'1I060A','03/01/2022','".$vaccine_number."','".$j."','2021-12-14','15:00:00','1','".$sn."','http://159.192.104.60/organization/pharmacy/service/dispensingx/vaccine_record.php')";
                $rs = mysql_query($query_rs, $hos) or die(mysql_error());
             }
        }    
    

?>
