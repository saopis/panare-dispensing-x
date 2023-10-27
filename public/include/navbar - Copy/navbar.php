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
	.dropdown-menu{
		
       }
.dropdown-menu > li > a:hover {
    background-color:transparent;
    background-image: none;
}
    

    
li.dropdown:last-child .dropdown-menu {
  right: 0;
  left: auto;
}

.dropdown-menu {
  top: auto;
}

</style>
  <!-- nav bar -->
<?php 
mysql_select_db($database_hos, $hos);
$query_rs_main_menu = "select * from ".$database_kohrx.".kohrx_main_menu order by sort_order ASC limit 8 ";
$rs_main_menu = mysql_query($query_rs_main_menu, $hos) or die(mysql_error());
$row_rs_main_menu = mysql_fetch_assoc($rs_main_menu);
$totalRows_rs_main_menu = mysql_num_rows($rs_main_menu);
?>

    <div class="navbar navbar-expand-lg navbar-dark bg-dark navbar-fixed-top" id="main_navbar">
        <a class="navbar-brand" href="#"><img src="images/dispensing2.png" width="166" height="32" class="d-inline-block align-top" style="margin-top:0px;" alt="" /></a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent"  >
		<button class="btn btn-dark" onClick="alertload('portal_link.php','90%','600')"><i class="far fa-window-restore font20 text-white" ></i>&nbsp;ระบบรายงาน</button>

         <ul class="navbar-nav mr-auto scrollable-menu">
			<?php $i=0; do{  ?>
			 <?php $i++; ?>
			<li class="nav-item dropdown" style="margin-left: 5px;">
            <button class="btn btn-dark btn-sm dropdown-toggle" type="button" id="dropdownMenu" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false" 
                    
            <?php  switch($row_rs_main_menu['link_type']){
                     case 1:
                    break; 
                    case 2:
                     echo "onclick=\"alertload('".$row_rs_main_menu['link']."','".$row_rs_main_menu['width']."','".$row_rs_main_menu['width']."');\"";
                     break;

                    case 3:
                     echo "onclick=\"window.open('".$row_rs_main_menu['link']."\"','_blank');\"";
                     break; 

                    case 4:
                     echo "onclick=\"alertload('menu_text.php?menu_id=".$row_rs_main_menu['id']."&menu_type=','".$row_rs_main_menu['width']."','".$row_rs_main_menu['height']."')\"";
                     break;

                    case 5:
                     echo " onclick=\"alertload('upload/".$row_rs_main_menu['file_link']."','".$row_rs_main_menu['width']."','".$row_rs_main_menu['height']."')\"";
                     break;

                    } ?>   >
              <?php echo $row_rs_main_menu['menu_name']; ?>
            </button>
            <ul class="dropdown-menu multi-level" role="menu" aria-labelledby="dropdownMenu" style="margin-top:0px;">
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
                  <a style="text-decoration:none; color:#000000;" <?php if($totalRows_rs_sub_menu2<>0){ ?> class="dropdown-item dropdown-toggle font14" tabindex="-1"<?php } else { ?>class=" font14"<?php } ?> href="#" 
        
                    <?php  switch($row_rs_sub_menu['link_type']){
                     case 1:
                    break; 
                    case 2:
                     echo "onclick=\"alertload('".$row_rs_sub_menu['link']."','".$row_rs_sub_menu['width']."','".$row_rs_sub_menu['width']."');\"";
                     break;

                    case 3:
                     echo "onclick=\"window.open('".$row_rs_sub_menu['link']."','_blank')\"";
                     break; 

                    case 4:
                     echo "onclick=\"alertload('menu_text.php?menu_id=".$row_rs_sub_menu['id']."&menu_type=main','".$row_rs_sub_menu['width']."','".$row_rs_sub_menu['height']."');\"";
                     break;

                    case 5:
                     echo " onclick=\"alertload('upload/".$row_rs_sub_menu['file_link']."','".$row_rs_sub_menu['width']."','".$row_rs_sub_menu['height']."');\"";
                     break;

                    } ?>                     
                    >
                    <?php echo $row_rs_sub_menu['sub_menu_name']; ?>
                </a>


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
                      <a style="text-decoration:none; color:#000000;" <?php if($totalRows_rs_sub_menu3<>0){ ?> class="dropdown-item font14" tabindex="-1"<?php } else { ?>class=" font14"<?php } ?> href="#" 
                         
                    <?php  switch($row_rs_sub_menu2['link_type']){
                     case 1:
                    break; 
                    case 2:
                     echo "onclick=\"alertload('".$row_rs_sub_menu2['link']."','".$row_rs_sub_menu2['width']."','".$row_rs_sub_menu2['width']."');\"";
                     break;

                    case 3:
                     echo "onclick=\"window.open('".$row_rs_sub_menu2['link']."','_blank');\"";
                     break; 

                    case 4:
                     echo "onclick=\"alertload('menu_text.php?menu_id=".$row_rs_sub_menu2['id']."&menu_type=sub_menu','".$row_rs_sub_menu2['width']."','".$row_rs_sub_menu2['height']."');\"";
                     break;

                    case 5:
                     echo " onclick=\"alertload('upload/".$row_rs_sub_menu2['file_link']."','".$row_rs_sub_menu2['width']."','".$row_rs_sub_menu2['height']."');\"";
                     break;

                    } ?>                     
                        
                    ><?php echo $row_rs_sub_menu2['sub_menu2_name']; ?></a>
                        
                      <?php if($totalRows_rs_sub_menu3<>0){ ?>
	                  <ul class="dropdown-menu">
                      <?php do{ ?>
	                    <li class="dropdown-item font14" 
                            <?php  switch($row_rs_sub_menu3['link_type']){
                             case 1:
                            break; 
                            case 2:
                             echo "onclick=\"alertload('".$row_rs_sub_menu3['link']."','".$row_rs_sub_menu3['width']."','".$row_rs_sub_menu3['width']."');\"";
                             break;

                            case 3:
                             echo "onclick=\"window.open('".$row_rs_sub_menu3['link']."','_blank');\"";
                             break; 

                            case 4:
                             echo "onclick=\"alertload('menu_text.php?menu_id=".$row_rs_sub_menu3['id']."&menu_type=sub_menu2','".$row_rs_sub_menu3['width']."','".$row_rs_sub_menu3['height']."');\"";
                             break;

                            case 5:
                             echo " onclick=\"alertload('upload/".$row_rs_sub_menu3['file_link']."','".$row_rs_sub_menu3['width']."','".$row_rs_sub_menu3['height']."');\"";
                             break;

                            } ?>  >
                      
                      <?php echo $row_rs_sub_menu3['sub_menu3_name']; ?></li>
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
        </li>
			<?php mysql_free_result($rs_sub_menu); ?>  
	 <?php } while($row_rs_main_menu = mysql_fetch_assoc($rs_main_menu)); ?>         
		</ul>
<div class="btn-group" >	
  <button type="button" class="btn btn-secondary rounded-left"  data-placement="bottom" title="ระบบเรียกผู้ป่วย" id="caller-server" onClick="callerOpenCheck();window.open('queue_server_requeue.php','caller_panel2');" ><i class="fas fa-headset font18"></i> off</button>
            <button class="btn btn-primary btn-sm dropdown-toggle rounded-0" id="dropdownMenu" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false" ><i class="fas fa-chevron-square-down font18"></i>

	<ul class="navbar-nav mr-auto scrollable-menu">
			<li class="nav-item dropdown">
            <ul class="dropdown-menu multi-level" role="menu" aria-labelledby="dropdownMenu" style="margin-top:0px;">
				<a class="nev-item dropdown-item" href="#" onclick="callerOpen();">แสดงหน้าต่างเรียกชื่อ</a>
				<a class="nev-item dropdown-item" href="#" onclick="alertload('caller_setting.php','90%','600');">ตั้งค่าการเรียกชื่อ</a>
				<a class="nev-item dropdown-item" href="#" onclick="alertload('speech_spell.php?patient_name=fname','90%','600');">ตั้งค่าการสะกดชื่อ</a>
				<a class="nev-item dropdown-item" href="#" onclick="alertload('queue_caller_prename.php','90%','600');">ตั้งค่าคำนำหน้าชื่อ</a>
				<a class="nev-item dropdown-item" href="#" onclick="alertload('text_to_speech.php','90%','600');">ประชาสัมพันธ์ด้วยเสียง</a>
				<a class="nev-item dropdown-item" href="#" onclick="alertload('information_marquee.php','90%','90%');">ประชาสัมพันธ์ด้วยข้อความ</a>
				<a class="nev-item dropdown-item" href="#" onclick="window.open('queue_caller_barcode.php','_new');">TV</a>		
			</ul>
			</li>
	</ul>	
			</button>	
				
  </div>		
        </div>

		
    </div>
<!-- nav bar -->
<?php mysql_free_result($rs_main_menu); ?>
<?php echo $caller_default; ?>
<script>
$(document).ready(function(){
<?php if($caller_default=='Y'){ ?>
	callerOpenCheck();
	window.open('queue_server_requeue.php','caller_panel2');	
<?php } ?>
	
});	
//ถ้าพบว่า caller_default='Y' ให้เปิด caller_server ทุกครั้ง	
</script>
