<div id="content">
<ul id="textListing">
<?php 
	
		$count = 1;
		foreach($fileList as $file)
		{
			?>
				<li id="li<?php echo $count; ?>"><input id="cb<?php echo $count; ?>" type="checkbox" name="check[]" class="input" />
				<a href="<?php echo $file['path']; ?>" title="<?php echo $file['name']; ?>" id="a<?php echo $count; ?>"><?php echo shortenFileName($file['name']); ?></a></li>
			<?php
			$count++;
		}
?>
</ul>
</div>