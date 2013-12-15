<?php

class LessonViewGeneratelesson extends JViewLegacy 
{
	public function display( $tpl = null)
	{

		$request = new JRequest;

		$post = $request->get('POST');

		$file = $request->get('FILES');


		if(  isset( $post['generate_lesson'] ))
		{

			$id = $this->getModel()->create_lesson( $post);

			if( $id === true)
				echo '<div class="alert alert-success">创建成功</div>';
			else if( preg_match('/^\d+$/', $id))
				echo '<div class="alert alert-error">输入的课程标题有重复 请修改</div>';
			else 
				echo '<div class="alert alert-error">创建失败 请刷新后重试</div>';
		}
		
		
	
		parent::display( $tpl = null);
	}
}