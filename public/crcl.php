<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<?php include('java_css_file.php'); ?>    
<script>
function crcl(){

	var factor_sex=$('#sex:checked').val();
	var result=(((140-document.getElementById('age').value)*document.getElementById('bw').value)/(72*document.getElementById('cr').value))*factor_sex;
	document.getElementById('ecrcl').value=result.toFixed(2);	
	}
</script>
</head>

<body>
<nav class="navbar navbar-dark bg-info text-white " >
  <!-- Navbar content -->
  <span class="card-title font_bord"  >&ensp;คำนวณหา Creatinin Clearance</span>
</nav>
<div class="p-2">
<p>Estimated Creatinine Clearanceการคำนวนหาการทำงานของไตเรียก Creatinine Clearance ซึ่งคำนวนได้โดยการกรอกข้อมูลลงในตาราง กรอก น้ำหนัก เพศ อายุ ค่า creatinin ในเลือด(ได้จากการเจาะเลือด) <span class="table_head_small"><span class="big_red16">ค่าปกติผู้ชาย 97-137&nbsp; ml/min&nbsp;&nbsp;,&nbsp; ผู้หญิง&nbsp; &nbsp;88-128&nbsp; &nbsp;ml/min</span></span></p>
<form id="form1" name="form1" method="post" action="">
  <table width="600" border="0" align="center" cellpadding="4" cellspacing="0">
    <tr>
      <td width="214" align="right" bgcolor="#BFCDDB">น้ำหนัก</td>
      <td width="370" bgcolor="#BFCDDB"><input name="bw" type="text" id="bw" size="10" /> 
      กก.</td>
    </tr>
    <tr>
      <td align="right" bgcolor="#BFCDDB">อายุ</td>
      <td bgcolor="#BFCDDB"><input name="age" type="text" id="age" size="10" /> 
        ปี</td>
    </tr>
    <tr>
      <td align="right" bgcolor="#BFCDDB">เพศ</td>
      <td bgcolor="#BFCDDB"><input name="sex" type="radio" id="sex" value="1" checked="checked" />
        ชาย 
        <input type="radio" name="sex" id="sex" value="0.85" />
        หญิง</td>
    </tr>
    <tr>
      <td align="right" bgcolor="#BFCDDB">Serum Creatinine</td>
      <td bgcolor="#BFCDDB"><input name="cr" type="text" id="cr" size="10" /> 
        mg/dl</td>
    </tr>
    <tr>
      <td align="right" bgcolor="#BFCDDB">&nbsp;</td>
      <td bgcolor="#BFCDDB"><input type="button" name="button" id="button" value="คำนวณ"  onclick="crcl()"/></td>
    </tr>
    <tr>
      <td colspan="2" align="center" bgcolor="#698AAB" class="table_head_small_white">Estimated Creatinine Clearance = 
        <input name="ecrcl" type="text" id="ecrcl" /> 
        ml/min</td>
    </tr>
    <tr>
      <td colspan="2" align="left" class="table_head_small_white"><p class="table_head_small">Note: การคำนวนนี้จะใช้ในกรณีที่ค่า creatinin คงที่</p>
        <p class="table_head_small">Remember:สูตรการคำนวน<br />
          <strong>Est. Creatinine Clearance = [[140 - age(yr)]*weight(kg)]/[72*serum Cr(mg/dL)]</strong> <br />
      <em>(multiply by 0.85 for women)<br />
      </em></p></td>
    </tr>
  </table>
</form>
</div>
</body>
</html>