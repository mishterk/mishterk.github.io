<?php
/**
 * @var \GroovyUI\Post\View_Model $data
 */
?>
<div class="post">
	<div class="post__title"><?= $data->title ?></div>
	<div class="post__author"><?= $data->author ?></div>
	<div class="post__excerpt"><?= $data->excerpt ?></div>
	<a href="" class="post__permalink"><?= $data->link ?></a>
</div>