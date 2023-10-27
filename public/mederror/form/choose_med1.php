<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<script src="../../include/jquery.js" type="text/javascript"></script>
<script  src="../../include/ajax_framework.js"></script>
<script language="javascript">
function GetValueQueryString  (key, default_)
{
  if (default_==null) default_="";
  key = key.replace(/[\[]/,"\\\[").replace(/[\]]/,"\\\]");
  var regex = new RegExp("[\\?&]"+key+"=([^&#]*)");
  var qs = regex.exec(window.location.href);
  if(qs == null)
    return default_;
  else
    return qs[1];
}
var getdata=GetValueQueryString("getdata");
var data3=GetValueQueryString("data2");

function formSubmit(displayDiv,indicator) {
	var URL = "choose_med2.php?getdata="+getdata+"&data3="+data3+"&data4=0";
	var data = getFormData("form1");
	ajaxLoad('post', URL, data, 'displayDiv','indicator');
	var e = document.getElementById('indicator');
	e.style.display = 'block';
	//$(document).ready(function(){parent.$.fn.colorbox.close()});	
	}
		

var data_name=GetValueQueryString("getdata");

function lotData(s_id,s_name)
	{	
		if(data_name==1){
		var typeid = self.opener.document.getElementById("med_error_type");
		typeid.value = s_id;
		
		var typename = self.opener.document.getElementById("med_error_type_name");
		typename.value = s_name;

		var typeid = self.opener.document.getElementById("cause_id");
		typeid.value = '';
		
		var typename = self.opener.document.getElementById("cause");
		typename.value = '';
		
		var typeid = self.opener.document.getElementById("sub_id");
		typeid.value = '';
		
		var typename = self.opener.document.getElementById("sub");
		typename.value = '';
		

		}
		if(data_name==2){
		var typeid = self.opener.document.getElementById("cause_id");
		typeid.value = s_id;
		
		var typename = self.opener.document.getElementById("cause");
		typename.value = s_name;
		
		var typeid = self.opener.document.getElementById("sub_id");
		typeid.value = '';
		
		var typename = self.opener.document.getElementById("sub");
		typename.value = '';


		}
		if(data_name==3){
		var typeid = self.opener.document.getElementById("sub_id");
		typeid.value = s_id;
		
		var typename = self.opener.document.getElementById("sub");
		typename.value = s_name;
		}
		
		if(data_name==4){
		var provid = self.opener.document.getElementById("driver_license_out_by");
		provid.value = s_id;
		
		var provname = self.opener.document.getElementById("chwname2");
		provname.value = s_name;
		}
		if(data_name==5){
		var provid = self.opener.document.getElementById("paties_license_out_by");
		provid.value = s_id;
		
		var provname = self.opener.document.getElementById("paties_license_out_by_name");
		provname.value = s_name;
		}
		window.close();
	}
</script>
</head>

<body onload="formSubmit('displayDiv','indicator')">
<form id="form1" name="form1" method="post" action="">
  <table width="300" border="0" cellpadding="3" cellspacing="0" class="table_head_small">
    <tr>
      <td width="37">ค้นหา 
      <input type="text" name="search" id="search" onkeyup="formSubmit('displayDiv','indicator')"/></td>
    </tr>
    <tr>
      <td><div id="indicator"  align="center" style="position:absolute; display:none; z-index:1000;padding:0px;"> <img src="../images/indicator.gif" hspace="10" align="absmiddle" />&nbsp;</div><div id="displayDiv">&nbsp;</div></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
    </tr>
  </table>
</form>
</body>
</html>