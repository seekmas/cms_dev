<ul class="uk-breadcrumb">
	<li><a href="/cms/">Home</a></li>
	<li class="uk-active"><span>Catalogue</span></li>
</ul>

<?php foreach ( $this->catalogue as $catalogue ) { ?>
<div class="uk-panel uk-panel-header">

<h3 class="uk-panel-title"><a href="index.php?option=com_lesson&view=lesson&id=<?php echo $catalogue[0];?>"><?php echo $catalogue[1];?></a></h3>

<section class="tm-main-bottom uk-grid" data-uk-grid-match="{target:'> div > .uk-panel'}" data-uk-grid-margin>

<div class="uk-width-1-1 uk-width-medium-2-6">
	<a class="uk-thumbnail uk-overlay-toggle" href="#">
	<div class="uk-overlay">
		<img src="./images/uploads/<?php echo $catalogue[3];?>" alt="">
		<div class="uk-overlay-area"></div>
	</div>
	
	<div class="uk-thumbnail-caption"><?php echo $catalogue[1];?></div>
	</a>
</div>

<div class="uk-width-1-1 uk-width-medium-3-6">
	<blockquote>
		<small><?php echo $catalogue[2];?></small>
	</blockquote> 
</div>

<div class="uk-width-1-1 uk-width-medium-1-6">
	<p><span class="uk-text-success"><?php echo $catalogue[4];?> People Enroll It </span></p>
	<p><button class="uk-button uk-button-small" type="button">Enroll Right Now ! </button></p>
</div>
</section>

</div>

<br/>
<?php }?>