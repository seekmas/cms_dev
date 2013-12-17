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

	public function updateUnitById( $data)
	{

		
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		
		$id = intval( $data['id']);

		$update = array(
			'sort'  => intval( $data['sort']) ,
			'title' => trim( $data['title']) ,
			'path'  => trim( $data['path']) ,
			'file'  => trim( $data['file']) , 
			'caption_a' => trim( $data['caption_a']) , 
			'caption_b' => trim( $data['caption_b']) , 
			'time'      => trim( $data['time']) , 
			'voice'     => intval( $data['voice']) , 
			'description' => trim( $data['description']) , 
			'cover' => trim( $data['cover']) , 
			'timeupdated' => time() , 
		);

		$query->update('#__lessons_unit')
			  ->set('sort =         ' . $update['sort'] )
 			  ->set('title = 		' . $db->quote( $update['title'])) 
 			  ->set('path = 		' . $db->quote( $update['path']))
 			  ->set('file = 		' . $db->quote( $update['file']))
 			  ->set('caption_a = 	' . $db->quote( $update['caption_a']))	
 			  ->set('caption_b = 	' . $db->quote( $update['caption_b']))
 			  ->set('time = 		' . $db->quote( $update['time']))
 			  ->set('voice = 		' . $update['voice'])
 			  ->set('description = 	' . $db->quote( $update['description']))
 			  ->set('cover = 		' . $db->quote( $update['cover']))
 			  ->set('timeupdated = 	' . $update['timeupdated'])
 			  ->where( 'id = 		' . $id);

 		$db->setQuery( $query);
 		$db->execute();


		$return = $db->setQuery( $query)->execute();

	}
	
}