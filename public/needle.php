<?php require_once('Connections/hos.php'); ?>
<?php 
include('include/function.php');

if(isset($_GET['action'])&&($_GET['action']=="save"))
{
if($_GET['syring_type']==0){
        echo $_GET['hn'];
		mysql_select_db($database_hos, $hos);
		$query_rs_delete = "delete from ".$database_kohrx.".kohrx_insulin_syring where hn='".$_GET['hn']."'";
		$rs_delete = mysql_query($query_rs_delete, $hos) or die(mysql_error());    
    
        //insert replicate_log
        mysql_select_db($database_hos, $hos);
        $update="insert into replicate_log (`event_time`,`client`,`sql`) values (current_timestamp,'KOHRX','delete from ".$database_kohrx.".kohrx_insulin_syring where hn=\'".$_GET['hn']."\'')";
        $qupdate= mysql_query($update, $hos) or die(mysql_error());
    
}
else{
    mysql_select_db($database_hos, $hos);
    $query_rs_search = "select * from ".$database_kohrx.".kohrx_insulin_syring where hn='".$_GET['hn']."'";
    $rs_search = mysql_query($query_rs_search, $hos) or die(mysql_error());
    $row_rs_search = mysql_fetch_assoc($rs_search);
    $totalRows_rs_search = mysql_num_rows($rs_search);

    if($_GET['syring_type']==1){
        $needle_type1="needle_type=NULL";
        $needle_type2="NULL";
        $needle_type1_log="needle_type=NULL";
        $needle_type2_log="NULL";
    }
    else{
        $needle_type1="needle_type='".$_GET['needle_type']."'";
        $needle_type2="'".$_GET['needle_type']."'";
        $needle_type1_log="needle_type=\'".$_GET['needle_type']."\'";
        $needle_type2_log="\'".$_GET['needle_type']."\'";

    }
    if($totalRows_rs_search<>0){
            mysql_select_db($database_hos, $hos);
            $query_rs_update = "update ".$database_kohrx.".kohrx_insulin_syring set syring_type='".$_GET['syring_type']."',".$needle_type1.",update_date=NOW() where hn='".$_GET['hn']."'";
            $rs_update = mysql_query($query_rs_update, $hos) or die(mysql_error());

        //insert replicate_log
        mysql_select_db($database_hos, $hos);
        $update="insert into replicate_log (`event_time`,`client`,`sql`) values (current_timestamp,'KOHRX','update ".$database_kohrx.".kohrx_insulin_syring set syring_type=\'".$_GET['syring_type']."\',".$needle_type1_log.",update_date=NOW() where hn=\'".$_GET['hn']."\'')";
        $qupdate= mysql_query($update, $hos) or die(mysql_error());


    }

    if($totalRows_rs_search==0){
        if($_GET['syring_type']!=0){
            mysql_select_db($database_hos, $hos);
            $query_rs_update = "insert into ".$database_kohrx.".kohrx_insulin_syring (hn,syring_type,needle_type,update_date) value ('".$_GET['hn']."','".$_GET['syring_type']."',".$needle_type2.",NOW())";
            $rs_update = mysql_query($query_rs_update, $hos) or die(mysql_error());

        //insert replicate_log
        mysql_select_db($database_hos, $hos);
        $update="insert into replicate_log (`event_time`,`client`,`sql`) values (current_timestamp,'KOHRX','insert into ".$database_kohrx.".kohrx_insulin_syring (hn,syring_type,update_date) value (\'".$_GET['hn']."\',\'".$_GET['syring_type']."\',".$needle_type2_log.",NOW())')";
        $qupdate= mysql_query($update, $hos) or die(mysql_error());

        }
    }
    }
echo "<script>parent.$.fn.colorbox.close();parent.drug_list_load_vn('".$_GET['vn']."');</script>";
exit();
}

mysql_select_db($database_hos, $hos);
$query_rs_search = "select * from ".$database_kohrx.".kohrx_insulin_syring where hn='".$_GET['hn']."'";
$rs_search = mysql_query($query_rs_search, $hos) or die(mysql_error());
$row_rs_search = mysql_fetch_assoc($rs_search);
$totalRows_rs_search = mysql_num_rows($rs_search);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<?php include('java_css_file.php'); ?>
<!-- colorbox -->
<script src="include/image-picker/image-picker.js"></script>
<link rel="stylesheet" href="include/image-picker/image-picker.css"/>    
<script>
    $(document).ready(function(){
        $('#save').click(function(){
                
                window.location.href='needle.php?action=save&syring_type='+$('#syring_type').val()+'&needle_type='+$('#needle_type').val()+'&hn='+$('#hn').val()+'&vn='+$('#vn').val();
                
        });
         $('.syring_img').hide();
         $('.card').hide();         
         $('#needle_select').hide();         
        
        <?php if($row_rs_search['syring_type']==""){ ?>
            $('#syring_type').val(0);
            $('.syring_img').hide();           
        <?php }?>
        <?php if($row_rs_search['syring_type']!=""){ ?>
            $('#syring_type').val('<?php echo $row_rs_search['syring_type']; ?>');
            <?php if($row_rs_search['syring_type']>="1"){ ?>
                $('#syring<?php echo $row_rs_search['syring_type']; ?>').show();
                $('.card').show();  
                <?php if($row_rs_search['syring_type']>"1"){ ?>
                    $('#needle_select').show();
                <?php if($row_rs_search['needle_type']==""){ ?>
                        $('#needle_type').val('1');
                <?php } ?>
                <?php if($row_rs_search['needle_type']!=""){ ?>
                        $('#needle_type').val('<?php echo $row_rs_search['needle_type']; ?>');
                <?php } ?>
                                                         
            <?php } ?>
            <?php } ?>
        <?php }?>
        
       
        $('#syring_type').change(function(){
            $('.syring_img').hide(); 

                if($('#syring_type').val()=="1"){
                    $('.card').show();         
                    $('#syring1').show();
                    $('#needle_select').hide();
                }
                else if($('#syring_type').val()=="2"){
                    $('.card').show();         
                    $('#syring2').show();
                    $('#needle_select').show();
                }
                else if($('#syring_type').val()=="3"){
                    $('.card').show();         
                    $('#syring3').show();
                    $('#needle_select').show();
                }
                else if($('#syring_type').val()=="4"){
                    $('.card').show();         
                    $('#syring4').show();
                    $('#needle_select').show();
                }			
                else{
                    $('.card').hide();         
                    $('.syring_img').hide(); 
                    $('#needle_select').hide();
                }
        });
        $("#needle_type").imagepicker({
          hide_select : true,
          show_label  : false
        })
    });
</script>      
    
<style>
input[type='radio']:after {
        width: 20px;
        height: 20px;
        border-radius: 15px;
        top: -2px;
        left: -1px;
        position: relative;
        background-color: #d1d3d1;
        content: '';
        display: inline-block;
        visibility: visible;
        border: 2px solid white;
    }

    input[type='radio']:checked:after {
        width: 20px;
        height:20px;
        border-radius: 15px;
        top: -2px;
        left: -1px;
        position: relative;
        background-color: #F69;
        content: '';
        display: inline-block;
        visibility: visible;
        border: 2px solid white;
    }
    
</style>
<style type="text/css">
            .thumbnails li img{
                width: 50px;
            }
</style>
</head>
<body>
<form id="form1" name="form1" method="post" action="">
<nav class="navbar navbar-dark thfont " style="background-color: #F69; color:#FFFFFF;">
  <!-- Navbar content -->
  <span class="card-title" style="padding-top:5px;">
<i class="fas fa-syringe">&ensp;บันทึกเข็มที่ผู้ป่วยใช้ฉีดอินซูลิน</i>
</span>
</nav>

  <div class="container mt-2">
    <div class="row p-3">
        <label class="col-form-label-sm col-5">เลือกชนิดของเข็มที่ใช้</label>
        <div class="col">
        <select id="syring_type" class="form-control-sm">
                <option value="0">ผู้ป่วยไม่ได้ฉีดอินซูลิน</option>
                <option value="1">Syring</option>
                <option value="2" class="bg-danger">Penfil (1 click= 2 units)</option>
                <option value="3" class="bg-info">Penfil (1 click= 1 units)</option>
                <option value="4" class="bg-light">MJpen (1 click= 1 units)</option>
		</select>            
      </div>
    </div>
        <div class="card">
         <div class="card-body text-center">
            <div id="syring1" class="syring_img"><img src="images/syring_insulin.jpg" width="350"  alt=""/></div>
            <div id="syring2" class="syring_img"><img src="images/gensupen.png" width="350"  alt=""/></div>
            <div id="syring3" class="syring_img"><img src="images/gensupen2.png" width="350"  alt=""/></div>
            <div id="syring4" class="syring_img"><img src="images/mjpen.png" width="350" style="height: auto"  alt=""/></div>
		</div>
      </div>
        <div class="row p-3" style="padding-bottom: 0px;" id="needle_select"> 
            <label class="col-form-label-sm col-4 text-right">เลือกชนิดเข็มที่ใช้</label> 
            <div class="col">
            <select class="image-picker" id="needle_type">
              <option value=""></option>
              <option data-img-src="images/5mm.png" value="0" <?php if($row_rs_search['needle_type']==0){ echo "selected"; } ?> >5 mm</option>
              <option data-img-src="images/6mm.png" value="1" <?php if($row_rs_search['needle_type']==1||$row_rs_search['needle_type']==""){ echo "selected"; } ?> >6 mm</option>
              <option data-img-src="images/8mm.png" value="2" <?php if($row_rs_search['needle_type']==2){ echo "selected"; } ?>>8 mm</option>
            </select>
            </div>
        </div>
  </div>
  <div class="text-center mt-2" >
  <input type="button" class="btn btn-danger btn-lg" name="save" id="save" value="บันทึก" />
  <input name="vstdate" type="hidden" id="vstdate" value="<?php echo $_GET['vstdate']; ?>" />
  <input name="hn" type="hidden" id="hn" value="<?php echo $_GET['hn']; ?>" />
  <input name="vn" type="hidden" id="vn" value="<?php echo $_GET['vn']; ?>" />
  </div>
</form>
</body>
</html>