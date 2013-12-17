<?php
require_once('../vendor/upyun/upyun.class.php');

$upyun = new UpYun('site-file-36lean', 'mot', 'wujiayao123');

try {

    $unit = $upyun->getList('/unit_cover');

}catch(Exception $e) {
    echo $e->getCode();
    echo $e->getMessage();
}
?>
<div class="btn-group">
    <a href="<?php echo JURI::base() . 'index.php?option=com_lesson&view=generatelesson';?>" class="btn btn-primary">新建课程</a>
    <a href="<?php echo JURI::base() . 'index.php?option=com_lesson';?>" class="btn btn-primary">总览</a>
    <a href="<?php echo JURI::base() . 'index.php?option=com_lesson&view=editlesson&id='.$this->info['parent_id'];?>" class="btn btn-primary">管理课程</a>
    <a href="#" class="btn btn-primary active">管理单元</a>
    <a href="<?php echo JURI::base() . 'index.php?option=com_lesson&view=upyun';?>" class="btn btn-primary">又拍云管理</a>
</div>

<div class="page-header">
    <h3>编辑 <?php echo $this->info['title'];?></h3>
</div>

<form action="" method="post">

<input type="hidden" name="id" value="<?php echo $this->info['id'];?>">

<div class="row-fluid">
<div class="span3">

    <div class="control-group">
    <img class="img-polaroid" src="<?php echo 'http://upload.36lean.com/unit_cover/'.$this->info['cover'];?>" width="80%" />
    </div>
</div>

<div class="span3">
    <div class="control-group">
        <label>序号</label>
        <div class="controls">
            <input type="text" name="sort" value="<?php echo $this->info['sort'];?>">
        </div>
    </div>

    <div class="control-group">
        <label>标题</label>
        <div class="controls">
            <input type="text" name="title" value="<?php echo $this->info['title'];?>">
        </div>
    </div>

    <div class="control-group">
        <label>服务器路径</label>
        <div class="controls">
            <input type="text" name="path" value="<?php echo $this->info['path'];?>">
        </div>
    </div>

    <div class="control-group">
        <label>视频文件</label>
        <div class="controls">
            <input type="text" name="file" value="<?php echo $this->info['file'];?>">
        </div>
    </div>
</div>

<div class="span3">
    <div class="control-group">
        <label>字幕1</label>
        <div class="controls">
            <input type="text" name="caption_a" value="<?php echo $this->info['caption_a'];?>">
        </div>
    </div>

    <div class="control-group">
        <label>字幕2</label>
        <div class="controls">
            <input type="text" name="caption_b" value="<?php echo $this->info['caption_b'];?>">
        </div>
    </div>

    <div class="control-group">
        <label>视频时长</label>
        <div class="controls">
            <input type="text" name="time" value="<?php echo $this->info['time'];?>">
        </div>
    </div>

    <div class="control-group">
        <label>语音语言</label>
        <div class="controls">
            <select name="voice">
                <?php foreach ($this->voice as $key => $value) { ?>
                <option <?php if( $this->info['voice']==$key){?>selected="selected"<?php }?> value="<?php echo $key;?>"><?php echo $value;?></option>
                <?php }?>
            </select>
        </div>
    </div>
</div>

<div class="span3">
    <div class="control-group">
        <label>Description</label>
        <div class="controls">
            <textarea name="description" style="width: 100%; height: 80px;"><?php echo $this->info['description'];?></textarea>
        </div>
    </div>

    <div class="control-group">
        <label>Videp Cover</label>
        <div class="controls">
            <select name="cover">
                <option value="0">从又拍云选择视频封面</option>
                <?php foreach ($unit as $u) { ?>
                    <option <?php if($u['name'] === $this->info['cover']){?>selected="selected"<?php }?> value="<?php echo $u['name'];?>"><?php echo $u['name'];?></option>
                <?php }?>
            </select>
        </div>
    </div>

</div>

</div>
    <button class="btn btn-primary" name="update_unit" value="1">更新</button>

</form>


