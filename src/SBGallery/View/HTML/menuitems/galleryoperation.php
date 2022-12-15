<?php
if($active)
{
	?>
	<a class="active-album-operation" href="<?= $url ?>"><strong><?= $subPage->title ?></strong></a>
	<?php
}
else
{
	?>
	<a class="album-operation" href="<?= $url ?>"><?= $subPage->title ?></a>
	<?php
}
?>
