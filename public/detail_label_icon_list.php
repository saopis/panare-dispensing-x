<?php ob_start();?>
<?php session_start();?>
<?php require_once('Connections/hos.php'); ?>
<?php include('include/function.php'); ?>
<?php
if(isset($_POST['hn'])&&$_POST['hn']!=""){
	$hn=$_POST['hn'];
}
if(isset($_GET['hn'])&&$_GET['hn']!=""){
	$hn=$_GET['hn'];
}


mysql_select_db($database_hos, $hos);
$query_rs_icon_list = "select l.id,i.icon,l.label_name,i.icon_html,i.icon_name,l.label_color,p.hn,p.label_comment,p.doctor from ".$database_kohrx.".kohrx_label_icon_patient p left outer join ".$database_kohrx.".kohrx_label_icon_list l on l.id=p.label_id left outer join ".$database_kohrx.".kohrx_label_icon i on i.id=l.label_icon_id where p.hn='".$hn."' order by l.id ASC";
$rs_icon_list = mysql_query($query_rs_icon_list, $hos) or die(mysql_error());
$row_rs_icon_list = mysql_fetch_assoc($rs_icon_list);
$totalRows_rs_icon_list = mysql_num_rows($rs_icon_list);

?>
<?php include('include/function_sql.php'); ?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Untitled Document</title>
	
<script>
$(function () {
  $('[data-toggle="tooltip"]').tooltip();
})	
</script>	
<style>
[data-tooltip] {
  position: relative;
  z-index: 10;
}

/* Positioning and visibility settings of the tooltip */
[data-tooltip]:before,
[data-tooltip]:after {
  position: absolute;
  visibility: hidden;
  opacity: 0;
  left: 50%;
  bottom: calc(100% + 5px); /* 5px is the size of the arrow */
  pointer-events: none;
  transition: 0.1s;
  will-change: transform;
}

/* The actual tooltip with a dynamic width */
[data-tooltip]:before {
  content: attr(data-tooltip);
  padding: 10px 18px;
  min-width: 50px;
  max-width: 300px;
  width: max-content;
  width: -moz-max-content;
  border-radius: 6px;
  font-size: 14px;
  background-color: rgba(59, 72, 80, 0.9);
  background-image: linear-gradient(30deg,
    rgba(59, 72, 80, 0.44),
    rgba(59, 68, 75, 0.44),
    rgba(60, 82, 88, 0.44));
  box-shadow: 0px 0px 24px rgba(0, 0, 0, 0.2);
  color: #fff;
  text-align: center;
  white-space: pre-wrap;
  transform: translate(-50%, -5px) scale(0.5);
}

/* Tooltip arrow */
[data-tooltip]:after {
  content: '';
  border-style: solid;
  border-width: 5px 5px 0px 5px; /* CSS triangle */
  border-color: rgba(55, 64, 70, 0.9) transparent transparent transparent;
  transition-duration: 0s; /* If the mouse leaves the element, 
                              the transition effects for the 
                              tooltip arrow are "turned off" */
  transform-origin: top;   /* Orientation setting for the
                              slide-down effect */
  transform: translateX(-50%) scaleY(0);
}

/* Tooltip becomes visible at hover */
[data-tooltip]:hover:before,
[data-tooltip]:hover:after {
  visibility: visible;
  opacity: 1;
}
/* Scales from 0.5 to 1 -> grow effect */
[data-tooltip]:hover:before {
  transition-delay: 0.1s;
  transform: translate(-50%, -5px) scale(1);
}
/* 
  Arrow slide down effect only on mouseenter (NOT on mouseleave)
*/
[data-tooltip]:hover:after {
  transition-delay: 0.2s; /* Starting after the grow effect */
  transition-duration: 0.2s;
  transform: translateX(-50%) scaleY(1);
}
/*
  That's it for the basic tooltip.

  If you want some adjustability
  here are some orientation settings you can use:
*/

/* LEFT */
/* Tooltip + arrow */
[data-tooltip-location="left"]:before,
[data-tooltip-location="left"]:after {
  left: auto;
  right: calc(100% + 5px);
  bottom: 50%;
}

/* Tooltip */
[data-tooltip-location="left"]:before {
  transform: translate(-5px, 50%) scale(0.5);
}
[data-tooltip-location="left"]:hover:before {
  transform: translate(-5px, 50%) scale(1);
}

/* Arrow */
[data-tooltip-location="left"]:after {
  border-width: 5px 0px 5px 5px;
  border-color: transparent transparent transparent rgba(55, 64, 70, 0.9);
  transform-origin: left;
  transform: translateY(50%) scaleX(0);
}
[data-tooltip-location="left"]:hover:after {
  transform: translateY(50%) scaleX(1);
}



/* RIGHT */
[data-tooltip-location="right"]:before,
[data-tooltip-location="right"]:after {
  left: calc(100% + 5px);
  bottom: 50%;
}

[data-tooltip-location="right"]:before {
  transform: translate(5px, 50%) scale(0.5);
}
[data-tooltip-location="right"]:hover:before {
  transform: translate(5px, 50%) scale(1);
}

[data-tooltip-location="right"]:after {
  border-width: 5px 5px 5px 0px;
  border-color: transparent rgba(55, 64, 70, 0.9) transparent transparent;
  transform-origin: right;
  transform: translateY(50%) scaleX(0);
}
[data-tooltip-location="right"]:hover:after {
  transform: translateY(50%) scaleX(1);
}



/* BOTTOM */
[data-tooltip-location="bottom"]:before,
[data-tooltip-location="bottom"]:after {
  top: calc(100% + 5px);
  bottom: auto;
}

[data-tooltip-location="bottom"]:before {
  transform: translate(-50%, 5px) scale(0.5);
}
[data-tooltip-location="bottom"]:hover:before {
  transform: translate(-50%, 5px) scale(1);
}

[data-tooltip-location="bottom"]:after {
  border-width: 0px 5px 5px 5px;
  border-color: transparent transparent rgba(55, 64, 70, 0.9) transparent;
  transform-origin: bottom;
}
</style>	
</head>

<body>
    
	<?php if($totalRows_rs_icon_list<>0){ ?>
	<hr class="m-0"/>
			<div class="row">
			<?php $intRows = 0; $i=0;
				do { $i++;
			?>
				<?php $intRows++; ?>
				<div class="col-sm-auto mt-1">
				<span class="badge badge-<?php echo $row_rs_icon_list
	['label_color']; ?> font14 p-1 cursor" style="width:100px;" data-tooltip="<?php echo $row_rs_icon_list['label_comment']; ?>" ><i class="<?php echo $row_rs_icon_list['icon_html']; ?> font20"></i>&nbsp;<?php echo $row_rs_icon_list['label_name']; ?></span>
                    
				</div>	
				<?php
				if(($intRows)%4==0)
				{
				?>
				</div>
				<div class="row">

				<?php 
					} 
				}while($row_rs_icon_list = mysql_fetch_assoc($rs_icon_list));
			?>
				</div>
	<?php } ?>
</body>
</html>
<?php mysql_free_result($rs_icon_list); ?>