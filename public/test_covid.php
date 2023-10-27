<?php $date="11".date('/m/').(date('Y')+543); ?>

<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Untitled Document</title>
<link href="include/kohrx/css/kohrx.css" rel="stylesheet" type="text/css" />

<?php include('java_css_file.php'); ?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.15/jquery.mask.min.js" ></script>
<script type="text/javascript">
$(document).ready(function() {    
//search

    $('#date1').val('<?php echo $date; ?>');
	
    $('#save').click(function(){
            $('#indicator').show();
        
                 var date1=$('#date1').val(),
                     hn=$('#hn').val();
                     
              $.ajax({
              type:"POST",
              url: "test_covid_save.php",
              data: {date1:date1,
                     hn:hn
                     ,function:'submit'},	  
              success: function(data){
                  $('#indicator').hide();
                  $('#result').html(data);
              }
            });
    });
	

});

</script>
    
</head>

<body>
<div class="card m-2" id="search-tool">
    <div class="card-body">
        <div class="row">
            <div class="col-sm-auto">
                <div class="form-row">
                    <div class="form-group col-auto">
                        <label>จากวันที่</label>
                        <input type="text" class="form-control form-control-sm" name="date1" id="date1" data-date-language="th-th" />
                    </div>    
                    <div class="form-group col-auto">
                        <label>hn</label>
                          <input type="text" id="hn" name="hn" class="form-control form-control-sm" style="padding: 3px;" />  
                        
                    </div>  
                      					
                    <div class="form-group col-auto">
                        <button class="btn btn-primary btn-sm" id="save" name="save" style="margin-top: 32px;">บันทึก</button>                        
                    </div>                       
                </div>    
                
            </div>
		</div>


                   
</div>
</div>
<div id="indicator"  align="center" style="position:absolute; display:none; z-index:1000;padding:0px;"><img src="images/indicator.gif" hspace="10" align="absmiddle" /></div><div id="result" class="p-2">&nbsp;</div>
    
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.2.0/js/bootstrap-datepicker.min.js"></script>
<script type="text/javascript" src="include/datepicker/js/bootstrap-datepicker-thai.js"></script>
<script src="include/datepicker/js/locales/bootstrap-datepicker.th.js"></script>    
<link rel="stylesheet" type="text/css" href="include/datepicker/css/datepicker.css" />

<script type="text/javascript">
$(document).ready(function(){
    $("#date1").datepicker( {
    format: "dd/mm/yyyy",
    startView: "days", 
    minViewMode: "days"
    });
    $("#date2").datepicker( {
    format: "dd/mm/yyyy",
    startView: "days", 
    minViewMode: "days"
    });    

});
</script>
    
</body>
</html>