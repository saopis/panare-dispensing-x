<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<?php include('java_css_file.php'); ?>    

<script>
function cal(){
	sumunit = $('#method').val().split("-");
	alert("ใช้ Insulin ทังหมด ="+(((parseInt(sumunit[0])+parseInt(sumunit[1])+parseInt(sumunit[2]))*$('#app').val())/300)+" หลอด ");
	}
$(document).ready(function($){ 
  	$("#method").mask("99-99-99");   
	});

</script>
</head>

<body>
<form id="form1" name="form1" method="post" action="">
<table width="300" border="0" align="center" cellpadding="3" cellspacing="0">
    <tr>
      <td colspan="2" bgcolor="#FFCC80"><span class="big_red16">คำนวณ Insulin 300 u</span></td>
    </tr>
    <tr>
      <td width="111" bgcolor="#FFCC80">วิธีฉีด(xx-xx-xx)</td>
      <td width="189" bgcolor="#FFCC80"><input type="text" name="method" id="method" /></td>
    </tr>
    <tr>
      <td bgcolor="#FFCC80">จำนวนวันนัด</td>
      <td bgcolor="#FFCC80"><input name="app" type="text" id="app" value="<?php echo $appdate; ?>" /> 
        วัน</td>
    </tr>
    <tr>
      <td bgcolor="#FF6600">&nbsp;</td>
      <td bgcolor="#FF6600"><input type="button" name="button" id="button" value="คำนวณ" onclick="cal()" /></td>
    </tr>
  </table>
</form>
<p>&nbsp;</p>
</body>
</html>