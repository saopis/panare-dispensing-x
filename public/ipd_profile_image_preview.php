<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Untitled Document</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">    
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script>
        $(document).ready(function(){
           $("#in").click(function(){
                $("img").width($("img").width()+100);
                $("img").height($("img").height()+100);
           });
           $("#out").click(function(){
                $("img").width($("img").width()-100);
                $("img").height($("img").height()-100);
           });
           $("#normal").click(function(){
               var height = $("img").height();
               var ratio=1;
                $("img").height('100vh');
               $("img").css("width", height * ratio);
           });
        });
    </script>
    <script>
        function rotateImage(degree) {
            $('#img_rotate').animate({
                transform: degree
            }, {
                step: function(now, fx) {
                    $(this).css({
                        '-webkit-transform': 'rotate(' + now + 'deg)',
                        '-moz-transform': 'rotate(' + now + 'deg)',
                        'transform': 'rotate(' + now + 'deg)'
                    });
                }
            });
        }
    </script>
    
<style>
.center-fit {
            max-width: 100%;
            max-height: 100vh;
            margin: auto;
        }    
</style>
</head>

<body>
<div style="position: fixed; right: 10px; top: 10 px; z-index: 1">หมุนภาพ&ensp;
<input type="button" class="w3-btn w3-green" value="90" onClick="rotateImage(this.value);" />
        <input type="button" class="w3-btn w3-green" value="-90" onClick="rotateImage(this.value);" />
        <input type="button" class="w3-btn w3-green" value="180" onClick="rotateImage(this.value);" />
        <input type="button" class="w3-btn w3-green" value="เริ่มต้น" onClick="rotateImage('360');" />
        <input type="button" class="w3-btn w3-green" value="ปริ้น" onClick="window.print();" />
    
    &emsp;ซูมภาพ&ensp;
    <button id="in">+</button>
    <button id="out">-</button>
</div> 
    <center style="z-index: -1px;">
    <img src="uploads/<?php echo $_GET['img']; ?>" style=" height: 100vh" id="img_rotate" />
    </center>    
</body>
</html>