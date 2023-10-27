<?php require_once('Connections/hos.php'); ?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>ใบแนะนำการฉีดอินซฺลินแบบปากกา</title>
<?php include('java_css_online.php'); ?>
<?php include('include/get_channel.php'); ?>
<?php include('include/function.php'); ?>
<?php include('include/function_sql.php'); ?>
<?php $hn=sprintf("%".$row_setting[24]."d", $_GET['hn']); ?>
<?php

  	mysql_select_db($database_hos, $hos);
	$query_logo = "select * from ".$database_kohrx.".kohrx_dispensing_setting where name = 'logo' ";
	$rs_logo = mysql_query($query_logo, $hos) or die(mysql_error());
	$row_logo = mysql_fetch_assoc($rs_logo);
	$totalRows_logo = mysql_num_rows($rs_logo);
?>

<link href="include/kohrx/css/kohrx.css" rel="stylesheet" type="text/css" />
<style>
body {
        margin: 0;
        padding: 0.5cm;
        background-color: #FAFAFA;
        font-size: 14px;
    }
    * {
        box-sizing: border-box;
        -moz-box-sizing: border-box;
    }
    .page {
        width: 210mm;
        min-height: 300mm;
        padding: 0.4cm;
        padding-right: 0;
        margin: 0.5cm auto ;
        border: 1px #D3D3D3 solid;
        border-radius: 5px;
        background: white;
        box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);*/
    }
    .subpage {
        padding: 0cm;
        margin-top: 0.5cm;
        margin-left: 0.5cm;
        margin-right: 0.2cm;
        /*border: 5px red solid;*/
        height: 280mm;
        width: 190mm;
        outline: 0 #FFFFFF solid;
        
    }

    
    @page {
        size: A5;
        margin: 0;
        padding: 0;
    }
    @media print {
        .no-print, .no-print *
            {
                display: none !important;
            }        
        body {-webkit-print-color-adjust: exact;}
        .page {
            margin: 0.5cm;
            margin-right: 10px;
            padding: 0;
            border: initial;
            border-radius: initial;
            width: initial;
            min-height: initial;
            box-shadow: initial;
            background: initial;
            page-break-after: always;
            font-size: 14px;
        }
    }
p.stb { text-indent: 0; margin-top: 0.8em;}
p.mtb { text-indent: 0; margin-top: 2.17em }
p.ltb { text-indent: 0; margin-top: 3.08em }
.dotshed {  border-bottom: dotted 1px #646464;}
.footer {
  position: fixed;
  left: 0;
  bottom: 30;
  width: 100%;
  color: white;
}
    .table-bordered td, .table-bordered th{
    border-color: black !important;
}
</style>
</head>

<body >

        <div class="fixed-top text-center p-3 no-print" style=""><button class="btn btn-success font18" onClick="window.print();"><i class="fas fa-print font20"></i>&nbsp;พิมพ์</button></div>

<div class="page thfont">
    <div class="subpage">
		<div class="position-absolute" style="margin-top:-30px;"><img src="data:image/jpeg;base64,<?php echo base64_encode($row_logo['picture']); ?> "  width="100" height="auto" vlign="middle" class="image"></div>
        
        <div class="text-center font20 font-weight-bold mt-5">ใบแนะนำการฉีดอินซูลินแบบปากกา</div>    
        <div class="text-center font18 mt-3"><strong>ชื่อ</strong>&nbsp;<?php echo ptname($hn); ?>&ensp;<strong>HN</strong>&nbsp;<?php echo $hn; ?></div> 
            <div class="card mt-3 border-dark">
            <div class="card-header bg-dark text-white font16 font-weight-bold"><span class="badge badge-light font16">1</span>&ensp;ชนิดของปากกาที่ใช้</div>
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <div class="card border-dark"><div class="card-body">
                                <div align="center"><img src="images/gensupen.png" width="270" height="31" alt=""/></div>
                                <div class="mt-2">
                              <a style="border: solid 1px #000000;" class="rounded" >&ensp;&ensp;&nbsp;</a>&nbsp;ปากกาสี&nbsp;<span class="font20 font_border" style="color: #FD0D11;"><u>" แดง "</u></span>
                                </div>
                                </div></div>
                        </div>
                        <div class="col">
                            <div class="card border-dark">
                                <div class="card-body">
                                <div align="center"><img src="images/gensupen2.png" width="270" height="31" alt=""/></div>
                                <div class="mt-2">
                              <a style="border: solid 1px #000000;" class="rounded" >&ensp;&ensp;&nbsp;</a>&nbsp;ปากกาสี&nbsp;<span class="font20 font_border" style="color:#2D9BA8;"><u>" เขียว "</u></span>
                                </div>    
                                </div>
                            </div>
                        </div>
                    </div>
                </div>    
            </div>  
        
        <div class="mt-2">
            <div class="card border-dark">
                <div class="card-header bg-dark text-white font-weight-bold font16"><span class="badge badge-light font16">2</span>&ensp;ขนาดที่ใช้</div>
                <div class="card-body">
                        <div class="row">
                                <div class="col-12">
                                <div class="font-weight-bold font16 mt-2" style="font-size: 30px"><i class="fas fa-sun btn btn-warning" style="font-size: 25px;">&nbsp;เช้า</i>&ensp;ฉีด..................ยูนิต  / หมุนปากกา...............ครั้ง</div>
                                <div class="font-weight-bold font16 mt-3"  style="font-size: 30px;"><i class="fas fa-moon btn btn-info"  style="font-size: 25px;">&nbsp;เย็น</i>&ensp;ฉีด..................ยูนิต  / หมุนปากกา...............ครั้ง</div>
                        </div>   
                            </div>
                        <div class="row">
                            <div class="col-12">
                            <div style="border: dotted 1px #676565" class="mt-1 font20 rounded p-1 mr-2 pt-2 text-center font16 bg-light font-weight-bold">
                                " ฉีดใต้ผิวหนังก่อนรับประทานอาหารไม่เกิน 15 นาที "
                            </div>
                            </div>
                    </div>

                </div>
            </div>

        </div>
        
        <div class="card border-dark mt-2">
        <div class="card-header bg-dark text-white font-weight-bold font16"><span class="badge badge-light font16">3</span>&ensp;ฉีดโดย</div>
        <div class="card-body p-1">
        <div class="text-center"><span style="font-size: 20px;">&#9634;&nbsp;ผู้ป่วย&emsp;&emsp;&#9634;&nbsp;ญาติ&emsp;&emsp;&#9634;&nbsp;อสม.&emsp;&emsp;&#9634;&nbsp;อื่นๆ...............</span></div>
        </div>
        </div>
        
        <div class="mt-2 font-weight-bold font20"><u>ข้อควรระวัง</u></div>
        <div class="mt-2 pl-5 font20">1. ห้ามปรับ เพิ่ม/ลดยาเอง  นอกจากแพทย์สั่ง</di>
        <div class="mt-2  font20">2. ถ้ามีอาการผิดปกติ  เช่น มือสั่น หน้ามืด ใจสั่น เหงื่อออก ให้รีบกลับมาพบแพทย์</di>
        <div class="mt-2  font20">3. เก็บยาในตู้เย็นช่องธรรมดา  และเก็บให้พ้นแสงแดด</di>
        <div class="mt-2  font20">4. ให้นำปากกาฉีดอินซูลิน / หรือไซริ้งค์พร้อมยามาที่โรงพยาบาลด้วยทุกครั้ง</di>
        <div class="mt-2  font20">5. ให้นับจำนวนหลอดยาอินซูลินและเข็มที่เหลือที่บ้านมาด้วย</di>
        <div class="mt-2  font20">6. ทิ้งเข็มในภาชนะที่แข็งแรงและมีฝาปิดสนิทหรือภาชนะที่โรงพยาบาลเตรียมไว้ให้</di>
        <div class="mt-4 font16 " style="width: 190mm"><strong class="text-dark">Pharmacist Note</strong><span class="text-white">.................................................................................................................................<br>
................................................................................................................................................................<br>................................................................................................................................................................<br>................................................................................................................................................................<br></span>
</di>
        
    <div class="mt-2" align="right" style="bottom: 50px; margin-left: 300px; width: 300px;">ลงชื่อ...................................ผู้ให้คำแนะนำ<br>วันที่ให้คำแนะนำ <?php echo dateThai(date('Y-m-d')); ?>&ensp;</div>
        
    </div>
    
    <!-- subpage -->
</div>

</body>
</html>
<?php mysql_free_result($rs_logo); ?>