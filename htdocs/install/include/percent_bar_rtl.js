// Percent Bar - Version 1.0
// Author: Brian Gosselin of http://scriptasylum.com
// Script featured on http://www.dynamicdrive.com
// Note: Modified by Dynamicdrive so incr/decrCount() accepts any percentage
//############################################################################
// Modified and remodelled by Rodrigo Pereira Lima aka TheRplima & Sina Asghari aka stranger <www.impresscms.ir>
// http://www.brinfo.com.br
// therplima@gmail.com
// Última Atualização: 16/09/2006
//############################################################################

var ratingMsgs = new Array(6);
var ratingMsgColors = new Array(6);

ratingMsgs[0] = qualityName1;       //Unsafe
ratingMsgs[1] = qualityName2;       //Weak
ratingMsgs[2] = qualityName3;       //Fair
ratingMsgs[3] = qualityName4;       //Strong
ratingMsgs[4] = qualityName5;       //Safe
ratingMsgs[5] = qualityName6;       //Not rated


//###########################################PARAMETROS DE CONFIGURAÇÃO##########################################
var unloadedcolor='lightgrey';      // BGCOLOR OF UNLOADED AREA
var barheight=23;                   // HEIGHT OF PROGRESS BAR IN PIXELS
var barwidth=200;                   // WIDTH OF THE BAR IN PIXELS
var bordercolor='black';            // COLOR OF THE BORDER

ratingMsgColors[0] = "#FD0202";     //Cor do texto e da barra para senhas inseguras
ratingMsgColors[1] = "#aa0033";     //Cor do texto e da barra para senhas fracas
ratingMsgColors[2] = "#ED710F";     //Cor do texto e da barra para senhas razoáveis
ratingMsgColors[3] = "#6699cc";     //Cor do texto e da barra para senhas fortes
ratingMsgColors[4] = "#008000";     //Cor do texto e da barra para senhas seguras
ratingMsgColors[5] = "#676767";     //Cor do texto e da barra para senhas sem classificação
//#####################FIM DOS PARAMETROS DE CONFIGURAÇÃO - NÃO ALTERE NADA PARA BAIXO###########################

// THE FUNCTION BELOW CONTAINS THE ACTION(S) TAKEN ONCE BAR REACHES 100%.
// IF NO ACTION IS DESIRED, TAKE EVERYTHING OUT FROM BETWEEN THE CURLY BRACES ({})
// BUT LEAVE THE FUNCTION NAME AND CURLY BRACES IN PLACE.
// PRESENTLY, IT IS SET TO DO NOTHING, BUT CAN BE CHANGED EASILY.
// TO CAUSE A REDIRECT, INSERT THE FOLLOWING LINE IN BETWEEN THE CURLY BRACES:
// window.location="http://redirect_page.html";
// JUST CHANGE THE ACTUAL URL IT "POINTS" TO.

var action=function()
{
//window.location="http://www.dynamicdrive.com
}

//*****************************************************//
//**********  DO NOT EDIT BEYOND THIS POINT  **********//
//*****************************************************//
var margin = '10px;';
var w3c=(document.getElementById)?true:false;
var ns4=(document.layers)?true:false;
var ie4=(document.all && !w3c)?true:false;
var ie5=(document.all && w3c)?true:false;
var ns6=(w3c && navigator.appName.indexOf("Netscape")>=0)?true:false;
var blocksize=(barwidth-2)/100;
barheight=Math.max(4,barheight);
var loaded=0;
var perouter=0;
var perdone=0;
var images=new Array();
var txt='';
if(ns4){
txt+='<table cellpadding=0 cellspacing=0 border=0><tr><td>';
txt+='<ilayer name="perouter" width="'+barwidth+'" height="'+barheight+'">';
txt+='<layer width="'+barwidth+'" height="'+barheight+'" bgcolor="'+bordercolor+'" top="0" right="0"></layer>';
txt+='<layer width="'+(barwidth-2)+'" height="'+(barheight-2)+'" bgcolor="'+unloadedcolor+'" top="1" right="1"></layer>';
txt+='<layer name="perdone" width="'+(barwidth-2)+'" height="'+(barheight-2)+'" bgcolor="green" top="1" right="1"></layer>';
txt+='<layer name="perouter1" style="position:relative; float:right; background:transparent; width:100px; height:'+barheight+'px; margin-right:10px; padding:5px; margin-bottom:-10px; font-weight:bold; color:'+ratingMsgColors[0]+'; font-size:12px; text-align:right;"></layer>';
txt+='</ilayer>';
txt+='</td></tr></table>';
}else{
txt+='<div id="perouter" onmouseup="hidebar()" style="position:relative; float:right; visibility:hidden; background-color:'+bordercolor+'; width:'+barwidth+'px; height:'+barheight+'px; margin-right:'+margin+'">';
txt+='<div style="position:absolute; top:1px; right:1px; width:'+(barwidth-2)+'px; height:'+(barheight-2)+'px; background-color:'+unloadedcolor+'; z-index:100; font-size:1px;"></div>';
txt+='<div id="perdone" style="position:absolute; top:1px; right:1px; width:0px; height:'+(barheight-2)+'px; background-color:green; z-index:100; font-size:1px;"></div>';
txt+='</div>';
txt+='<div id="perouter1" style="position:relative; float:right; background:transparent; width:100px; height:'+barheight+'px; margin-right:10px; padding:5px; margin-bottom:-10px; font-weight:bold; color:'+ratingMsgColors[0]+'; font-size:12px; text-align:right;"></div>';
}

document.write(txt);

function incrCount(prcnt){
loaded+=prcnt;
setCount(loaded);
}

function decrCount(prcnt){
loaded-=prcnt;
setCount(loaded);
}

function setCount(prcnt){
loaded=prcnt;
if(loaded<0)loaded=0;
if(loaded>=100){
loaded=100;
setTimeout('hidebar()', 400);
}
clipid(perdone, 0, loaded, barheight-2, 0);
}

//THIS FUNCTION BY MIKE HALL OF BRAINJAR.COM
function findlayer(name,doc){
var i,layer;
for(i=0;i<doc.layers.length;i++){
layer=doc.layers[i];
if(layer.name==name)return layer;
if(layer.document.layers.length>0)
if((layer=findlayer(name,layer.document))!=null)
return layer;
}
return null;
}

function progressBarInit(){
perouter=(ns4)?findlayer('perouter',document):(ie4)?document.all['perouter']:document.getElementById('perouter');
perdone=(ns4)?perouter.document.layers['perdone']:(ie4)?document.all['perdone']:document.getElementById('perdone');
clipid(perdone,0,0,barheight-2,0);
if(ns4)perouter.visibility="show";
else perouter.style.visibility="visible";
}

function hidebar(){
action();
//(ns4)? perouter.visibility="hide" : perouter.style.visibility="hidden";
}

function clipid(id,t,r,b,l){
val = blocksize*r;
if(ns4){
id.clip.right=l;
id.clip.top=t;
id.clip.right=val;
id.clip.bottom=b;
}else id.style.width=val+"px";
  if (r >= 0 && r <= 30){
    document.getElementById('perouter1').innerHTML=ratingMsgs[0];
    document.getElementById('perouter1').style.color=ratingMsgColors[0];
    document.getElementById('perdone').style.backgroundColor=ratingMsgColors[0];
  }else if (r > 30 && r <= 50){
    document.getElementById('perouter1').innerHTML=ratingMsgs[1];
    document.getElementById('perouter1').style.color=ratingMsgColors[1];
    document.getElementById('perdone').style.backgroundColor=ratingMsgColors[1];
  }else if (r > 50 && r <= 70){
    document.getElementById('perouter1').innerHTML=ratingMsgs[2];
    document.getElementById('perouter1').style.color=ratingMsgColors[2];
    document.getElementById('perdone').style.backgroundColor=ratingMsgColors[2];
  }else if (r > 70 && r <= 90){
    document.getElementById('perouter1').innerHTML=ratingMsgs[3];
    document.getElementById('perouter1').style.color=ratingMsgColors[3];
    document.getElementById('perdone').style.backgroundColor=ratingMsgColors[3];
  }else if (r > 90 && r <= 100){
    document.getElementById('perouter1').innerHTML=ratingMsgs[4];
    document.getElementById('perouter1').style.color=ratingMsgColors[4];
    document.getElementById('perdone').style.backgroundColor=ratingMsgColors[4];
  }else{
    document.getElementById('perouter1').innerHTML=ratingMsgs[5];
    document.getElementById('perouter1').style.color=ratingMsgColors[5];
    document.getElementById('perdone').style.backgroundColor=ratingMsgColors[5];
  }
}

progressBarInit();

window.onresize=function(){
if(ns4)setTimeout('history.go(0)' ,400);
}
