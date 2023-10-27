<?php require_once('Connections/hos.php'); ?>
<?php
include('include/function.php');

$today=date('Y-m-d H:i:s');

function notify_message($params, $token) {
  $queryData = array(
    'message'          => $params["message"],
    'stickerPackageId' => $params["stickerPkg"],
    'stickerId'        => $params["stickerId"],
    'imageThumbnail'   => $params["imageThumbnail"],
    'imageFullsize'    => $params["imageFullsize"],
  );
  $queryData = http_build_query($queryData, '', '&');
  $headerOptions = array(
    'http' => array(
      'method'  => 'POST',
      'header'  => "Content-Type: application/x-www-form-urlencoded\r\n"
      . "Authorization: Bearer " . $token . "\r\n"
      . "Content-Length: " . strlen($queryData) . "\r\n",
      'content' => $queryData,
    ),
  );
  $context = stream_context_create($headerOptions);
  $result = file_get_contents(LINE_API, FALSE, $context);
  $res = json_decode($result);
  return $res;
}                       

define('LINE_API', "https://notify-api.line.me/api/notify");
$token = "9yxVCY7uDvvDZQ4Kd7a4pH3w7c5c9nyG1mzQcvWQGeb"; //ใส่Token ที่copy เอาไว้

if(isset($_POST['an'])&&$_POST['an']!=""){
	$an=$_POST['an'];
}
if(isset($_GET['an'])&&$_GET['an']!=""){
	$an=$_GET['an'];
}

if($_POST['order_save']=="save"){
mysql_select_db($database_hos, $hos);
$query_update = "update ".$database_kohrx.".kohrx_ipd_order_image set checked='Y',checked_date=NOW() where id='".$_POST['id']."'";
//echo $query_order_list;
$update = mysql_query($query_update, $hos) or die(mysql_error());    
}
if($_POST['order_save']=="unsave"){
mysql_select_db($database_hos, $hos);
$query_update = "update ".$database_kohrx.".kohrx_ipd_order_image set checked='N',checked_date=NULL where id='".$_POST['id']."'";
//echo $query_order_list;
$update = mysql_query($query_update, $hos) or die(mysql_error());    
}
if($_POST['delete_id']!=""){  
mysql_select_db($database_hos, $hos);
$query_ipt = "select k.image_name,i.an,concat(p.pname,p.fname,' ',p.lname) as ptname,a.bedno,i.hn,k.order_date,k.order_time from ".$database_kohrx.".kohrx_ipd_order_image k left outer join ipt i on i.an=k.an left outer join patient p on p.hn=i.hn left outer join iptadm a on a.an=i.an where k.id='".$_POST['delete_id']."'";
//echo $query_ipt;
$ipt = mysql_query($query_ipt, $hos) or die(mysql_error());
$row_ipt = mysql_fetch_assoc($ipt);
$totalRows_ipt = mysql_num_rows($ipt);
	
	$an=$row_ipt['an'];
	$ptname=$row_ipt['ptname'];
	$hn=$row_ipt['hn'];
	$bedno=$row_ipt['bedno'];
	$order_date=$row_ipt['order_date'];
	$order_time=$row_ipt['order_time'];    
    
    mysql_select_db($database_hos, $hos);
    $query_delete = "delete from ".$database_kohrx.".kohrx_ipd_order_image where id='".$_POST['delete_id']."'";
    $delete = mysql_query($query_delete, $hos) or die(mysql_error());
    
    unlink('uploads/'.$row_ipt['image_name']);
    
    mysql_free_result($ipt);
    

    $mymessage = "มีการลบภาพ order\n"; //Set new line with '\n'
    $mymessage .= "ชื่อ: ".$ptname."\n";
    $mymessage .= "HN: ".$hn."\n";
    $mymessage .= "AN: ".$an."\n";
    $mymessage .= "เตียง: ".$bedno."\n";
    $mymessage .= "วันที่สั่ง :".dateThai($order_date)."\n";
    $mymessage .= "เวลาที่สั่ง :".$order_time."\n";
    $imageFile = new CURLFile('uploads/'.$file); // Local Image file Path

                       
$params = array(
  "stickerPkg"     => 2, //stickerPackageId
  "stickerId"      => 34, //stickerId
  "message"        => $mymessage //ข้อความที่ต้องการส่ง สูงสุด 1000 ตัวอักษร
  //"imageThumbnail" => $imageFile, // max size 240x240px JPEG
);
$res = notify_message($params, $token);
// print_r($res);
}

mysql_select_db($database_hos, $hos);
$query_order_list = "select * from ".$database_kohrx.".kohrx_ipd_order_image where an='".$an."' order by order_date DESC,order_time DESC,capture_date DESC";
$order_list = mysql_query($query_order_list, $hos) or die(mysql_error());
$row_order_list = mysql_fetch_assoc($order_list);
$totalRows_list = mysql_num_rows($order_list);

?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Untitled Document</title>
    
<?php if(isset($_GET['an'])&&$_GET['an']!=""){
 include('bootstrap4.php');
}
?>
    <script>
        function zoomInImage(imgid){
                $("#"+imgid).width($("#"+imgid).width()+100);
                $("#"+imgid).height($("#"+imgid).height()+100);
            }
        function zoomOutImage(imgid){
                $("#"+imgid).width($("#"+imgid).width()-100);
                $("#"+imgid).height($("#"+imgid).height()-100);
        }
        function rotateImage(imgid,degree) {
            $('#'+imgid).animate({
                transform: degree
            }, {
                step: function(now, fx) {
                    $(this).css({
                        '-webkit-transform': 'rotate(' + now + 'deg)',
                        '-moz-transform': 'rotate(' + now + 'deg)',
                        'transform': 'rotate(' + now + 'deg)'
                    });
                }
            });
        }
    </script>
<script>
    
function deletePic(id,an){
                    $('#indicator').show();
                    var dataString="delete_id="+id+'&an='+an;
                   $.ajax({
				   type: "POST",
				   url: "ipd_order_list.php",
				   cache: false,
				   data: dataString,
				   success: function(html)
					{
                    $('indicator').hide();
                        order_list();
					}
				 });
}    
</script>	
</head>

<body>
<?php if($totalRows_list<>0){ do{     ?>
<div class="card mt-2">
    
	<div class="card-header" style="font-size: 11px;"><?php echo "วันที่สั่ง : ".date_db2th($row_order_list['order_date'])."&nbsp;".substr($row_order_list['order_time'],0,5); ?>        <div class="text-right" style="position: absolute; right: 100px; z-index: 1; top: 10px;"><input type="button" class="w3-btn w3-green" value="90" onClick="rotateImage('<?php echo $row_order_list['id']; ?>',this.value);" />
        <input type="button" class="w3-btn w3-green" value="-90" onClick="rotateImage('<?php echo $row_order_list['id']; ?>',this.value);" />
        <input type="button" class="w3-btn w3-green" value="180" onClick="rotateImage('<?php echo $row_order_list['id']; ?>',this.value);" />
        <input type="button" class="w3-btn w3-green" value="เริ่มต้น" onClick="rotateImage('<?php echo $row_order_list['id']; ?>','360');" />
        <input type="button" id="in" class="w3-btn w3-green" onClick="zoomInImage('<?php echo $row_order_list['id']; ?>')" value="Z+" />
        <input type="button" id="out" class="w3-btn w3-green" onClick="zoomOutImage('<?php echo $row_order_list['id']; ?>')" value="Z-" />

        </div>
<?php if(isset($_POST['an'])&&$_POST['an']!=""){ ?>
    <?php if($row_order_list['checked']=="N"){ ?>
    <button class="btn btn-success btn-sm" style="position: absolute; right: 5px; top:5px;" id="order_check" name="order_save" onClick="order_save('save','<?php echo $row_order_list['id']; ?>');">บันทึก</button><?php } else { ?>
<button class="btn btn-danger btn-sm" style="position: absolute; right: 5px; top:5px;" id="order_check" name="order_unsave" onClick="order_save('unsave','<?php echo $row_order_list['id']; ?>');">ยกเลิกบันทึก</button>    <?php } ?>  
	<?php } ?>
		
    </div>

	<div class="card-body" style="padding: 0px;" >
        <?php if(DateTimeDiff($row_order_list['capture_date'],$today)<=1){ ?>
        <button type="button" class="close" onClick="if(confirm('ต้องการลบรูปนี้จริงหรือไม่')==true){ deletePic('<?php echo $row_order_list['id']; ?>','<?php echo $row_order_list['an']; ?>'); }"  style="position:absolute;right: 20px; margin-top: 5px; font-size: 30px;">&times;</button>
        <?php } ?>
		<div style=" position: absolute;margin-top:5px; margin-left:10px;"><span style="text-shadow: 1px 1px #ffffff; font-size: 12px"><?php echo datetime_db2th($row_order_list['capture_date']); ?></span></div>
		<center>
        <i class="far fa-eye"  style="position:absolute;left: 20px; margin-top: 25px; font-size: 50px; color: #929292;text-shadow: 1px 1px #FFFFFF; cursor: pointer;" onClick="window.open('ipd_profile_image_preview.php?img=<?php echo $row_order_list['image_name']; ?>','_new');"></i>    
        <img src="uploads/<?php echo $row_order_list['image_name']; ?>" class="rounded" id="<?php echo $row_order_list['id']; ?>" style="display: flex; height: 100vh; z-index: -1"  />
        </center>    
        <?php if(($row_order_list['remark']!="") and ($row_order_list['remark']!=NULL) ){ ?><div class="alert alert-warning" style="margin-bottom: 0px;" role="alert"><?php echo $row_order_list['remark']; ?>
        </div>	
        <?php } ?>
    </div>
</div>
<?php }while($row_order_list = mysql_fetch_assoc($order_list)); } ?>	
	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>  
<script src="include/datetimepicker/js/jquery.datetimepicker.full.min.js"></script>
<link rel="stylesheet" href="include/datetimepicker/css/jquery.datetimepicker.min.css">
<script>
$(document).ready(function () {
jQuery.datetimepicker.setLocale('th');

$('#datetimepicker').datetimepicker({
 mask:'39/19/9999 99:99',
 format:'d/m/Y H:i'
});

});
</script>	

</body>
</html>
<?php mysql_free_result($order_list); ?>