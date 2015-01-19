<?php
	// Sample file manager for filechooser control

	// Directory where files will be stored
	$root = dirname(__FILE__).'\files';

	// This checks to see if a raw upload was sent (necessary
	// if you are using the upload progress indicator)
	$params		= array();
	$rawupload	= false;
	
	if ($_SERVER['HTTP_CONTENT_DISPOSITION']) {
		if ($_SERVER['HTTP_X_UPLOAD_PARAMETERS'])
			parse_str($_SERVER['HTTP_X_UPLOAD_PARAMETERS'], $params);
		$params['a'] = 'upload';
		$rawupload = true;
	} else {
		$params = $_REQUEST;
	}

	$action = $params['a'];

	function return_error($msg) {
		header('HTTP/1.1 500 Internal Server Error');
		header('Status: 500 Internal Server Error');
		echo $msg;
	}

	if ($action == 'listdir') {
		$dir = $params['d'];
		header('Content-type: text/javascript');
		echo json_encode(getDirectoryEntry($root.'/'.$dir, $dir));
	}
	else if ($action == 'delete') {
		$parent = $params['p'];
		$file = $params['f'];
		if (rmrf($root.'/'.$parent.'/'.$file))
			echo 'Deleted '.$file;
		else
			return_error('Delete failed');
	}
	else if ($action == 'createdir') {
		// Create a directory
		$parent = $params['p'];
		$directory = $params['d'];
		if (mkdir($root.'/'.$parent.'/'.$directory))
			echo 'Created '.$directory;
		else
			return_error('Create folder failed');
	}
	else if ($action == 'upload') {
		// Upload a file
		$parent = $params['p'];
		if ($rawupload) {
			$disposition = explode('; filename=', $_SERVER['HTTP_CONTENT_DISPOSITION']);
			$file = $disposition[1];
			$name = basename($file);
			if ($postin = fopen('php://input', 'r')) {
				if ($fileout = fopen($root.'/'.$parent.'/'.$file, 'w')) {
					while ($chunk = fread($postin, 4096))
						fwrite($fileout, $chunk);
					fclose($fileout);
				} else {
					return_error('Upload failed');
				}
				fclose($postin);
			} else {
				return_error('Upload failed');
			}
		} else {
			$file = $_FILES['i']['tmp_name'];
			$name = basename($_FILES['i']['name']);
			if (!rename($file, $root.'/'.$parent.'/'.$name))
				return_error('Upload failed');
		}
	} else {
		return_error('Invalid request');
	}

	function getDirectoryEntry($dir, $reldir) {

		global $root;
		$self = $_SERVER['SCRIPT_NAME'];
		$url = str_replace($_SERVER['DOCUMENT_ROOT'], '', $root);

		if (is_dir($dir)) {
			$entry = array('path' => $reldir,
						'parent' => dirname($reldir),
						'url' => $url.$reldir,
						'status' => 'success',
						'files' => array(),
						'fileManager' => $_SERVER['SCRIPT_NAME']);
			if ($entry['parent'] == '.')
				$entry['parent'] = null;
			$files = scandir($dir);
			foreach ($files as $file) {
				if (substr($file, 0, 1) == '.')
					continue;
				if (is_dir($dir.'/'.$file)) {
					$dfiles = scandir($dir.'/'.$file);
					$filect = sizeof($dfiles);
					foreach ($dfiles as $dfile)
						if (substr($dfile, 0, 1) == '.')
							$filect--;
					$entry['files'][] = array(
						'name' => $file,
						'size' => $filect,
						'type' => 'directory',
						'path' => $reldir.'/'.$file,
						'url' => $url.$reldir.'/'.$file);
				} else {
					$entry['files'][] = array(
						'name' => $file,
						'size' => filesize($dir.'/'.$file),
						'type' => 'file',
						'url' => $url.$reldir.'/'.$file);
				}
			}
			return $entry;
		} else {
			return array('status' => 'error');
		}

	}

	function rmrf($path) {
		if (is_dir($path)) { 
			$files = scandir($path); 
			foreach ($files as $file) 
				if ($file != '.' && $file != '..') 
					rmrf($path."/".$file);
			return rmdir($path); 
		} else {
			return unlink($path);
		}
	}
