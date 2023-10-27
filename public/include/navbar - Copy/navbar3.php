<style>
.dropdown-submenu {
    position: relative;
}

.dropdown-submenu>.dropdown-menu {
    top: 0;
    left: 100%;
    margin-top: -6px;
    margin-left: -2px;
    -webkit-border-radius: 6px 6px 6px 6px;
    -moz-border-radius: 0 6px 6px;
    border-radius: 6px 6px 6px 6px;
}

.dropdown-submenu:hover>.dropdown-menu {
    display: block;

}

.dropdown-submenu>a:after {
    display: block;
    content: " ";
    float: right;
    width: 0;
    height: 0;
    border-color: transparent;
    border-style: solid;
    border-width: 5px 0 5px 5px;
    border-left-color: #ccc;
    margin-top: 5px;
    margin-right: -10px;
}

.dropdown-submenu:hover>a:after {
    border-left-color: #fff;

}

.dropdown-submenu.pull-left {
    float: none;
}

.dropdown-submenu.pull-left>.dropdown-menu {
    left: -100%;
    margin-left: 10px;
    -webkit-border-radius: 6px 0 6px 6px;
    -moz-border-radius: 6px 0 6px 6px;
    border-radius: 6px 0 6px 6px;
}

</style>
  <!-- nav bar -->
<?php 
mysql_select_db($database_hos, $hos);
$query_rs_main_menu = "select * from ".$database_kohrx.".kohrx_main_menu order by sort_order ASC limit 8";
$rs_main_menu = mysql_query($query_rs_main_menu, $hos) or die(mysql_error());
$row_rs_main_menu = mysql_fetch_assoc($rs_main_menu);
$totalRows_rs_main_menu = mysql_num_rows($rs_main_menu);
?>

<nav style="position: fixed;" class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top" >
<div class="container-fluid">
<div class="navbar-header">
		<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation" style="height:46px;">
			<span class="navbar-toggler-icon"></span>
		 </button>
<img src="images/dispensing.png" width="166" height="32" class="d-inline-block align-top" style="margin-top:7px;" alt="" />
	</div>
  
<div class="collapse navbar-collapse" id="navbarNavAltMarkup">
 <ul class="nav navbar-nav">
	 <?php $i=0; do{ $i++; ?>
        <li class="dropdown">
          <a class="dropdown-toggle" data-toggle="dropdown" href="#" <?php if($row_rs_main_menu['link_type']=="2"||$row_rs_main_menu['link_type']=="4"||$row_rs_main_menu['link_type']=="5"){ echo "onclick=\"alertload('".$row_rs_main_menu['link']."','".$row_rs_main_menu['width']."','".$row_rs_main_menu['height']."');\""; } else if($row_rs_main_menu['link_type']=="3"){ echo "onclick=\"window.open('".$row_rs_main_menu['link']."','_new');\""; } ?>><?php echo $row_rs_main_menu['menu_name']; ?><span class="caret"></span></a>

		  <ul class="dropdown-menu">
<?php 
/////////// sub 1 /////////////////
mysql_select_db($database_hos, $hos);
$query_rs_sub_menu = "select * from ".$database_kohrx.".kohrx_sub_menu where main_menu_id='".$row_rs_main_menu['id']."' order by sort_order ASC";
$rs_sub_menu = mysql_query($query_rs_sub_menu, $hos) or die(mysql_error());
$row_rs_sub_menu = mysql_fetch_assoc($rs_sub_menu);
$totalRows_rs_sub_menu = mysql_num_rows($rs_sub_menu);

do{
//////////////// submenu2 ///////////////
mysql_select_db($database_hos, $hos);
$query_rs_sub_menu2 = "select * from ".$database_kohrx.".kohrx_sub_menu2 where sub_menu_id='".$row_rs_sub_menu['id']."' order by sort_order ASC";
$rs_sub_menu2 = mysql_query($query_rs_sub_menu2, $hos) or die(mysql_error());
$row_rs_sub_menu2 = mysql_fetch_assoc($rs_sub_menu2);
$totalRows_rs_sub_menu2 = mysql_num_rows($rs_sub_menu2);

?>
            <li <?php if($totalRows_rs_sub_menu2<>0){ ?>class="dropdown"<?php } ?></l>>
			  <a href="#" <?php if($totalRows_rs_sub_menu2<>0){ ?>class="dropdown-toggle" data-toggle="dropdown"<?php } ?>><?php echo $row_rs_sub_menu['sub_menu_name']; ?><?php if($totalRows_rs_sub_menu2<>0){ ?><span class="caret"></span><?php } ?></a>
			  <?php if($totalRows_rs_sub_menu2<>0){ ?>
				<ul class="dropdown-menu">
				<?php do{ ?>
					<li><a href="#"><?php echo $row_rs_sub_menu2['sub_menu2_name']; ?></a></li>
                <?php }while($row_rs_sub_menu2 = mysql_fetch_assoc($rs_sub_menu2)); ?>

			  	</ul>
			  <?php } ?>
			</li>
<?php mysql_free_result($rs_sub_menu2); ?>  
<?php }while($row_rs_sub_menu = mysql_fetch_assoc($rs_sub_menu)); ?>
          </ul>
        </li>
	 <?php mysql_free_result($rs_sub_menu); ?>  
	 <?php }while($row_rs_main_menu = mysql_fetch_assoc($rs_main_menu)); ?>
      </ul>
</div>
</div>
<!-- fluid -->
</nav>
<!-- nav bar -->
<?php mysql_free_result($rs_main_menu); ?>
