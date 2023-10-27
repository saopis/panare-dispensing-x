<?php require_once('Connections/hos.php'); ?>
<?php include('include/function.php'); ?>
<?php
//===== setting ==========//
mysql_select_db($database_hos, $hos);
$query_rs_monograph = "select m.*,concat(d.name,' ',d.strength) as drugname from ".$database_kohrx.".kohrx_drug_monograph m left outer join drugitems d on d.icode=m.icode where m.icode='".$_GET['icode']."'";
//echo $query_rs_monograph;
$rs_monograph = mysql_query($query_rs_monograph, $hos) or die(mysql_error());
$row_rs_monograph = mysql_fetch_assoc($rs_monograph);
$totalRows_rs_monograph = mysql_num_rows($rs_monograph);
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Untitled Document</title>
<?php include('java_css_online.php'); ?> 
<style>
    html,body{overflow: hidden}
</style>
</head>

<body>
    <nav class="navbar navbar-expand-md navbar-dark bg-info">
    <div class="mx-auto order-0"><h4 class="text-white"><i class="fas fa-prescription font20"></i>&ensp;Drug Monograph : <span class="font-weight-bold"><?php echo $row_rs_monograph['drugname']; ?></span></h4>        
</div>
</nav>
    <?php if($row_rs_monograph['monograph_type']==1){ ?>
    <iframe src="<?php echo $row_rs_monograph['monograph']; ?>" frameborder="0" style="overflow:hidden;height:92%;width:100%" height="100%" width="100%"></iframe>
    <?php } else { ?>
    <div class="p-3"><?php echo $row_rs_monograph['monograph']; ?></div>
    <?php } ?>
    
</body>
</html>
<?php mysql_free_result($rs_monograph); ?>