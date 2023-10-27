<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Untitled Document</title>
<style>
.upload-btn-wrapper {
  position: relative;
  overflow: hidden;
  display: inline-block;
  width: 100%;
  margin-top: 10px;
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
<?php include('bootstrap4.php'); ?>
<script src="https://code.jquery.com/jquery-3.5.1.js"></script>	
	
<script>
$(document).ready(function(){
			 $('.close').click(function(){
				$('.content').fadeOut( "slow" );
			})
			$('.input-save').hide();
});	
</script>
	
</head>

<body>
<form method="post" id="image-form" enctype="multipart/form-data" onSubmit="return false;">	
<div class="upload-btn-wrapper">
	<button class="btn2 browse text-success">+ เลือกรูป&nbsp;<i class="fas fa-image" style="font-size: 30px;"></i>&nbsp;/&nbsp;ถ่ายภาพ&nbsp;<i class="fas fa-camera text-success" style="font-size: 30px; "></i></button>
  <input type="file" name="fileUpload" id="fileUpload" />
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
		<div class="form-group col-md-6">
			<label>หมายเหตุ</label>	
      <input type="text" class="form-control" id="remark" name="remark">
      <input type="hidden" class="form-control" id="an" name="an" value="<?php echo $_POST['an']; ?>">
			
    </div>	
  </div>
	</div>	

		
			<div class="row p-3 input-save" style="display: none">
					<input type="submit" name="submit" id="submit" value="อัพโหลด" class="btn btn-info w-100" >
					<input type="button" name="cancel" id="cancel" value="ยกเลิก" class="btn btn-danger w-100" >
				</div>
	</form>	
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
			$('.browse').hide();
			$('#submit').show();
			$('.input-save').show();
			$('#msg').hide();  
		  };
		  // read the image file as a data URL.
		  reader.readAsDataURL(this.files[0]);
		});
		
		
		$(document).ready(function(e) {
		$('#preview').hide();	
		  $("#image-form").on("submit", function() {
			$("#msg").html('<div class="alert alert-info"><i class="fa fa-spin fa-spinner"></i> Please wait...!</div>');
			var formData = new FormData(this);
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
					'<div class="alert alert-success mt-2" id="success" style="position:absolute; width:100%"><i class="fa fa-thumbs-up"></i> อัพโหลดภาพเรียบร้อย </div>'
				  );
				  //$("#msg").html(html);
					$('#success').fadeOut(5000);
					$('#preview').hide();
					$('#file').val("");
				  	$('.content').hide();
				  	$('.input-save').hide();
					document.getElementById("preview").src = "https://placehold.it/80x80";
				  	order_list();
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