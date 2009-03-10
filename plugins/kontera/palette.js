// JavaScript Document
var myPalColor = "";

function findPosX(obj)
  {
    var curleft = 0;
    if(obj.offsetParent)
        while(1) 
        {
          curleft += obj.offsetLeft;
          if(!obj.offsetParent)
            break;
          obj = obj.offsetParent;
        }
    else if(obj.x)
        curleft += obj.x;
    return curleft;
  }

  function findPosY(obj)
  {
    var curtop = 0;
    if(obj.offsetParent)
        while(1)
        {
          curtop += obj.offsetTop;
          if(!obj.offsetParent)
            break;
          obj = obj.offsetParent;
        }
    else if(obj.y)
        curtop += obj.y;
    return curtop;
  }

var ttVis = "0";

function ttClose(id) {
document.getElementById(id).style.display = 'none';

	}

function d2h(d) {
var myHex = (d-0).toString(16);
if (myHex.length < 2) {
return "0" + myHex;
} 
else {
return myHex;
}

}
function h2d(h) {return parseInt(h,16);} 

function createSwatches() {

}

var colors = new Array(
"#ffffff","#dadada","#c1c1c1","#a7a7a7","#8e8e8e","#737373", "#595959","#3a3a3a" ,"#212121","#000000","#ff0000","#ffff00","#00ff00" ,"#0000ff", 

"#ee1c24","#f79779","#8dc73f","#c4df9b","#0054a6","#8393ca", "#c7b29a","#c69c6d","#f6989d","#f49ac2","#c80000","#c8c800","#00c800" ,"#0000c8", 

"#f26522","#f78e56","#39b54a","#82ca9c","#2e3192","#8781be", "#736357","#8c6239","#f16eaa","#f26d7d","#960000","#969600" ,"#009600", "#000096", 

"#f8941d","#fdc689","#00aef0","#6dd0f7","#662d91","#a186be", "#534741","#754c24","#ed008c","#ee145b","#640000","#646400" ,"#006400", "#000064", 

"#fff200","#fff899","#0072bc","#7da7d9","#92278f","#bd8cbf", "#362f2d","#603913","#9e005d","#9e0039","#320000","#323200" ,"#003200", "#000032"

);

function openPalette(id) {
myPalette = document.getElementById('palette');
myPalette.style.display = 'block';
myDiv = document.getElementById(id);
myPalette.style.top = findPosY(myDiv) + 21 +"px";
myPalette.style.left = findPosX(myDiv) + 0 + "px";

}


function pal_reset() {
myPalette = document.getElementById('palette');
myValue = document.getElementById('color_value');
myPreview = document.getElementById('color_preview');
myPalette.style.display = 'none';
myPreview.style.backgroundColor = "transparent";
myPreview.style.color = "#0000ff";
myPreview.style.borderBottomColor = "#0000ff";
myValue.value = "#0000ff";
}



function changeVal(color,divClose) {
//myValue = document.getElementById('color_value');
if (divClose == 'close') { 
myPreview = document.getElementById('color_preview');
//myValue.value = color;
//myPreview.style.backgroundColor = color;
myPreview.style.backgroundColor = "transparent";
myPreview.style.color = color;
myPreview.style.borderBottomColor = color;
if (color  ==  "#ffffff") {
myPreview.style.backgroundColor = "#aaaaaa";	
	}
}

//myPalette = document.getElementById('palette');
//myPalette.style.display = 'none';
//}
myPalColor = color;
}


function setVal() {
myValue = document.getElementById('color_value');
myValue.value = myPalColor;
myPalette = document.getElementById('palette');
myPalette.style.display = 'none';
}

function createCols(j,x) {

var myCols = x;

for (i=1; i<=myCols; i++) {

myColor = colors[i + j*14 - 15];
//myColor = i + j*14 - 15;
/*
var myR = Math.floor(255/14 * i);
var myG = Math.floor(255/14 * i);
var myB = Math.floor(255/14 * i);
var myColor = "#" + d2h(myR) + d2h(myG)+ d2h(myB);
*/

document.write ('<td><div class="swatch" style="background-color:'+ myColor +'" onClick="changeVal(\''+myColor+'\',\'close\')\"></div></td>');

//document.write ('<td>'+ myColor +'</td>');
}
}


function createRows(y,x) {
var myRows = y;
for (j=1; j<=myRows; j++) {
document.write ('<tr>');
createCols(j,x);
document.write ('</tr>');

}
}


