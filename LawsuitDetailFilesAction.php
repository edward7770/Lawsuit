<?php 
	function set_file_name($file)
	{
		$filenameOri=$_FILES[$file]["name"];
		$ext = pathinfo($filenameOri, PATHINFO_EXTENSION);
		////$new_file_name = basename($filename, ".$ext");
		$new_file_name = strtolower($file);
		$new_file_name = $new_file_name.$_POST['dId'].date("YmdGis.").$ext;
		return $new_file_name;
	}
	
	function check_file($file,$file_path)
	{
		$filename=$_FILES[$file]["name"];
		/////$new_file_name=$GLOBALS['file_name'];
		$ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
		if($ext=="jpg" || $ext=="jpeg" || $ext=="png" || $ext=="gif" || $ext=="pdf" || $ext=="docx" || $ext=="xlsx")
		{
			///echo "it is pdf/jpg";
		}
		else
		{
			$msg='Please upload jpeg / jpg / png / gif / pdf / docx / xlsx file only';
			exit($msg);
		}
		$filesize = $_FILES[$file]["size"];
		// Verify file size - 1MB maximum
        $maxsize = 1024 * 1024 * 1;
		// Check if file was uploaded without errors
		if(isset($filename) && $_FILES[$file]["error"] == 0){
			if($filesize > $maxsize)
			{
				$msg="Error: File size of $filename is larger than the allowed limit.";
				exit($msg);
			}
		}
		else
		{
			echo "Error: " . $_FILES["file"]["error"];
		}
		/*
			//exit($new_file_name);
			if (is_writable($file_path.$new_file_name.".$ext")) 
			//if(file_exists($file_path.$new_file_name.$ext))
			{
			$msg="The file name $new_file_name is already exists";
			exit($msg);
			}
		*/
	}
	
	function upload_file($file,$file_path,$filename)
	{
		if(!empty($file_path))
			$mk_dir=create_directory($file_path);
		else 
		$mk_dir=true;
		if(!$mk_dir)
			exit("Unable to create folder.");
		$file_loc=$_FILES[$file]['tmp_name'];
		if(move_uploaded_file($file_loc,$file_path.$filename))
		{
			return 1;
		}
		else
		{
			$msg='Failed to upload file. Please try again';
			exit($msg);
		}
	}
	function create_directory($folderPath)
	{
		$folderPath=$folderPath;
		if (!file_exists($folderPath)) {
			if (mkdir($folderPath, 0777, true)) {
				return true;
				} else {
				return false;
			}
		}
		else 
		return true;
	}
	function deleteDirectory($postData)
	{
		if($index=='idCustomerImage'  && !empty($val))
		{
			unlink($val);
			}
	}
?>