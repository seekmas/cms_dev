<?php

/**
 * A lesson model
 * 
 * @package    com_lesson.models
 * @subpackage Components
 * @license    GNU/GPL
 */


class LessonModelCatalogue extends JModelLegacy
{
	function getCatalogue( $id = 1)
	{

		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		
		$query->select($db->quoteName('id') .','. $db->quoteName('title') . ',' . $db->quoteName('description') . ' , ' .$db->quoteName('cover') . ' , ' . $db->quoteName('enroll') )
			  ->from($db->quoteName('#__lessons'));
		
		$q = $db->setQuery($query)->execute();

		$catalogue = $q->fetch_all();

		$q->free();

		return $catalogue;
	}
}