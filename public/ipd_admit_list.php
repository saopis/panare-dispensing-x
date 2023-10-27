<?php require_once('Connections/hos.php'); ?>
<?php
if($_POST['action']=="search"){
    echo $_POST['hnan'];
		if($_POST['datecheck']=="Y"){
			if($_POST['pttype']=="ad"){
				$search="and ipt.dchstts is null  and ipt.regdate between '".$_POST['datestart']."' and '".$_POST['dateend']."' ";
			}
			else if($_POST['pttype']=="dc"){
				$search="and ipt.dchdate between '".$_POST['datestart']."' and '".$_POST['dateend']."'";
			} 
		}
		if($_POST['datecheck']=="N"){
				    $search="and ipt.dchstts is null";                    
		}
		
    if($_POST['ward']!=""){
        $ward="and ipt.ward='".$ward."'";
    }	
    if($_POST['hnan']!=""){
        $search.=" and ( locate('".$_POST['hnan']."',ipt.an) or locate('".$_POST['hnan']."',ipt.hn))";
    }
    if($_POST['order']!=""){
        if($_POST['order']=="1"){
            $order=" order by ipt.an";
        }
        else if($_POST['order']=="2"){
            $order=" order by iptadm.bedno";
        }
    }
    
}
else{
    $search="and ipt.dchstts is null";                    
}

if($_POST['action']=="order"){
    if($_POST['order_type']=="1"){
        $order=" order by ipt.an";
    }
    else if($_POST['order_type']=="2"){
        $order=" order by iptadm.bedno";
    }
    
}
if(!isset($_POST['action']) or ($_POST['action']=="")){
        $order="order by ipt.regdate,ipt.regtime";  
}


include('include/function.php');


mysql_select_db($database_hos, $hos);
$query_ipt = "select ipt.an,ipt.hn,ipt.vn,ipt.regdate,ipt.regtime,iptadm.bedno,roomno.name as room,concat(patient.pname,patient.fname,' ',patient.lname) as name,aa.age_y,aa.age_m,ward.name as wardname  from ipt left outer join iptadm on iptadm.an=ipt.an   left outer join patient on patient.hn=ipt.hn  left outer join roomno on roomno.roomno=iptadm.roomno  left outer join an_stat aa on aa.an=ipt.an   left outer join ward w on w.ward = ipt.ward  left outer join ward on ward.ward=roomno.ward  where ipt.regdate is not null ".$ward." ".$search."    ".$order;
//echo $query_ipt;
$ipt = mysql_query($query_ipt, $hos) or die(mysql_error());
$row_ipt = mysql_fetch_assoc($ipt);
$totalRows_ipt = mysql_num_rows($ipt);
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Untitled Document</title>

<style>

.container0 {
   height: auto;
   overflow: hidden;
}
.left {
    width: 100px;
    float: left;
}

.right {
    float: none; /* not needed, just for clarification */
    /* the next props are meant to keep this block independent from the other floated one */
    width: auto;
}
</style>  
<?php include('bootstrap4.php'); ?>
<script src="https://code.jquery.com/jquery-3.5.1.js"></script>	
    
<script>
	$(document).ready(function(){
		$('#indicator').hide(); 
        <?php if($_POST['datecheck']=="Y"){ ?>
            $(".checkdate").show();
        <? }else{ ?>
            $(".checkdate").hide();
        <?php } ?>
		$('#datecheck').click(function(){
			if($("#datecheck").is(':checked')){
				$(".checkdate").show();  // checked
			}
			else
				$(".checkdate").hide();  // unchecked
		});
        

		
        $('#search-button').click(function(){
            $('#indicator').show();	


            var dataString="action=search&search="+$('#search').val()+'&datestart='+$('#datestart').val()+'&dateend='+$('#dateend').val()+'&datecheck='+($('#datecheck').prop('checked') ? 'Y' : 'N')+'&pttype='+$('#pttype').val()+'&hnan='+$('#hnan').val()+'&ward='+$('#ward').val()+'&order='+$('#order_type2').val();
             $.ajax({
				   type: "POST",
				   url: "ipd_admit_list.php",
				   cache: false,
				   data: dataString,
				   success: function(html)
					{
						$("#result").html(html);
						$('#indicator').hide();
				        $(".checkdate").show();  // checked
					}
				 });
        });
        
        $('#order_type').change(function(){
                    
                var dataString="action=order&order_type="+$('#order_type').val();
                 $.ajax({
				   type: "POST",
				   url: "ipd_admit_list.php",
				   cache: false,
				   data: dataString,
				   success: function(html)
					{
						$("#result").html(html);
						$('#indicator').hide();	
					}
				 });
        });
        
        $('#togger-search').click(function(){
            $('#tools').show();            
            $('#panel-search').show();
            $('#panel-order').hide();            
        });
        
        $('#togger-picture').click(function(){
			$('#indicator').show();
			$('#result').load('ipd_picture_search.php<?php if(isset($_POST['pt_type'])&&($_POST['pt_type']!="")){ echo "?pt_type=".$_POST['pt_type']; } ?>',function(responseTxt, statusTxt, xhr){
                if(statusTxt == "success")
					$('#indicator').hide();
			});            
        });
        
        $('#togger-order').click(function(){
            $('#tools').show();            
            $('#panel-search').hide();
            $('#panel-order').show();            
        });
        $('#close-search').click(function(){
            $('#panel-search').fadeOut( "slow" );
        });
        $('#close-order').click(function(){
            $('#panel-order').fadeOut( "slow" );
        });
        
    });
    function an_select(an){
			$('#indicator').show();
			$('#result').load('ipd_patient.php?an='+an+'&pt_type=<?php echo $_POST['pt_type']; ?>',function(responseTxt, statusTxt, xhr){
                if(statusTxt == "success")
					$('#indicator').hide();
			});
	}
	
</script>
</head>

<body>
    
<div style="position: absolute; right: 15px; top:10px; font-size: 20px;">
    <button class="btn btn-sm btn-light" id="togger-picture"><i class="fas fa-images" style="font-size: 25px;"></i></buttn>
    <button class="btn btn-sm btn-light" id="togger-search"><i class="fas fa-search" style="font-size: 25px;" ></i></button>
    <button class="btn btn-sm btn-light" id="togger-order"><i class="fas fa-sort-alpha-down" style="font-size: 25px;" ></i></button>
</div>
<div class="row p-2">
  <div class="col">
    <div class="list-group" id="list-tab" role="tablist">
      <?php do { ?>
		<a class="list-group-item list-group-item-action" id="list-<?php echo $row_ipt['an']; ?>-list" data-toggle="list" onClick="an_select('<?php echo $row_ipt['an']; ?>');" role="tab" aria-controls="<?php echo $row_ipt['an']; ?>">
		  <div class="container0">
          <div class="left text-left">
                <span class="badge badge-dark p-2" style="font-size: 25px; width: 80px;"><?php echo $row_ipt['bedno']; ?></span>
		  </div>
		  <div class="right">
            <div style="font-size: 20px" class="pl-2"><?php echo $row_ipt['name']; ?></div>
            <div style="font-size: 12px" class="pl-2"><strong>AN:</strong> <?php echo $row_ipt['an']; ?>&emsp;<strong>HN: </strong><?php echo $row_ipt['hn']; ?>&emsp;<strong>RegDate:</strong> <?php echo date_db2th($row_ipt['regdate']); ?></div>
		  </div>
        </div>
	  </a>
	<?php }while($row_ipt = mysql_fetch_assoc($ipt)); ?>
    </div>
  </div>
</div>	
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