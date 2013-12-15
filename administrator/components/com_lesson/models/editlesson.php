<?php

class LessonModelEditlesson extends JModelLegacy {

	public function getCatalogueById( $id)
	{
		$db = JFactory::getDBO();

		$query = $db->getQuery();

		$sql = 'SELECT * FROM #__lessons where id = '.$id;

		$q = $db->setQuery( $sql)->execute();

		$result  = $q->fetch_assoc();

		$q->free();

		return $result;
	}

	public function getUnitByCatalogueId( $id)
	{
		$db = JFactory::getDBO();

		$query = $db->getQuery();

		$query = 'SELECT * FROM #__lessons_unit where parent_id = '.$id .' order by sort asc';

		$q = $db->setQuery( $query)->execute();

		$result  = $q->fetch_all();

		$q->free();

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

	public function insertUnit( $data )
	{

		if( ! $data['sort'])
			$data['sort'] = 0;

		$db = $this->getDBO();
		$query = $db->getQuery(true);

		$query->clear()
			  ->insert($db->quoteName('#__lessons_unit'))
			  ->columns($db->quoteName(array('sort','parent_id', 'title', 'path', 'file', 'caption_a' , 'caption_b' , 'time' , 'voice' , 'timecreated')))
			  ->values( $data['sort']					 .','. 
			  			$db->quote( $data['id']         ).','.
			  			$db->quote( $data['title'] 		).','.
			  			$db->quote( $data['path'] 		).','.
			  			$db->quote( $data['file'] 		).','.
			  			$db->quote( $data['caption_a'] 	).','.
			  			$db->quote( $data['caption_b'] 	).','.
			  			$db->quote( $data['time'] 		).','.
			  			$data['voice']					 .','.
			  			$db->quote( time() ) 
			  		  );
			  	
		
		$db->setQuery($query);

		$db->execute();
	}

	public function updateAllUnit( $data )
	{

		$db = $this->getDBO();
		$query = $db->getQuery(true);

		$collection = array();

		foreach ($data as $key => $value) {
			if( preg_match('/^\d_/', $key))
			{
				preg_match('/^(\d)_(\w+)/', $key , $match) ;
				$id = intval( $match[1]) ;
				$name = $match[2] ;
				$collection[$id][$name] = trim( $value ) ;
			}
		}

		foreach ($collection as $key => $value) {
			$q = $query->clear()
 				       ->update('#__lessons_unit');
 			
 			foreach ($value as $k => $v) {
 				$q->set( $k.'=' . $db->quote($v));
 			}

			$q->where('id='.$key);

			$db->setQuery( $q);
			$db->execute();

		}
	}

	public function updateLesson( $lesson)
	{	


		$db = JFactory::getDBO();

		$query = $db->getQuery();
		$query->clear()
 			  ->update('#__lessons')
 			  ->set('sort = ' . $lesson['sort'] ) 
 			  ->set('title = ' . trim( $db->quote( $lesson['title'])))
 			  ->set('description = ' . trim( $db->quote( $lesson['description'])))
 			  ->set('free = ' . $lesson['free'])	
 			  ->set('visible = ' . $lesson['visible'])
 			  ->set('cover = '. $lesson['cover'])
 			  ->set('createdtime = ' . time())
 			  ->where( 'id = ' . $lesson['id']);
 		$db->setQuery( $query);
 		$db->execute();
	}

}