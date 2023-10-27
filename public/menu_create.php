<?php require_once('Connections/hos.php'); ?>

<?php
if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  if (PHP_VERSION < 6) {
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
  }

  $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}
}

if($order!=""){
if($_GET['menu_type']==""){
$table="kohrx_main_menu";
}
if($_GET['menu_type']=="main"){
$table="kohrx_sub_menu";
$condition="main_menu_id='$id' and";
}
if($_GET['menu_type']=="sub_menu"){
$table="kohrx_sub_menu2";
$condition="sub_menu_id='$id' and";
}
if($_GET['menu_type']=="sub_menu2"){
$table="kohrx_sub_menu3";
$condition="sub_menu2_id='$id' and";
}
if($_GET['method']=="down"){
mysql_select_db($database_hos, $hos);
$query_rs_order = "SELECT * from  ".$database_kohrx.".$table where ".$condition." sort_order>'$order' order by sort_order ASC limit 1 ";
$rs_order = mysql_query($query_rs_order, $hos) or die(mysql_error());
$row_rs_order = mysql_fetch_assoc($rs_order);
$totalRows_rs_order = mysql_num_rows($rs_order);


mysql_select_db($database_hos, $hos);
$query_update = "update ".$database_kohrx.".$table set sort_order=(".$row_rs_order['sort_order']."-1) where id='$row_rs_order[id]'";
$rs_update = mysql_query($query_update, $hos) or die(mysql_error());

mysql_select_db($database_hos, $hos);
$query_update = "update ".$database_kohrx.".$table set sort_order=(".$order."+1) where id='$id2'";
$rs_update = mysql_query($query_update, $hos) or die(mysql_error());

if($_GET['menu_type']==""){
	echo "<meta http-equiv=\"refresh\" content=\"0;URL=menu_create.php\" />";
	exit();
}

}
if($_GET['method']=="up"){
mysql_select_db($database_hos, $hos);
$query_rs_order = "SELECT * from  ".$database_kohrx.".$table where ".$condition." sort_order<'$order' order by sort_order DESC limit 1 ";
$rs_order = mysql_query($query_rs_order, $hos) or die(mysql_error());
$row_rs_order = mysql_fetch_assoc($rs_order);
$totalRows_rs_order = mysql_num_rows($rs_order);

mysql_select_db($database_hos, $hos);
$query_update = "update ".$database_kohrx.".$table set sort_order=(".$row_rs_order['sort_order']."+1) where id='$row_rs_order[id]'";
$rs_update = mysql_query($query_update, $hos) or die(mysql_error());

mysql_select_db($database_hos, $hos);
$query_update = "update ".$database_kohrx.".$table set sort_order=(".$order."-1) where id='$id2'";
$rs_update = mysql_query($query_update, $hos) or die(mysql_error());

if($_GET['menu_type']==""){
	echo "<meta http-equiv=\"refresh\" content=\"0;URL=menu_create.php\" />";
exit();
}
}
mysql_free_result($rs_order);
}

if(isset($_POST['save'])&&($_POST['save']=="บันทึก")){

if($link_type==5){
	if(trim($_FILES["fileUpload"]["tmp_name"]) != "")
{
$length = 10;

$randomString = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, $length);

		$images = $_FILES["fileUpload"]["tmp_name"];
		$ext = pathinfo($_FILES["fileUpload"]["name"], PATHINFO_EXTENSION);
		$filename=$randomString.".".$ext;
		copy($_FILES["fileUpload"]["tmp_name"],"upload/".$filename);
	}
chmod("upload/".$filename, 0755);
}

mysql_select_db($database_hos, $hos);
if($menu_type2==""){
$table="main";
mysql_select_db($database_hos, $hos);
$query_rs_order = "SELECT * from  ".$database_kohrx.".kohrx_main_menu order by sort_order DESC limit 1 ";
$rs_order = mysql_query($query_rs_order, $hos) or die(mysql_error());
$row_rs_order = mysql_fetch_assoc($rs_order);
$totalRows_rs_order = mysql_num_rows($rs_order);

$query_insert = "insert into ".$database_kohrx.".kohrx_main_menu (menu_name,link_type,link,width,height,sort_order,menu_text,file_link) value ('".$_POST['name']."','".$_POST['link_type']."','".$_POST['link']."','".$_POST['width']."','".$_POST['height']."',(".$row_rs_order['sort_order']."+1),'".$_POST['text']."','".$filename."')";

}
if($menu_type2=="main"){
$table="sub_menu";

mysql_select_db($database_hos, $hos);
$query_rs_order = "SELECT * from  ".$database_kohrx.".kohrx_sub_menu where main_menu_id='$id2' order by sort_order DESC limit 1 ";
$rs_order = mysql_query($query_rs_order, $hos) or die(mysql_error());
$row_rs_order = mysql_fetch_assoc($rs_order);
$totalRows_rs_order = mysql_num_rows($rs_order);


if($totalRows_rs_order==0){
$row_rs_order['sort_order']=0;	
	}

$query_insert = "insert into ".$database_kohrx.".kohrx_sub_menu (main_menu_id,sub_menu_name,link_type,link,width,height,sort_order,menu_text,file_link) value ('$id2','".$_POST['name']."','".$_POST['link_type']."','".$_POST['link']."','".$_POST['width']."','".$_POST['height']."',(".$row_rs_order['sort_order']."+1),'".$_POST['text']."','".$filename."')";
}
if($menu_type2=="sub_menu"){
$table="sub_menu2";

mysql_select_db($database_hos, $hos);
$query_rs_order = "SELECT * from  ".$database_kohrx.".kohrx_sub_menu2 where sub_menu_id='$id2' order by sort_order DESC limit 1 ";
$rs_order = mysql_query($query_rs_order, $hos) or die(mysql_error());
$row_rs_order = mysql_fetch_assoc($rs_order);
$totalRows_rs_order = mysql_num_rows($rs_order);


if($totalRows_rs_order==0){
$row_rs_order['sort_order']=0;	
	}
$query_insert = "insert into ".$database_kohrx.".kohrx_sub_menu2 (sub_menu_id,sub_menu2_name,link_type,link,width,height,sort_order,menu_text,file_link) value ('$id2','".$_POST['name']."','".$_POST['link_type']."','".$_POST['link']."','".$_POST['width']."','".$_POST['height']."',(".$row_rs_order['sort_order']."+1),'".$_POST['text']."','".$filename."')";
}

if($menu_type2=="sub_menu2"){
$table="sub_menu3";

mysql_select_db($database_hos, $hos);
$query_rs_order = "SELECT * from  ".$database_kohrx.".kohrx_sub_menu3 where sub_menu2_id='$id2' order by sort_order DESC limit 1 ";
$rs_order = mysql_query($query_rs_order, $hos) or die(mysql_error());
$row_rs_order = mysql_fetch_assoc($rs_order);
$totalRows_rs_order = mysql_num_rows($rs_order);

if($totalRows_rs_order==0){
$row_rs_order['sort_order']=0;	
	}
$query_insert = "insert into ".$database_kohrx.".kohrx_sub_menu3 (sub_menu2_id,sub_menu3_name,link_type,link,width,height,sort_order,menu_text,file_link) value ('$id2','".$_POST['name']."','".$_POST['link_type']."','".$_POST['link']."','".$_POST['width']."','".$_POST['height']."',(".$row_rs_order['sort_order']."+1),'".$_POST['text']."','".$filename."')";
}

$rs_insert = mysql_query($query_insert, $hos) or die(mysql_error());
mysql_free_result($rs_order);


echo "<meta http-equiv=\"refresh\" content=\"0;URL=".$_GET['link']."\" />";
	}

mysql_select_db($database_hos, $hos);
$query_rs_link_type = "SELECT * from  ".$database_kohrx.".kohrx_menu_link_type";
$rs_link_type = mysql_query($query_rs_link_type, $hos) or die(mysql_error());
$row_rs_link_type = mysql_fetch_assoc($rs_link_type);
$totalRows_rs_link_type = mysql_num_rows($rs_link_type);

mysql_select_db($database_hos, $hos);
if(!isset($_GET['menu_type'])&&($_GET['menu_type']=="")){
	$menu="main";
$query_rs_main_menu = "SELECT m.*,l.link_type_name FROM ".$database_kohrx.".kohrx_main_menu m left outer join ".$database_kohrx.".kohrx_menu_link_type l on l.id=m.link_type  ORDER BY m.sort_order ASC";
}

if(isset($_GET['menu_type'])&&($_GET['menu_type']=="main")){
		$menu="sub_menu";
$query_rs_main_menu = "SELECT m.sort_order ,m.sub_menu_name as menu_name,m.id,m.main_menu_id,m.width,m.height,m.link,l.link_type_name,m.main_menu_id as last_id,m.file_link FROM ".$database_kohrx.".kohrx_sub_menu m left outer join ".$database_kohrx.".kohrx_menu_link_type l on l.id=m.link_type left outer join ".$database_kohrx.".kohrx_main_menu m2 on m2.id=m.main_menu_id left outer join ".$database_kohrx.".kohrx_sub_menu2 s on s.sub_menu_id=m.id WHERE main_menu_id='$_GET[id]' group by m.id ORDER BY m.sort_order ASC";

$query_rs_main_menu2 = "SELECT m.menu_name as menu_name2,m.id as main_menu_id FROM ".$database_kohrx.".kohrx_main_menu m  WHERE id='$_GET[id]' group by m.id ";
$rs_main_menu2 = mysql_query($query_rs_main_menu2, $hos) or die(mysql_error());
$row_rs_main_menu2 = mysql_fetch_assoc($rs_main_menu2);
$totalRows_rs_main_menu2 = mysql_num_rows($rs_main_menu2);

}
if(isset($_GET['menu_type'])&&($_GET['menu_type']=="sub_menu")){
		$menu="sub_menu2";
$query_rs_main_menu = "SELECT m.sort_order, m.sub_menu2_name as menu_name,m.id,m.sub_menu_id,m.width,m.height,m.link,l.link_type_name,m.sub_menu_id as last_id,m.file_link FROM ".$database_kohrx.".kohrx_sub_menu2 m left outer join ".$database_kohrx.".kohrx_menu_link_type l on l.id=m.link_type  where sub_menu_id='$_GET[id]' ORDER BY m.sort_order ASC";

$query_rs_main_menu2 = "SELECT m2.menu_name as menu_name2,m.sub_menu_name as menu_name3,m.main_menu_id,m.id,m.file_link FROM ".$database_kohrx.".kohrx_sub_menu m  left outer join ".$database_kohrx.".kohrx_main_menu m2 on m2.id=m.main_menu_id where m.id='$_GET[id]'";
$rs_main_menu2 = mysql_query($query_rs_main_menu2, $hos) or die(mysql_error());
$row_rs_main_menu2 = mysql_fetch_assoc($rs_main_menu2);
$totalRows_rs_main_menu2 = mysql_num_rows($rs_main_menu2);

}
if(isset($_GET['menu_type'])&&($_GET['menu_type']=="sub_menu2")){
		$menu="sub_menu3";
$query_rs_main_menu = "SELECT m.sort_order, m.sub_menu3_name as menu_name,m.id,m.sub_menu2_id,m.width,m.height,m.link,l.link_type_name,m.sub_menu2_id as last_id,m.file_link FROM ".$database_kohrx.".kohrx_sub_menu3 m left outer join ".$database_kohrx.".kohrx_menu_link_type l on l.id=m.link_type  where sub_menu2_id='$_GET[id]' ORDER BY m.sort_order ASC";

$query_rs_main_menu2 = "SELECT m2.menu_name as menu_name2,s2.sub_menu_name as menu_name3,m.sub_menu2_name as menu_name4,s2.main_menu_id,m.id as id3,s2.id,m.file_link FROM ".$database_kohrx.".kohrx_sub_menu2 m left outer join ".$database_kohrx.".kohrx_sub_menu s2 on s2.id=m.sub_menu_id left outer join ".$database_kohrx.".kohrx_main_menu m2 on m2.id=s2.main_menu_id where m.id='$_GET[id]'";
$rs_main_menu2 = mysql_query($query_rs_main_menu2, $hos) or die(mysql_error());
$row_rs_main_menu2 = mysql_fetch_assoc($rs_main_menu2);
$totalRows_rs_main_menu2 = mysql_num_rows($rs_main_menu2);
}

$rs_main_menu = mysql_query($query_rs_main_menu, $hos) or die(mysql_error());
$row_rs_main_menu = mysql_fetch_assoc($rs_main_menu);
$totalRows_rs_main_menu = mysql_num_rows($rs_main_menu);


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<?php include('java_css_file.php'); ?>    


<script type="text/javascript">
$(document).ready(function(){
	$('#link_url').hide();    
	$('#link_width').hide();	
	$('#link_height').hide();	
	$('#attach_file').hide();
	$('#textarea').hide();
	
	$('#summernote').summernote({
minHeight: 180,
  placeholder: 'พิมพ์ข้อความตรงนี้ ...',
  focus: false,
  airMode: false,
  fontNames: ['Roboto', 'Calibri', 'Times New Roman', 'Arial'],
  fontNamesIgnoreCheck: ['Roboto', 'Calibri'],
  dialogsInBody: true,
  dialogsFade: true,
  disableDragAndDrop: false,
  toolbar: [
    // [groupName, [list of button]]
    ['style', ['bold', 'italic', 'underline', 'clear']],
    ['para', ['style', 'ul', 'ol', 'paragraph']],
    ['fontsize', ['fontsize']],
    ['font', ['strikethrough', 'superscript', 'subscript']],
	['color', ['color']],
	['table', ['table']],
    ['height', ['height']],
    ['misc', ['undo', 'redo', 'print', 'help', 'fullscreen']]
  ],
  popover: {
    air: [
      ['color', ['color']],
      ['font', ['bold', 'underline', 'clear']]
    ]
  },
  print: {
    //'stylesheetUrl': 'url_of_stylesheet_for_printing'
  }	});

});

function link_type_change(a){
	if(a==1){
		$('#link').hide();
		$('#width').hide();
		$('#height').hide();
		$('#link').val("");
		$('#width').val("");
		$('#height').val("");
		$('#text').hide();
		}

	if(a==2){
		$('#width').show();
		$('#height').show();
		$('#text').hide();
		}
	if(a==3){
		$('#width').val("");
		$('#width').hide();
		$('#height').hide();
		$('#text').hide();
		}
	if(a==4){
		$('#link').hide();
		$('#width').hide();
		$('#height').hide();
		$('#link').val("");
		$('#width').val("");
		$('#height').val("");
		$('#text').show();
		}

	}

function linktype(link_id){
		if(link_id==1){
		$('#link').hide();
		$('#width').hide();
		$('#height').hide();
	$('#link_width').hide();	
	$('#link_height').hide();	
	$('#link_url').hide();	
	$('#attach_file').hide();
	$('#attach_file').val("");
		$('#link').val("");
		$('#width').val("");
		$('#height').val("");
		$('#text').hide();
	$('#textarea').hide();
	$('#textarea').val("");

		}

	if(link_id==3){
	$('#link_url').show();	
	$('#link').show();	

	$('#link_width').hide();	
	$('#link_height').hide();	
	$('#textarea').hide();
	$('#textarea').val("");
	$('#attach_file').hide();
	$('#attach_file').val("");
	}
	if(link_id==2){
	$('#link_url').show();	
	$('#link').show();	
	$('#link_width').show();	
	$('#link_height').show();
	$('#width').show();
	$('#height').show();	
	$('#textarea').hide();
	$('#textarea').val("");
	$('#attach_file').hide();
	$('#attach_file').val("");
	}

	if(link_id==4){
	$('#textarea').show();	
	$('#link_url').hide(); 
	$('#link_width').show();	
	$('#link_height').show();	
	$('#width').show();	
	$('#height').show();	
	$('#attach_file').hide();
	$('#attach_file').val("");

	}
	if(link_id==5){
	$('#attach_file').show();	
	$('#link_url').hide(); 
	$('#link_width').show();	
	$('#link_height').show();	
	$('#textarea').hide();
	$('#textarea').val("");
	}
	}
</script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.11/summernote-bs4.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.11/summernote-bs4.min.js"></script>
<script>
function alertload(url,w,h,str,queue){$.colorbox({width:w,height:h, iframe:true, href:url+"?"+str+"="+queue,onOpen : function () {$('html').css('overflowY','hidden');},
onCleanup :function(){$('html').css('overflowY','auto');}
		});}			
</script>
<style>
html,body{overflow:hidden; }
	::-webkit-scrollbar {
    width: 10px;
}

::-webkit-scrollbar-track {
    box-shadow: inset 0 0 10px 10px #E6E6E6;
    border: solid 3px transparent;
}

::-webkit-scrollbar-thumb {
    box-shadow: inset 0 0 10px 10px #CCCCCC;
    border: solid 3px transparent;
}
</style>
</head>

<body>

<nav class="navbar navbar-dark bg-info text-white " >
  <!-- Navbar content -->
  <span class="card-title font_bord"  >&ensp;ระบบจัดการเมนูู</span>
</nav>
<div style="overflow:scroll;overflow-x:hidden;overflow-y:auto; height:550px;">	
<form id="form1" name="form1" method="post"  enctype="multipart/form-data" >
<div class="p-3">
<div class="card">
<div class="card-body">
<div class="row">
<div class="col-5">
	<div class="form-group row">
	<label for="name" class="col-sm-3 col-form-label">ชื่อเมนู</label>	
	<div class="col-sm-9"><input type="text" name="name" id="name" class="form-control form-control-sm" /></div>
	</div>	
	<div class="form-group row">
	<label for="link_type" class="col-sm-3 col-form-label">Link Type</label>	
	<div class="col-sm-9">
		<select name="link_type" id="link_type" class="form-control form-control-sm" onchange="linktype(this.value);" >
			  <?php
		do {  
		?>
			  <option value="<?php echo $row_rs_link_type['id']?>"><?php echo $row_rs_link_type['link_type_name']?></option>
			  <?php
		} while ($row_rs_link_type = mysql_fetch_assoc($rs_link_type));
		  $rows = mysql_num_rows($rs_link_type);
		  if($rows > 0) {
			  mysql_data_seek($rs_link_type, 0);
			  $row_rs_link_type = mysql_fetch_assoc($rs_link_type);
		  }
		?>
			</select>
		(*) ต้องใส่ค่า กว้างxยาว ด้วย 	
	</div>
	</div>
	<div class="form-group row" id="link_url">
	<label for="link" class="col-sm-3 col-form-label">Link URL</label>	
	<div class="col-sm-9"><input type="text" name="link" id="link" class="form-control form-control-sm" /></div>
	</div>	
	<div class="form-group row" id="link_width">
	<label for="width" class="col-sm-3 col-form-label">ความกว้าง</label>	
	<div class="col-sm-9"><input name="width" class="form-control form-control-sm" type="text" id="width"/></div>
	</div>	
	<div class="form-group row" id="link_height">
	<label for="height" class="col-sm-3 col-form-label">ความสูง</label>	
	<div class="col-sm-9"><input name="height" type="text" id="height" class="form-control form-control-sm" /></div>
	</div>	
	<div class="form-group row" id="attach_file">
	<label for="height" class="col-sm-3 col-form-label">แนบไฟล์</label>	
	<div class="col-sm-9"><input type="file" name="fileUpload" id="fileUpload" /></div>
	</div>

  	<div class="form-group row">
    <div class="col-sm-12 text-right">
		<input type="submit" name="save" id="save" value="บันทึก" class="btn btn-danger btn-sm" />
		<input name="menu_type2" type="hidden" id="menu_type2" value="<?php echo $menu_type; ?>" />
		<input name="id2" type="hidden" id="id2" value="<?php echo $id; ?>" />
    </div>
  	</div>		
</div>

<div class="col-7"><div class="p-2" id="textarea"><textarea id="summernote" name="text"></textarea></div></div>
</div>
	</div>
	</div>
	</div>
<nav aria-label="breadcrumb">
  <ol class="breadcrumb rounded">
    <li class="breadcrumb-item"><a href="menu_create.php">หน้าหลัก</a></li>
	<?php if( $menu_type=="main"||$menu_type=="sub_menu"||$menu_type=="sub_menu2"){ ?>
    <li class="breadcrumb-item active" aria-current="page">
        <a href="menu_create.php?menu_type=main&amp;id=<?php echo $row_rs_main_menu2['main_menu_id']; ?>"><?php print $row_rs_main_menu2['menu_name2']; ?></a></li>
	  	 <?php }?>
	<?php if(isset($menu_type)and( $menu_type=="sub_menu"||$menu_type=="sub_menu2")){ ?>
	  <li class="breadcrumb-item active" aria-current="page"><a href="menu_create.php?menu_type=sub_menu&amp;id=<?php echo $row_rs_main_menu2['id']; ?>" ><?php print $row_rs_main_menu2['menu_name3']; ?></a>
		</li><?php } ?>
	  <?php if(isset($menu_type)and( $menu_type=="sub_menu2")){ ?>
	  <li class="breadcrumb-item active" aria-current="page">
<a href="menu_create.php?menu_type=sub_menu2&amp;id=<?php echo $row_rs_main_menu2['id3']; ?>" ><?php print $row_rs_main_menu2['menu_name4']; ?></a>
</li><?php } ?>

	</ol>
</nav>
<table width="100%" border="0" cellpadding="3" cellspacing="0" class="table table-striped table-hover thfont " style="margin-top: -20px;">
	  <thead >
    <tr class="bg-dark text-white">
        <td height="29" align="center">ลำดับ</td>
        <td width="231">ชื่อเมนู</td>
        <td align="center" >ชนิดลิงค์</td>
        <td width="163">ลิงค์</td>
        <td align="center">กว้าง</td>
        <td align="center">ยาว</td>
        <td align="center">&nbsp;</td>
    </tr>
</thead>
<tbody>
      <?php $i=0; do { $i++; ?>
    <tr class="grid">
      <td width="79" align="left"  ><?php echo $i; ?> <?php if($row_rs_main_menu['sort_order']!=$totalRows_rs_main_menu){ ?><a href="menu_create.php?menu_type=<?php echo $_GET['menu_type']; ?>&id=<?php echo $_GET['id']; ?>&id2=<?php echo $row_rs_main_menu['id']; ?>&order=<?php echo $row_rs_main_menu['sort_order']; ?>&method=down"><i class="fas fa-chevron-circle-down font20 text-dark"></i></a><?php } echo "&ensp;"; if($row_rs_main_menu['sort_order']!=1){ ?><a href="menu_create.php?menu_type=<?php echo $_GET['menu_type']; ?>&id=<?php echo $_GET['id']; ?>&id2=<?php echo $row_rs_main_menu['id']; ?>&order=<?php echo $row_rs_main_menu['sort_order']; ?>&method=up"><i class="fas fa-chevron-circle-up font20"></i></a><?php } ?></td>
      <td  ><a href="menu_create.php?menu_type=<?php echo $menu; ?>&amp;id=<?php echo $row_rs_main_menu['id']; ?>" class="table_head_small_bord"><?php print $row_rs_main_menu['menu_name'];  ?></a></td>
        <td width="83" align="center"   ><?php print $row_rs_main_menu['link_type_name']; ?> </td>
        <td   ><?php if($row_rs_main_menu['file_link']==""){print substr($row_rs_main_menu['link'],0,100);}else {print $row_rs_main_menu['file_link'];} ?></td>
        <td width="42" align="center"   ><?php print $row_rs_main_menu['width']; ?></td>
        <td width="32" align="center"   ><?php print $row_rs_main_menu['height']; ?></td>
        <td width="41" align="center"   ><i class="fas fa-pen-alt font20 cursor" onclick="window.location='menu_edit.php?menu_type=<?php echo $menu; ?>&amp;id2=<?php echo $row_rs_main_menu['id']; ?>&amp;sort_order=<?php echo $row_rs_main_menu['sort_order']; ?>&amp;id4=<?php echo $row_rs_main_menu['last_id']; ?>';" ></i></td>
    </tr>        
    <?php } while ($row_rs_main_menu = mysql_fetch_assoc($rs_main_menu)); ?>
</tbody>
  </table>
</form>
</div>
</body>
</html>
<?php
mysql_free_result($rs_main_menu);

mysql_free_result($rs_link_type);
?>
