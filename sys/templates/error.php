<div class="fwErrorWrapper">
	<div class="fwErrorMain">
		<h3><?php echo $title; ?></h3>
		<p><?php echo $message; ?></p>
		<div><?php echo isset($line)? $line : '';?></div>
	</div>
	<div class="fwErrorDebug"><?php echo $debug;?></div>
</div>
