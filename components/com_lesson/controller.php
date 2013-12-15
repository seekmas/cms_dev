<?php
jimport('joomla.application.component.controller');

class LessonController extends JControllerLegacy
{

	public function __construct( $config = array())
	{
		parent::__construct( $config);
	}

	public function display($cachable = false, $urlparams = array())
	{

		/**
		* do nothing here
		*/
		parent::display($cachable , $urlparams );
	}
	
}