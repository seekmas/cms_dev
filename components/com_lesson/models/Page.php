<?php

class LessonModelPage extends JModelLegacy
{
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
}