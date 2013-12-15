<?php

class LessonViewEditunit extends JViewLegacy 
{
	public function display( $tpl = null)
	{

		$request = new JRequest;

		$get = $request->get();

		$info = $this->getModel('editunit')->getUnitById( intval( $get['id'] ) );

		if( isset( $get['remove']))
		{

			$this->getModel()->removeUnitById( intval( $get['id']) );

			JFactory::getApplication()->redirect( JURI::base() . 'index.php?option=com_lesson&view=editlesson&id='.$info['parent_id']);
		}

		
		$voice = $this->getModel('editunit')->getVoice();

		$this->assignRef( 'info' , $info );	
		$this->assignRef( 'voice' , $voice );


		parent::display( $tpl = null);
	}
}