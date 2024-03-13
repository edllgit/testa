<?php

function download_report($outputstring){ 
	$filename = "direct-lens_rpt_" . date("m-j-y") . ".xls"; 
	$file = "reports/".$filename; 

	$fp=fopen($file, "w");
	fwrite($fp, $outputstring);
	fclose($fp);
	
   //First, see if the file exists
   if (!is_file($file)) { die("<b>404 File not found!</b>"); }

   //Gather relevent info about file
   $len = filesize($file);
   $filename = basename($file);
   $file_extension = strtolower(substr(strrchr($filename,"."),1));

   //This will set the Content-Type to the appropriate setting for the file
   switch( $file_extension ) {
     case "pdf": $ctype="application/pdf"; break;
     case "exe": $ctype="application/octet-stream"; break;
     case "zip": $ctype="application/zip"; break;
     case "doc": $ctype="application/msword"; break;
     case "xls": $ctype="application/vnd.ms-excel"; break;
     case "ppt": $ctype="application/vnd.ms-powerpoint"; break;
     case "gif": $ctype="image/gif"; break;
     case "png": $ctype="image/png"; break;
     case "jpeg":
     case "jpg": $ctype="image/jpg"; break;
     case "mp3": $ctype="audio/mpeg"; break;
     case "wav": $ctype="audio/x-wav"; break;
     case "mpeg":
     case "mpg":
     case "mpe": $ctype="video/mpeg"; break;
     case "mov": $ctype="video/quicktime"; break;
     case "avi": $ctype="video/x-msvideo"; break;

     //The following are for extensions that shouldn't be downloaded (sensitive stuff, like php files)
     case "php":
     case "htm":
     case "html":
     case "txt": die("<b>Cannot be used for ". $file_extension ." files!</b>"); break;

     default: $ctype="application/force-download";
   }

   //Begin writing headers
   header("Pragma: public");
   header("Expires: 0");
   header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
   header("Cache-Control: private",false); 
   
   //Use the switch-generated Content-Type
   header("Content-Type: $ctype");

   //Force the download
   header("Content-Disposition: attachment; filename=\"".$filename."\";");
   header("Content-Transfer-Encoding: binary");
   header("Content-Length: ".$len);
   set_time_limit(0);
	readfile_chunked($file, $retbytes=true);
   	if (unlink ($file)){ 
		$rptSent = " Report successfully downloaded.";
    } else { 
		$rptSent = " ERROR: Report not sent.";
    } 
	return ($rptSent);
}

function readfile_chunked($file, $retbytes){
	$chunksize = 1*(1024*1024); // how many bytes per chunk
	$buffer = "";
	$cnt = 0;
	$handle = fopen($file, "rb");
	if ($handle === false){
		return false;
	}

	while (!feof($handle)){
		$buffer = fread($handle, $chunksize);
		echo $buffer;
		if ($retbytes){
			$cnt += strlen($buffer);		
		}
	}
	$status = fclose($handle);
	if ($rebytes && $status){
		return ($cnt); // return num bytes delivered, like readfile() does
	}
	return $status;
}

?>