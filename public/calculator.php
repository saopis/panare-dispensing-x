<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<style type="text/css">
<!--
#demo {
	margin:0px;
/* delete the style declaration below if you do not want it to be transparent! */
}
#calculator {
	filter:alpha(opacity=85);
	-moz-opacity:.85;
	opacity:.85;
}
.drag
{
	position:relative;
	cursor:hand
}
#calculator
{
	background-color:#F4F4F4;
	cursor:move;
	width:190px;
}
.calculatorcontainer
{
	padding:5px;
	border-top:1px solid #C0C0C0;
	border-left:1px solid #C0C0C0;
	border-right:2px outset #C0C0C0;
	border-bottom:2px outset #C0C0C0;
}
#calculator #control
{
	text-align:right;
}
#calculator input
{
	width:40px;
	height:30px;
	margin:2px;
	background-color:#FFF;
	font-family:verdana,arial,helvetica,sans-serif;
	font-size:0.95em;
	border:1px solid #C0C0C0;
	cursor:hand;
	cursor:pointer;
}
#calculator img
{
	border:0px none;
}
#calculator #result
{
	width:173px;
	font-size:1.3em;
	padding:3px;
	cursor:text;
}
#calculator .operation
{
	color:#999;
	font-weight:bold;
	background-color:#DDD;
}
#calculator .equals
{
	color:#FFF;
	font-weight:bold;
	background-color:#336699;
}
#version
{
	float:left;
	padding:2px 0px 0px 2px;
	font-size:0.65em;
}
#version a
{
	color:#333;
	cursor:move;
	text-decoration:none;
}

-->
</style>

<script type="text/javascript">
<!--

var calculation = "";
var resultDone = false;

function addToCalc(val) {
  if(isNaN(val) && isNaN(calculation.substring(calculation.length-1, calculation.length)))
    return false;

  if(!isNaN(val) && resultDone) {
    resetCalc();
    resultDone = false;
  } else if(isNaN(val) && resultDone) {
    resultDone = false;
  }
  calculation += val;
  showResult();
}

function addToCalcDirect(val) {
  calculation = val;
}

function resetCalc() {
  calculation = "";
  showResult();
}

function positiveNegative() {
  if(calculation.substring(0, 1) == "-")
    calculation = calculation.substring(1, calculation.length);
  else
    calculation = "-" + calculation;
  showResult();
}

function calculate() {
  if(calculation != "") {
    try {
      calculation = eval(calculation);
    } catch(e) {
      reportError("Error!");
    }
    resultDone = true;
    showResult();
  }
  else
    return false;
}

function percentage() {
  try {
    calculation = eval(calculation) / 100;
  } catch(e) {
    reportError("Error!");
  }
  resultDone = true;
  showResult();
}

function squareRoot()
{
  try {
    calculation = Math.sqrt(eval(calculation));
  } catch(e) {
    reportError("Error!");
  }
  resultDone = true;
  showResult();
}

function showResult() {
  calculation = calculation.toString();
  if(calculation == "NaN") {
    reportError("Error!");
  } else {
    document.getElementById("result").value = calculation;
  }
}

function reportError(msg) {
  calculation = msg;
  document.getElementById("result").value = msg;
}

function closeCalculator() {
  document.getElementById("calculator").style.display = "none";
}

function about() {
  msg = "เครื่องคิดเลข (Calculator)\n===============\n\n";
  msg += "พัฒนาโดย James Crooke\nhttp://www.cj-design.com";
  alert(msg);
}

var ie = document.all;
var ns6 = document.getElementById && !document.all;
var dragapproved=false;
var z, x, y;

function move(e) {
  if (dragapproved) {
    z.style.left=ns6? temp1+e.clientX-x: temp1+event.clientX-x;
    z.style.top=ns6? temp2+e.clientY-y : temp2+event.clientY-y;
    return false;
  }
}

function drags(e) {
  if (!ie&&!ns6)
  return;
  var firedobj = ns6? e.target : event.srcElement;
  var topelement = ns6? "HTML" : "BODY";
  while (firedobj.tagName != topelement&&firedobj.className != "drag") {
    firedobj = ns6? firedobj.parentNode : firedobj.parentElement;
  }
  if (firedobj.className == "drag") {
    dragapproved = true;
    z = firedobj;
    temp1 = parseInt(z.style.left+0);
    temp2 = parseInt(z.style.top+0);
    x = ns6? e.clientX: event.clientX;
    y = ns6? e.clientY: event.clientY;
    document.onmousemove=move;
    return false;
  }
}
document.onmousedown=drags;
document.onmouseup=new Function("dragapproved=false");
// -->
</script>
</head>

<body>
<div id="demo" align="center">
  <form onsubmit="calculate(); return false;">
  <div id="calculator" class="drag">
    <div class="calculatorcontainer">
      <div id="version">เครื่องคิดเลข (Calculator)</div>
      <div id="control"></div>
      <input type="text" name="input" size="16" id="result" onclick="this.focus()" onkeyup="addToCalcDirect(this.value)"><br />
      <input type="button" value="1" onclick="addToCalc(this.value)"><input type="button" value="2" onclick="addToCalc(this.value)"><input type="button" value="3" onclick="addToCalc(this.value)"><input type="button" value="+" onclick="addToCalc(this.value)" class="operation"><br />
      <input type="button" value="4" onclick="addToCalc(this.value)"><input type="button" value="5" onclick="addToCalc(this.value)"><input type="button" value="6" onclick="addToCalc(this.value)"><input type="button" value="-" onclick="addToCalc(this.value)" class="operation"><br />
      <input type="button" value="7" onclick="addToCalc(this.value)"><input type="button" value="8" onclick="addToCalc(this.value)"><input type="button" value="9" onclick="addToCalc(this.value)"><input type="button" value="*" onclick="addToCalc(this.value)" class="operation"><br />
      <input type="button" value="+/-" onclick="positiveNegative()" class="operation"><input type="button" value="0" onclick="addToCalc(this.value)"><input type="button" value="." onclick="addToCalc(this.value)" class="operation"><input type="button" value="/" onclick="addToCalc(this.value)" class="operation"><br />
      <input type="button" value="sqrt" onclick="squareRoot()" class="operation"><input type="button" value="%" onclick="percentage()" class="operation"><input type="button" value="c" onclick="resetCalc()" class="operation"><input type="button" value="=" onclick="calculate()" class="equals">
    </div>
  </div>
  </form>
</div>
</body>
</html>