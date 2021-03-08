<?php defined('_JEXEC') or die('Restricted access');
jimport('joomla.filesystem.file');
jimport('joomla.filesystem.folder');
class archiveCommonFileTransfer
{
    public static function download ($file)
    {
      if(JFile::exists($file)){
            
			// сбрасываем буфер вывода PHP, чтобы избежать переполнения памяти выделенной под скрипт
			// если этого не сделать файл будет читаться в память полностью!
			if (ob_get_level()) {
			  ob_end_clean();
			}

			// заставляем браузер показать окно сохранения файла
			header('Content-Description: File Transfer');
			header('Content-Type: application/octet-stream');
			header('Content-Disposition: attachment; filename=' . basename($file));
			header('Content-Transfer-Encoding: binary');
			header('Expires: 0');
			header('Cache-Control: must-revalidate');
			header('Pragma: public');
			header('Content-Length: ' . filesize($file));
			// читаем файл и отправляем его пользователю
			readfile($file);
			exit;
		} else {
			throw new Exception(JTEXT::_('ARTICULUS.ARTICLE.FILE.ERROR'));
		}
    }

    public static function sendToUser ($data,$fname='tmp')
    {

      			// сбрасываем буфер вывода PHP, чтобы избежать переполнения памяти выделенной под скрипт
			// если этого не сделать файл будет читаться в память полностью!
			if (ob_get_level()) {
			  ob_end_clean();
			}

			// заставляем браузер показать окно сохранения файла
			header('Content-Disposition: attachment; filename="'.basename($fname).'"');
			header('Content-Type: application/octet-stream');
			
			// читаем файл и отправляем его пользователю
			echo ($data);
			exit;
	
		
	}
	
	public static function save($data,$path)
	{
		JFile::write($path,$data);
    }
    
    public static function move ($source,$dest)
    {

        if(JFile::exists($source))
        {
	
            if(!JFile::upload($source,$dest))
            { 
				
                throw new Exception ('UNABLE TO UPLOAD FILE');
            }
            //@TASK РАБОТА С АРХИВАМИ
            
            
        }
        return $dest;

	}
	
	public static function delete($source)
	{
		if (JFile::exists($source))
		{
			echo JFile::delete($source);
		}
	}

}