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
@media screen and (max-width: 1024px) {
	div.navbar-header{}
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
	<a class="navbar-brand" href="#">
	<img src="images/dispensing.png" width="166" height="32" class="d-inline-block align-top" style="margin-top:-15px;" alt="" />
	</a>
</div>


<div class="collapse navbar-collapse" id="navbarNavAltMarkup">
<div class="navbar kohrx-menu" >
<?php $i=0; do{ $i++; ?>
<div class="dropdown" style="margin-left:5px;">
            <button class="btn btn-dark btn-sm dropdown-toggle" type="button" id="dropdownMenu" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" <?php if($row_rs_main_menu['link_type']=="2"||$row_rs_main_menu['link_type']=="4"||$row_rs_main_menu['link_type']=="5"){ echo "onclick=\"alertload('".$row_rs_main_menu['link']."','".$row_rs_main_menu['width']."','".$row_rs_main_menu['height']."');\""; } else if($row_rs_main_menu['link_type']=="3"){ echo "onclick=\"window.open('".$row_rs_main_menu['link']."','_new');\""; } ?>>
              <?php echo $row_rs_main_menu['menu_name']; ?>
            </button>
            <ul class="dropdown-menu multi-level" role="menu" aria-labelledby="dropdownMenu" style="margin-top:12px;">
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

                <li class="dropdown-<?php if($totalRows_rs_sub_menu2<>0){echo "submenu"; } else {echo "item"; } ?> " >
                  <a style="text-decoration:none; color:#000000;" <?php if($totalRows_rs_sub_menu2<>0){ ?> class="dropdown-item font14" tabindex="-1"<?php } else { ?>class=" font14"<?php } ?> href="#" <?php if($row_rs_sub_menu['link_type']=="2"||$row_rs_sub_menu['link_type']=="4"||$row_rs_sub_menu['link_type']=="5"){ echo "onclick=\"alertload('".$row_rs_sub_menu['link']."','".$row_rs_sub_menu['width']."','".$row_rs_sub_menu['height']."');\""; } else if($row_rs_sub_menu['link_type']=="3"){ echo "onclick=\"window.open('".$row_rs_sub_menu['link']."','_new');\""; } ?>><?php echo $row_rs_sub_menu['sub_menu_name']; ?></a>


<?php 

if($totalRows_rs_sub_menu2<>0){ ?>                  
                  <ul class="dropdown-menu">
                  <?php do{ 
//////////// submenu 3 ///////////////////
mysql_select_db($database_hos, $hos);
$query_rs_sub_menu3 = "select * from ".$database_kohrx.".kohrx_sub_menu3 where sub_menu2_id='$row_rs_sub_menu2[id]' order by sort_order ASC";
$rs_sub_menu3 = mysql_query($query_rs_sub_menu3, $hos) or die(mysql_error());
$row_rs_sub_menu3 = mysql_fetch_assoc($rs_sub_menu3);
$totalRows_rs_sub_menu3 = mysql_num_rows($rs_sub_menu3);				  
				  ?>
                    <li class="dropdown-<?php if($totalRows_rs_sub_menu3<>0){echo "submenu"; } else {echo "item"; } ?>">
                      <a style="text-decoration:none; color:#000000;" <?php if($totalRows_rs_sub_menu3<>0){ ?> class="dropdown-item font14" tabindex="-1"<?php } else { ?>class=" font14"<?php } ?> href="#" <?php if($row_rs_sub_menu2['link_type']=="2"||$row_rs_sub_menu2['link_type']=="4"||$row_rs_sub_menu2['link_type']=="5"){ echo "onclick=\"alertload('".$row_rs_sub_menu2['link']."','".$row_rs_sub_menu2['width']."','".$row_rs_sub_menu2['height']."');\""; } else if($row_rs_sub_menu2['link_type']=="3"){ echo "onclick=\"window.open('".$row_rs_sub_menu2['link']."','_new');\""; } ?>><?php echo $row_rs_sub_menu2['sub_menu2_name']; ?></a>
                      <?php if($totalRows_rs_sub_menu3<>0){ ?>
	                  <ul class="dropdown-menu">
                      <?php do{ ?>
	                    <li class="dropdown-item font14" <?php if($row_rs_sub_menu3['link_type']=="2"||$row_rs_sub_menu3['link_type']!="4"||$row_rs_sub_menu3['link_type']!="5"){ echo "onclick=\"alertload('".$row_rs_sub_menu3['link']."','".$row_rs_sub_menu3['width']."','".$row_rs_sub_menu3['height']."');\""; } else if($row_rs_sub_menu3['link_type']=="3"){ echo "onclick=\"window.open('".$row_rs_sub_menu3['link']."','_new');\""; } ?>><?php echo $row_rs_sub_menu3['sub_menu3_name']; ?></li>
					  <?php } while($row_rs_sub_menu3 = mysql_fetch_assoc($rs_sub_menu3)) ?>		
                      </ul>
                      <?php } ?>                      
                    </li>
			<?php mysql_free_result($rs_sub_menu3); ?>                      
				<?php } while ( $row_rs_sub_menu2 = mysql_fetch_assoc($rs_sub_menu2)); ?>
                  </ul>
<?php } ?>
                </li>
			<?php mysql_free_result($rs_sub_menu2); ?>  
                <?php }while($row_rs_sub_menu = mysql_fetch_assoc($rs_sub_menu)); ?>
              </ul>
        </div>
			<?php mysql_free_result($rs_sub_menu); ?>  
			  <?php } while($row_rs_main_menu = mysql_fetch_assoc($rs_main_menu)); ?>
</div>
</div>

</nav>
<!-- nav bar -->
<?php mysql_free_result($rs_main_menu); ?>
