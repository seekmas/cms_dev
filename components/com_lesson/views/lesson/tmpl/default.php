<ul class="uk-breadcrumb">
	<li><a href="index.php">Home</a></li>
	<li><a href="index.php?option=com_lesson&view=catalogue">Catalogue</a></li>
	<li class="uk-active"><span><?php echo $this->lesson['title'];?></span></li>
</ul>

<div class="uk-panel uk-panel-header">
<h3 class="uk-panel-title"> <?php echo $this->lesson['title'];?></h3>

<section class="uk-grid" data-uk-grid-match="{target:'> div > .uk-panel'}" data-uk-grid-margin>
	<div class="uk-width-1-1 uk-width-medium-2-6">

		<div class="wk-gallery wk-gallery-wall polaroid">
			<a><div>
				<img src="images/uploads/<?php echo $this->lesson['cover'];?>" width="100%" />
					<p class="title"><?php echo $this->lesson['title'];?></p>
				</div>
			</a>
		</div>

	</div>

	<div class="uk-width-1-1 uk-width-medium-4-6">
		<blockquote>
			<p><?php echo $this->lesson['description'];?></p>.
		</blockquote>
	</div>
</section>
</div>

<hr/>


<section class="uk-grid" data-uk-grid-match="{target:'> div > .uk-panel'}" data-uk-grid-margin>
	<div class="uk-width-1-1 uk-width-medium-2-6">
		<div class="uk-panel uk-panel-box">
		<ul class="uk-nav uk-nav-dropdown uk-panel">
			<li class="uk-nav-header">Unit Catalogue</li>
			<?php foreach( $this->units as $key => $unit){?>
				<li><a href="index.php?option=com_lesson&view=page&id=<?php echo $unit[0];?>"><?php echo $key+1;?> . <?php echo $unit[1];?></a></li>
			<?php }?>
		</ul>
		</div>
	</div>



	<div class="uk-width-1-1 uk-width-medium-4-6">
	<?php foreach( $this->units as $key => $unit){?>

		<p><a href="index.php?option=com_lesson&view=page&id=<?php echo $unit[0];?>"><?php echo $key+1;?> . <?php echo $unit[1];?> </a></p>

		<p>
			<small><?php echo $unit['4'];?></small>
		</p>


	<?php }?>
	</div>

</section>