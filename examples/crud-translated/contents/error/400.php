<?php
if($GLOBALS["error"] === null)
{
	?>
	<p>
		The request could not be processed because invalid parameters were provided.
	</p>
	<?php
}
else
{
	?>
	<p><?= $GLOBALS["error"] ?></p>
	<?php
}
