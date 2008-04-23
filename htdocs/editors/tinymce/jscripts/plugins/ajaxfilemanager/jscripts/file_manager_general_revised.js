/*
/*
	* author: Logan Cai
	* Email: cailongqun [at] yahoo [dot] com [dot] cn
	* Website: www.phpletter.com
	* Created At: 21/April/2007
	* Modified At: 1/June/2007
*/
// Returns true if the passed value is found in the
// array. Returns false if it is not.
Array.prototype.inArray = function (value,caseSensitive)
{
	var i;
	for (i=0; i < this.length; i++) 
	{
		// use === to check for Matches. ie., identical (===),
		if(caseSensitive){ //performs match even the string is case sensitive
		if (this[i].toLowerCase() == value.toLowerCase()) 
		{
			return true;
		}
		}else
		{
			if (this[i] == value) 
			{
				return true;
			}
		}
	}
	return false;
};
 var dcTime=250;    // doubleclick time
 var dcDelay=100;   // no clicks after doubleclick
 var dcAt=0;        // time of doubleclick
 var savEvent=null; // save Event for handling doClick().
 var savEvtTime=0;  // save time of click event.
 var savTO=null;    // handle of click setTimeOut
 var linkElem = null;
 
 
 function hadDoubleClick() 
 {
   var d = new Date();
   var now = d.getTime();
   if ((now - dcAt) < dcDelay) 
   {
     return true;
   }
   return false;
 };
 
/**
*	enable left click to preview certain files
*/
function enablePreview(elem, num)
{
		
		$(elem).each(
				 function()
				 {
					 
					 $(this).click(function ()
					{
						
						//alert('single click');
						var num = getNum(this.id);
						var path = files[num].path;
						//alert('now: ' + now + '; dcat: ' + dcAt + '; dcDelay: ' + dcDelay);
						if (hadDoubleClick())
						{
							return false;
						}else
						{
							linkElem = $('#a' + num).get(0);
						}	
						
				       d = new Date();
						savEvtTime = d.getTime();
						savTO = setTimeout(function()
						{
						if (savEvtTime - dcAt > 0) 
						{
						//check if this file is previewable
						
						
						var ext = getFileExtension(path);
						var supportedExts = supporedPreviewExts.split(",");
						var isSupportedExt = false;
						for (i in supportedExts)
						{
							var typeOf = typeof(supportedExts[i]);
							//alert(supportedExts[i]);
							if(typeOf.toLowerCase() == 'string' && supportedExts[i].toLowerCase() == ext.toLowerCase())
							{
								isSupportedExt = true;
								break;
							}
						
						}
												
						if(isSupportedExt)
						{
							switch(files[num].cssClass)
							{
								case 'fileVideo':
								case 'fileMusic':
								case 'fileFlash':
																											
									$('#playGround').html('<a id="playGround' + num + '" href="' + files[num].path + '"><div id="player">&nbsp;this is mine</div></a> ');
									
									
									$('#playGround' + num).html('');																		
									$('#playGround' + num).media({ width: 255, height: 210,  autoplay: true  });		
									//alert($('#playGround' + num).html());																	
									showThickBox($('#a' + num).get(0), appendQueryString('#TB_inline', 'height=250'  + '&width=256' + '&inlineId=winPlay&modal=true'));
						
									break;
								default:
									showThickBox(linkElem, appendQueryString(path, 'KeepThis=true&TB_iframe=true&height=' + thickbox.height + '&width=' + thickbox.width));	
									
							}
							
						}
						
						}
						
						
						return false;															
						
						}, dcTime);	
																	 
																	 return false;
																	 
																	 });
					$(this).dblclick(function()
					{
					   var d = new Date();
					   dcAt = d.getTime();
					   if (savTO != null) {
					     clearTimeout( savTO );          // Clear pending Click  
					     savTO = null;
					     
					   }
					  
					   if(typeof(selectFile) != 'undefined')
					   {
					   	
					   	 selectFile(files[num].url);
					   }else
							generateDownloadIframe(appendQueryString(getUrl('download'), 'path=' + files[num].path, ['path']));					   {
					   	
					   }
					   						
					}
					);

				 }
				 );
};

/**
* add over class to the specific table
*/
function tableRuler(element)
{
	
    var rows = $(element);
	
    $(rows).each(function(){
        $(this).mouseover(function(){
            $(this).addClass('over');
        });
        $(this).mouseout(function(){
            $(this).removeClass('over');
        });
    });
};

function previewMedia(rowNum)
{
	$('#preview' +rowNum).html('');
	$('#preview' +rowNum).media({ width: 255, height: 210,  autoplay: true  });
	return false;
};

function getFileExtension(filename) 
{ 
 if( filename.length == 0 ) return ""; 
 var dot = filename.lastIndexOf("."); 
 if( dot == -1 ) return ""; 
 var extension = filename.substr(dot + 1,filename.length); 
 return extension; 
}; 

function closeWindow()
{
	if(window.confirm(warningCloseWindow))
	{
		window.close();
	}
	return false;
};


/**
*	return the url with query string
*/
function getUrl(index,limitNeeded , viewNeeded, searchNeeded)
{

	var queryStr = '';
	var excluded = new Array();
	
	if(typeof(limitNeeded) == 'boolean' && limitNeeded)
	{
		var limit = document.getElementById('limit');
		var typeLimit = typeof(limit);
		if(typeLimit != 'undefined')
		{
			excluded[excluded.length] = 'limit';
			queryStr += (queryStr == ''?'':'&') + 'limit=' + limit.options[limit.selectedIndex].value;				
		}
		
	}
	if(typeof(viewNeeded) == 'boolean' && viewNeeded)
	{
		queryStr += (queryStr == ''?'':'&') + 'view=' +  getView();
		excluded[excluded.length] = 'view';
		
	}
	
	if(typeof(searchNeeded) == 'boolean' && searchNeeded && searchRequired)
	{
		var search_recursively = 0;
		$('input[@name=search_recursively][@checked]').each(
															function()
															{
																search_recursively = this.value;	
															}
															);		
		var searchFolder = document.getElementById('search_folder');
		queryStr += (queryStr == ''?'':'&') + 'search=1&search_name=' + $('#search_name').val() + '&search_recursively=' + search_recursively + '&search_mtime_from=' + $('#search_mtime_from').val() + '&search_mtime_to=' + $('#search_mtime_to').val() + '&search_folder=' +  searchFolder.options[searchFolder.selectedIndex].value;
		excluded[excluded.length] = 'search';
		excluded[excluded.length] = 'search_recursively';
		excluded[excluded.length] = 'search_mtime_from';
		excluded[excluded.length] = 'search_mtime_to';
		excluded[excluded.length] = 'search_folder';
		excluded[excluded.length] = 'search_name';
		excluded[excluded.length] = 'search';
		
	}
	
	

	return appendQueryString(appendQueryString(urls[index], queryString), queryStr, excluded);
};
/**
*	change view
*/
function changeView()
{

		var url = getUrl('view', true, true);
		$('#rightCol').empty();
		ajaxStart('#rightCol');		
		
		$('#rightCol').load(url, 
					{},
					function(){
							ajaxStop('#rightCol img.ajaxLoadingImg');
							urls.present = getUrl('home', true, true);
							initAfterListingLoaded();
						});
};

function goParentFolder()
{

		searchRequired = false;
		var url = appendQueryString(getUrl('view', true, true), 'path=' + parentFolder.path , ['path']);
		$('#rightCol').empty();
		ajaxStart('#rightCol');		
		
		$('#rightCol').load(url, 
					{},
					function(){
							urls.present = appendQueryString(getUrl('home', true, true), 'path=' + parentFolder.path , ['path']);
							ajaxStop('#rightCol img.ajaxLoadingImg');
							initAfterListingLoaded();
						});

};


/**
*	append Query string to the base url
* @param string baseUrl the base url
* @param string the query string
* @param array remove thost url variable from base url if any matches
*/
function appendQueryString(baseUrl, queryStr, excludedQueryStr)
{
	
	if(typeof(excludedQueryStr) == 'object' && excludedQueryStr.length)
	{
		var isMatched = false;
		var urlParts = baseUrl.split("?");
		baseUrl = urlParts[0];
		var count = 1;
		if(typeof(urlParts[1]) != 'undefined' && urlParts[1] != '')
		{//this is the query string parts
			var queryStrParts = urlParts[1].split("&");
			for(var i=0; i < queryStrParts.length; i++)
			{
				//split into query string variable name & value
				var queryStrVariables = queryStrParts[i].split('=');
				for(var j=0; j < excludedQueryStr.length; j++)
				{
					if(queryStrVariables[0] == excludedQueryStr[j])
					{
						isMatched = true;
					}
				}	
				if(!isMatched)
				{
					baseUrl += ((count==1?'?':'&') + queryStrVariables[0] + '=' + queryStrVariables[1]);
					count++;
				}
			}
		}

	}
	if(queryStr != '')
	{
		return (baseUrl.indexOf('?')> -1?baseUrl + '&' + queryStr:baseUrl + '?' + queryStr);
	}else
	{
		return baseUrl;
	}
	
	
	
	
};



/**
*	initiate when the listing page is loaded
* add main features according to the view
*/
function initAfterListingLoaded()
{
	
	parsePagination();

/*	parseCurrentFolder();
	var view = getView();
	
	setDocInfo('root');
	
	if(view != '')
	{
			
		switch(view)
		{

				
			case 'thumbnail':
				//enableContextMenu('dl.thumbnailListing, dl.thumbnailListing dt, dl.thumbnailListing dd, dl.thumbnailListing a');
				enableContextMenu('dl.thumbnailListing');
				for(i in files)
				{
					if(files[i].type== 'folder')
					{//this is foder item
						
						enableFolderBrowsable(i);
					}else
					{//this is file item
						
						switch(files[i].cssClass)
						{
							case 'filePicture':
								//$('#a' + i).attr('rel', 'ajaxphotos');
								//retrieveThumbnail(i);
								
								break;
							case 'fileFlash':
								break;
							case 'fileVideo':
								break;			
							case 'fileMusic':
								break;
							default:
							
								
						}
						enablePreview('#dt' + i, i);
						enablePreview('#thumbUrl' + i, i);
						enablePreview('#a' + i, i);

					}
					enableShowDocInfo( i);
					
				}
				break;
			case 'detail':
			default:
				
				enableContextMenu('#fileList tr');
				for(i in files)
				{
					if(files[i].type== 'folder')
					{//this is foder item
						enableFolderBrowsable(i);
					}else
					{//this is file item
						switch(files[i].cssClass)
						{
							case 'filePicture':
								$('#row' + i + ' td a').attr('rel', 'ajaxphotos');
								break;
							case 'fileFlash':
								break;
							case 'fileVideo':
								break;			
							case 'fileMusic':
								break;
							default:						
								
						};	
						enablePreview('#row' + i + ' td a', i);
						
					}	
					enableShowDocInfo(i);				
				}				
				break;

			
		}
	}*/
	
	
	
};

function enableFolderBrowsable(num, debug)
{
	
	switch(getView())
	{
		case 'thumbnail':
			$('#dt'+ num + ' , #dd' + num + ' a').each(function()
																						 
				{		
/*					if(typeof(debug) != 'undefined' && debug)
					{
						alert(this.tagName  + ' ' +  files[num].path);
					}*/
					doEnableFolderBrowsable(this, num);
				}
			);
			break;
		case 'detail':
		default:
		$('#row' + num + ' td[a]').each(function()
																						 
				{		
					doEnableFolderBrowsable(this, num );
				}
			);
			
	}
	
		
		
	
};

function doEnableFolderBrowsable(elem, num)
{
									 $(elem).click(function()
									{
									 {
									 	searchRequired = false;
									 	var typeNum = typeof(num);
									 	if(typeNum.toUpperCase() == 'STRING')
									 	{
									 		var fpath = (num.indexOf(urls.view) >=0?num:files[num].path);
									 	}else
									 	{
									 		var fpath = files[num].path;
									 	}								 	
									 	
									 	
										 var url = appendQueryString(getUrl('view', true, true), 'path=' + fpath, ['path']);
										 
										 
										 $('#rightCol').empty();	
										 ajaxStart('#rightCol');
										$('#rightCol').load(url, 
													{},
													function(){
														    urls.present = appendQueryString(getUrl('home', true, true), 'path=' + fpath, ['path']);
															ajaxStop('#rightCol img.ajaxLoadingImg');
															initAfterListingLoaded();
														});																									 
									 };
									 return false;	

								}
								);									 
};

/**
* @param mixed destinationSelector where the animation image will be append to
*	@param mixed selectorOfAnimation the jquery selector of the animation 
*/
function ajaxStart(destinationSelector, id, selectorOfAnimation)
{
	if(typeof(selectorOfAnimation) == 'undefined')
	{//set defaullt animation
		selectorOfAnimation = '#ajaxLoading img';
	}
	if(typeof(id) != 'undefined')
	{
		$(selectorOfAnimation).clone().attr('id', id).appendTo(destinationSelector);

	}else
	{
		$(selectorOfAnimation).clone(true).appendTo(destinationSelector);
		
	}
	
	
};
/**
* remove the ajax animation 
*	@param mixed selectorOfAnimation the jquery selector of the animation 
*/
function ajaxStop(selectorOfAnimation)
{
	$(selectorOfAnimation).remove();
};
/**
*	change pagination limit
*/
function changePaginationLimit(elem)
{
		var url = getUrl('view', true, true, true);
		$('#rightCol').empty();
		ajaxStart('#rightCol');				
		$('#rightCol').load(url, 
					{},
					function(){
							urls.present = appendQueryString(getUrl('home', true, true), 'path=' + parentFolder.path , ['path'])
							ajaxStop('#rightCol img.ajaxLoadingImg');
							initAfterListingLoaded();
						});	
};

/**
*	get a query string variable value from an url
* @param string index
* @param string url
*/
function getUrlVarValue(url, index)
{
	
	if(url != '' && index != '')
	{
		var urlParts = url.split("?");
		baseUrl = urlParts[0];	
		var count = 1;
		if(typeof(urlParts[1]) != 'undefined' && urlParts[1] != '')
		{//this is the query string parts
			var queryStrParts = urlParts[1].split("&");
			for(var i=0; i < queryStrParts.length; i++)
			{
				//split into query string variable name & value
				var queryStrVariables = queryStrParts[i].split('=');
				if(queryStrVariables[0] == index)
				{
					return queryStrVariables[1];
				}
			}
		}		
	}
	return '';

};
/**
*	parse current folder
*/
function parseCurrentFolder()
{
	var folders = currentFolder.friendly_path.split('/');
	var str = '';
	var url = getUrl('view', true, true);

	var parentPath = '';
	for(var i = 0; i < folders.length; i++)
	{
		if(i == 0)
		{
			parentPath += paths.root;
			str += '/<a href="' + appendQueryString(url, 'path='+ parentPath, ['path']) + '"><span class="folderRoot">' + paths.root_title + '</span></a>'
			
		}else
		{
			if(folders[i] != '')
			{
				
				parentPath += folders[i] + '/';
				str += '/<a href="' + appendQueryString(url, 'path='+ parentPath , ['path']) + '"><span class="folderSub">' + folders[i] + '</span></a>';
			}
		}
	}
	$('#currentFolderPath').empty().append(str);
	$('#currentFolderPath a').each(
																 function()
																 {
																	 doEnableFolderBrowsable(this, $(this).attr('href'));
																 }
																 );
};

/**
*	enable pagination as ajax function call
*/
function parsePagination()
{
	$('p.pagination a[id!=pagination_parent_link]').each(function ()
																		 {
																			 $(this).click(
																										 function()
																										 {
																													

																											var page =  getUrlVarValue($(this).attr('href'), 'page');
																											var url = appendQueryString(getUrl('view', true, true, true),'page=' + page, ['page']);
																											$('#rightCol').empty();
																											ajaxStart('#rightCol');
																											$('#rightCol').load(url, 
																														{},
																														function(){
																															urls.present = appendQueryString(getUrl('home', true, true, true),'page=' + page, ['page']);
																																ajaxStop('#rightCol img.ajaxLoadingImg');
																																initAfterListingLoaded();
																															});	
																											return false;
																										 }
																										 
																										 );
																		 }
																		 );
};
/**
*	get current view
*/
function getView()
{
	var view = $('input[@name=view][@checked]').get(0);
	if(typeof(view) != 'undefined')
	{
		return view.value;
	}else
	{
		return '';
	}
};

function getNum(elemId)
{
	
	if(typeof(elemId) != 'undefined' && elemId != '')
	{
		var r = elemId.match(/[\d\.]+/g);	
		if(typeof(r) != 'undefined' &&  r &&  typeof(r[0]) != 'undefined')
		{
			return r[0];
		}		
	}

	return 0;
};

function enableContextMenu(jquerySelectors)
{
	
	$(jquerySelectors).contextMenu('contextMenu', 
																 {
																 bindings:
																 {
																		'menuSelect':function(t)
																		{
																			var num = (getNum($(t).attr('id')));	
																			
																			selectFile(files[num].url);
																		},
																		'menuPlay':function(t)
																		{
																			var num = (getNum($(t).attr('id')));																			
																			$('#playGround').html('<a id="playGround' + num + '" href="' + files[num].path + '"><div id="player">&nbsp;this is mine</div></a> ');
																			
																			
																			$('#playGround' + num).html('');																		
																			$('#playGround' + num).media({ width: 255, height: 210,  autoplay: true  });		
																			//alert($('#playGround' + num).html());																	
																			showThickBox($('#a' + num).get(0), appendQueryString('#TB_inline', 'height=250'  + '&width=258' + '&inlineId=winPlay&modal=true'));

																			
																			
																		},
																		'menuPreview':function(t)
																		{
																			var num = (getNum($(t).attr('id')));
																			$('#a' + num).click();					
																		},
																		'menuDownload':function(t)
																		{
																			var num = (getNum($(t).attr('id')));		
																			generateDownloadIframe(appendQueryString(getUrl('download', false, false), 'path=' + files[num].path, ['path']));
																		},
																		'menuRename':function(t)
																		{
																			var num = (getNum($(t).attr('id')));
																			$('#renameName').val(files[num].name);
																			$('#original_path').val(files[num].path);
																			$('#renameNum').val(num);
																			showThickBox($('#a' + num).get(0), appendQueryString('#TB_inline', 'height=100' + '&width=250' + '&inlineId=winRename&modal=true'));
																		},
																		'menuEdit':function(t)
																		{
																			var num = (getNum($(t).attr('id')));
																			var url = '';
																			switch(files[num].cssClass)
																			{
																				case 'filePicture':
																					url = getUrl('image_editor');
																					break;
																				default:
																					url = getUrl('text_editor');
																					
																			}
																				 var param = "status=yes,menubar=no,resizable=yes,scrollbars=yes,location=no,toolbar=no";
																				 param += ",height=" + screen.height + ",width=" + screen.width;
																				if(typeof(window.screenX) != 'undefined')
																				{
																					param += ",screenX = 0,screenY=0";
																				}else if(typeof(window.screenTop) != 'undefined' )
																				{
																					param += ",left = 0,top=0" ;
																				}		 
																				var newWindow = window.open(url + ((url.lastIndexOf("?") > - 1)?"&":"?") + "path="  + files[num].path,'', param);
																				newWindow.focus( );																						
	
																				
																		},
																		'menuCut':function(t)
																		{
																			
																		},
																		'menuCopy':function(t)
																		{
																			
																		},
																		'menuPaste':function(t)
																		{
																			
																		},
																		'menuDelete':function(t)
																		{
																			var num = (getNum($(t).attr('id')));
																			if(window.confirm(warningDelete))
																			{
																				$.getJSON(appendQueryString(getUrl('delete', false,false), 'delete=' + files[num].path, ['delete']), 
																				function(data)
																				{
																					if(typeof(data.error) == 'undefined')
																					{
																						alert('Unexpected Error.');
																					}
																					else if(data.error != '')
																					{
																						alert(data.error);
																					}else
																					{//remove deleted files
																						switch(getView())
																						{
																							case 'thumbnail':																													$('#dl' + num ).remove();
																								break;
																							case 'detail':
																							default:
																								$('#row' + num).remove();
																								
																						}
																						files[num] = null;
																					}
																				}
																				);
																				
																							 				
																			}																			
																		}																 	
																 },
																 	onContextMenu:function(events)
																	{
																	
																		return true;
																	},																 
																	onShowMenu:function(events, menu)
																	{
																		num = 1;
																		switch(getView())
																		{
																			case 'thumbnail':
																				var num = getNum(events.target.id);
																		
																				break;
																			case 'detail':
																			default:
																				switch(events.target.tagName.toLowerCase())
																				{
																					case 'span':
																						
																						if($(events.target).parent().get(0).tagName.toLowerCase()  == 'a')
																						{
																							
																							var num = getNum($(events.target).parent().parent().parent().attr('id'));			
																						}else
																						{
																							var num = getNum($(events.target).parent().parent().parent().parent().attr('id'));			
																						}
																						
																						
																					
																						break;
																					case 'td':
																					var num = getNum($(events.target).parent().attr('id'));																				
																						break;
																					case 'a':
																				    case 'input':
																				      var num = getNum($(events.target).parent().parent().attr('id'));			
																				      break;
																				}
																		}
											              
																		var menusToRemove = new Array;
																		if(typeof(selectFile) == 'undefined')
																		{
																			menusToRemove[menusToRemove.length] = '#menuSelect';
																		}
																		if(!permits.edit)
																		{
																			menusToRemove[menusToRemove.length] = '#menuEdit';
																		}
																		menusToRemove[menusToRemove.length] = '#menuCut';
																		menusToRemove[menusToRemove.length] = '#menuCopy';
																		menusToRemove[menusToRemove.length] = '#menuPaste';
																		switch(files[num].type)
																		{
																			case 'folder':
																				if(numFiles < 1)
																				{
																					menusToRemove[menusToRemove.length] = '#menuPaste';
																				}
																				menusToRemove[menusToRemove.length] = '#menuPreview';
																				menusToRemove[menusToRemove.length] = '#menuDownload';
																				menusToRemove[menusToRemove.length] = '#menuEdit';		
																				menusToRemove[menusToRemove.length] = '#menuPlay';
																				menusToRemove[menusToRemove.length] = '#menuDownload';
																				
																				break;
																			default:
																			var isSupportedExt = false;
																			if(!permits.edit)
																			{
																			var ext = getFileExtension(files[num].path);
																			var supportedExts = supporedPreviewExts.split(",");
																			
																			for(var i = 0; i < supportedExts.length; i++)
																			{
																			if(typeof(supportedExts[i]) != 'undefined' && typeof(supportedExts[i]).toLowerCase() == 'string' && supportedExts[i].toLowerCase() == ext.toLowerCase())
																			{
																			isSupportedExt = true;
																			break;
																			}
																			}
																				
																			}
																			if(!isSupportedExt)
																			{
																				menusToRemove[menusToRemove.length] = '#menuEdit';
																			}
	
																																					
																			switch(files[num].cssClass)
																			{
																				case 'filePicture':
																					menusToRemove[menusToRemove.length] = '#menuPlay';
																					break;
																				case 'fileCode':
																					menusToRemove[menusToRemove.length] = '#menuPlay';
																					break;
																				case 'fileVideo':
																				case 'fileFlash':
																				case 'fileMusic':
																				
																					menusToRemove[menusToRemove.length] = '#menuPreview';																					menusToRemove[menusToRemove.length] = '#menuEdit';
																					break;
																				default:
																					menusToRemove[menusToRemove.length] = '#menuPreview';
																					menusToRemove[menusToRemove.length] = '#menuPlay';
																					
																					
																					
																			}
																			menusToRemove[menusToRemove.length] = '#menuPaste';
																		}
																		
																		if(!permits.del)
																		{
																			menusToRemove[menusToRemove.length] = '#menuDelete';
																		}  
																		if(!permits.cut)
																		{
																			menusToRemove[menusToRemove.length] = '#menuCut';
																		} 
																		if(!permits.copy)
																		{
																			menusToRemove[menusToRemove.length] = '#menuCopy';
																		} 
																		if(!permits.cut  && !permits.copy)
																		{
																			menusToRemove[menusToRemove.length] = '#menuPaste';
																		} 
																		if(permits.rename)
																		{
																			menusToRemove[menusToRemove.length] = '#menuRename';
																		} 
																																																																						
																		
																		$(menusToRemove.join(','), menu).parent().remove();																		
																		
																
																		return menu;
																	}
																 }
																 );	
};
