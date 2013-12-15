<?php

class LessonViewEditlesson extends JViewLegacy 
{
	public function display( $tpl = null)
	{

        $request = new JRequest;

        $id = $request->getInt('id');

        $get = $request->get();

        if( isset( $get['generate_unit']) && $get['generate_unit'] == 1)
        {
            $this->getModel()->insertUnit( $get );
        }
        if( isset( $get['update_all']) && $get['update_all'] == 1)
        {
            $this->getModel()->updateAllUnit( $get );
        }

        if( isset( $get['save_lesson']) && $get['save_lesson'] == 1)
        {
            $this->getModel()->updateLesson( $get );
        }

        

        $info = $this->getModel()->getCatalogueById( $id);
        $unit = $this->getModel()->getUnitByCatalogueId( $id);
        $voice = $this->getModel()->getVoice();

        $this->assignRef( 'info' , $info );
        $this->assignRef( 'unit' , $unit );

        $this->assignRef('voice' , $voice);

		parent::display( $tpl = null);
	}
}