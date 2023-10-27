<?php require_once('Connections/hos.php'); ?>
<?php //echo $_GET['pttype']; ?>
<?php
mysql_select_db($database_hos, $hos);
$query_ipt = "select ipt.an,ipt.hn,ipt.vn,ipt.regdate,ipt.regtime,iptadm.bedno,roomno.name as room,concat(patient.pname,patient.fname,' ',patient.lname) as name,aa.age_y,aa.age_m,ward.name as wardname   from ipt left outer join iptadm on iptadm.an=ipt.an   left outer join patient on patient.hn=ipt.hn  left outer join roomno on roomno.roomno=iptadm.roomno  left outer join an_stat aa on aa.an=ipt.an   left outer join ward w on w.ward = ipt.ward  left outer join ward on ward.ward=roomno.ward  where  ipt.an='".$_GET['an']."'";
$ipt = mysql_query($query_ipt, $hos) or die(mysql_error());
$row_ipt = mysql_fetch_assoc($ipt);
$totalRows_ipt = mysql_num_rows($ipt);
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Untitled Document</title>
<?php //include('bootstrap4.php'); ?>
<?php include('bootstrap4.php'); ?>
<script src="https://code.jquery.com/jquery-3.5.1.js"></script>	

	
<script>
	$(document).ready(function(){
        $('#indicator').show();
        $('#panel-search').fadeOut( "slow" );
        $('#panel-order').fadeOut( "slow" );

		order_list();
		
			
			$('.close').click(function(){
				$('.content').fadeOut( "slow" );
			})
		/*
		$('#camera').click(function(){
			$('#indicator').show();
			//alert();
			var dataString="an=
            $.ajax({
				   type: "POST",
				   url: "take_photo.php",
				   cache: false,
				   data: dataString,
				   success: function(html)
					{
						$("#order_picture").html(html);
						$('#indicator').hide();	
					}
				 });
					
		});	
		*/
		$('#cancel').click(function(){
			order_list();
		});
	});
	
	function order_list(){
                    //$('#indicator').hide();
					$('#preview').hide();
					$('#file').val("");
				  	$('.content').hide();
				  	$('.input-save').hide();
					document.getElementById("preview").src = "https://placehold.it/80x80";
				  	$('#order_list').show();
		
			var dataString="an=<?php echo $_GET['an']; ?>";
			$('#indicator').show();	

            $.ajax({
				   type: "POST",
				   url: "ipd_order_list.php",
				   cache: false,
				   data: dataString,
				   success: function(html)
					{
						$("#order_list").html(html);
						$('#indicator').hide();	
                        $('#remark').val("");
					}
				 });
			
	}

function order_save(medthod,id){
            
            $('#indicator').show();	
			var dataString="an=<?php echo $_GET['an']; ?>"+'&order_save='+medthod+'&id='+id;

            $.ajax({
				   type: "POST",
				   url: "ipd_order_list.php",
				   cache: false,
				   data: dataString,
				   success: function(html)
					{
						$("#order_list").html(html);
						$('#indicator').hide();	
					}
				 });
    
}    
    
	function admit_list(){
			$('#indicator').show();
            $.ajax({
				   type: "POST",
				   url: "ipd_admit_list.php",
				   cache: false,
				   data: "",
				   success: function(html)
					{
						$("#result").html(html);
						$('#indicator').hide();	
					}
				 });		
	}
	
</script>	
<style>
.upload-btn-wrapper {
  position: absolute;
  overflow: hidden;
  display: inline-block;
  width: 50px;
  top: 5px;
  right:0px;
  /*border:solid 1px #000000;*/
}

.btn2 {
  border: 1px dashed gray;
  color: black;
  background-color: #A0F587;
  padding: 8px 20px;
  border-radius: 8px;
  font-size: 20px;
  font-weight: bold;
  width: 100%;
  margin:20 auto;
}

.upload-btn-wrapper input[type=file] {
  font-size: 100px;
  position: absolute;
  left: 0;
  top: 0;
  opacity: 0;
}
.container2 {
  position: relative;
  max-width: 800px; /* Maximum width */
  margin: 0 auto; /* Center it */
}

.container2 .content {
  position: absolute; /* Position the background text */
  bottom: 0; /* At the bottom. Use top:0 to append it to the top */
  background: rgb(0, 0, 0); /* Fallback color */
  background: rgba(0, 0, 0, 0.5); /* Black background with 0.5 opacity */
  color: #f1f1f1; /* Grey text */
  width: 100%; /* Full width */
  padding: 5px; /* Some padding */
}
</style>
	
</head>

<body>
<button class="btn btn-light" onClick="window.location='ipd_admit.php<?php if(isset($_GET['pt_type'])&&($_GET['pt_type']!="")){ echo "?pt_type=".$_GET['pt_type']; } ?>'" style="position: absolute; top: 10px;right: 20px" ><i class="fas fa-chevron-left"></i></button>
<form method="post" id="image_form" name="image_form" enctype="multipart/form-data" >	

<div class="card text-center">
  <div class="card-header">
<ul class="nav nav-tabs card-header-tabs" id="myTab" role="tablist">
<!-- ///////////// Camera ////////////-->
<div class="upload-btn-wrapper text-right" style="cursor: pointer">	
	<i class="fas fa-camera text-secondary browse" style="font-size: 40px;margin-right: 10px; cursor: pointer;" id="camera"></i>
	  <input type="file" name="fileUpload" id="fileUpload" accept=".jpg, .png, .gif " />

</div>
<!-- //////// Camera ///////////////////-->	
  
	<li class="nav-item ">
    <a class="nav-link active" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">ข้อมูลทั่วไป</a>
  </li>
</ul>

</div>
<div class="card-body">
<div class="tab-content" id="myTabContent">
  <div class="tab-pane fade text-left show active" id="profile" role="tabpanel" aria-labelledby="profile-tab">
	  <h2 class="card-title"><?php echo $row_ipt['name']; ?></h2>
	  <div><span class="font-weight-bold" >อายุ:</span>&nbsp;<?php echo $row_ipt['age_y']; ?>&nbsp;ปี&emsp;<?php echo $row_ipt['age_m']; ?>&nbsp;เดือน</div>
	  <div><span class="font-weight-bold" >AN:</span>&nbsp;<?php echo $row_ipt['an']; ?>&emsp;<span class="font-weight-bold" >HN:</span>&nbsp;<?php echo $row_ipt['hn']; ?></div>
</div>
</div>
  </div>
</div>
	

<div >
					<div class="text-center container2">

						<center><img src="https://placehold.it/80x80" style="display:flex;max-width: 100%; " id="preview" class="img-thumbnail"></center>
					<div class="content" style="display: none;">
						<button type="button" class="close" style="position: absolute;right: 5px; top: 0px;">&times;</button>
						<h1>ภาพตัวอย่าง</h1>
						<p>กรุณาตรวจสอบความชัดของภาพก่อนทำการ upload</p>
					  </div>

					</div>

				</div>
	<div class="form-row input-save" style="display: none">
	<div class="form-group col-md-auto">
	<label>วันที่สั่ง</label>
      <input type="text" class="form-control" id="datetimepicker" name="datetimepicker" style="width: 200px;" value="<?php echo date('d/m/Y H:i'); ?>">
		</div>
	<div class="form-group col-md-auto">
	<label>ประเภทผู้ป่วย</label>
	<select name="pt_type" id="pt_type" class="form-control">
		<option value="1" <?php if(!isset($_GET['pt_type'])||$_GET['pt_type']==""){ echo "selected"; } ?> >Admit</option>
		<option value="2" <?php if(isset($_GET['pt_type'])&&($_GET['pt_type']=="2")){ echo "selected"; } ?>>Continue</option>
		<option value="3" <?php if(isset($_GET['pt_type'])&&($_GET['pt_type']=="3")){ echo "selected"; } ?>>Discharge</option>
	</select>	
	</div>		
		<div class="form-group col-md-6">
			<label>หมายเหตุ</label>	
      <input type="text" class="form-control" id="remark" name="remark">
      <input type="hidden" class="form-control" id="an" name="an" value="<?php echo $_GET['an']; ?>">
			
    </div>
			
  </div>
			<div class="row p-3 input-save" style="display: none; margin-top: -20px;">
					<input type="submit" name="submit" id="submit" value="อัพโหลด" class="btn btn-info w-100" >
				</div>
			<div class="row p-3 input-save" style="display: none; margin-top: -20px;">
					<input type="button" name="cancel" id="cancel" value="ยกเลิก" class="btn btn-danger w-100" >
				</div>	
	</form>

<div id="order_picture"></div>
<div id="order_list"></div>
	
<div id="msg"></div>
    
	<script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.6/umd/popper.min.js" integrity="sha384-wHAiFfRlMFy6i5SRaxvfOCifBUQy1xHdJ/yoi7FRNXMRBu5WHdZYu1hA6ZOblgut" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.min.js" integrity="sha384-B0UglyR+jN6CkvvICOB2joaf5I4l3gm9GU6Hc1og6Ls7i6U/mkkaduKaBhlAXv9k" crossorigin="anonymous"></script>
    <script>
		$(document).on("click", ".browse", function() {
		  var file = $(this)
			.parent()
			.parent()
			.parent()
			.find(".file");
		  file.trigger("click");
			
		});
		$('input[type="file"]').change(function(e) {
		 $('#msg').show();
		 $('#msg').html('<div class="text-center" style="font-size:20px;"><i class="fa fa-spin fa-spinner"></i> กำลังโหลดรูปภาพ...</div>');  	
		  var fileName = e.target.files[0].name;
		  $("#file").val(fileName);
		
		  var reader = new FileReader();
		  reader.onload = function(e) {
			// get loaded data and render thumbnail.
			$('#preview').show();
			document.getElementById("preview").src = e.target.result;
			$('.content').show();
			$('#submit').show();
			$('.input-save').show();
			$('#msg').hide();  
			$('#order_list').hide(); 
		  };
		  // read the image file as a data URL.
		  reader.readAsDataURL(this.files[0]);
		});
		
		
		$(document).ready(function(e) {
		$('#preview').hide();
          $('#indicator').hide();
		  $("#image_form").on("submit", function(e) {
			e.preventDefault();  
			$("#msg").html('<div class="alert alert-info"><i class="fa fa-spin fa-spinner"></i> Please wait...!</div>');
            $('#indicator').show();
			var formData = new FormData(this);
			var itemId = $("#userId").val();
			var fileUpload = $("#fileUpload").val();

			//Your Formdata Ready To Send
			formData.append('fileUpload', fileUpload);

			$.ajax({
			  type: "POST",
			  url: "ipd_order_post.php",
			  data: formData, 
			  contentType: false, // The content type used when sending data to the server.
			  cache: false, // To unable request pages to be cached
			  processData: false, // To send DOMDocument or non processed data file it is set to false
			  success: function(html) {
				  $('#msg').show();
				  $("#msg").html(
					'<div class="alert alert-success mt-2" id="success" style="position:absolute; width:100%;top:50%"><i class="fa fa-thumbs-up"></i> อัพโหลดภาพเรียบร้อย </div>'
				  );
				  //$("#msg").html(html);
					$('#success').fadeOut(5000);
					$('#preview').hide();
					$('#file').val("");
				  	$('.content').hide();
				  	$('.input-save').hide();
					document.getElementById("preview").src = "https://placehold.it/80x80";
				  	$('#order_list').show();
				  	$('.upload-btn-wrapper').show();
				  	order_list();
                    $('#indicator').hide();
                  
			  },
			  error: function(html) {
				$("#msg").html(
				  '<div class="alert alert-danger"><i class="fa fa-exclamation-triangle"></i> There is some thing wrong.</div>'
				);
			  }
			});
		  });
		});
	</script>   
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
<?php mysql_free_result($ipt); ?>