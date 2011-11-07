<?php
class PDF_Metadata extends FileTypeActiveRecord {
	public function writeMetadata(array $metadata, $file_id, $user_id) {
		$possible_fields = array(
			'Author' => 'author',  
			'Creation-Date' => 'creation_date', 
			'Last-Modified' => 'last_modified', 
			'creator' => 'creator',
			'producer' => 'producer',
			'resourceName' => 'resource_name', 
			'title' => 'title',
			'xmpTPg' => 'pages',
			'subject' => 'subject',
			'keywords' => 'keywords',
			'licensed_to' => 'licensed_to',
			'file_id' => 'file_id',
			'user_id' => 'user_id'
		);
		
		$actual_fields = $this->returnedFields($possible_fields, $metadata);
		$query_fields = $this->addIdInfo($actual_fields, array('file_id' => 'file_id', 'user_id' => 'user_id'));
		$full_metadata = $this->addIdInfo($metadata, array('file_id' => $file_id, 'user_id' => $user_id));
		$bind_params = $this->bindValuesBuilder($query_fields, $full_metadata);
		
		$sql = 'INSERT INTO pdf_metadata(' . $this->queryBuilder($query_fields) . ') 
			VALUES(' . $this->queryBuilder($query_fields, true) . ')';
		
		$write_files = Yii::app()->db->createCommand($sql);
		$write_files->execute($bind_params);
	}
}