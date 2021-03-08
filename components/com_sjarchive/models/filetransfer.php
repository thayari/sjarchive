<?php defined('_JEXEC') or die('Restricted access');
class archiveModelFileTransfer extends JModelLegacy 
{
    public function download ($file)
    {
			// echo '<pre>';
			// var_dump(JPATH_SITE.DIRECTORY_SEPARATOR.$file);
			// echo '</pre>';
			// die();

		$file = JPATH_SITE.DIRECTORY_SEPARATOR.$file;

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

    public function sendToUSer ($file)
    {
      			// сбрасываем буфер вывода PHP, чтобы избежать переполнения памяти выделенной под скрипт
			// если этого не сделать файл будет читаться в память полностью!
			if (ob_get_level()) {
			  ob_end_clean();
			}

			// заставляем браузер показать окно сохранения файла
			header('Content-Description: File Transfer');
			header('Content-type: text/xml');
			
			// читаем файл и отправляем его пользователю
			echo ($file);
			exit;
	
		
    }
}