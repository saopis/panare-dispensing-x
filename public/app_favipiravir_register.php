<?php ob_start();?>
<?php session_start();?>
<?php /*if(($_SESSION['r_opd']!='Y') and ($_SESSION['r_finance']!='Y')){
	echo "คุณไม่ได้รับสิทธิ์ให้ใช้งานในส่วนนี้";
	exit();
	} 
	*/
?>
<?php require_once('Connections/hos.php'); ?>
<?php include('include/function.php'); ?>
<?php include('include/function_sql.php'); ?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
<title>ทะเบียนผู้ป่วยใช้ยา Favipiravir</title>
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
	
<?php include('include/datepicker/datepicker.php'); ?>
	
</script>
		<style type="text/css">

			html, body {
				height: 100%;
				min-height: 100%;
				margin: 0;
				padding: 0;
			}
			body { background-color:#E8CFD4; }

			#top-component {
				bottom: 20em;
			}

			#horizontal-divider {
				bottom: 20em;
				height: 5px;
			}

			#bottom-component {
				height: 92vh;
			}

			#left-component {
				width: 20em;
			}

			#vertical-divider {
				left: 20em;
				width: 5px;
			}

			#right-component {
				left: 20em;
				margin-left: 5px;
			}

			#top-component2 {
				bottom: 0;
			}

			#horizontal-divider2 {
				bottom: 50%;
			}

			#bottom-component2 {
				height: 50%;
			}


		</style>

		<script>
			
	function page_load(divid,page){
						/*
							swal({
								title:"ระบบกำลังประมวลผล", 
								text:"กรุณารอสักครู่...",
								icon: "https://uploads.toptal.io/blog/image/122376/toptal-blog-image-1489080120310-07bfc2c0ba7cd0aee3b6ba77f101f493.gif",
								buttons: false,      
								closeOnClickOutside: false,
								//timer: 3000,
								//icon: "success"
							});	
						*/
			Swal.fire({
				title: 'กำลังโหลด',
				html: 'กรุณารอสักครู่',
				//timer: 2000,
				timerProgressBar: true,
				didOpen: () => {
								Swal.showLoading()
							  }
							})	
		
	//$('#indicator').show();
	$("#"+divid).load(page,function(responseTxt, statusTxt, xhr){
        if(statusTxt == "success")
			swal.close();
		//$('#indicator').hide();            
        if(statusTxt == "error")
            alert("โหลดข้อมูลไม่สำเร็จ กรุณาลองใหม่อีกครั้ง");
		//$('#indicator').hide();            
    	});
	}
			
			$(function() {

				$('div.split-pane').splitPane();				
				page_load('left-app','app_favipiravir_register_record.php');
				page_load('main-app','app_favipiravir_register_list.php');		
			});
			
        function ModalLoad(id,hn){
            //$('#loader1').show();
            $("#ModalBody").html("กรุณารอ..กำลังโหลดข้อมูล");
            $('#Modal').modal('show');

            $.ajax({
				   type: "POST",
				   url: "app_favipiravir_register_record.php",
				   cache: false,
				   data: {id:id,hn:hn,method:'edit'},
				   success: function(html)
					{                        
                        $("#ModalBody").html(html);
                        
					}
				 });    
            
        };	
				/////////////////////////
				function delete_record(id){
							$("#ModalBody").html("กรุณารอ..กำลังโหลดข้อมูล");
            				$('#Modal').modal('show');

							$.ajax({
							url : "app_favipiravir_register_list.php",
							type: "POST",
							data : {did:id},
							cache: false,
							success: function(html)
							{
								//data - response from server
								$("#ModalBody").html(html);
							},
							error: function (jqXHR, textStatus, errorThrown)
							{
								//$('.throw_error').fadeIn(1000).show();
							}
							});					
				};
				///////////////
			
		</script>

</head>

<body>
			<div class="banner p-2"><i class='fas fa-dna' style='font-size:20px'></i>&ensp;ทะเบียนผู้ป่วยใช้ยา Favipiravir</div>

		<div class="p-1"><!-- This div is added for styling purposes only. It's not part of the split-pane plugin. -->

				<div class="split-pane-component" id="bottom-component">
					<div class="split-pane fixed-left" id="split-pane-2">
						<div class="split-pane-component" id="left-component">
							<div class="pretty-split-pane-component-inner" style="padding: 0px; padding-right: 1px; overflow-x: hidden"  id="left-app"><!-- This div is sadfsadfadded for styling purposes only. It's not part of the split-pane plugin. -->
							</div>
						</div>
						<div class="split-pane-divider" id="vertical-divider"></div>
						<div class="split-pane-component" id="right-component">
							<div class="split-pane horizontal-percent">
								<div class="split-pane-component" id="top-component2">
									<div class="pretty-split-pane-component-inner" style="padding: 10px;  overflow-x: hidden" id="main-app"><!-- This div is added for styling purposes only. It's not part of the split-pane plugin. -->
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
		</div>

<!-- Modal -->
<div class="modal fade" id="Modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true" >
  <div class="modal-dialog modal modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header bg-light">
        <h5 class="modal-title" id="exampleModalLongTitle"><i class='fas fa-dna' style='font-size:20px'></i>&nbsp;แก้ไขทะเบียนผู้ป่วยใช้ยา Favipiravir</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
       
      <div class="modal-body" id="ModalBody">

      </div>

    </div>
  </div>
</div>  
	
	
</body>
	<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-36251023-1']);
  _gaq.push(['_setDomainName', 'jqueryscript.net']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>
	
</html>