<?php
$document = JFactory::getDocument();
$document->addScript('components/com_lesson/views/asset/jwplayer/jwplayer.js');
?>

<ul class="uk-breadcrumb">
  <li><a href="index.php">Home</a></li>
  <li><a href="index.php?option=com_lesson&view=catalogue">Catalogue</a></li>
  <li><a href="index.php?option=com_lesson&view=lesson&id=<?php echo $this->page['parent_id'];?>">Back To Lesson</a></li>
  <li class="uk-active"><span><?php echo $this->page['title'];?></span></li>
</ul>

<h3><?php echo $this->page['title'];?></h3>

<blockquote>
	<?php echo $this->page['description'];?>
</blockquote>

<hr/>

<video width="100%" height="600px" poster="" controls="controls" preload="none">
  <source type="video/mp4" src="http://127.0.0.1/youtube/<?php echo $this->page['path'];?>/<?php echo $this->page['file'];?>" />
</video>

<div class="input-append">
<input class="span10" type="text" />
<button class="btn btn-primary" value="1">Write down</button>
</div>

<div class="controls">

<button class="btn btn-primary">Markdown</button>
<button class="btn btn-primary">Like</button>
<button class="btn btn-primary">Comment</button>
<button class="btn btn-primary">Share</button>

</div>