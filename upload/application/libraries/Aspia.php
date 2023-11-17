<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Aspia {
	
	var $CI;

	public function __construct()
	{
		$this->CI =& get_instance();
	}
	
	public function max_size_upload($return='KB')
	{
		$upload_max_filesize = $this->return_bytes(ini_get('upload_max_filesize'));
		$post_max_size = $this->return_bytes(ini_get('post_max_size'));
		
		if ($upload_max_filesize == $post_max_size) { $max_size = $upload_max_filesize; }
		if ($upload_max_filesize < $post_max_size) { $max_size = $upload_max_filesize; }
		if ($upload_max_filesize > $post_max_size) { $max_size = $post_max_size; }
		
		if (isset($max_size) AND $max_size > 0)
		{
			switch ($return)
			{
				case 'B': return $max_size;
				case 'KB': return $max_size / 1024;
				case 'MB': return $max_size / 1024 / 1024;
				case 'GB': return $max_size / 1024 / 1024 / 1024;
			}
		}
		else
		{
			return 0;
		}
	}
	
	// Return bytes
	public function return_bytes($size_str)
	{
		switch (substr ($size_str, -1))
		{
			case 'K': case 'k': return (int)$size_str * 1024;
			case 'M': case 'm': return (int)$size_str * 1048576;
			case 'G': case 'g': return (int)$size_str * 1073741824;
			case 'T': case 't': return (int)$size_str * 1099511627776;
			default: return $size_str;
		}
	}
}
