<div class="btn-group">
	<a href="<?php echo JURI::base() . 'index.php?option=com_lesson&view=editlesson';?>" class="btn btn-primary">新建课程</a>
	<a href="<?php echo JURI::base() . 'index.php?option=com_lesson';?>" class="btn btn-primary">总览</a>
    <a href="#" class="btn btn-primary active">管理课程</a>
    <a href="#" class="btn btn-primary">管理单元</a>
</div>
<hr/>

<div class="page-header">
<p class="lead">修改基础信息</p>


<form action="" method="post">
<input type="hidden" name="id" id="id" value="<?php echo $this->info['id'];?>">

<div class="container-fluid">
<div class="row-fluid">

	<div class="span6">

	<div class="control-group">
		<label>上次更新</label>
		<div class="controls">
			<strong><?php echo $this->info['createdtime'] ? date( 'Y-m-d h:i:s' , $this->info['createdtime'] ) : '未更新' ;?></strong>
		</div>
	</div>

	<div class="control-group">
		<label>序号</label>
		<div class="controls">
			<input type="text" name="sort" value="<?php echo $this->info['sort'];?>" />
		</div>
	</div>

	<div class="control-group">
		<label>标题</label>
		<div class="controls">
			<input type="text" name="title" value="<?php echo $this->info['title'];?>" />
		</div>
	</div>

	<div class="control-group">
		<label>描述内容</label>
		<div class="controls">
		<textarea name="description"
				  id=""
				  cols=""
			      rows=""
				  style="width: 100%; height: 200px;"
				  class="mce_editable"><?php echo $this->info['description'];?></textarea>
		</div>
	</div>
	</div>

	<div class="span6">

	<div class="control-group">
		<label>课程类型</label>
		<div class="controls">
			<select name="free">
				<option <?php if( $this->info['free'] == 0) {?>selected="selected"<?php }?> value="0">收费</option>
				<option <?php if( $this->info['free'] == 1) {?>selected="selected"<?php }?> value="1">免费</option>
			</select>
		</div>
	</div>	

	<div class="control-group">
		<label>可见性</label>
		<div class="controls">
			<select name="visible">
				<option <?php if( $this->info['visible'] == 1) {?>selected="selected"<?php }?> value="1">可见</option>
				<option <?php if( $this->info['visible'] == 0) {?>selected="selected"<?php }?> value="0">隐藏</option>
			</select>
		</div>
	</div>	

	<img src="<?php echo JURI::root() . 'images/uploads/' . $this->info[4];?>" width="300px" />

	<div class="control-group">
		<label>更新封面</label>
		<div class="controls">
			<select name="cover">
				<option value="0">从又拍云选择封面图片</option>
			</select>
		</div>
	</div>

	</div>
</div>
</div>
<button class="btn btn-primary" name="save_lesson" value="1" type="submit">更新数据</button>
</form>
</div>

<div class="page-header">
<p class="lead">课程单元信息</p>


<table class="table table-bordered table-condensed table-hover">

<tr>
	<td class="span1">序号</td>
	<td class="span2">标题</td>
	<td class="span1">视频目录</td>
	<td class="span3">视频文件</td>
	<td class="span1">字幕A</td>
	<td class="span1">字幕B</td>
	<td class="span1">时长</td>
	<td class="span1">语音</td>
	<td class="span1">更多</td>
</tr>

<form action="" method="post">
<input type="hidden" name="id" value="<?php echo $this->info['id'];?>">
	<tr>
		<td><input class="span12" type="text" name="sort" value="" /></td>
		<td><input class="span12" type="text" name="title" value="" /></td>
		<td><input class="span12" type="text" name="path" value="" /></td>
		<td><input class="span12" type="text" name="file" value="" /></td>
		<td><input class="span12" type="text" name="caption_a" value="" /></td>
		<td><input class="span12" type="text" name="caption_b" value="" /></td>
		<td><input class="span12" type="text" name="time" value="" /></td>
		<td>

		<select class="span12" name="voice">
			<option value="0">Language</option>
		<?php foreach ($this->voice as $lang_id => $language) { ?>
			<option value="<?php echo $lang_id;?>"><?php echo $language;?></option>
		<?php }?>
		</select>
		</td>
		<td><button class="btn btn-primary" name="generate_unit" type="submit" value="1">创建</td>
	</tr>
</form>

<form action="" method="post">
<?php foreach ($this->unit as $key => $value) { ?>

	<tr>
		<td><input class="span12" type="text" name="<?php echo $value[0];?>_sort" value="<?php echo $value[10];?>" /></td>
		<td><input class="span12" type="text" name="<?php echo $value[0];?>_title" value="<?php echo $value[2];?>" /></td>
		<td><input class="span12" type="text" name="<?php echo $value[0];?>_path" value="<?php echo $value[3];?>" /></td>
		<td><input class="span12" type="text" name="<?php echo $value[0];?>_file" value="<?php echo $value[4];?>" /></td>
		<td><input class="span12" type="text" name="<?php echo $value[0];?>_caption_a" value="<?php echo $value[6];?>" /></td>
		<td><input class="span12" type="text" name="<?php echo $value[0];?>_caption_b" value="<?php echo $value[7];?>" /></td>
		<td><input class="span12" type="text" name="<?php echo $value[0];?>_time" value="<?php echo $value[8];?>" /></td>
		<td>

		<select class="span12" name="<?php echo $value[0];?>_voice">
			<option>Language</option>
		<?php foreach ($this->voice as $lang_id => $language) { ?>
			<option <?php if( $value[9] == $lang_id){?>selected="selected"<?php }?> value="<?php echo $lang_id;?>"><?php echo $language;?></option>
		<?php }?>
		</select>
		</td>
		<td>
			<a href="<?php echo JURI::base() . 'index.php?option=com_lesson&view=editunit&id='.$value[0];?>">编辑</a> / 
			<a href="<?php echo JURI::base() . 'index.php?option=com_lesson&view=editunit&remove=1&id='.$value[0];?>">删除</a>
		</td>
	</tr>
<? }?>
</table>

<button class="btn btn-primary" type="submit" name="update_all" value="1">更新全部</button>
</form>