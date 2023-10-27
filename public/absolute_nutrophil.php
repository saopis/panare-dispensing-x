<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<?php include('java_css_file.php'); ?>    
<script>
function anc(){
	var wbc=document.getElementById('wbc').value;
	var n=document.getElementById('neutrophil').value;
	var result=document.getElementById('result');
		
		result.value=(wbc*n)/100;
	}
</script>
</head>

<body>
<nav class="navbar navbar-dark bg-info text-white " >
  <!-- Navbar content -->
  <span class="card-title font_bord"  >&ensp;Absolute Neutrophil Count</span>
</nav>
<div class="p-2">
<form id="form1" name="form1" method="post" action="">
  <table width="500" border="0" cellpadding="2" cellspacing="0" class="table_head_small">
    <tr>
      <td width="138">total WBC</td>
      <td width="362"><input name="wbc" type="text" class="table_head_small" id="wbc" onkeyup="anc()"/></td>
    </tr>
    <tr>
      <td>% Neutrophil</td>
      <td><input name="neutrophil" type="text" class="table_head_small" id="neutrophil" onkeyup="anc()" /></td>
    </tr>
    <tr>
      <td>ผลลัพธ์</td>
      <td><input name="result" type="text" class="table_head_small" id="result" readonly="readonly" /></td>
    </tr>
  </table>
</form>
<div> </div>
<div id="post-body-753950870319179818" itemprop="description articleBody">
  <p><br />
    <span class="head_small_gray">คือจำนวนของ WBC ชนิด neutrophill granulocyte ที่อยู่ในกระแสเลือด โดยจะคิดจาก neutrophil ที่เป็น % รวมกับจำนวน band (immature neutrophil) ที่เป็น % เช่นกัน<br />
    <br />
    โดยปกติ จำนวน ANC จะมีมากกว่า 1500 / micro L<br />
    ถ้ามีค่า  ANC &lt; 500 = Neutropenia ซึ่งคนไข้จะมีความเสี่ยงต่อการติดเชื้อสูง<br />
    <br />
    สูตรคำนวน<br />
    <strong>ANC = (%neutrophil  x WBC)/ 100</strong><br />
    <br />
    NCI Risk CategoryANC<br />
    0 = Within normal limits<br />
    1 = ≥1500 - &lt;2000/mm³<br />
    2 = ≥1000 - &lt;1500/mm³<br />
    3 = ≥500 - &lt;1000/mm³<br />
    4 = &lt; 500/mm³</span></p>
  <p class="big_red16">** ถ้า ANC&lt;1,500&nbsp; และ WBC &lt; 3,000 กรณีผู้ป่วยได้รับ Clozapine ให้หยุดยาทันที</p>
</div>
</div>
</body>
</html>