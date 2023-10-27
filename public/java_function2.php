<script>
$(document).ready(function(){
	
	<?php if($totalRows_channel<>0){ ?>
	//++++++++++++ defaut focus ++++++++++++//
	firstFocus('<?php echo $row_channel['cursor_position']; ?>');
	//++++++++++++ defaut focus ++++++++++++//
	<?php } ?>
//+++++++++++ show hide +++++++++++//
        $('#indicator').hide();  
        $('#indicator-save').hide();  
      	$('#shortcut_indicator').hide()                       

////////////   EMR /////////////////	  
        $('#emr').click(function(){
            alertload('emr.php?hn='+$('#hidden_hn').val(),'95%','95%');
            //alertload('emr.php?hn='+$('#hidden_hn').val(),'99%','600');
        });
////////////   RX-history /////////////////	  
        $('#rx-history').click(function(){
            alertload('drug_history.php?hn='+$('#hidden_hn').val(),'800','500');
        });

////////////   receive-history /////////////////	  
        $('#receive-history').click(function(){
                $('#indicator').show();
                $("#dispen-body").load('visit_list.php?action=history&hn='+$('#hidden_hn').val(), function(responseTxt, statusTxt, xhr){
                    
                    if(statusTxt == "success")
                      //alert("External content loaded successfully!");
                        $('#indicator').hide();
                    if(statusTxt == "error")
                       alert("Error: " + xhr.status + ": " + xhr.statusText);    
               });

        });

      

	//////////////////////////////////////////
	
	$("#queue").keypress(function(event) {
  		return /\d/.test(String.fromCharCode(event.keyCode));
	});	
	$("#hn").keypress(function(event) {
  		return /\d/.test(String.fromCharCode(event.keyCode));
	});	
	$("#an").keypress(function(event) {
  		return /\d/.test(String.fromCharCode(event.keyCode));
	});	
    
    //ปิดตัวเรียกเสียง
    $('#caller_panel').hide();
    
    //ปิดปุ่ม ขยาย card การเรียกชื่อ
    $('#btn_caller_up').hide();
    //btn caller down
    $('#btn_caller_down').click(function(){
        /* $('#caller_body').animate({height:'50px',width:'140px',position: 'fixed',float:'right',right: '5px',left: '460px'}, 500);
        $("#caller_header").hide();
        $("#caller_header2").show();
        $('#btn_caller_down').hide();
        $('#btn_caller_up').show();
		*/
		$('#caller_panel').hide();
    });
    //btn caller up
    $('#btn_caller_up').click(function(){
        $('#caller_body').animate({position: 'fixed',float:'right',height:'305px',width:'600px',left:'0px'}, 500);
        $("#caller_header").show();
        $("#caller_header2").hide();
        $('#btn_caller_down').show();
        $('#btn_caller_up').hide();
    });
	//caller check
	
});
         
//===== เกี่ยวกับการเปิด modal ========//
function drug_interaction_show(vn) {
        
	 $('#modal-body-danger').load('detail_drug_interaction.php?vn='+vn,function(responseTxt, statusTxt, xhr){
                            if(statusTxt == "success")
								$('#modal-title-danger').html('<i class="fas fa-exclamation-triangle font20"></i>&ensp;แจ้งเตือนการเกิดปฏิริยาระหว่างยา : Drug Interaction Check');
								$('#myModal-danger').modal('show');
                            if(statusTxt == "error")
                            	alert("Error: " + xhr.status + ": " + xhr.statusText);    
                        });
		
};
function drug_creatinine_show(vn,id,crcl,result,hn,order_date) {
        
	 $('#modal-body-primary').load('drug_creatinine.php?vn='+vn+'&id='+id+'&crcl='+crcl+'&cr='+result+'&hn='+hn+'&lab_date='+order_date,function(responseTxt, statusTxt, xhr){
                            if(statusTxt == "success")
								$('#modal-title-primary').html('<i class="fas fa-exclamation-triangle font20"></i>&ensp;ยาที่ต้องปรับในผู้ป่วยที่มีค่าการทำงานของไตผิดปกติ');
								$('#myModal-primary').modal('show');
                            if(statusTxt == "error")
                            	alert("Error: " + xhr.status + ": " + xhr.statusText);    
                        });
		
};
    
function pathway_show(vn) {
        
	 $('#modal-body-xl').load('pathway.php?vn='+vn,function(responseTxt, statusTxt, xhr){
                            if(statusTxt == "success")
								$('#modal-title-xl').html('<i class="fas fa-project-diagram font20"></i>&ensp;แสดงเส้นทางการรับบริการของผู้ป่วย');
								$('#myModal-xl').modal('show');
                            if(statusTxt == "error")
                            	alert("Error: " + xhr.status + ": " + xhr.statusText);    
                        });
		
};
 function modal_custom_show(modal_type,url,title,icon) {
	 $('#modal-body-'+modal_type).load(url,function(responseTxt, statusTxt, xhr){
                            if(statusTxt == "success")
								$('#modal-title-'+modal_type).html(icon+'&ensp;'+title);
								$('#myModal-'+modal_type).modal('show');
                            if(statusTxt == "error")
                            	alert("Error: " + xhr.status + ": " + xhr.statusText);    
                        });
		
};   
//===== โหลดหน้า detail ======//
    
function detail_load(hn,vstdate,method,full_screen){
                
                $("#dispen-body").load('detail.php?hn='+hn+'&vstdate='+encodeURIComponent(vstdate)+'&full_screen='+full_screen, function(responseTxt, statusTxt, xhr){
                    if(statusTxt == "success")
                      //alert("External content loaded successfully!");
                        $('#indicator').hide();
                    if(statusTxt == "error")
                      alert("Error: " + xhr.status + ": " + xhr.statusText);
                  });

    
}  
function detail_load_vn(vn,hn,vstdate){
                
                $("#dispen-body").load('detail.php?vn='+vn+'&hn='+hn+'&vstdate='+encodeURIComponent(vstdate), function(responseTxt, statusTxt, xhr){
                    if(statusTxt == "success")
                      //alert("External content loaded successfully!");
                        $('#indicator').hide();
                    if(statusTxt == "error")
                      alert("Error: " + xhr.status + ": " + xhr.statusText);
                  });

    
}  

//========โหลดเฉพาะ drug_list ======//
//วันที่ตามค่าตัวแปรที่ส่งไป
function drug_list_load(hn,vstdate,vn,pdx,dx0,dx1,dx2,dx3,dx4,dx5,age_y,datediff){
                        $('#drug_indicator').show();
                        $('#drug_list').load('detail_drug_list.php?hn='+hn+'&vstdate='+encodeURIComponent(vstdate)+'&vn='+vn+'&pdx='+pdx+'&dx0='+dx0+'&dx1='+dx1+'&dx2='+dx2+'&dx3='+dx3+'&dx4='+dx4+'&dx5='+dx5+'&age_y='+age_y+'&date_diff='+datediff,function(responseTxt, statusTxt, xhr){
                            if(statusTxt == "success")
                            $('#drug_indicator').hide();
                            if(statusTxt == "error")
                            alert("Error: " + xhr.status + ": " + xhr.statusText);    
                        });

}
//วันที่ตาม input vstdate
function drug_list_load2(hn,method){
                        $('#drug_indicator').show();
                        $('#drug_list').load('detail_drug_list.php?enter='+method+'&hn='+hn+'&vstdate='+encodeURIComponent($('#vstdate').val()),function(responseTxt, statusTxt, xhr){
                            if(statusTxt == "success")
                            $('#drug_indicator').hide();
                            if(statusTxt == "error")
                            alert("Error: " + xhr.status + ": " + xhr.statusText);    
                        });

}

function drug_list_load_vn(vn,vstdate){
                        $('#drug_indicator').show();
                        $('#drug_list').load('detail_drug_list.php?vn='+vn+'&vstdate='+vstdate,function(responseTxt, statusTxt, xhr){
                            if(statusTxt == "success")
                            $('#drug_indicator').hide();
                            if(statusTxt == "error")
                            alert("Error: " + xhr.status + ": " + xhr.statusText);    
                        });

}

//============ drug off ===========//
function drug_off_load(hn,pdx,vstdate,vn){
                        $('#drug_off_indicator').show();
                        $('#drug-off').load('detail_drug_off.php?hn='+hn+'&pdx='+pdx+'&vstdate='+encodeURIComponent($('#vstdate').val())+'&vstdate1='+vstdate+'&vn='+vn,function(responseTxt, statusTxt, xhr){
                            if(statusTxt == "success")
                            $('#drug_off_indicator').hide();
                            if(statusTxt == "error")
                            alert("Error: " + xhr.status + ": " + xhr.statusText);    
                        });

	
	}
////////////////////////////////////
    //======= counseling =========//
                    function counseling_load(hn){
                        $('counseling_indicator').show();
                        $('#counseling_list').load('detail_drug_counseling.php?hn='+hn,function(responseTxt, statusTxt, xhr){
                            if(statusTxt == "success")
                                    $('#counseling_indicator').hide();
                            if(statusTxt == "error")
                            alert("Error: " + xhr.status + ": " + xhr.statusText);    
                        });
                    }
                                       
    /////////////////////////////  
    //======= ค้างจ่าย =========//
                    function accrued_load(hn){
    
                        $('accrued_indicator').show();
                        $('#accrued_list').load('detail_drug_accrued.php?hn='+hn,function(responseTxt, statusTxt, xhr){
                            if(statusTxt == "success")
                                    $('#accrued_indicator').hide();
                            if(statusTxt == "error")
                            alert("Error: " + xhr.status + ": " + xhr.statusText);    
                        });
                    }
    //==============================================//
					//========= ปฏิเสธรับยา  =========//
                    function refuse_load(hn,vstdate,action,id){ 
                        $('#refuse_indicator').show();
                        $('#refuse_list').load('detail_drug_refuse.php?hn='+hn+'&vstdate='+encodeURIComponent(vstdate)+'&action='+action+'&id='+id,function(responseTxt, statusTxt, xhr){
                            if(statusTxt == "success")
                               $('#refuse_indicator').hide();
                            if(statusTxt == "error")
                            alert("Error: " + xhr.status + ": " + xhr.statusText);    
                        });
                    }
                                       
                        /////////////////////////////  
//========= diag shortcut ========//
              function shortcut_load(hn,vstdate,pdx,dx0,dx1,dx2,dx3,dx4,dx5,age_y,pttype,vn){                    
                  $('#shortcut_indicator').show();                                                   
                    $('#shortcut').load('diag_shortcut.php?hn='+hn+'&vstdate='+encodeURIComponent(vstdate)+'&pdx='+pdx+'&dx0='+dx0+'&dx1='+dx1+'&dx2='+dx2+'&dx3='+dx3+'&dx4='+dx4+'&dx5='+dx5+'&age_y='+age_y+'&pttype='+pttype+'&vn='+vn,function(responseTxt, statusTxt, xhr){
                      if(statusTxt == "success")
                     $('#shortcut_indicator').hide();                        
                      if(statusTxt == "error")
                         alert("Error: " + xhr.status + ": " + xhr.statusText);    
                        });
				}
///////////////////////////// 
//========= Label icon ========//
              function label_load(hn){                    
                  $('#shortcut_indicator').show();                                                   
                    $('#label_icon').load('detail_label_icon_list.php?hn='+hn,function(responseTxt, statusTxt, xhr){
                      if(statusTxt == "success")
                     $('#shortcut_indicator').hide();                        
                      if(statusTxt == "error")
                         alert("Error: " + xhr.status + ": " + xhr.statusText);    
                        });
					}
///////////////////////////// 
//============ load lab list ==========//
            function load_lab_list(hn,vstdate,vn,age_y,sex){
                        $('lab_indicator').show();
                        $('#lab-list').load('detail_lab_list.php?enter=hn&hn='+hn+'&vstdate='+encodeURIComponent(vstdate)+'&vn='+vn+'&age_y='+age_y+'&sex='+sex,function(responseTxt, statusTxt, xhr){
                            if(statusTxt == "success")
                                    $('#lab_indicator').hide();
                            if(statusTxt == "error")
                            alert("Error: " + xhr.status + ": " + xhr.statusText);    
                        });                
            }
    
 //========= drp load ========//

          function drp_load2(hn,action,drp_id){
		  $('#drp_indicator').show();
          $('#drp_list2').load('detail_drp_list2.php?hn='+hn+'&action='+action+'&id='+drp_id+'&vstdate='+encodeURIComponent($('#vstdate').val()),function(responseTxt, statusTxt, xhr){
          if(statusTxt == "success")
               $('#drp_indicator').hide();
          if(statusTxt == "error")
               alert("Error: " + xhr.status + ": " + xhr.statusText);    
           });
		  }
///////////////////////////// 
//========= drp load(old) ========//

          function drp_load(hn,action,drp_id){
		  $('#drp_indicator').show();
          $('#drp_list').load('detail_drp_list.php?hn='+hn+'&action='+action+'&id='+drp_id+'&vstdate='+encodeURIComponent($('#vstdate').val()),function(responseTxt, statusTxt, xhr){
          if(statusTxt == "success")
               $('#drp_indicator').hide();
          if(statusTxt == "error")
               alert("Error: " + xhr.status + ": " + xhr.statusText);    
           });
		  }
///////////////////////////// 
 
    //แก้ไขรายการยา
 function drugedit(vn,qty){
        var x = document.getElementsByName('drug_no')
		for(var k=0;k<x.length;k++)
		
          if(x[k].checked){
			str=x[k].value;
			n=str.split("|");
			//alertload2('include/autocomplete/drugedit.php?icode='+n[0]+'&vn='+vn+'&qty='+qty+'&hos_guid='+n[1],'60%','60%');
	if(qty=='edit'){
        alertload('include/autocomplete/drugedit.php?icode='+n[0]+'&vn='+vn+'&qty='+qty+'&hos_guid='+n[1],'60%','60%');
	}

	if(qty=='payable'){
            alertload('payable.php?icode='+n[0]+'&vn='+vn,'60%','60%');
	}

	if(qty=='refuse'){
         $.fancybox({
		'type' : 'iframe',
		'autoSize': true,
		'autoScale': false,
		maxWidth : 500,
		minHeight   : 350,
		arrows : false,
		scrolling : 'no',

		    beforeShow  : function() {
                $('html').css('overflowY','hidden');
            },
            afterClose   : function() {
                $('html').css('overflowY','auto');
            },
		'href'	: 'drug_refuse.php?icode='+n[0]+'&vn='+vn+'&hos_guid='+n[1]+'&hn='+n[3]+'&qty='+n[2]
		});	
	}

		  }

      }
	  
//ให้คำปรึกษาด้านยา
 function drugsuggess(hn,depart,vstdate){
        var x = document.getElementsByName('drug_no')
        for(var k=0;k<x.length;k++)
          if(x[k].checked){
			str=x[k].value;
			n=str.split("|");
			//alertload2('form_couselling.php?icode='+n[0]+'&hn='+hn,'80%','80%');
		if(depart=="OPD"){
        alertload('form_couselling.php?icode='+n[0]+'&hn='+hn+'&vstdate='+vstdate,'60%','60%')
            
			}
		
		if(depart=="IPD"){
        alertload('form_couselling.php?icode='+n[0]+'&hn='+hn+'&vstdate='+vstdate,'60%','60%')

            }
          }
      }
//respondent_link
function respondent_link(id,name)
	{
		if(id!=""){
		switch(id)
		{
			<?
			mysql_select_db($database_hos, $hos);

			$strSQL = "SELECT * FROM ".$database_kohrx.".kohrx_adr_check_respondent";
			$objQuery = mysql_query($strSQL);
			while($objResult = mysql_fetch_array($objQuery))
			{
			?>
				case "<?=$objResult["id"];?>":
				document.getElementById(name).value = "<?=$objResult["id"];?>";
							
				break;
			<?
			}
            mysql_free_result($objQuery);
			?>
			default:
			 document.getElementById(respondent).value = "1";
		}
		}
	}

function answer_link(id,name)
	{
		if(id!=""){
		switch(id)
		{
			<?
			mysql_select_db($database_hos, $hos);

			$strSQL = "SELECT * FROM ".$database_kohrx.".kohrx_adr_check_answer";
			$objQuery = mysql_query($strSQL);
			while($objResult = mysql_fetch_array($objQuery))
			{
			?>
				case "<?=$objResult["id"];?>":
				document.getElementById(name).value = "<?=$objResult["id"];?>";
							
				break;
			<?
			}
            mysql_free_result($objQuery);

			?>
			default:
			 document.getElementById(respondent).value = "1";
		}
		}
	}
//เลือก list แล้วให้ input เปลี่ยนข้อมูลตาม
function doctorcode(icode,doctor){
	document.getElementById(doctor).value=icode;
	}
//คีย์ doctorcode แล้วให้ listbox เปลี่ยน
function resutName(icode,doctor)
	{
		if(icode!=""){
		switch(icode)
		{
			<?
			mysql_select_db($database_hos, $hos);

			$strSQL = "SELECT o.name,o.doctorcode FROM opduser o left outer join doctor d on d.code=o.doctorcode where d.active='Y'";
			$objQuery = mysql_query($strSQL);
			while($objResult = mysql_fetch_array($objQuery))
			{
			?>
				case "<?=$objResult["doctorcode"];?>":
				document.getElementById(doctor).value = "<?=$objResult["doctorcode"];?>";
							
				break;
			<?
			}
            mysql_free_result($objQuery);
			?>
			default:
			 document.getElementById(doctor).value = "0066";
		}
		}
	}
//setNextfocus
    
    function setNextFocus(objId){
		
		//SELECT TEXT RANGE
		$.fn.selectRange = function(start, end) {
			return this.each(function() {
				if (this.setSelectionRange) {
					this.focus();
					this.setSelectionRange(start, end);
				} else if (this.createTextRange) {
					var range = this.createTextRange();
					range.collapse(true);
					range.moveEnd('character', end);
					range.moveStart('character', start);
					range.select();
				}
			});
		};  
		//SET CURSOR POSITION
		$.fn.setCursorPosition = function(pos) {
		  this.each(function(index, elem) {
			if (elem.setSelectionRange) {
			  elem.setSelectionRange(pos, pos);
			} else if (elem.createTextRange) {
			  var range = elem.createTextRange();
			  range.collapse(true);
			  range.moveEnd('character', pos);
			  range.moveStart('character', pos);
			  range.select();
			}
		  });
		  return this;
		};        

		
        if (event.keyCode == 13){
        if(objId!="Submit"){
	        var obj=document.getElementById(objId);
            if (obj){
                obj.focus();
            }
        }
		if(objId=="Submit"){
			formSubmit3('save','displayIndiv','indicator','1');
			}	
		$('#'+objId).selectRange(0,5);  	
		}
		
	}
	
    function setNextFocus2(objId){
	        var obj=document.getElementById(objId);
            if (obj){
                obj.focus();
			}
			}

// save 
function dispen_save(vn,cursor){
    if($('#notime').prop("checked") == true){ var notime='Y'; } else { var notime='';}
    if($('#lock').prop("checked") == true){ var lock='Y'; } else { var lock='';}
    
                $('#indicator-save').show();
                $("#dispen-body").load('dispen_save.php?vn='+vn+'&rx_print='+$('#rx_print').val()+'&notime='+notime+'&respondent2='+$('#respondent2').val()+'&respondent2_other='+$('#respondent2_other').val()+'&lock='+lock+'&prepare='+$('#prepare').val()+'&dispen='+$('#dispen').val()+'&check='+$('#check').val()+'&depcode1='+$('#depcode1').val()+'&user1='+$('#user1').val()+'&cur_dep='+$('#cur_dep').val()+'&remark='+$('#remark').val()+'&respondent='+$('#respondent').val()+'&answer='+$('#answer').val()+'&cursor_position='+cursor+'&notime='+($('#notime').prop('checked') ? 'Y' : 'N')+'&note='+encodeURIComponent($('#note').val()), function(responseTxt, statusTxt, xhr){
                    if(statusTxt == "success")
                      //alert("External content loaded successfully!");
                       $('#indicator-save').hide();
                    if(statusTxt == "error")
                        alert("Error: " + xhr.status + ": " + xhr.statusText);    
                    });
    
}

    
function openNav() {
  document.getElementById("myNav").style.width = "100%";
}

/* Close when someone clicks on the "x" symbol inside the overlay */
function closeNav() {
  document.getElementById("myNav").style.width = "0%";
}

function alertload(url,w,h,functions){
	 $.colorbox({fixed:true,width:w,height:h, iframe:true, href:url, onOpen : function () {$('html').css('overflowY','hidden');},onCleanup :function(){
$('html').css('overflowY','auto');}
,onClosed:function(){ functions }});

}



//ใส่ 0 ข้างหน้าตัวเลข
function leftPad(value, length) { 
    return ('0'.repeat(length) + value).slice(-length); 
}

//q page load
var myload;	
function loadlink(page2){
myload=setInterval(function(){	$('#rx_queue').load(page2);
}, 5000);
}
    
function loadlink2(page_target,payment2){
clearInterval(myload);
myload=setInterval(function(){	page_load2('rx_queue',page_target+'?pay='+payment2);}, 5000); }

function loadlink3(page_target,payment2,hn){
clearInterval(myload);
myload=setInterval(function(){ page_load2('rx_queue',page_target+'?pay='+payment2+'&hn='+hn);}, 5000); }

function q_page_load(divid,page,page2,payment){
	$("#"+divid).load(page,function(responseTxt, statusTxt, xhr){
        if(statusTxt == "success")
		loadlink2(page2,1);
        $('#indicator').hide();
		
        if(statusTxt == "error")
        alert("โหลดข้อมูลไม่สำเร็จ กรุณาลองใหม่อีกครั้ง");
    });
	}

function page_load2(divid,page){
	//$('#indicator').show();
	$("#"+divid).load(page,function(responseTxt, statusTxt, xhr){
        if(statusTxt == "success")
		$('#indicator').hide();            
        if(statusTxt == "error")
            alert("โหลดข้อมูลไม่สำเร็จ กรุณาลองใหม่อีกครั้ง");
		$('#indicator').hide();            
    });
	}
</script>
<script type="text/javascript">   
$(function(){
     
    $.datetimepicker.setLocale('th'); // ต้องกำหนดเสมอถ้าใช้ภาษาไทย และ เป็นปี พ.ศ.
     
    // กรณีใช้แบบ inline
  /*  $("#testdate4").datetimepicker({
        timepicker:false,
        format:'d-m-Y',  // กำหนดรูปแบบวันที่ ที่ใช้ เป็น 00-00-0000            
        lang:'th',  // ต้องกำหนดเสมอถ้าใช้ภาษาไทย และ เป็นปี พ.ศ.
        inline:true  
    });    */   
     
     
    // กรณีใช้แบบ input
    $("#calendar").datetimepicker({
        timepicker:false,
        format:'d/m/Y',  // กำหนดรูปแบบวันที่ ที่ใช้ เป็น 00-00-0000            
        lang:'th',  // ต้องกำหนดเสมอถ้าใช้ภาษาไทย และ เป็นปี พ.ศ.
        onSelectDate:function(dp,$input){
            var yearT=new Date(dp).getFullYear();  
            var yearTH=yearT+543;
            var fulldate=$input.val();
            var fulldateTH=fulldate.replace(yearT,yearTH);
            $('#vstdate').val(fulldateTH);
        },
    });       
    // กรณีใช้กับ input ต้องกำหนดส่วนนี้ด้วยเสมอ เพื่อปรับปีให้เป็น ค.ศ. ก่อนแสดงปฏิทิน
     
     
});

//======= Popup ========//
function popup(url,name,windowWidth,windowHeight){      
    myleft=(screen.width)?(screen.width-windowWidth)/2:100;   
    mytop=(screen.height)?(screen.height-windowHeight)/2:100;     
    properties = "width="+windowWidth+",height="+windowHeight;  
    properties +=",scrollbars=yes, top="+mytop+",left="+myleft;     
    window.open(url,name,properties);  
}  

//tooltip
$(function () {
  $('[data-toggle="tooltip"]').tooltip()
})

//===== แสดงนาฬิกาเวลาปัจจุบัน Clock =======//
function clockUpdate() {
  var date = new Date();
  function addZero(x) {
    if (x < 10) {
      return x = '0' + x;
    } else {
      return x;
    }
  }



  var h = addZero(date.getHours());
  var m = addZero(date.getMinutes());
  var s = addZero(date.getSeconds());

  $('.digital-clock').text(h + ':' + m + ':' + s)
}
$(document).ready(function() {
 //============ update เวลาเป็นปัจจุบัน ============//
  clockUpdate();
  setInterval(clockUpdate, 1000);
//============ update เวลาเป็นปัจจุบัน ============//

});
//===== แสดงนาฬิกาเวลาปัจจุบัน Clock =======//

//================ default cursor =================//
function firstFocus(objId){
	$('#'+objId).focus();
	}
//================ default cursor =================//
</script>
<script>
//==== dropdown ===//
var maxHeight = 170;
$(function(){

    $("#cssmenu ul li ul li").hover(function() {

         var $container = $(this),
             $list = $container.find("ul"),
             $anchor = $container.find("a"),
             height = $list.height() * 1.1,       // make sure there is enough room at the bottom
             multiplier = height / maxHeight;     // needs to move faster if list is taller

        // need to save height here so it can revert on mouseout            
        $container.data("origHeight", $container.height());

        // so it can retain it's rollover color all the while the dropdown is open
        $anchor.addClass("hover");

        // make sure dropdown appears directly below parent list item    
        $list
            .show()
            .css({
                paddingTop: $container.data("origHeight")
            });

        // don't do any animation if list shorter than max
        if (multiplier > 1) {
            $container
                .css({
                    height: maxHeight,

                })
                .mousemove(function(e) {
                    var offset = $container.offset();
                    var relativeY = ((e.pageY - offset.top) * multiplier) - ($container.data("origHeight") * multiplier);
                    if (relativeY > $container.data("origHeight")) {
                        $list.css("top", -relativeY + $container.data("origHeight"));
                    };
                });
        }

    }, function() {

        var $el = $(this);

        // put things back to normal
        $el
            .height($(this).data("origHeight"))
            .find("ul")
            .css({ top: 0 })
            .hide()
            .end()
            .find("a")
            .removeClass("hover");

    });

    // Add down arrow only to menu items with submenus
    $(".dropdown > li:has('ul')").each(function() {
        $(this).find("a:first").append("i");
    });

    });

// check login
function reloadCheck () {

     $('#check_login').load('check_login_expire.php?recent_queue='+$('#recent_q').val());
}
//===== an search =====//
	function an_search(an){
				$('#queue').val("");
				$('#hn').val("");
                $("#dispen-body").load('detail_ipd.php?an='+an, function(responseTxt, statusTxt, xhr){
                    if(statusTxt == "success")
                      //alert("External content loaded successfully!");
                        $('#indicator').hide();
						$('#an').val(an);
                    
					if(statusTxt == "error")
                      alert("Error: " + xhr.status + ": " + xhr.statusText);
                  });
		
	}
function hn_fill(hn){
    $('#hn').val(hn);
    $('#queue').val('');
}
function vstdate_fill(vstdate){
    $('#vstdate').val(vstdate);
}
function q_fill(queue){
    $('#queue').val(queue);
    $('#hn').val('');
}
function modal_close_id(id){
    $('#'+id).modal('hide');
}
    
function checkCalleriframe(){
    if (!$('#caller-panel').attr('src')){
        alert('empty');   
    }
    else{
        alert('not empty')
    }
}

function callerOpen(){
    $('#caller_panel').show();
}  
	
function callerClose(){
    var confirm1=confirm('ต้องการปิดหน้าต่างการเรียกผู้ป่วยจริงหรือไม่?');
    if (confirm1) {
        $('#caller_panel').hide();
        $('#caller_panel2').attr('src','');
		$('#caller-server').removeClass("btn-success").addClass("btn-secondary");
		$('#caller-server').html('<i class="fas fa-headset font18"></i> Off');		
		
    }
}
function callerAutoClose(){
    $('#caller_panel').hide();
    $('#caller_panel2').attr('src','');
		$('#caller-server').removeClass("btn-success").addClass("btn-secondary");
		$('#caller-server').html('<i class="fas fa-headset font18"></i> Off');			
} 

function callerOpenCheck(){
	if($.trim($('#caller_panel').html())){
		$('#caller-server').removeClass("btn-secondary").addClass("btn-success");
		$('#caller-server').html('<i class="fas fa-headset font18"></i> On');
	}
	else{
		$('#caller-server').removeClass("btn-success").addClass("btn-secondary");
		$('#caller-server').html('<i class="fas fa-headset font18"></i> Off');		
	}

}

function modalCallerCheck(detail) {
	 $('#modal-title-caller-check').html('<i class="fa fa-exclamation-circle font20" aria-hidden="true"></i>&emsp;แจ้งเตือนระบบเรียกชื่อ');
	 $('#modal-body-caller-check').html('<div class="p-3 thfont font16 text-center"><i class="fa fa-exclamation-triangle text-danger" aria-hidden="true" style="font-size:100px; padding-bottom:20px"></i><br><span style="font-size:16px;">'+detail+'</span></div>');   
	 $('#myModal-caller-check').modal('show');
	callerAutoClose();
	exit();
		
};
</script>