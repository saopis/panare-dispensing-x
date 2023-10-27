<?php require_once('Connections/hos.php'); ?>
<? include("include/FCKeditor/fckeditor.php") ; ?>

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

if(isset($_POST['button'])&&($_POST['button']=="แก้ไข")){

 if($_POST['link_type']==1){
	$width=",width=NULL";
	$height=",height=NULL";
	$link=",link=NULL";
	$text=",menu_text=NULL";
	$files=",file_link=NULL";

	if($_POST['file_old']!=""){
		unlink('upload/'.$_POST['file_old']);
	}
}

else if($_POST['link_type']==2){
	$width=",width='".$_POST['width']."'";
	$height=",height='".$_POST['height']."'";
	$link=",link='".$_POST['link']."'";
	$text=",menu_text=NULL";
	$files=",file_link=NULL";

	if($_POST['file_old']!=""){
		unlink('upload/'.$_POST['file_old']);
	}
}

else if($_POST['link_type']==3){
	$width=",width=NULL";
	$height=",height=NULL";
	$link=",link='".$_POST['link']."'";
	$text=",menu_text=NULL";
	$files=",file_link=NULL";

	if($_POST['file_old']!=""){
		unlink('upload/'.$_POST['file_old']);
	}
}
else if($_POST['link_type']==4){
	$width=",width='".$_POST['width']."'";
	$height=",height='".$_POST['height']."'";
	$link=",link=NULL";
	$text=",menu_text='".$_POST['text']."'";
	$files=",file_link=NULL";

	if($_POST['file_old']!=""){
		unlink('upload/'.$_POST['file_old']);
	}
}
else if($_POST['link_type']==5){

	if(trim($_FILES["file_link"]["tmp_name"]) != "")
{

$length = 10;

$randomString = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, $length);

		$images = $_FILES["file_link"]["tmp_name"];
		$ext = pathinfo($_FILES["file_link"]["name"], PATHINFO_EXTENSION);
		$filename=$randomString.".".$ext;
		copy($_FILES["file_link"]["tmp_name"],"upload/".$filename);
	}
if($_POST['file_old']!=""){
unlink('upload/'.$_POST['file_old']);
}
chmod("upload/".$filename, 0755);

$files=",file_link='".$filename."'";
$width=",width='".$_POST['width']."'";
$height=",height='".$_POST['height']."'";
$link=",link=NULL";

$text=",menu_text=NULL";

}


	
mysql_select_db($database_hos, $hos);
if($menu_type2=="main"){
$menu_type_text="";
$query_update = "update ".$database_kohrx.".kohrx_main_menu set menu_name='".$_POST['name']."',link_type='".$_POST['link_type']."'".$width.$height.$link.$text.$files." where id='$id3' ";
}
if($menu_type2=="sub_menu"){
	$menu_type_text="main";

$query_update = "update ".$database_kohrx.".kohrx_sub_menu set sub_menu_name='".$_POST['name']."',link_type='".$_POST['link_type']."'".$width.$height.$link.$text.$files." where id='$id3' ";
}
if($menu_type2=="sub_menu2"){
$menu_type_text="sub_menu";

$query_update = "update ".$database_kohrx.".kohrx_sub_menu2 set sub_menu2_name='".$_POST['name']."',link_type='".$_POST['link_type']."'".$width.$height.$link.$text.$files." where id='$id3' ";
}
if($menu_type2=="sub_menu3"){
$menu_type_text="sub_menu2";
$query_update = "update ".$database_kohrx.".kohrx_sub_menu3 set sub_menu3_name='".$_POST['name']."',link_type='".$_POST['link_type']."'".$width.$height.$link.$text.$files." where id='$id3' ";
}
$rs_update = mysql_query($query_update, $hos) or die(mysql_error());

echo "<script>parent.window.location.href='index.php';</script>";
exit();

}
if(isset($_POST['button2'])&&($_POST['button2']=="ลบ")){

if($_POST['file_old']!=""){
	unlink('upload/'.$_POST['file_old']);
}
if($menu_type2=="main"){
$menu_type_text="";
mysql_select_db($database_hos, $hos);
//ค้นหาเลข order
$query_rs_order = "SELECT * from  ".$database_kohrx.".kohrx_main_menu where sort_order>'$sort_order' ";
$rs_order = mysql_query($query_rs_order, $hos) or die(mysql_error());
$row_rs_order = mysql_fetch_assoc($rs_order);
$totalRows_rs_order = mysql_num_rows($rs_order);
if($totalRows_rs_order<>0){
do{
// update เลข order ที่อยู่ข้างล่าง
$query_rs_update = "update  ".$database_kohrx.".kohrx_main_menu set sort_order=(sort_order-1) where id='$row_rs_order[id]' ";
$rs_update = mysql_query($query_rs_update, $hos) or die(mysql_error());
}while($row_rs_order = mysql_fetch_assoc($rs_order)
);
}
$query_delete = "delete from  ".$database_kohrx.".kohrx_main_menu where id='$id3' ";
}
if($menu_type2=="sub_menu"){
$menu_type_text="main";
//ค้นหาเลข order
$query_rs_order = "SELECT * from  ".$database_kohrx.".kohrx_sub_menu where main_menu_id='$id4' and sort_order>'$sort_order'";
$rs_order = mysql_query($query_rs_order, $hos) or die(mysql_error());
$row_rs_order = mysql_fetch_assoc($rs_order);
$totalRows_rs_order = mysql_num_rows($rs_order);
if($totalRows_rs_order<>0){
do{
// update เลข order ที่อยู่ข้างล่าง
$query_rs_update = "update  ".$database_kohrx.".kohrx_sub_menu set sort_order=(sort_order-1) where id='$row_rs_order[id]'";
$rs_update = mysql_query($query_rs_update, $hos) or die(mysql_error());
}while($row_rs_order = mysql_fetch_assoc($rs_order)
);
}
$query_delete = "delete from  ".$database_kohrx.".kohrx_sub_menu where id='$id3' ";

}
if($menu_type2=="sub_menu2"){
	$menu_type_text="sub_menu";
//ค้นหาเลข order
$query_rs_order = "SELECT * from  ".$database_kohrx.".kohrx_sub_menu2 where sub_menu_id='$id4' and sort_order>'$sort_order'";
$rs_order = mysql_query($query_rs_order, $hos) or die(mysql_error());
$row_rs_order = mysql_fetch_assoc($rs_order);
$totalRows_rs_order = mysql_num_rows($rs_order);
if($totalRows_rs_order<>0){
do{
// update เลข order ที่อยู่ข้างล่าง
$query_rs_update = "update  ".$database_kohrx.".kohrx_sub_menu2 set sort_order=(sort_order-1) where id='$row_rs_order[id]'";
$rs_update = mysql_query($query_rs_update, $hos) or die(mysql_error());
}while($row_rs_order = mysql_fetch_assoc($rs_order)
);
}
$query_delete = "delete from ".$database_kohrx.".kohrx_sub_menu2  where id='$id3' ";
}
if($menu_type2=="sub_menu3"){
$menu_type_text="sub_menu2";
//ค้นหาเลข order
$query_rs_order = "SELECT * from  ".$database_kohrx.".kohrx_sub_menu3 where sub_menu2_id='$id4' and sort_order>'$sort_order'";
$rs_order = mysql_query($query_rs_order, $hos) or die(mysql_error());
$row_rs_order = mysql_fetch_assoc($rs_order);
$totalRows_rs_order = mysql_num_rows($rs_order);
if($totalRows_rs_order<>0){
do{
// update เลข order ที่อยู่ข้างล่าง
$query_rs_update = "update  ".$database_kohrx.".kohrx_sub_menu3 set sort_order=(sort_order-1) where id='$row_rs_order[id]'";
$rs_update = mysql_query($query_rs_update, $hos) or die(mysql_error());
echo $query_rs_update;
}while($row_rs_order = mysql_fetch_assoc($rs_order)
);
}
$query_delete = "delete from ".$database_kohrx.".kohrx_sub_menu3  where id='$id3' ";
}
$rs_delete = mysql_query($query_delete, $hos) or die(mysql_error());

echo "<script>parent.window.location.href='index.php';</script>";
exit();

}
mysql_select_db($database_hos, $hos);
$query_rs_link_type = "SELECT * from  ".$database_kohrx.".kohrx_menu_link_type";
$rs_link_type = mysql_query($query_rs_link_type, $hos) or die(mysql_error());
$row_rs_link_type = mysql_fetch_assoc($rs_link_type);
$totalRows_rs_link_type = mysql_num_rows($rs_link_type);

mysql_select_db($database_hos, $hos);
if(isset($_GET['menu_type'])&&($_GET['menu_type']=="main")){
$query_rs_main_menu = "SELECT m.*,m.menu_name as name,menu_text FROM ".$database_kohrx.".kohrx_main_menu m left outer join ".$database_kohrx.".kohrx_menu_link_type l on l.id=m.link_type where m.id='$_GET[id2]'";
}
if(isset($_GET['menu_type'])&&($_GET['menu_type']=="sub_menu")){
$query_rs_main_menu = "SELECT *,sub_menu_name as name,menu_text FROM ".$database_kohrx.".kohrx_sub_menu m left outer join ".$database_kohrx.".kohrx_menu_link_type l on l.id=m.link_type WHERE m.id='$_GET[id2]' ";
}
if(isset($_GET['menu_type'])&&($_GET['menu_type']=="sub_menu2")){
	$menu_type_text="sub_menu";

$query_rs_main_menu = "SELECT *,sub_menu2_name as name,menu_text FROM ".$database_kohrx.".kohrx_sub_menu2 m left outer join ".$database_kohrx.".kohrx_menu_link_type l on l.id=m.link_type  where m.id='$_GET[id2]' ";
}
if(isset($_GET['menu_type'])&&($_GET['menu_type']=="sub_menu3")){
$menu_type_text="sub_menu2";
$query_rs_main_menu = "SELECT *,sub_menu3_name as name,menu_text FROM ".$database_kohrx.".kohrx_sub_menu3 m left outer join ".$database_kohrx.".kohrx_menu_link_type l on l.id=m.link_type   where m.id='$_GET[id2]' ";
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
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.11/summernote-bs4.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.11/summernote-bs4.min.js"></script>
<script type="text/javascript">
$(document).ready(function(){
  
  $('#text').summernote({
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

	<?php if($row_rs_main_menu['link_type']==1){ ?>
	$('#link_width').hide();	
	$('#link_height').hide();	
	$('#link_url').hide();	
	$('#link_attach').hide();
	$('#link_textarea').hide();

	<?php } ?>

	<?php if($row_rs_main_menu['link_type']==3){ ?>
	$('#link_url').show();	
	$('#link_width').hide();
	$('#link_height').hide();	
	$('#link_textarea').hide();
	$('#link_attach').hide();
	<?php } ?>
	
	<?php if($row_rs_main_menu['link_type']==2){ ?>
	$('#link_url').show();	
	$('#link_width').show();	
	$('#link_height').show();	
	$('#link_textarea').hide();
	$('#link_attach').hide();
	<?php } ?>

	<?php if($row_rs_main_menu['link_type']==4){ ?>
	$('#link_textarea').show();	
	$('#link_url').hide();
	$('#link_width').show();	
	$('#link_height').show();	
	$('#link_attach').hide();

	<?php } ?>
	
	<?php if($row_rs_main_menu['link_type']==5){ ?>
	$('#link_attach').show();	
	$('#link_url').hide(); 
	$('#link_width').show();	
	$('#link_height').show();	
	$('#link_textarea').hide();
	<?php } ?>

});
function linktype(link_id){

		if(link_id==1){

	$('#link_width').hide();	
	$('#link_height').hide();	
	$('#link_url').hide();	
	$('#link_attach').hide();
	$('#file_link').val("");
		$('#link').val("");
		$('#width').val("");
		$('#height').val("");
	$('#link_textarea').hide();
	$('#textarea').val("");

		}

	if(link_id==3){
	$('#link_url').show();	
	$('#link_width').hide();
	$('#width').val("");
	$('#link_height').hide();	
	$('#height').val("");
	$('#link_textarea').hide();
	$('#textarea').val("");
	$('#link_attach').hide();
	$('#file_link').val("");
	}
	if(link_id==2){
	$('#link_url').show();	
	$('#link_width').show();	
	$('#link_height').show();	
	$('#link_textarea').hide();
	$('#link_attach').hide();
	$('#file_link').val("");
	}

	if(link_id==4){
	$('#link_textarea').show();	
	$('#link_url').hide();
	$('#link').val("");
	$('#link_width').show();	
	$('#link_height').show();	
	$('#link_attach').hide();
	$('#file_link').val("");

	}
	if(link_id==5){
	$('#link_attach').show();	
	$('#link_url').hide(); 
	$('#link_width').show();	
	$('#link_height').show();	
	$('#link_textarea').hide();
	$('#textarea').val("");
	$('#link').val("");
	$('#width').val("");
	$('#height').val("");
	}
	}
</script>
<style>
html,body{overflow:hidden; }
</style>
</head>

<body>
<nav class="navbar navbar-dark bg-info text-white " >
  <!-- Navbar content -->
  <span class="card-title font_bord"  ><span class="btn btn-dark cursor" onClick="window.history.back();"><i class="fas fa-angle-double-left font20"></i>&ensp;ย้อนกลับ</span>&ensp;แก้ไขเมนูู</span>
</nav>
<div class="p-2">
<form id="form1" name="form1" method="post" action="menu_edit.php" enctype="multipart/form-data">
<div class="form-group row">
	<label for="name" class="col-sm-2 col-form-label">ชื่อเมนู</label>
    <div class="col-sm-9">
	<input name="name" type="text" class=" form-control" id="name" value="<?php echo $row_rs_main_menu['name']; ?>" />    
    </div>
</div>
<div class="form-row">
	<label for="name" class="col-sm-2 col-form-label">ชนิดของเมนู</label>
    <div class=" form-group col-md-2">
    <label >Link type</label>
	<select name="link_type" class="form-control" id="link_type" onChange="linktype(this.value);">
        <?php
do {  
?>
        <option value="<?php echo $row_rs_link_type['id']?>"<?php if (!(strcmp($row_rs_link_type['id'],$row_rs_main_menu['link_type']))) {echo "selected=\"selected\"";} ?>><?php echo $row_rs_link_type['link_type_name']?></option>
        <?php
} while ($row_rs_link_type = mysql_fetch_assoc($rs_link_type));
  $rows = mysql_num_rows($rs_link_type);
  if($rows > 0) {
      mysql_data_seek($rs_link_type, 0);
	  $row_rs_link_type = mysql_fetch_assoc($rs_link_type);
  }
?>
      </select>    
    </div>
        <div class=" form-group col-md-1" id="link_width">
	    <label >กว้าง</label>
        <input name="width" type="text" class=" form-control" id="width" value="<?php echo $row_rs_main_menu['width']; ?>"  />
		</div>
        <div class=" form-group col-md-1" id="link_height">
	    <label >สูง</label>
	    <input name="height" type="text" class="form-control" id="height" value="<?php echo $row_rs_main_menu['height']; ?>"  />
		</div>
        <div class=" form-group col-md-5" id="link_url">
        <label >URL</label>
		<input name="link" id="link" class="form-control" value="<?php echo $row_rs_main_menu['link']; ?>" />
        </div>
</div>  
<div class="form-group row" id="link_textarea">
	<label for="name" class="col-sm-2 col-form-label">ข้อความ</label>
    <div class="col-sm-9">
	<textarea name="text" id="text" class="form-control" ><?php echo $row_rs_main_menu['menu_text']; ?></textarea>
    </div>
</div>
<div class="form-group row" id="link_attach">
	<label for="name" class="col-sm-2 col-form-label">File upload</label>
    <div class="col-sm-auto">
	<input name="file_link" type="file" id="file_link" value="<?php echo $row_rs_main_menu['file_link']; ?>" />
    </div>
	<?php if($row_rs_main_menu['file_link']!=""){ ?>
	<div class="col-sm-auto text-left">
	<div class="card">
	<div class="card-body" style="background-color:#DFD8DC; padding: 5px;"><i class="fas fa-paperclip" style="font-size: 30px;"></i>&ensp;<?php echo $row_rs_main_menu['file_link']; ?></div>
	</div>
	</div>
	<?php } ?>

</div>

<div class="form-group row">
	<label for="name" class="col-sm-2 col-form-label"></label>
    <div class="col-sm-9">
	<input type="submit" name="button" id="button" value="แก้ไข" class="btn btn-primary" />
        <input type="submit" name="button2" onClick="return confirm('ต้องการลบรายการนี้จริงหรือไม่?')" id="button2" value="ลบ" class="btn btn-danger" />
      <input name="menu_type2" type="hidden" id="menu_type2" value="<?php echo $menu_type; ?>" />
      <input name="id3" type="hidden" id="id2" value="<?php echo $_GET['id2']; ?>" />
      <input name="sort_order" type="hidden" id="sort_order" value="<?php echo $sort_order; ?>" />
		<input name="file_old" type="hidden" value="<?php echo $row_rs_main_menu['file_link']; ?>" />
    </div>
</div>
</form>
</div>
</body>
</html>
<?php 
mysql_free_result($rs_main_menu);
mysql_free_result($rs_link_type);

?>