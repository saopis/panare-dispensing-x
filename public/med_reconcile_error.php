<?php ob_start();?>
<?php session_start();?>
<?php require_once('Connections/hos.php'); ?>
<?php
if($_GET['do']=="save"){
    mysql_select_db($database_hos, $hos);
    $query_insert = "insert into ".$database_kohrx.".kohrx_med_reconcile_error (med_reconcile_id,error_type,error_cause,error_subtype,detail,solv,reporter,category,consult,drug_type) value ('".$_GET['med_reconcile_id']."','".$_GET['error_type']."','".$_GET['error_cause']."','".$_GET['error_subtype']."','".$_GET['detail']."','".$_GET['solv']."','".$_SESSION['doctorcode']."','".$_GET['category']."','".$_GET['consult']."','".$_GET['drug_type']."')";
    $insert = mysql_query($query_insert, $hos) or die(mysql_error());
    echo "<script>parent.$.fn.colorbox.close();parent.med_reconcile_load();</script>";
    exit();
}
if($_GET['do']=="edit"){
    mysql_select_db($database_hos, $hos);
    $query_update = "update ".$database_kohrx.".kohrx_med_reconcile_error set error_type='".$_GET['error_type']."',error_cause='".$_GET['error_cause']."',error_subtype='".$_GET['error_subtype']."',detail='".$_GET['detail']."',solv='".$_GET['solv']."',reporter='".$_SESSION['doctorcode']."',category='".$_GET['category']."',consult='".$_GET['consult']."',drug_type='".$_GET['drug_type']."' where id='".$_GET['id']."'";
    $update = mysql_query($query_update, $hos) or die(mysql_error());
    echo "<script>window.location='med_reconcile_error_detail.php?med_reconcile_id=".$_GET['med_reconcile_id']."';</script>";
    exit();
}

/*
for($i=41;$i<=80;$i++){
    if($i%2==0){
                mysql_select_db($database_hos, $hos);
                $query_update = "insert into drugusage_1 (code,name1,name2,shortlist,status,iperday,dosageform,ename1,ename2,iperdose) value ('0-0-".$i."','ฉีดยาใต้ผิวหนังก่อนอาหาร 15 นาที','ก่อนอาหารเย็น  ".$i."  ยูนิต','0-0-".$i." ฉีดใต้ผิวหนัง','Y','1','','ฉีดใต้ผิวหนัง','ก่อนอาหารเย็น  ".$i."  ยูนิต','".$i."') ";
                $udpate = mysql_query($query_update, $hos) or die(mysql_error());
    }
}
*/
?>
<?php
mysql_select_db($database_hos, $hos);
$query_type_error = "SELECT * FROM ".$database_kohrx.".kohrx_med_error_error_type where mr='Y' ORDER BY `order_type`ASC";
$type_error = mysql_query($query_type_error, $hos) or die(mysql_error());
$row_type_error = mysql_fetch_assoc($type_error);
$totalRows_type_error = mysql_num_rows($type_error);

mysql_select_db($database_hos, $hos);
$query_rs_drug = "SELECT m.* FROM  ".$database_kohrx.".kohrx_med_reconcile m where m.id='".$_GET['id']."'";
$rs_drug = mysql_query($query_rs_drug, $hos) or die(mysql_error());
$row_rs_drug = mysql_fetch_assoc($rs_drug);
$totalRows_rs_drug = mysql_num_rows($rs_drug);

if($_GET['action']=="edit"){
	mysql_select_db($database_hos, $hos);
	$query_rs_edit = "SELECT * FROM  ".$database_kohrx.".kohrx_med_reconcile_error where id='".$_GET['id']."'";
	$rs_edit = mysql_query($query_rs_edit, $hos) or die(mysql_error());
	$row_rs_edit = mysql_fetch_assoc($rs_edit);
	$totalRows_rs_edit = mysql_num_rows($rs_edit);
}
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Untitled Document</title>
<?php include('java_css_file.php'); ?> 
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.11/summernote-bs4.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.11/summernote-bs4.min.js"></script>
<script>
    $(document).ready(function(){

		
        //$('#save').prop('disabled',true);
        $('#save').click(function(){
            window.location='med_reconcile_error.php?do=save&med_reconcile_id=<?php echo $_GET['id']; ?>&vn=<?php echo $row_rs_drug['vn']; ?>&error_type='+$('#error_type').val()+'&error_cause='+$('#error_cause').val()+'&error_subtype='+$('#error_subtype').val()+'&detail='+encodeURIComponent($('#detail').val())+'&solv='+encodeURIComponent($('#suggest').val())+'&category='+$('#category').val()+'&consult='+$('#consult').val()+'&drug_type='+$('#drug_type').val();
        });
        $('#edit').click(function(){
            window.location='med_reconcile_error.php?do=edit&med_reconcile_id=<?php echo $row_rs_edit['med_reconcile_id']; ?>&id=<?php echo $_GET['id']; ?>&error_type='+$('#error_type').val()+'&error_cause='+$('#error_cause').val()+'&error_subtype='+$('#error_subtype').val()+'&detail='+encodeURIComponent($('#detail').val())+'&solv='+encodeURIComponent($('#suggest').val())+'&category='+$('#category').val()+'&consult='+$('#consult').val()+'&drug_type='+$('#drug_type').val();
        });
		
    $('#detail').summernote({
  minHeight: 100,
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

$('#suggest').summernote({
  minHeight: 100,
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
            //เลือกความคลาดเคลื่อนหลัก
        $("#error_type").change(function()
            {
                $('#error_subtype').html('<select><option value="">= เลือกประเภทย่อย 2 =</option></select>');

                if($(this).val()!=""&&$('#error_cause').val()!=""){
                    $('#save').prop('disabled',false);
                }
                else {
                    $('#save').prop('disabled',true);
                }

            var id=$(this).val();
            var dataString = 'id='+ id+'&type=main&mr=Y';
            $("#error_subtype").val("");
            $.ajax
            ({
            type: "POST",
            url: "mederror/get_error_type.php",
            data: dataString,
            cache: false,
            success: function(html)
            {
            $("#error_cause").html(html);
            } 
            });
        });
        //===============//
        //เลือกความคลาดเคลื่อนย่อย1
        $("#error_cause").change(function()
            {

                if($(this).val()!=""&&$('#error_type').val()!=""){
                    $('#save').prop('disabled',false);
                }
                else {
                    $('#save').prop('disabled',true);
                }


            var id=$(this).val();
            var dataString = 'id='+ id+'&type=sub&mr=Y';
            $.ajax
            ({
            type: "POST",
            url: "mederror/get_error_type.php",
            data: dataString,
            cache: false,
            success: function(html)
            {
            $("#error_subtype").html(html);
            } 
            });
        });
        //===============//
        //เลือกความคลาดเคลื่อนย่อย2
        $("#error_subtype").change(function(){
                $('#error_subtype_edit').hide();
                $('#error_subtype_open').show();

                if($(this).val()!=""){
                    $('#error_subtype_edit_btn').show();
                    $('#error_subtype_delete_btn').show();
                }
                else {
                    $('#error_subtype_edit_btn').hide();		
                    $('#error_subtype_delete_btn').hide();
                }

        });
//============ edit ============//
		<?php if($_GET['action']=="edit"){ ?>
			$('#error_type').val('<?php echo $row_rs_edit['error_type']; ?>');

                $('#error_subtype').html('<select><option value="">= เลือกประเภทย่อย 2 =</option></select>');

            var id=<?php echo $row_rs_edit['error_type'];?>;
            var dataString = 'id='+ id+'&type=main';
            $("#error_subtype").val("");
            $.ajax
            ({
            type: "POST",
            url: "mederror/get_error_type.php",
            data: dataString,
            cache: false,
            success: function(html)
            {
            $("#error_cause").html(html);
			$('#error_cause').val('<?php echo $row_rs_edit['error_cause']; ?>');
            } 
            });	
			<?php if($row_rs_edit['error_subtype']!=0){ ?>
				//subtype
				var id=<?php echo $row_rs_edit['error_cause']; ?>;
				var dataString = 'id='+ id+'&type=sub';
				$.ajax
				({
				type: "POST",
				url: "mederror/get_error_type.php",
				data: dataString,
				cache: false,
				success: function(html)
				{
				$("#error_subtype").html(html);
				$("#error_subtype").val('<?php echo $row_rs_edit['error_subtype']; ?>');
				} 
				});
		
			<?php } ?>
			//category
			$('#category').val('<?php echo $row_rs_edit['category']; ?>');
			$('#consult').val('<?php echo $row_rs_edit['consult']; ?>');
			$('#drug_type').val('<?php echo $row_rs_edit['drug_type']; ?>');
		
		<?php } ?>
		
    });
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

<body >
<nav class="navbar navbar-dark bg-info text-white " style="padding-bottom: 10px;" >
  <!-- Navbar content -->
  <ul class="navbar-nav">
    <li class="nav-item">
      <i class="fas fa-list-alt font20"></i>&ensp;บันทึกความคลาดเคลื่อนทางยาจาก Med. Reconcile
	  &emsp;<?php if($_GET['action']=="edit"){ ?><button class="btn btn-dark btn-sm" onClick="window.location='med_reconcile_error_detail.php?med_reconcile_id=<?php echo $row_rs_edit['med_reconcile_id']; ?>'">ย้อนกลับ</button><?php } ?>
	  </li>
  </ul>
	
</nav>
<div class="p-3" style="overflow:scroll;overflow-x:hidden;overflow-y:auto; height:90vh;">
    <div class="row pl-3">
        <label class="col-form-label col-2">ชื่อยา</label>
        <div class="col-10 text-danger" style="font-size: 20px;">
            <?php echo $row_rs_drug['drug_name']; ?>            
        </div>
    </div>
    
    <div class="row pl-3">
        <label class="col-form-label col-2">เลือกประเภท</label>
        <div class="col-1">
                <span class="badge badge-dark" style="font-size:16px;">1</span>
        </div>
        <div class="col-5">
            <select class="form-control form-control-sm" id="error_type">
                		<option value="">-- กรุณาเลือก --</option>	
                        <?php do { ?>
                            <option value="<?php echo $row_type_error['id']; ?>"><?php echo $row_type_error['type_thai']; ?></option>
                        <?php
                        } while ($row_type_error = mysql_fetch_assoc($type_error));
                          $rows = mysql_num_rows($type_error);
                          if($rows > 0) {
                              mysql_data_seek($type_error, 0);
                             $row_type_error = mysql_fetch_assoc($type_error);
                          }
                        ?>

                </select>    
        </div>
    </div>
    <div class="row pl-3">
        <label class="col-form-label col-2"></label>
        <div class="col-1">
                <span class="badge badge-dark" style="font-size:16px;">2</span>
        </div>
        <div class="col-5">
        <select id="error_cause" name="error_cause" class="form-control form-control-sm">
                <option value="">= เลือกประเภทย่อย 1 =</option>
            </select>
    </div>
    </div>   
    <div class="row pl-3 mt-2">
        <label class="col-form-label col-2"></label>
        <div class="col-1">
                <span class="badge badge-dark" style="font-size:16px;">3</span>
        </div>
        <div class="col-5">
            <select id="error_subtype" name="error_subtype" class="form-control form-control-sm">
                <option value="">= เลือกประเภทย่อย 2 =</option>
            </select>
    </div>
    </div> 
    <div class="row pl-3 mt-2">
        <label class="col-form-label col-2">ระดับความรุนแรง</label>
        <div class="col-1">
                <span class="badge badge-dark" style="font-size:16px;">4</span>
        </div>
        <div class="col-5">
            <select id="category" name="category" class="form-control form-control-sm">
                <option value="A">A</option>
                <option value="B">B</option>
                <option value="C">C</option>
                <option value="D">D</option>
                <option value="E">E</option>
                <option value="F">F</option>
                <option value="G">G</option>
                <option value="H">H</option>
                <option value="I">I</option>    
            </select>
    </div>
    </div> 
    <div class="row pl-3 mt-2">
        <label class="col-form-label col-3">การขอคำปรึกษาจากผู้สั่ง</label>
        <div class="col-5">
			<select id="consult" class="form-control">
				<option value="0">ไม่ได้ consult</option>
				<option value="1">consult แล้วยืนยันไม่ปรับเปลี่ยน</option>
				<option value="2">consult แล้วยืนยันปรับเปลี่ยน</option>
			</select>
		</div>
        <label class="col-form-label col-auto">ประเภทยา</label>
        <div class="col-2">
			<select id="drug_type" class="form-control">
				<option value="1">Admit</option>
				<option value="2">D/C</option>
			</select>
		</div>
		
	</div>
	<div class="row pl-3 mt-2">
        <div class="col-12">
            <div>บรรยายรายละเอียด</div>
			<textarea name="detail" id="detail" style="height:" ><?php if($_GET['action']=="edit"){ echo $row_rs_edit['detail']; } else { echo $row_rs_drug['drug_name']." สั่งใช้ ".$row_rs_drug['drugusage']; } ?></textarea>
            <div>การแก้ไข</div>
            <textarea name="suggest" cols="70" rows="3" id="suggest"><?php if($_GET['action']=="edit"){ echo $row_rs_edit['solv']; } ?></textarea>            
    </div>
    </div> 
    <div class="pl-3 mt-2 text-right" ><?php if($_GET['action']=="edit"){ ?><button class="btn btn-danger" id="edit" >แก้ไข</button><?php }else{ ?><button class="btn btn-success" id="save" disabled >บันทึก</button><?php } ?></div>
</div>
</body>
</html>
<?php mysql_free_result($type_error); mysql_free_result($rs_drug); ?>