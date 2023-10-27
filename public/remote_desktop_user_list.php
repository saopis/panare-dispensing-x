<?php require_once('Connections/hos.php'); ?>
<?php
include('include/function.php');
              function notify_message($params, $token) {
              $queryData = array(
                'stickerPackageId' => $params["stickerPkg"],
                'stickerId'        => $params["stickerId"],
                'message'          => $params["message"],
                //'imageThumbnail'   => $params["imageThumbnail"],
                //'imageFullsize'    => $params["imageFullsize"],
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

if(isset($_GET['mode'])&&($_GET['mode']=="off")){
    mysql_select_db($database_hos, $hos);
    $query_insert = "update dispensing.kohrx_remote_desktop_user set user_active='Y' where user_name not in ('mh01','mh02')";
    $rs_insert = mysql_query($query_insert, $hos) or die(mysql_error());
	echo "<script>window.close();</script>";
}


if(isset($_GET['notify'])&&($_GET['notify']=="Y")){
                mysql_select_db($database_hos, $hos);
                $query_user3 = "select r.*,u.user_name from dispensing.kohrx_remote_desktop_request r left outer join dispensing.kohrx_remote_desktop_user u on u.id=r.user_id where r.id='".$_GET['id']."'";
                //echo $query_user3;
                //exit();
                $rs_user3 = mysql_query($query_user3, $hos) or die(mysql_error());
                $row_user3 = mysql_fetch_assoc($rs_user3);
                $totalRows_user3 = mysql_num_rows($rs_user3);  

                $mymessage  = "\n".$row_user3['officer_name']."\n"; //Set new line with '\n'
                $mymessage .= "ที่ร้องขอใช้งาน remotedesktop\n";
                $mymessage .= "ได้รับการอนุมัติแล้ว!!\n";
                $mymessage .= "วันที่: ".dateThai(date('Y-m-d'))."\n";
                $mymessage .= "เวลา : ".date("H:i")."\n";
                //$imageFile = new CURLFile('/ipd/uploads/'.$file, $file); // Local Image file Path
                //$imageFile=new CurlFile('/ipd/uploads/'.$file, 'image/jpg', $file);				   

                define('LINE_API', "https://notify-api.line.me/api/notify");
                $token = "c58zezc3sZAEZNGVaTP4odirYsyRD4MTzyik8n0mWKX"; //ใส่Token ที่copy เอาไว้
                $params = array(
                  //"stickerPkg"     => 2, //stickerPackageId
                  //"stickerId"      => 34, //stickerId
                  "message"        => $mymessage, //ข้อความที่ต้องการส่ง สูงสุด 1000 ตัวอักษร
                  //"imageThumbnail" => "http://10.0.1.17:89/ipd/uploads/1623086294.jpg", // max size 240x240px JPEG
                  //"imageFullsize"  => "http://10.0.1.17:89/ipd/uploads/1623086294.jpg", //max size 1024x1024px JPEG
                );
                $res = notify_message($params, $token);
                // print_r($res);
   
    mysql_select_db($database_hos, $hos);
    $query_insert = "update dispensing.kohrx_remote_desktop_request set notify_status='Y' where id='".$_GET['id']."'";
    //echo $query_insert;
    //exit();
    $rs_insert = mysql_query($query_insert, $hos) or die(mysql_error());
    
    
mysql_free_result($rs_user3);
    echo "<script>window.location='remote_desktop_user_list.php'</script>";
    exit();

}
if(isset($_POST['status'])&&($_POST['status']=="ใช่")){
    if($_POST['status2']=='Y'){
        $change="user_active='N'";
    }
    else{
        $change="user_active='Y'";
        
    }
    mysql_select_db($database_hos, $hos);
    $query_insert = "update dispensing.kohrx_remote_desktop_user set ".$change." where id='".$_POST['id']."'";
    //echo $query_insert;
    //exit();
    $rs_insert = mysql_query($query_insert, $hos) or die(mysql_error());

    echo "<script>parent.$.fn.colorbox.close();parent.window.location.reload();</script>";
    exit();
}
mysql_select_db($database_hos, $hos);
$query_user = "select * from dispensing.kohrx_remote_desktop_user order by user_name";
$rs_user = mysql_query($query_user, $hos) or die(mysql_error());
$row_user = mysql_fetch_assoc($rs_user);
$totalRows_user = mysql_num_rows($rs_user);  
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Untitled Document</title>
<link href="include/kohrx/css/kohrx.css" rel="stylesheet" type="text/css" />
<meta name="viewport" content="width=device-width, initial-scale=1">
<?php include('java_css_online.php'); ?>
<!-- colorbox -->
<script src="include/colorbox/js/jquery.colorbox.js"></script>
<link rel="stylesheet" href="include/colorbox/css/colorbox.css"/>
    
	<!-- container-fluid -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.2.0/js/bootstrap-datepicker.min.js"></script>
<script type="text/javascript" src="include/datepicker/js/bootstrap-datepicker-thai.js"></script>
<script src="include/datepicker/js/locales/bootstrap-datepicker.th.js"></script>    
<link rel="stylesheet" type="text/css" href="include/datepicker/css/datepicker.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/js/bootstrap.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.0.1/js/bootstrap.bundle.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/v/bs5/dt-1.10.25/datatables.min.js"></script>   

<!-- <script src="https://code.jquery.com/jquery-3.5.1.js"></script> -->
<script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.25/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.7.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.bootstrap5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.print.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.colVis.min.js"></script>
    
 

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.0.1/css/bootstrap.min.css"/>
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.25/css/dataTables.bootstrap5.min.css"/>
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.7.1/css/buttons.bootstrap5.min.css"/>
<script>
$(document).ready(function() {
    $('#example').append('<caption style="caption-side: bottom"></caption>');

	$('#example').DataTable( {
		"pageLength": 50,
		"lengthMenu": [[10, 25, 50,100, -1], [10, 25, 50,100, "All"]],
		
        dom: 'Bfrtip',
		columnDefs: [
            {
                targets: 1,
                className: 'noVis'
            }
        ],
        buttons: [  
				{
				extend: 'colvis',
				text: '<i class="fas fa-table"></i>&nbsp;Column',
				className: 'btn btn-default',
				titleAttr: 'COLOUMN',	
				columnText: function ( dt, idx, title ) {
					return (idx+1)+': '+title;
					}
				}
			,
            {
				extend: 'copy',
				text: '<i class="fas fa-copy"></i>&nbsp;Copy',
				className: 'btn btn-default',
				titleAttr: 'COPY',
				exportOptions: {
					columns: ':not(.notexport)',
					columns: ':visible'
					}
				}
			,
				{
				extend: 'csv',
				text: '<i class="fas fa-file-csv"></i>&nbsp;CSV',
				className: 'btn btn-default',
				titleAttr: 'CSV',	
				exportOptions: {
					columns: ':not(.notexport)',
					columns: ':visible'
					}
				}			
			, 
				{
				extend: 'excel',
				text: '<i class="fas fa-file-excel"></i>&nbsp;Excel',
				className: 'btn btn-default',
				titleAttr: 'EXCEL',
				exportOptions: {
					columns: ':not(.notexport)',
					columns: ':visible'
					}
				}	
			, 
				{
				extend: 'pdf',
				text: '<i class="fas fa-file-pdf"></i>&nbsp;PDF',
				className: 'btn btn-default',
				titleAttr: 'PDF',
				exportOptions: {
					columns: ':not(.notexport)',
					columns: ':visible'
					}
				}			
			,
                       {
                       extend: 'print',
					   text: '<i class="fas fa-print"></i> Print',
					   titleAttr: 'PRINT',
                       exportOptions: {
                          // stripHtml : false,
                           messageBottom: null,
						   columns: ':not(.notexport)',
						   columns: ':visible'

                           //columns: [ 0, 1, 2, 3, 4 ] //Your Colume value those you want
                           }
                         }
			
        ],
		language: {
        search: "_INPUT_",
        searchPlaceholder: "ค้นหา..."
    	}
    } );
});
</script>
<style>
.dataTables_length,.dataTables_filter {
    margin-left: 10px;
	margin-right: 15px;
    float: right;
}

</style>
<script>
function copyTextToClipboard(text) {
  var textArea = document.createElement("textarea");
  textArea.value = text
  document.body.appendChild(textArea);
  textArea.focus();
  textArea.select();

  try {
    var successful = document.execCommand('copy');
    var msg = successful ? 'successful' : 'unsuccessful';
    console.log('Copying text command was ' + msg);
  } catch (err) {
    console.log('Oops, unable to copy');
  }

  document.body.removeChild(textArea);
}	
function alertload(url,w,h,functions){
	 $.colorbox({fixed:true,width:w,height:h, iframe:true, href:url, onOpen : function () {$('html').css('overflowY','hidden');},onCleanup :function(){
$('html').css('overflowY','auto');}
,onClosed:function(){ functions }});
}  
    
function winreload(){
    window.location.reload();
}
</script>
</head>

<body>
<? if(!isset($_GET['do'])){ ?>
<nav class="navbar navbar-info" style="background-color: #7CA1A0;">
  <span class="text-white h5">
  <i class="fas fa-laptop-house"></i>&ensp;รายชื่อผู้ขออนุญาติใช้ remote desktop
</span>
</nav>
<div class="p-3">
    <table id="example" class="table table-bordered table-hover table-sm display " style="width:100%; font-size:14px">
        <thead>
            <tr>
                <td align="center">user id</td>
                <td align="center">user nmae</td>
                <td align="center">password</td>
                <td align="center">Y=ว่าง</td>
                <td >ผู้ขอใช้ล่าสุด</td>
                <td >วันและเวลาที่ขอ</td>
                <td align="center">สถานะแจ้งเตือน</td>
            </tr>
        </thead>
        <tbody>
            <?php do{ ?>
            <?php 
                mysql_select_db($database_hos, $hos);
                $query_user2 = "select * from dispensing.kohrx_remote_desktop_request where user_id='".$row_user['id']."' order by id DESC limit 1";
                $rs_user2 = mysql_query($query_user2, $hos) or die(mysql_error());
                $row_user2 = mysql_fetch_assoc($rs_user2);
                $totalRows_user2 = mysql_num_rows($rs_user2);  

            ?>
            <tr>
                <td align="center"><?php echo $row_user['id']; ?></td>
                <td align="center"><?php echo $row_user['user_name']; ?></td>
                <td align="center"><?php if($row_user['user_active']=="N"&&($row_user2['random_password']!="")){ ?><?php echo $row_user2['random_password'];  ?>&ensp;<i class="fas fa-copy" onClick="copyTextToClipboard('<?php echo $row_user2['random_password']; ?>')" style="cursor: pointer; font-size: 20px;"></i><?php } ?></td>
                <td align="center"><span class="badge <?php if($row_user['user_active']=="Y"){ echo "badge-success";}else { echo "badge-danger";} ?> p-2"  <?php if($row_user['user_active']=="N"){ ?> onClick="alertload('remote_desktop_user_list.php?do=change_active&user=<?php echo $row_user['user_name']; ?>&id=<?php echo $row_user['id']; ?>&status=<?php echo $row_user['user_active']; ?>','500','300')" <?php } ?> style="font-size: 16px;cursor: pointer"><?php echo $row_user['user_active']; ?><span></span></td>
                <td ><?php if($row_user['user_active']=="N"){ echo $row_user2['officer_name']; } ?></td>
                <td ><?php if($row_user['user_active']=="N"){ echo dateThai($row_user2['request_date']); } ?></td>
                <td align="center" ><?php if($row_user['user_active']=="N"){ if($row_user2['notify_status']=="N"){ ?><button class="btn btn-primary btn-sm" onClick="window.location='remote_desktop_user_list.php?notify=Y&id=<?php echo $row_user2['id']; ?>'" >แจ้งเตือน</button><?php } else { ?><button class="btn btn-secondary btn-sm" >แจ้งเตือนแล้ว</button><? } } ?></td>
            </tr>  
            <?php mysql_free_result($rs_user2); ?>
            <?php }while($row_user = mysql_fetch_assoc($rs_user)); ?>
        </tbody>
    </table>
</div> 
<?php } else { ?>
<form id="form1" method="post" action="remote_desktop_user_list.php">
<div class="p-3 h4 text-center">
ต้องการเปลี่ยนสถานะ user : <?php echo $_GET['user']; ?> จริงหรือไม่?
    <div class="mt-5">
        <input type="hidden" id="status2" name="status2" value="<?php echo $_GET['status']; ?>"/>
        <input type="hidden" id="id" name="id" value="<?php echo $_GET['id']; ?>"/>
        <input type="submit" class="btn btn-danger" value="ใช่" name="status" id="status"/>
    </div>
</div>    
</form>    
<?php }?>
</body>
</html>
<?php mysql_free_result($rs_user); ?>