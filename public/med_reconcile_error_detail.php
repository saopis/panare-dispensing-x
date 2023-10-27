<?php ob_start();?>
<?php session_start();?>
<?php require_once('Connections/hos.php'); ?>
<?php
if($_GET['action']=="delete"){
	mysql_select_db($database_hos, $hos);
    $query_delete = "delete from ".$database_kohrx.".kohrx_med_reconcile_error where id='".$_GET['id']."'";
    $delete = mysql_query($query_delete, $hos) or die(mysql_error());
	
}

mysql_select_db($database_hos, $hos);
$query_rs_error = "SELECT e.*,m.drug_name,t.type_thai,c.name as cause_name,s.sub_name FROM ".$database_kohrx.".kohrx_med_reconcile_error e left outer join ".$database_kohrx.".kohrx_med_error_error_type t on t.id=e.error_type left outer join ".$database_kohrx.".kohrx_med_error_error_cause c on c.id=e.error_cause left outer join ".$database_kohrx.".kohrx_med_error_error_sub_cause s on s.id=e.error_subtype left outer join ".$database_kohrx.".kohrx_med_reconcile m on m.id=e.med_reconcile_id  where e.med_reconcile_id='".$_GET['med_reconcile_id']."'";
//echo $query_rs_error;
$rs_error = mysql_query($query_rs_error, $hos) or die(mysql_error());
$row_rs_error = mysql_fetch_assoc($rs_error);
$totalRows_rs_error = mysql_num_rows($rs_error);

if($totalRows_rs_error==0){
	echo "<script>parent.$.fn.colorbox.close();</script>";
	exit();
}
?>

<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Untitled Document</title>
<?php include('java_css_file.php'); ?> 
<script>
function error_delete(id,m_id) {
  var result = confirm("ต้องการลบ?");
  if (result==true) {
	  window.location.href='med_reconcile_error_detail.php?action=delete&id='+id+'&med_reconcile_id='+m_id;
  } else {
   return false;
  }
}
	
</script>
<style>
/*
div.show-button {
    position: relative;
    float:left;
    margin-top:5px;
	margin-right: 5px;
}
*/

.hover-btn {
	position:absolute;
    display: none;
	top:3px;
	right: 3px;
}
.hover-btn2 {
	position:absolute;
    display: none;
	bottom:3px;
	right: 3px;
}
.show-button:hover .hover-btn{
    display: block;
}
.show-button:hover .hover-btn2{
    display: block;
}
.show-button:hover{
	background-color: #E8E4E4;		
	}
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

<body>
<nav class="navbar navbar-dark bg-secondary text-white " style="padding-bottom: 10px;" >
  <!-- Navbar content -->
  <ul class="navbar-nav">
    <li class="nav-item">
      <i class="fas fa-list-alt font20"></i>&ensp;รายการบันทึกความคลาดเคลื่อนทางยาจาก Med. Reconcile
    </li>
  </ul>
	
</nav>
<div class="alert alert-secondary " style="font-size: 20px;" role="alert">
  <?php echo $row_rs_error['drug_name']; ?>
</div>
<?php if($totalRows_rs_error<>0){ ?>
<div class="pl-3 pb-3 pr-3" style="overflow:scroll;overflow-x:hidden;overflow-y:auto; height:80vh;">
    <?php $i=0; do{ $i++; ?>
    <div class="card mt-2 show-button">
        <div class="card-body">
			<span class=" hover-btn" onClick="window.location='med_reconcile_error.php?action=edit&id=<?php echo $row_rs_error['id']; ?>'"><i class="fas fa-pen-square cursor text-dark " style="font-size: 30px;"></i></span>
			<span class="hover-btn2 " onClick="error_delete('<?php echo $row_rs_error['id']; ?>','<?php echo $row_rs_error['med_reconcile_id']; ?>')" ><i class="fas fa-minus-square cursor text-secondary" style="font-size: 30px;" ></i></span>
			
            <div class="row">
                <div class="col" style="-ms-flex: 0 0 50px;flex: 0 0 50px;"><span class="badge badge-dark p-2"><?php echo $i; ?></span></div>
                <div class="col"><?php echo "<span style='font-size:16px'><strong>".$row_rs_error['type_thai']."</strong>&ensp;<i class='fas fa-angle-double-right'></i>&ensp;".$row_rs_error['cause_name']; if($row_rs_error['error_subtype']!=0){ echo "&ensp;<i class='fas fa-angle-double-right'></i>&ensp;".$row_rs_error['sub_name']; } "</span>"; ?>&emsp;<span class="badge badge-primary font20"><?php echo $row_rs_error['category']; ?></span>&ensp;<span class="badge badge-secondary p-2 font14"><?php if($row_rs_error['consult']==0||$row_rs_error['consult']==""){ echo "ไม่ได้ consult"; } else if($row_rs_error['consult']==1){ echo "consult / ไม่เปลี่ยน"; } else { echo "consult / เปลี่ยน"; } ?></span>
				&ensp;<span class="badge badge-danger p-2 font14"><?php if($row_rs_error['drug_type']==1){ echo "Admit"; } else if($row_rs_error['drug_type']==2){ echo "Discharge"; } ?></span>					
				</div>
                
            </div>
            <div class="row">
                <div class="col" style="-ms-flex: 0 0 50px;flex: 0 0 50px;"></div>
				<div class="col"><?php echo $row_rs_error['detail']; ?></div>
			</div>
        </div>

	</div>

    <?php } while($row_rs_error = mysql_fetch_assoc($rs_error)); ?>
</div>
<?php } ?>
<script>
var coll = document.getElementsByClassName("collapsible");
var i;

for (i = 0; i < coll.length; i++) {
  coll[i].addEventListener("click", function() {
    this.classList.toggle("active");
    var content = this.nextElementSibling;
    if (content.style.maxHeight){
      content.style.maxHeight = null;
    } else {
      content.style.maxHeight = content.scrollHeight + "px";
    } 
  });
}
</script>
</body>

</html>