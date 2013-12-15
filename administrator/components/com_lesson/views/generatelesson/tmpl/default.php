<div class="btn-group">
	<a href="<?php echo JURI::base() . 'index.php?option=com_lesson&view=generatelesson';?>" class="btn btn-primary active">新建课程</a>
	<a href="<?php echo JURI::base() . 'index.php?option=com_lesson';?>" class="btn btn-primary">总览</a>
    <a href="#" class="btn btn-primary">管理课程</a>
    <a href="#" class="btn btn-primary">管理单元</a>
</div>
<hr/>

<div class="row-fluid">
<div class="span6">
<form action="" method="post" enctype="multipart/form-data">

	<div class="control-group">
		<label>课程名</label>
		<div class="controls">
			<input type="text" name="title" />
		</div>
	</div>

	<div class="control-group">
		<label>序号</label>
		<div class="controls">
			<input type="text" name="sort" />
		</div>
	</div>
	
	<div class="control-group">
		<label>课程描述</label>
		<div class="controls">
			<textarea name="description" style="width: 100%; height: 200px;"></textarea>
		</div>
	</div>

	<div class="control-group">
		<label>可见</label>
		<div class="controls">
			<select name="visible">
				<option value="0">隐藏</option>
				<option value="1">显示</option>
			</select>
		</div>
	</div>	

	<div class="control-group">
		<label>类型</label>
		<div class="controls">
			<select name="free">
				<option value="0">收费</option>
				<option value="1">免费</option>
			</select>
		</div>
	</div>

	<button class="btn btn-primary" name="generate_lesson" value="1">生成课程</button>
</form>

</div>
<div class="span6"></div>

</div>