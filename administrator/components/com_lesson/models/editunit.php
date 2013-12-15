<?php

class LessonModelEditunit extends JModelLegacy {

	public function getUnitById( $id )
	{
		$db = JFactory::getDBO();

		$sql = 'SELECT * from #__lessons_unit where id = '.$id;

		$result = $db->setQuery( $sql)
		      		 ->execute()->fetch_assoc();

		return $result;
	}

	public function getVoice()
	{
		return array(
			'1' => 'English' , 
			'2' => 'Chinese' , 
			'3' => 'Japanese' , 
			'4' => 'Another' , 
 		);
	}

	public function removeUnitById( $id)
	{
		$db = JFactory::getDBO();

		$sql = 'DELETE FROM #__lessons_unit WHERE id = '.$id;

		$db->setQuery($sql)->execute();

	}
	
}