<?php
if($GLOBALS["error"] === null)
{
	?>
	<p>The page does not exists!</p>
	<?php
}
else
{
	?>
	<?= $GLOBALS["error"] ?>
	<?php
}
?>
