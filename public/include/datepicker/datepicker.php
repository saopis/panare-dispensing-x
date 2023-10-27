<link rel="stylesheet" href="http://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="include/datepicker/js/jquery-ui.js"></script>
<script>
function set_cal(ele)//function สร้างตัวเลือกปฎิทิน
    {
      $( ele ).datepicker({
          onSelect:(date_text)=>
          {
            let arr=date_text.split("/");
            let new_date=arr[0]+"/"+arr[1]+"/"+(parseInt(arr[2])+543).toString();
            $(ele).val(new_date);
            $(ele).css("color","");
          },
          beforeShow:()=>{

            if($(ele).val()!="")
            {
              let arr=$(ele).val().split("/");
              let new_date=arr[0]+"/"+arr[1]+"/"+(parseInt(arr[2])-543).toString();
              $(ele).val(new_date);

            }
           
            $(ele).css("color","black");
          },
          onClose:()=>{

              $(ele).css("color","");

              if($(ele).val()!="")
              {

                  let arr=$(ele).val().split("/");
                  if(parseInt(arr[2])<2500)
                  {
                      let new_date=arr[0]+"/"+arr[1]+"/"+(parseInt(arr[2])+543).toString();
                      $(ele).val(new_date);
                  }
              }


          },
          dateFormat:"dd/mm/yy", //กำหนดรูปแบบวันที่เป็น วัน/เดือน/ปี
          changeMonth:true,//กำหนดให้เลือกเดือนได้
          changeYear:true,//กำหนดให้เลือกปีได้
          showOtherMonths:true,//กำหนดให้แสดงวันของเดือนก่อนหน้าได้
      });

    

    }

</script>