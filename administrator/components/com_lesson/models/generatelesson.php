<?php

class LessonModelGeneratelesson extends JModelLegacy {

	public function create_lesson( $data)
	{

		if( ! trim( $data['title']) )
			
			return 0; 

		$new = array(
			'title' => trim( $data['title']) , 
			'sort'  => intval( $data['sort']) , 
			'description' => trim( $data['description']) , 
			'visible'	=> intval( $data['visible']) , 
			'free'	=> intval( $data['free']) , 
		);

		$db = JFactory::getDBO();

		$query = $db->getQuery();

		$query->clear()
			  ->select( 'id' )
			  ->from( $db->quoteName('#__lessons'))
			  ->where( 'title = '. $db->quote( $data['title']) );

		$result = $db->setQuery( $query)->loadResult();

		if( $result === NULL)
		{
			$query->clear()
			  	  ->insert($db->quoteName('#__lessons'))
			  	  ->columns($db->quoteName(array('title' , 'sort' , 'description' , 'visible' , 'free')))
			  	  ->values( $db->quote($data['title']) . ',' . $db->quote($data['sort']) . ',' . $db->quote( $data['description']) . ',' . $db->quote( $data['visible']) . ',' . $db->quote($data['free']));

			$return = $db->setQuery( $query)->execute();

			return $return;
		}else
		{
			return $result;
		}
		
	}

}