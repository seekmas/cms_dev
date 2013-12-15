<?php

class LessonViewPage extends JViewLegacy
{
	public function display( $tpl = null)
	{
			
		$request = new JRequest();

		$id = $request->getInt('id');

		$page = $this->getModel()->getPageById( $id);

		$this->assignRef('page' , $page);

		parent::display( $tpl);

	}
}