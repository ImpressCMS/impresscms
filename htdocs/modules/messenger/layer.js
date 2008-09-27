var vis = new Array();

function MM_findObj(n, d) { //v4.0
  var p,i,x;  if(!d) d=document; if((p=n.indexOf("?"))>0&&parent.frames.length) {
    d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}
  if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
  for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=MM_findObj(n,d.layers[i].document);
  if(!x && document.getElementById) x=document.getElementById(n); return x;
}

function swap_couche(couche){

	triangle = MM_findObj('triangle' + couche);
	var cells = document.getElementById("z").getElementsByTagName("div");
	 if (vis[couche] == 'hide'){
	 if (triangle) triangle.src = 'images/deplierhaut.gif';
     for (i=0; i < cells.length; i++)
	 {
	 if (cells[ i ].className == couche)
	 {
	 cells[ i ].style.display = 'none';
	 } }
	 vis[couche] = 'show';
	 //document.getElementById("couche").style.display='none';
	} else {
	
	vis[couche] = 'hide';
    if (triangle) triangle.src = 'images/deplierbas.gif';
    for (i=0; i < cells.length; i++)
		{
		if (cells[ i ].className == couche)
			{
				cells[ i ].style.display = 'block';
			} }
		
}
}
 
 
 function mp_cache(baliseId) 
{
  document.getElementById("tbl").rows[1].cells[0].style.display='none';
  document.getElementById("tbl").rows[2].cells[0].style.display='none';
  document.getElementById("tbl").rows[3].cells[1].colSpan='3';
  document.getElementById("tbl").rows[3].cells[0].style.display='none';

}

	
function ChangeStatut(obj) {
n=0;
temp = document.prvmsg.elements.length;
for (i=0;i<temp;i++)
 {
if (document.prvmsg.elements[i].checked)
{ n=n+1;
 }
   }
 
if (n == 2) {
document.getElementById("del").disabled = false;
document.getElementById("lu").disabled = false;
document.getElementById("nlu").disabled = false;
document.getElementById("reply").disabled = false;
} else if (n >= 3) {
document.getElementById("del").disabled = false;
document.getElementById("lu").disabled = false;
document.getElementById("nlu").disabled = false;
document.getElementById("reply").disabled = true;
} else {
document.getElementById("del").disabled = true;
document.getElementById("lu").disabled = true;
document.getElementById("nlu").disabled = true;
document.getElementById("reply").disabled = true;
}	

 }

function ChangeStatut2(obj2) {
if (document.prvmsg.ct_file.length) { 
document.getElementById("move").disabled = false;
document.getElementById("delfile").disabled = false;
} else {
document.getElementById("move").disabled = true;
document.getElementById("delfile").disabled = true;
}	
   }
   
function cacheFlash()
  {
false;
  }
  
function msgpop(msg) {
var fir = (document.getElementById);
var content ="<table width=100% border=0 cellpadding=1 cellspacing=0 bgcolor=lightyellow><tr><td><font size=2><font color=black> <center>"+msg+"</center></font></td></tr></table>";
if (fir) {
   document.getElementById("tooltip").style.visibility="visible";
   document.getElementById("tooltip").innerHTML = content;
   }
   }
 
function hideAll(view)
{
document.getElementById(view).style.display = 'none';
}
function showForm(view)
{
document.getElementById(view).style.display = 'block';
}

function getSelect(id){
	if (window.getSelection){
		ele = document.getElementById(id);
		var selection = ele.value.substring(
			ele.selectionStart, ele.selectionEnd
		);
	}
	else if (document.getSelection){
		var selection = document.getSelection();
	}
	else if (document.selection){
		var selection = document.selection.createRange().text;
	}
	else{
		var selection = null;
	}
	return selection;
}

function appendSelectOption2(selectMenuId, optionName, optionValue){
	var selectMenu = xoopsGetElementById(selectMenuId);
	var newoption = new Option(optionName, optionValue);
	selectMenu.options[selectMenu.length] = newoption;
	selectMenu.options.selected=true;
	//selectMenu.options[selectMenu.length].selected = true;
for ( var n=0;n<selectMenu.length;n++ )
{
 selectMenu.options[n].selected=true;
}
}