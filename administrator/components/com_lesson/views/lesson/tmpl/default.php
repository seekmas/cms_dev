<?php
$catalogue = $this->getModel()->getCatalogue();
$unit_number = $this->getModel()->getUnitsNumber();

$catalogue_number = count($catalogue);
?>

<div class="btn-group">
	<a href="<?php echo JURI::base() . 'index.php?option=com_lesson&view=generatelesson';?>" class="btn btn-primary">新建课程</a>
	<a href="<?php echo JURI::base() . 'index.php?option=com_lesson';?>" class="btn btn-primary active">总览</a>
    <a href="#" class="btn btn-primary">管理课程</a>
    <a href="#" class="btn btn-primary">管理单元</a>
</div>

<hr/>

<span class="label"><h3>课程总数 : <?php echo $catalogue_number;?></h3></span>
<span class="label"><h3>单元总数 : <?php echo $unit_number;?></h3></span>

<table class="table table-bordered table-condensed">
	<tr>
		<td class="span1">ID</td>
		<td class="span2">Title</td>
		<td class="span4">Description</td>
		<td class="span2">CoverImage</td>
		<td class="span1">Timeupdated</td>
		<td class="span2">Action</td>
	</tr>
	<?php foreach ($catalogue as $key => $value) { ?>
	
	<tr>
		<td><?php echo $value[0];?></td>
		<td><h4><?php echo $value[1];?></h4></td>
		<td><?php echo $value[2];?></td>
		<td><img src="<?php echo JURI::root() . 'images/uploads/' . $value[3];?>" width="200px" /> </td>
		<td><?php echo $value[4] ? date('Y-m-d h:i' , $value[4]) : 'None';?></td>
		<td>
			<a href="<?php echo JURI::base() . 'index.php?option=com_lesson&view=editlesson&id=' . $value[0];?>" class="btn btn-primary">Edit</a>
			<a class="btn btn-primary">Refresh</a>
		</td>
	</tr>

	<?php }?>
</table>
