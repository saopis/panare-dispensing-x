<?php require_once('Connections/hos.php'); ?>
<?php if($_GET['action']=="select"){
			mysql_select_db($database_hos, $hos);
			$query_rs_drug = "select concat(name,' ',strength) as drugname,k.icode from ".$database_kohrx.".kohrx_had k left outer join drugitems d on d.icode=k.icode where istatus='Y' order by d.name ASC ";
			//echo $query_rs_drug;
			$rs_drug = mysql_query($query_rs_drug, $hos) or die(mysql_error());
			$row_rs_drug = mysql_fetch_assoc($rs_drug);
			$totalRows_rs_drug = mysql_num_rows($rs_drug);
		} 

?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>ระบบทะเบียนคุมยาความเสี่ยงสูง</title>
<?php if(!isset($_GET['action'])){ ?>
	
<?php include('java_css_file.php'); ?>
<link rel="stylesheet" href="include/split/css/split-pane.css" />
		<!-- The style sheet below is not part of the split-pane plugin. Feel free to use it, or style things your own way. -->
<link rel="stylesheet" href="include/split/css/pretty-split-pane.css" />
<script src="https://code.jquery.com/jquery-1.12.4.min.js" integrity="sha384-nvAa0+6Qg9clwYCGGPpDQLVpLNn0fRaROjHqs13t4Ggj3Ez50XnGQqc/r8MhnRDZ" crossorigin="anonymous"></script>
<script src="include/split/js/split-pane.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/js/bootstrap.min.js"></script>
<script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.1/js/buttons.bootstrap4.min.js"></script>
<script src="https://cdn.datatables.net/select/1.3.1/js/dataTables.select.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.1/js/buttons.flash.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.1/js/buttons.print.min.js"></script>
<script src="https://kit.fontawesome.com/1ed6ef1358.js" crossorigin="anonymous"></script>
<script src="https://cdn.datatables.net/buttons/1.6.4/js/buttons.colVis.min.js"></script>
<!--
<script src="include/sweetalert/sweetalert.min.js"></script>
<link rel="stylesheet" href="include/sweetalert/sweetalert.css"/>	
-->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.4.17/dist/sweetalert2.all.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.4.17/dist/sweetalert2.css">	
	
	
<script>
function sweetalertLoad(){
					Swal.fire({
						  title: 'กำลังโหลดข้อมูล',
						  html: 'กรุณารอสักครู่',
						  //timer: 2000,
						  timerProgressBar: true,
						 allowOutsideClick: false,
						 allowEscapeKey: false,	
						  didOpen: () => {
							Swal.showLoading()
						  }
				})
}
function modalClose(){
	$('#Modal').modal('hide');
	//loadNotify();
}
function modal2Close(){
	$('#Modal2').modal('hide');
	//loadNotify();
}		
function hadSelected(icode,drug,action){
		$('#icode').val(icode);
		$('#navtitle').text(drug);
		Drugload(icode,action);
		//alert();
		modalClose();
}	


	function ModalLoad(title,url,action){
            $("#ModalBody").html("กรุณารอ..กำลังโหลดข้อมูล");
			$('#exampleModalLongTitle').text(title);
            $('#Modal').modal('show');
            var dataString='action='+action;

            $.ajax({
				   type: "GET",
				   url: url,
				   cache: false,
				   data: dataString,
				   success: function(html)
					{                        
                        $("#ModalBody").html(html);
					}
				 });            
        }
	function ModalLoad2(title,url,action,id,hn){
			
            $("#ModalBody2").html("กรุณารอ..กำลังโหลดข้อมูล");
			$('#exampleModalLongTitle2').text(title);
            $('#Modal2').modal('show');

            $.ajax({
				   type: "POST",
				   url: url,
				   cache: false,
				   data: {action:action,icode:$('#icode').val(),id:id,hn:hn},
				   success: function(html)
					{                        
                        $("#ModalBody2").html(html);
					}
				 });            
        }	

	function Drugload(icode,action){
			//alert(action);
				Swal.fire({
						  title: 'กำลังโหลดข้อมูล',
						  html: 'กรุณารอสักครู่',
						  //timer: 2000,
						  timerProgressBar: true,
						 allowOutsideClick: false,
						 allowEscapeKey: false,	
						  didOpen: () => {
							Swal.showLoading()
						  }
				})
	            $.ajax({
				   type: "POST",
				   url: 'app_had_item.php',
				   cache: false,
				   data: {action:action,icode:icode},
				   success: function(html)
					{                        
                        $("#list").html(html);
						Swal.close();
					}
				 });  
		}
	
	function Drugload2(action,icode){
			//alert(action);
				Swal.fire({
						  title: 'กำลังโหลดข้อมูล',
						  html: 'กรุณารอสักครู่',
						  //timer: 2000,
						  timerProgressBar: true,
						 allowOutsideClick: false,
						 allowEscapeKey: false,	
						  didOpen: () => {
							Swal.showLoading()
						  }
				})
	            $.ajax({
				   type: "POST",
				   url: 'app_had_item.php',
				   cache: false,
				   data: {action:action,icode:icode,datestart:$('#datestart').val(),dateend:$('#dateend').val()},
				   success: function(html)
					{                        
                        $("#list2").html(html);
						Swal.close();
					}
				 });  
		}
				
	function delete_record(id){
							$("#ModalBody2").html("กรุณารอ..กำลังโหลดข้อมูล");
            				$('#Modal2').modal('show');

							$.ajax({
							url : "app_had_item.php",
							type: "POST",
							data : {did:id,action:'operate',icode:$('#icode').val()},
							cache: false,
							success: function(html)
							{
								//data - response from server
								$("#ModalBody2").html(html);
							},
							error: function (jqXHR, textStatus, errorThrown)
							{
								//$('.throw_error').fadeIn(1000).show();
							}
							});					
				};
	function monitorPrint(){
		window.open('document/had/'+$('#icode').val()+'.pdf','_new');	
	}
	
	function ptPrint(hn,vstdate){
		window.open('app_had_monitor_print.php?hn='+hn+'&vstdate='+vstdate+'&icode='+$('#icode').val(),'_new');	
	}
	
	$(document).ready(function(){
	<?php
	if(!isset($_GET['had_item'])&&($_GET['had_item']=="")){ ?>
		//alert();
		ModalLoad('เลือกรายการยาความเสี่ยงสูง','app_had_stock.php','select');
	<?php }	?>
		
	});	
<?php } ?>	
</script>
<style>
	html,body{
		overflow-x: hidden;
	}
</style>	

</head>

<body>
<?php if($_GET['action']=="select"){ ?>
	<div style="overflow:scroll;overflow-x:hidden;overflow-y:auto; height:75vh; margin-right: -10px; margin-top: -10px; margin-bottom: -10px ">
	<?php $i=0; do{ $i++; ?>
	<?php
			mysql_select_db($database_hos, $hos);
			$query_rs_remain = "select (select sum(qty) from ".$database_kohrx.".kohrx_had_use where icode='".$row_rs_drug['icode']."' and in_out_method='1') as receive,(select sum(qty) from ".$database_kohrx.".kohrx_had_use where icode='".$row_rs_drug['icode']."' and in_out_method='2') as pay ";
			//echo $query_rs_drug;
			$rs_remain = mysql_query($query_rs_remain, $hos) or die(mysql_error());
			$row_rs_remain = mysql_fetch_assoc($rs_remain);
			$totalRows_rs_remain = mysql_num_rows($rs_remain);
			
			if($row_rs_remain['receive']<>""){
			$receive=$row_rs_remain['receive'];
			}
			else{
				$receive=0;
			}
			if($row_rs_remain['pay']<>""){
			$pay=$row_rs_remain['pay'];
			}
			else{
				$pay=0;
			}				   
			
			mysql_free_result($rs_remain);
	?>	
	<div class="row p-3" onClick="hadSelected('<?php echo $row_rs_drug['icode']; ?>','<?php echo $row_rs_drug['drugname']; ?>','load');" style="cursor: pointer"><span class="badge badge-danger p-1 font16" style="width: 30px; height: 30px; border-radius: 50%; "><?php echo $i; ?></span>&emsp;<?php echo $row_rs_drug['drugname']; ?>&ensp;<span class="badge badge-dark p-2 font16" style="width: 50px"><?php echo $receive-$pay; ?></span> </div>
	<?php } while($row_rs_drug = mysql_fetch_assoc($rs_drug)); ?>
	</div>	
<?php } else if($_GET['action']==""){ ?>
<nav class="navbar navbar-expand-lg navbar-light text-dark " style="background-color: #7EB2DF" >
	<span class="thfont text-white">ระบบทะเบียนคุมยาความเสี่ยงสูง :</span>&nbsp;<span id="navtitle" class="thfont font-weight-bolder font20"></span>&emsp;<button class="btn btn-primary" onClick="ModalLoad('เลือกรายการยาความเสี่ยงสูง','app_had_stock.php','select');">เลือกรายการ</button>
</nav>	

	
<input type="hidden" name="icode" id="icode" />	
	
<!-- modal	-->
<div class="modal fade" id="Modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true" >
  <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header bg-danger text-white">
        <h5 class="modal-title" id="exampleModalLongTitle">เลือกตัวยา</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
       
      <div class="modal-body" id="ModalBody">

      </div>

    </div>
  </div>
</div>  
<!-- modal	-->
<div class="modal fade" id="Modal2" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true" >
  <div class="modal-dialog modal modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header bg-info text-white">
        <h5 class="modal-title" id="exampleModalLongTitle2">เลือกตัวยา</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
       
      <div class="modal-body" id="ModalBody2">

      </div>

    </div>
  </div>
</div>  
<!-- modal	-->	
<div id="list"></div>	
<?php } ?>	

	
</body>
	

</html>