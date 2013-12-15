<?php
require_once('../vendor/upyun/upyun.class.php');

class LessonViewUpyun extends JViewLegacy 
{

	private $upyun;

	public function __construct( $config)
	{
		parent::__construct( $config);

		$this->upyun = new UpYun('site-file-36lean', 'mot', 'wujiayao123');
	}

	public function display( $tpl = null)
	{
		$request = new JRequest;

		$get = $request->get();

		if( ! isset( $get['var']) )
			$get['var'] = 'lesson_cover';

		if( isset( $get['remove']))
		{
			$file = $get['file'];
			$this->upyun->delete( '/'.$file);
			JFactory::getApplication()->redirect( JURI::base() . 'index.php?option=com_lesson&view=upyun&var='.$get['var']);
		}

		$this->assignRef('var' , $get['var']);

		parent::display( $tpl = null);
	}

}