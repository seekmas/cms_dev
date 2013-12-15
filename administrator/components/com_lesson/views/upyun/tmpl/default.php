<?php 
require_once('../vendor/upyun/upyun.class.php');

$upyun = new UpYun('site-file-36lean', 'mot', 'wujiayao123');

//$upyun->rmDir('/lesson_cover');
//$upyun->makeDir('/lesson_cover');


try {

$list = $upyun->getList('/'.$this->var);

}catch(Exception $e) {
    echo $e->getCode();
    echo $e->getMessage();
    $upyun->makeDir('/'.$this->var);
}

JHtml::stylesheet(Juri::root() . 'media/uploadify/uploadify.css');
?>

<script>
	$ = jQuery.noConflict();
</script>
<script src="<?php echo Juri::root() . 'media/uploadify/jquery.uploadify.min.js';?>"></script>
<div class="btn-group">
	<a href="<?php echo JURI::base() . 'index.php?option=com_lesson&view=generatelesson';?>" class="btn btn-primary">新建课程</a>
	<a href="<?php echo JURI::base() . 'index.php?option=com_lesson';?>" class="btn btn-primary">总览</a>
    <a href="#" class="btn btn-primary">管理课程</a>
    <a href="#" class="btn btn-primary">管理单元</a>
    <a href="<?php echo JURI::base() . 'index.php?option=com_lesson&view=upyun';?>" class="btn btn-primary active">又拍云管理</a>
</div>

<hr/>

<div class="well">
	<div class="control-group">
	<label>上传位置</label>
	<div class="btn-group">
		<a href="<?php echo Juri::base() .'index.php?option=com_lesson&view=upyun&var=lesson_cover';?>" class="btn btn-primary <?php if( $this->var === 'lesson_cover'){?>active<?php }?>">课程封面</a>
		<a href="<?php echo Juri::base() .'index.php?option=com_lesson&view=upyun&var=unit_cover';?>" class="btn btn-primary <?php if( $this->var === 'unit_cover'){?>active<?php }?>">单元封面</a>
		<a href="<?php echo Juri::base() .'index.php?option=com_lesson&view=upyun&var=other';?>" class="btn btn-primary <?php if( $this->var === 'other'){?>active<?php }?>">其它图片</a>
	</div>
	</div>
	<form>
		选择图片文件
		<div id="queue"></div>
		<input id="file_upload" name="file_upload" type="file" multiple="true">
	</form>

	<script type="text/javascript">
		<?php $timestamp = time();?>
		
		jQuery(function(){
			jQuery('#file_upload').uploadify({
				'formData'     : {
					'timestamp' : '<?php echo $timestamp;?>',
					'token'     : '<?php echo md5('unique_salt' . $timestamp);?>' ,
					'dir' 		: '<?php echo $this->var;?>' , 
				},
				'swf'      : '<?php echo Juri::root() . 'media/uploadify';?>/uploadify.swf',
				'uploader' : '<?php echo Juri::root() . 'media/uploadify';?>/uploadify.php',
				'debug'    : true ,
			});
		});

	</script>
</div>


<table class="table table-bordered table-hover table-condensed">
<?php foreach ($list as $file) { 
	if( $file['type'] !== 'file')
		continue;
?>
<tr>
	<td><img src="<?php echo 'http://site-file-36lean.b0.upaiyun.com/'.$this->var.'/'.$file['name'];?>" width="75px"></td>
	<td><?php echo $file['name'];?></td>
	<td><?php echo date('Y-m-d h:i:s' , $file['time']);?></td>
	<td><?php echo $file['size'];?></td>
	<td>
		<a href="#">改名</a>
		<a href="#">生成缩略图</a>
		<a href="<?php echo Juri::base() .'index.php?option=com_lesson&view=upyun&var='.$this->var.'&remove=1&file='.$this->var.'/'.$file['name'];?>">删除</a>
	</td>
</tr>

<?php }?>
</table>