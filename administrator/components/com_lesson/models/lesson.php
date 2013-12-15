<?php

class LessonModelLesson extends JModelLegacy {
	
	function getCatalogue( $id = 1)
	{

		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		
		$query->select($db->quoteName('id') .','. $db->quoteName('title') . ',' . $db->quoteName('description') . ' , ' .$db->quoteName('cover') . ' , ' . $db->quoteName('createdtime') . ',' . $db->quoteName('enroll') )
			  ->from($db->quoteName('#__lessons'));
		
		$q = $db->setQuery($query)->execute();

		$catalogue = $q->fetch_all();

		$q->free();

		return $catalogue;
	}

	public function getLessonById( $id = 0)
	{

		$id = intval( $id);

		if( 0 === $id )
			return ;

		$db = JFactory::getDBO();

		$query = $db->getQuery(true);

		$query->select( $db->quoteName('id') .','. $db->quoteName('title') . ',' . $db->quoteName('description') . ' , ' .$db->quoteName('cover') . ' , ' . $db->quoteName('enroll') )
			  ->from( $db->quoteName('#__lessons'))
			  ->where( 'id = '.$id);

		$q = $db->setQuery( $query)->execute();

		$lesson = $q->fetch_assoc();

		$q->free();

		return $lesson;
	}

	public function getUnitByLessonId( $id = 0)
	{
		$id = intval( $id);

		if( 0 === $id)
			return ;

		$db = JFactory::getDBO();

		$query = $db->getQuery(true);

		$query->select( $db->quoteName('id') . ',' . $db->quoteName('title') .','. $db->quoteName('path') . ',' . $db->quoteName('file') .','. $db->quoteName('description'))
			  ->from( $db->quoteName('#__lessons_unit'))
			  ->where( 'parent_id = '.$id);
		
		$q = $db->setQuery( $query)->execute();

		$units = $q->fetch_all();

		$q->free();

		return $units;

	}

	public function getPageById( $id = 0)
	{

		$id = intval( $id);

		if( 0 === $id )
			return ;

		$db = JFactory::getDBO();

		$query = $db->getQuery(true);

		$query->select( '*' )
			  ->from( $db->quoteName('#__lessons_unit'))
			  ->where( 'id = '.$id);

		$q = $db->setQuery( $query)->execute();

		$lesson = $q->fetch_assoc();

		$q->free();

		return $lesson;
	}

	public function getUnitsNumber()
	{

   		$db = JFactory::getDBO();
   		$query = 'SELECT count(*) FROM #__lessons_unit';
   		$db->setQuery( $query );
   		$number = $db->loadResult();
		return $number;			  
	}	

}