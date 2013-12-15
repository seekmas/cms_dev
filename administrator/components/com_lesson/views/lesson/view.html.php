<?php

class LessonViewLesson extends JViewLegacy 
{
	public function display( $tpl = null)
	{
		parent::display( $tpl = null);
	}

    public function getToolbar() {
        // add required stylesheets from admin template
        $document    = JFactory::getDocument();
        $document->addStyleSheet('administrator/templates/system/css/system.css');

        //load the JToolBar library and create a toolbar
        jimport('joomla.html.toolbar');
        $bar = new JToolBar( 'toolbar' );

        return $bar->render();
    }
}