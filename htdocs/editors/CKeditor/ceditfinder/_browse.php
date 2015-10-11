<?php
/**
 * Functions for the CKEditor image browser plugin (ceditfinder)
 *
 * @category	ICMS
 * @package		Editors
 * @subpackage	CKEditor
 * @author
 * @author		modified by ImpressCMS
 * @todo		needs security review
 */
defined( 'ceditFinder' ) or die( 'Restricted access' );
function dirList ( $directory ) {
	// @todo properly validate $directory, limit to the imagedirectory

	$results = array();
	$handler = @opendir( $directory );
	if ( $handler === FALSE ) return $results;
	while ( false !== ( $file = readdir( $handler ) ) ) {
		if ($file != '.' && $file != '..' && preg_match("/\.(jpg|png|bmp|gif|svg)$/i", $file))
			$results[] = $file;
	}
	closedir( $handler );
	return $results;
}

function getThumb( $folder, $file ) {
	// @todo properly validate $folder and $file

	// Returns path to thumbnail - creates it if one doesn't exist
	global $cfconfig;

	//echo '<pre>' . $folder . ' -> ' . $file . '</pre>';
	$thumb = $folder . $file;
	if ( $folder == '' ) $thumb = '/' . $thumb;
	$thumbfilepath = $cfconfig['fileroot'] . $cfconfig['imagecache'] . $thumb;
	$origfile = $cfconfig['fileroot'] . $cfconfig['imagefolder'] . $folder . $file;
	if ( file_exists( $thumbfilepath ) ) {
		// Thumbnail exists... check it's creation date is newer than the
		// original file (otherwise it may be out of date)
		if ( filemtime( $origfile ) < filemtime( $thumbfilepath ) ) {
		// Thumbnail is up-to-date
		return $cfconfig['imagecache'] . $thumb;
	}
	}

	// Check thumbs folder exists
	if ( !file_exists( dirname($thumbfilepath) ) ) {
		// Create thumbs folder
		if (!mkdir( dirname($thumbfilepath), 0775, true )) {
			echo '<pre>' . dirname($thumbfilepath) . '</pre>';
			return false;
		}
	}

	// Load image and resize to thumbnail size
	$image = new simpleimage();
	$image->load( $origfile );
	if ( $image->isImage() === FALSE ) return FALSE; // This is not an image!
	if ( $image->getWidth() <= $cfconfig['thumbwidth'] && $image->getHeight() <= $cfconfig['thumbheight'] ) {
		// Doesn't need resizing, so return link to original file
		return $cfconfig['imagefolder'] .  $folder . $file;
	}
	$image->resize( $cfconfig['thumbwidth'], $cfconfig['thumbheight'], true );
	$image->save( $thumbfilepath );
	return $cfconfig['imagecache'] . $thumb;
}

function showSubfolders( $folder ) {
}

function showParentFolder( $folders ) {
	global $browseURL;
	if ( count($folders) > 0 ) {
		// Show folder to move up a level
		$parentfolder = '';
		//echo '<pre>'; print_r( $folders ); echo '</pre>';
		for ( $i=1; $i < ( count($folders) - 1 ) ; $i++ ) {
		$parentfolder .= '/' . $folders[$i];
	}
	if ( $parentfolder == '' ) $parentfolder = '/';
	echo '<a href="' . $browseURL . 'folder=' . urlencode( $parentfolder ) . '"><p class="imagefolder">Parent folder</p></a>';
	}
}
function showFolder( $folder ) {
	// Displays the pictures within the given folder
	global $cfconfig, $browseURL;

	if ( $folder == '/' ) $folder = '';
	if ( $folder != '' ) {
		$folders = explode( '/', $folder );
		echo '<h3>Current folder: ';
		$path = '';
		if ( $folders[0] == '' ) unset( $folders[0] );
		if ( $folders[count($folders)] == '' ) unset( $folders[count($folders)] );
		foreach ( $folders as $subpath ) {
			if ( $subpath != '' ) {
				$path .= '/' . $subpath;
				echo '/' . '<a href="' . $browseURL . '&amp;folder=' . urlencode( $path ) . '">';
				echo $subpath . '</a>';
			}
		}
		echo '</h3>';
	} else {
		echo '<h3>Current folder: root</h3>';
	}
	// Get file listing of folder
	//echo '<pre>' . $imagefolder . $folder . '</pre>';
	$filelist = dirList($cfconfig['fileroot'] . $cfconfig['imagefolder'] . $folder);
	if ( count( $filelist ) < 1 ) {
		echo '<p>No images found.</p>';
		showCreateFolderForm( $folder );
		showUploadImageForm( $folder );
	} else {
		//echo '<pre>'; print_r( $filelist ); echo '</pre>';
		$pichtml = '<script type="text/javascript">
		function SelectImage( url ) {
		var CKEditorFuncNum = ' . $_GET['CKEditorFuncNum'] . ';
		window.opener.CKEDITOR.tools.callFunction( CKEditorFuncNum, url );
		window.close();
	}
		</script>';
		$subfolders = array();
		foreach ( $filelist as $file ) {
			if (is_dir($cfconfig['fileroot'] . $cfconfig['imagefolder'] . $folder . $file)) {
				$subfolders[] = $file;
			} else {
				$thumb = getThumb( $folder, $file );
				// echo '<pre>' . $thumb . '</pre>';
				if ( $thumb !== FALSE ) {
					// Create image view
					$imagediv = '<div class="thumbviewimage"';
/*				if ($_GET['CKEditorFuncNum'] < 1) {
					$imagediv .= '><a href="' . $browseURL . '&amp;action=edit&amp;name=' . $file . '&amp;folder=' . $folder . '" onmouseover="Tip(\'Edit image\', DELAY, 0)" onmouseout="UnTip()">';
					$imagediv .= '<img src="' . $thumb;
					$imagediv .= '" alt="' . $file . '" /></a></div>';
				} else {
*/					$imagediv .= ' onmouseover="Tip(\'Select ' . $file . '\', DELAY, 0)" onmouseout="UnTip()" onclick="SelectImage(\'';
					if ( $folder == '' ) {
						$imagediv .=  $cfconfig['imagefolder'] .$folder . $file;
					} else {
						$imagediv .=  $cfconfig['imagefolder'] . substr($folder, 1) . $file;
					}
					$imagediv .= '\');" >';
					$imagediv .= '<img src="' . $cfconfig['baseurl'] . $thumb;
					$imagediv .= '" alt="' . $file . '"/></div>';
//				}
				// Create controls view
				$controldiv = '<div class="thumbviewcontrols">';
				$controldiv .= '<a href="' . $browseURL . '&amp;action=edit&amp;name=' . $file . '&amp;folder=' . $folder . '" onmouseover="Tip(\'Edit image\', DELAY, 0)" onmouseout="UnTip()">';
				$controldiv .= '<img src="images/edit.png" alt="Edit" /></a>';
				$controldiv .= '<a href="' . $browseURL . '&amp;action=delimage&amp;name=' . $file . '&amp;folder=' . $folder . '" onmouseover="Tip(\'Delete image\', DELAY, 0)" onmouseout="UnTip()"';
				if ( $cfconfig['confirmdelete'] ) $controldiv .= ' onclick="return confirm(\'Are you sure you want to delete ' . $file . ' ?\')"';
				$controldiv .= ' >';
				$controldiv .= '<img src="images/delete.png" alt="Delete" /></a>';
				$controldiv .= '</div>';

				// Add image to display
				$pichtml .= '<div class="thumbview">' . /*$controldiv .*/ $imagediv . "</div>\r\n";
				}
			}
		}
		if ( count( $subfolders ) > 0 && false ) {
			// We have subfolders, list them
			foreach( $subfolders as $subfolder ) {
			if ( $subfolder != 'cfthumbs' ) {
				echo '<div class="folderlisting">';
				echo '<div class="deletefolder">';
				echo '<a href="' . $browseURL . '&amp;action=delfolder&amp;folder=';
				echo urlencode( $folder . '/' . $subfolder ) . '" onmouseover="Tip(\'Delete ' . $subfolder . '\', DELAY, 0)" onmouseout="UnTip()"';
				if ( $cfconfig['confirmdelete'] ) echo ' onclick="return confirm(\'Are you sure you want to delete?\')"';
				echo ' >';
				echo '<img src="/cms/images/icons/delete.png" />';
				echo '</a></div>';
				echo '<a href="' . $browseURL . '&amp;folder=' . urlencode( $folder . DS . $subfolder ) . '">';
				echo '<p class="imagefolder">';
				echo $subfolder . '</p></a>';
				echo '</div>';
			}
		}
		}
		showCreateFolderForm( $folder );
		showUploadImageForm( $folder );
		echo $pichtml;
		//addImageForm( $id );
	} // SQL error
}

function showEditImageForm( $folder, $filename ) {
	global $cfconfig, $browseURL, $rooturl;
	if ( $folder == DS ) $folder = '';
	if (!file_exists($cfconfig['fileroot'] . $folder . $filename)) {
		echo '<p>Image not found.</p>';
		return;
	}
	$image = new simpleimage();
	$image->load($cfconfig['fileroot'] . $folder . $filename);
	if ( $folder != '' ) {
		$folders = explode( '/', $folder );
		echo '<h3';
		$path = '';
		if ( $folders[0] == '' ) unset( $folders[0] );
		if ( $folders[count($folders)] == '' ) unset( $folders[count($folders)] );
		foreach ( $folders as $subpath ) {
			if ( $subpath != '' ) {
				$path .= '/' . $subpath;
				echo '<a href="' . $browseURL . '&amp;folder=' . urlencode( $path ) . '">/';
				echo $subpath . '</a>';
			}
		}
		echo '</h3>';
	}
	echo '<h2>Viewing ' . $filename . '</h2>';
	echo '<img src="' . $cfconfig['baseurl'] . '/', $folder . $filename . '" />';
	?>
<form action="" method="post" name="EditImage">
	<table cellspacing="0" cellpadding="5">
		<tr>
			<td>Filename:</td>
			<td><input name="Name" type="text" value="<?php echo $filename; ?>" />
			</td>
		</tr>
		<tr>
			<td>Size:</td>
			<td><input name="width" type="text" id="width"
				value="<?php echo $image->getWidth(); ?>" size="6" />px by <input
				name="height" type="text" id="height"
				value="<?php echo $image->getHeight(); ?>" size="6" />px<br /> <input
				name="chkConstrainProportions" type="checkbox"
				id="chkConstrainProportions" value="yes" /> Constrain proportions</td>
		</tr>
	</table>
	<p>
		<input type="submit" name="submit" id="submit" value="Apply Changes" />
	</p>
</form>
<?php
}


function showImageEditor( $folder, $filename ) {
	?>
<div id="image-editor">
	<div class="toolbar">
		<button onclick="ImageEditor.save()">Apply Changes</button>
		<span class="spacer"> || </span>
		<button onclick="ImageEditor.undo()">Revert</button>
	</div>
	<div class="toolbar">
		w:<input id="txt-width" type="text" size="3" maxlength="4" /> h:<input
			id="txt-height" type="text" size="3" maxlength="4" /> <input
			id="chk-constrain" type="checkbox" checked="checked" />Constrain
		<button onclick="ImageEditor.resize();">Resize</button>
		<span class="spacer"> || </span>
		<button onclick="ImageEditor.rotate(90)">90&deg;CCW</button>
		<button onclick="ImageEditor.rotate(270)">90&deg;CW</button>
		<span class="spacer"> || </span>
		<button onclick="ImageEditor.crop()">Crop</button>
		<span id="crop-size"></span>
	</div>
	<div class="toolbar">
		<button onclick="ImageEditor.grayscale()">Gray Scale</button>
		<button onclick="ImageEditor.sepia()">Sepia</button>
		<button onclick="ImageEditor.pencil()">Pencil</button>
		<button onclick="ImageEditor.emboss()">Emboss</button>
		<button onclick="ImageEditor.sblur()">Blur</button>
		<button onclick="ImageEditor.smooth()">Smooth</button>
		<button onclick="ImageEditor.invert()">Invert</button>
		<button onclick="ImageEditor.brighten()">Brighten</button>
		<button onclick="ImageEditor.darken()">Darken</button>
	</div>
	<div id="image"></div>
</div>
<?php
}


function addImageForm( $catid ) {
	?>
<?php
}


function showCreateFolderForm( $folder ) {
	?>
<form action="" method="post" name="CreateFolder">
	<p class="imagefolderadd">
		<input name="newfolder" type="text" maxlength="100" id="newfolder" />
		<input type="submit" name="submit" id="submit" value="Create Folder" />
		<input name="folder" type="hidden" id="folder"
			value="<?php echo $folder; ?>" />
	</p>
</form>
<?php
}


function showUploadImageForm( $folder ) {
	?>
<form action="" method="post" enctype="multipart/form-data"
	name="UploadImage">
	<p class="uploadimage">
		<input type="file" name="image" id="image" /> <input type="submit"
			name="submit" id="submitimage" value="Add Image" /> <input
			name="folder" type="hidden" id="folder"
			value="<?php echo $folder; ?>" />
	</p>
</form>
<?php
}

function ListFolder( $path, $editlink = '' ) {
	global $cfconfig, $folder;
	$html = '';
	$dir_handle = @opendir($cfconfig['fileroot'] . $cfconfig['imagefolder'].$path) or die("Unable to open " . $cfconfig['fileroot'].$cfconfig['imagefolder'].$path);
	//Leave only the last folder name
	$active = FALSE;
	if ( ( $path . '/' ) == $folder ) $active = TRUE;
	if ( $folder == '' && $path == '' ) $active = TRUE;
	$p = explode( '/', $path );
	$dirname = end( $p );
	if ( $dirname == '' ) $dirname = '/';

	//display the target folder.
	$html .= '<li';
	//if ( $active ) $html .= ' class="active"';
	$html .= '>';
	if ( $editlink != '' )
		$html .= '<a ';
	if ( $active ) $html .= 'class="active" ';
	if ( $path == '' ) {
		$html .= 'href="' . $editlink . '" >';
	} else {
		$html .= 'href="' . $editlink . '&amp;folder=' . urlencode( $path ) . '" >';
	}
	$html .= $dirname;
	if ( $editlink != '' ) $html .= '</a>';
	$html .= "\n";
	$html .= "<ul>\n";
	while ( false !== ( $file = readdir( $dir_handle ) ) )
	{
		if( $file != "." && $file != ".." )
		{
			if (is_dir($cfconfig['fileroot'] . $cfconfig['imagefolder'] . $path . '/' . $file))
			{
				if ( $file!="cfthumbs" ) $html .= ListFolder( $path . '/' . $file, $editlink );
			}
		}
	}
	$html .= "</ul>\n";
	$html .= "</li>\n";

	closedir($dir_handle);
	return $html;
}

function ShowFolderTree( $editlink = '' ) {
	global $browseURL;
	if ( $editlink == '' ) $editlink = $browseURL;
	$html = '<div id="foldertree">';
	$html .= "\n<ul>";
	$html .= ListFolder( '', $editlink );
	$html .= "\n</ul>";
	$html .= '</div>';
	echo $html;
}
// end _browse.php