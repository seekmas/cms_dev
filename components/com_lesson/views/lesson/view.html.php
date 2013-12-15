<?php

class LessonViewLesson extends JViewLegacy
{
	public function display( $tpl = null)
	{

		$app = JFactory::getApplication();
		
		$request = new JRequest();

		$lesson_id = $request->getInt('id');

		$lesson = $this->getModel()->getLessonById( $lesson_id);

		$units = $this->getModel()->getUnitByLessonId( $lesson_id);

		$this->assignRef('lesson' , $lesson);
		$this->assignRef('units' , $units);

		parent::display( $tpl);
	}
}