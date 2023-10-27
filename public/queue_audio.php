<?php ob_start();?>
<?php session_start();?>
<?php require_once('Connections/hos.php'); ?>
<?php
	mysql_select_db($database_hos, $hos);
	$update = "update ".$database_kohrx.".kohrx_login_check set last_time=NOW() where login_name='".$_SESSION['username_log']."' and substr(last_time,1,10)=CURDATE()";
	$rs_update = mysql_query($update, $hos) or die(mysql_error());
	
		//บันทึกลง log
		mysql_select_db($database_hos, $hos);
		$insert_log = "insert into replicate_log (`event_time`,`client`,`sql`) values (current_timestamp,'KOHRX','update ".$database_kohrx.".kohrx_login_check set last_time=NOW() where login_name=\'".$_SESSION['username_log']."\' and substr(last_time,1,10)=CURDATE()')";
		$rs_insert_log = mysql_query($insert_log, $hos) or die(mysql_error());
/*
$txt=htmlspecialchars($_GET['text']);
$txt=rawurlencode($txt);
$html=file_get_contents('https://translate.google.com/translate_tts?ie=UTF-8&client=gtx&q='.$txt.'&tl=th-TH',-1);
	$player="<audio hidden autoplay controls><source src='data:audio/mpeg;base64,".base64_encode($html)."'></audio>";
	echo $player;
*/
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Untitled Document</title>
<?php include('java_css_file.php'); ?>	
    
<script>
			/* JS comes here */
            function textToAudio(t) {
                let msg = document.getElementById("text-to-speech").value;
                
                let speech = new SpeechSynthesisUtterance();
                speech.lang = "th-TH";
                
                speech.text = t;
                speech.volume = 1;
                //speech.voice=25;
                speech.rate = 0.7;
                speech.pitch = 1;
                
                window.speechSynthesis.speak(speech);
            }
            $(document).ready(function(){
                textToAudio("<?php echo $_GET['text']; ?>");
            });
		</script>		
</head>

	<body>
	    <h2>JavaScript Text to Speech</h2>
		<div><input type="text" id="text-to-speech" placeholder="Enter text to speak..."/></div>
        <div><button type="button" onclick="textToAudio('ทดสอบระบบ')">Speak</button></div>
        <br/>
        <div><small><b>NOTE:</b> Enter text and click on speak button.</small></div>

	</body>
</html>
