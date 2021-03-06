<?php
/**
* @todo this should probably be done last
*/
class purgeSystemCommand extends CConsoleCommand {
	public $file_info = 'file_info';
	public $error_list;
	
	public function __construct() {
		$this->error_list = Yii::getPathOfAlias('application.messages') . '\/' . 'error_list_' . date('Y-m-d') . '.txt';
	}
	
	/**
	* Gets files that are more than 30 days old for deletion
	* Trying to keep it DB agnostic, would use DATE_SUB with MySQL
	* @access public
	* @return object Yii DAO object
	*/
	public function filesToDelete() {
		$thirty_days = time() - (30 * 24 * 60 * 60);
		$files = Yii::app()->db->createCommand()
			->select('id, temp_file_path, problem_file')
			->from($this->file_info)
			->where(':download_time <= download_time', array(':download_time' => $thirty_days))
			->queryAll();
		
		return $files;
	}
	
	/**
	* Delete a downloaded (via Curl or FTP) file's information from the database.
	* These records are linked to a file on the server
	* @access protected
	* @return object Yii DAO object
	*/
	protected function clearDb($file_id) {
		$sql = "DELETE FROM " . $this->file_info . " WHERE id = ?";
		$write_zip = Yii::app()->db->createCommand($sql)
			->execute(array($file_id));
	}
	
	/**
	* Delete processed download lists, url lists, and ftp lists from the database.
	* These records aren't linked to a file on the server
	* 1 = processed
	* @access protected
	* @return object Yii DAO object
	*/
	protected function clearLists($table) {
		$sql = "DELETE FROM $table WHERE processed = ?";
		$clear = Yii::app()->db->createCommand($sql)
			->execute(array(1));
	}
	
	/**
	* Get current date/time in ISO 8601 date format
	* @access protected
	* @return string
	*/
	protected function getDateTime() {
		return date('c');
	}
	
	/**
	* Remove file from the file system
	* @param $file_path
	* @access public
	* @return boolean
	*/
	public function removeFile($file_path, $file_id) {
		if(file_exists($file_path)) {
			$delete_file = @unlink($file_path);
			
			if($delete_file == false) {
				$this->logError($this->getDateTime() . " - $file_id, with path: $file_path could not be deleted.");
			} 
		}
		$this->clearDb($file_id);
	}
	
	/**
	* Remove directory from the file system if empty
	* RecursiveDirectoryIterator should account for . and .. files.
	* This should look at files beneath each user's root folder.
	* @param $dir_path
	* @access public
	* @return boolean
	*/
	public function removeDir($dir_path) {
		$dir_list = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir_path, FilesystemIterator::SKIP_DOTS));
		
		foreach($dir_list as $dir) {
			if($dir->isDir() && empty($dir)) {
				$delete_dir = @rmdir($dir);
				
				if($delete_dir == false) {
					$this->logError($this->getDateTime() . " - Directory: $dir_path could not be deleted.");
				}
			}
		}
	}
	
	/**
	* Writes file and directory deletion failures to file.
	* @param $error_text
	* @access protected
	*/
	protected function logError($error_text) {
		$fh = fopen($this->error_list, 'ab');
		fwrite($fh, $error_text . ",\r\n");
		fclose($fh);
	}
	
	/**
	* Mails file and directory deletion failures to sys. admin
	* @access protected
	*/
	protected function mailError() {
		if(file_exists($this->error_list)) {
			$to = 'webmaster@example.com';
			$subject = 'Cinch file and directory deletion errors';
			$from = 'From: webmaster@example.com' . "\r\n";
			
			$message = "The following deletion errors occured:\r\n";
			$message .= file_get_contents($this->error_list);
			
			mail($to, $subject, $message, $headers);
		} else {
			return false;
		}
	}
	
	public function run() {
		$this->clearLists('upload');
		$this->clearLists('files_for_download');
		
		$files = $this->filesToDelete();
		if(empty($files)) { exit; }
		
		foreach($files as $file) {
			$this->removeFile($file['temp_file_path'], $file['id']);
		}
		
		$this->removeDir(Yii::getPathOfAlias('application.uploads'));
		$this->mailError();
	}
}