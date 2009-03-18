function xoopsFormValidate_formScrapNew() { 
myform = window.document.formScrapNew; 
if ( myform.text.value == "" ) { 
window.alert("Please enter text"); 
myform.text.focus(); 
return false; 
}
return true;
		}

function cleanScrapForm(id,defaultvalue) { 

	
var ele = xoopsGetElementById(id);
if (ele.value==defaultvalue){    
ele.value = "";
}
}

function goToUserPage(id) { 

var ele = xoopsGetElementById(id);
openWithSelfMain('index.php?uid='.ele.value);
}

function changeVisibility(id) { 

var elestyle = xoopsGetElementById(id);

    if (elestyle.style.visibility == "hidden") {
        elestyle.style.visibility = "visible";
                
    } else {
        elestyle.style.visibility = "hidden";
    }
   

}

function changeReplyVisibility(idform) { 

changeVisibility(idform);
}

function tribeImgSwitch(img) { 

var elestyle = xoopsGetElementById(img).style;

        elestyle.display = "none";
  

}
