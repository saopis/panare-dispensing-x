<?php require_once('../Connections/hos.php'); ?>
<?php

  // Initialize message variable
  $msg = "";

if (isset($_POST['delete'])) {
    mysql_select_db($database_hos, $hos);
	$q_delete = "update ".$database_kohrx.".kohrx_dispensing_setting set picture=NULL where name = 'logo' ";
	$delete = mysql_query($q_delete, $hos) or die(mysql_error());
  
}

// If upload button is clicked ...

if (isset($_POST['upload'])) {
  	// Get image name
  	$image = $_FILES['image']['name'];

    $fp = fopen($_FILES["image"]["tmp_name"],"r");
    $ReadBinary = fread($fp,filesize($_FILES["image"]["tmp_name"]));
    fclose($fp);
    $FileData = addslashes($ReadBinary);
      
    mysql_select_db($database_hos, $hos);
	$q_insert = "update ".$database_kohrx.".kohrx_dispensing_setting set picture='".$FileData."' where name = 'logo' ";
	$insert = mysql_query($q_insert, $hos) or die(mysql_error());

  }
?>
<?php

  	mysql_select_db($database_hos, $hos);
	$query_logo = "select * from ".$database_kohrx.".kohrx_dispensing_setting where name = 'logo' ";
	$rs_logo = mysql_query($query_logo, $hos) or die(mysql_error());
	$row_logo = mysql_fetch_assoc($rs_logo);
	$totalRows_check = mysql_num_rows($rs_logo);
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Untitled Document</title>
<?php include('../java_css_online.php'); ?>
<link rel="stylesheet" href="../include/kohrx/css/kohrx.css"/>
<style type="text/css">
   #content{
   }
    img{width: 200px;height: auto;}

.upload-btn-wrapper {
  position: relative;
  overflow: hidden;
  display: inline-block;
}


.upload-btn-wrapper input[type=file] {
  font-size: 100px;
  position: absolute;
  left: 0;
  top: 0;
  opacity: 0;
}
    
</style>

</head>

<body>
                      <div>โลโก้โรงพยาบาล</div>
                      <div class="mt-3 thfont">
                          <div class="thfont">
                              <?php
                                if($row_logo['picture']!=""){
                                    echo "<img src=\"data:image/jpeg;base64,".base64_encode($row_logo['picture'])."\" vlign=\"middle\" border=\"0\" style=\"border-radius: 8px; border:solid 1px #E3E1E1\" class=\"image\">";
                                }
                              ?>
                              <form method="POST" action="upload_logo.php" enctype="multipart/form-data">
                              <?php if($row_logo['picture']==""){ ?>
                                <input type="hidden" name="size" value="1000000">

                                  <div>
                                    
                                  <input type="file" name="image">&nbsp;<button type="submit" name="upload" class="btn btn-primary"><i class="fas fa-file-import font20"></i>&ensp;อัพโหลด</button>
                                </div>
                              <?php } else { ?>
                                <div style="width: 200px;" align="center" class="mt-3">
                                    <button type="submit" name="delete" class="btn btn-danger" onClick="return confirm('ต้องการลบรูปนี้จริงหรือไม่?');"><i class="fas fa-trash font20"></i>&ensp;ลบรูป</button>
                                </div>  
                            <?php } ?>
                              </form>
                            </div>
                      </div>
                  
</body>
</html>
