<?php

class LessonModelLesson extends JModelLegacy
{
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

		$db =JFactory::getDBO();

		$query = $db->getQuery(true);

		$query->select( $db->quoteName('id') . ',' . $db->quoteName('title') .','. $db->quoteName('path') . ',' . $db->quoteName('file') .','. $db->quoteName('description'))
			  ->from( $db->quoteName('#__lessons_unit'))
			  ->where( 'parent_id = '.$id);
		
		$q = $db->setQuery( $query)->execute();

		$units = $q->fetch_all();

		$q->free();

		return $units;

	}
}