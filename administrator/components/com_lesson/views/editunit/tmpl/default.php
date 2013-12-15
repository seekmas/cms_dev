<div class="btn-group">
    <a href="<?php echo JURI::base() . 'index.php?option=com_lesson&view=editlesson';?>">新建课程</a>
    <a href="<?php echo JURI::base() . 'index.php?option=com_lesson';?>" class="btn btn-primary">总览</a>
    <a href="<?php echo JURI::base() . 'index.php?option=com_lesson&view=editlesson&id='.$this->info['parent_id'];?>" class="btn btn-primary">管理课程</a>
    <a href="#" class="btn btn-primary active">管理单元</a>
</div>

<div class="page-header">
    <h3>编辑 <?php echo $this->info['title'];?></h3>
</div>
<div class="row-fluid">
<div class="span6">
<form action="" method="post">

    <div class="control-group">
        <label>序号</label>
        <div class="controls">
            <input type="text" name="" value="<?php echo $this->info['sort'];?>">
        </div>
    </div>

    <div class="control-group">
        <label>标题</label>
        <div class="controls">
            <input type="text" name="" value="<?php echo $this->info['title'];?>">
        </div>
    </div>

    <div class="control-group">
        <label>服务器路径</label>
        <div class="controls">
            <input type="text" name="" value="<?php echo $this->info['path'];?>">
        </div>
    </div>

    <div class="control-group">
        <label>视频文件</label>
        <div class="controls">
            <input type="text" name="" value="<?php echo $this->info['file'];?>">
        </div>
    </div>

    <div class="control-group">
        <label>字幕1</label>
        <div class="controls">
            <input type="text" name="" value="<?php echo $this->info['caption_a'];?>">
        </div>
    </div>

    <div class="control-group">
        <label>字幕2</label>
        <div class="controls">
            <input type="text" name="" value="<?php echo $this->info['caption_b'];?>">
        </div>
    </div>

    <div class="control-group">
        <label>视频时长</label>
        <div class="controls">
            <input type="text" name="" value="<?php echo $this->info['time'];?>">
        </div>
    </div>

    <div class="control-group">
        <label>语音语言</label>
        <div class="controls">
            <select>
                <?php foreach ($this->voice as $key => $value) { ?>
                <option <?php if( $this->info['voice']==$key){?>selected="selected"<?php }?> value="<?php echo $key;?>"><?php echo $value;?></option>
                <?php }?>
            </select>
        </div>
    </div>

    <div class="control-group">
        <label>Description</label>
        <div class="controls">
            <textarea name="description"
                  id=""
                  cols=""
                  rows=""
                  style="width: 100%; height: 200px;"
                  class="mce_editable"><?php echo $this->info['description'];?></textarea>
        </div>
    </div>

    <button class="btn btn-primary" name="update_unit" value="1">更新</button>

</form>
</div>

<div class="span6"></div>

</div>

