<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Installer extends CI_Controller {
	
	public function __construct()
	{
		parent::__construct();
		if ($this->config->item('system_installed', 'aspia') === TRUE)
		{
			show_404();
		}
		
		$this->content = FALSE;
	}
	
	private function _RenderPage()
	{
		$full_page_data = array(
			'title'			=> $this->config->item('site_title', 'aspia'),
			'content'		=> $this->content,
			'step'			=> $this->step
		);
		
		$this->load->view('installer', $full_page_data);
	}
	
	public function index()
	{
		$this->step = 'welcome';
		$this->_RenderPage();
	}
	
	public function step($step=NULL)
	{
		$page_data = FALSE;
		// List of files for checking
		
		$check_files = array(
			'config_main'		=> './application/config/config.php',
			'config_aspia'		=> './application/config/aspia.php',
			'config_database'	=> './application/config/database.php',
		);
			
		if (is_null($step))
		{
			redirect('installer/step/1');
		}
		if ($step == '1')
		{
			$status_ok = TRUE;
			
			foreach($check_files as $key => $path)
			{
				if (is_writable($path))
				{
					$check_result[] = array(
						'name'			=> $path,
						'write'			=> TRUE
					);
				}
				else
				{
					$check_result[] = array(
						'name'			=> $path,
						'write'			=> FALSE
					);
					$status_ok = FALSE;
				}
			}
			
			$page_data = array(
				'check_result'		=> $check_result,
				'status_ok'			=> $status_ok
			);
		}
		elseif ($step == '2')
		{
			$status_ok = NULL;
			$status_db_ok = NULL;
			
			// WebUI access
			$default_value['admin_login']			= $this->config->item('login', 'auth');
			$default_value['admin_password']		= $this->config->item('password', 'auth');
			
			// System settings
			$default_value['system_domain']			= $this->input->server('HTTP_HOST', TRUE);
			
			// Database settings
			$default_value['database_hostname']		= 'localhost';
			$default_value['database_username']		= 'root';
			$default_value['database_password']		= '';
			$default_value['database_name']			= 'aspia';
			
			// Available languages
			$this->load->helper('directory');
			
			if ($this->input->post())
			{
				$post_data = $this->input->post();
				
				foreach($default_value as $key => $value)
				{
					if (isset($post_data[$key]) AND $post_data[$key] != '' AND is_string($post_data[$key]))
					{
						$input_settings[$key] = $post_data[$key];
					}
					else
					{
						break;
					}
				}
				
				if (count($default_value) === count($input_settings))
				{
					// Config main replace:
					$config_main_array = file($check_files['config_main']);
					$config_main_replace = array(
						'base_url'			=> 'https://'.$input_settings['system_domain'].'/',
						'cookie_domain'		=> $input_settings['system_domain'],
					);
					
					$config_main_result = $this->config_strings_replace($config_main_array, $config_main_replace);
					$config_main_put = file_put_contents($check_files['config_main'], $config_main_result);
					
					// Config aspia replace:
					$config_aspia_array = file($check_files['config_aspia']);
					$config_aspia_replace = array(
						'login'				=> $input_settings['admin_login'],
						'password'			=> $input_settings['admin_password']
					);
					
					$config_aspia_result = $this->config_strings_replace($config_aspia_array, $config_aspia_replace);
					$config_aspia_put = file_put_contents($check_files['config_aspia'], $config_aspia_result);
					
					// Config database replace:
					$config_database_array = file($check_files['config_database']);
					$config_database_replace = array(
						'hostname'			=> $input_settings['database_hostname'],
						'username'			=> $input_settings['database_username'],
						'password'			=> $input_settings['database_password'],
						'database'			=> $input_settings['database_name']
					);
					
					$connect_mysql = @mysqli_connect($input_settings['database_hostname'], $input_settings['database_username'], $input_settings['database_password'], $input_settings['database_name']);
					
					if ($connect_mysql === FALSE)
					{
						$status_db_ok = FALSE;
						$status_ok = FALSE;
					}
					else
					{
						$config_database_result = $this->config_strings_replace($config_database_array, $config_database_replace, '=>');
						$config_database_put = file_put_contents($check_files['config_database'], $config_database_result);
						
						// Fill database:
						$db_dump = file_get_contents('./storage/installer/mysql_scheme.sql');
						mysqli_multi_query($connect_mysql, $db_dump);
						mysqli_close($connect_mysql);
						
						$status_db_ok = TRUE;
						$status_ok = TRUE;
					}
				}
				else
				{
					$status_ok = FALSE;
				}
			}
			else
			{
				$post_data = $default_value;
			}
			
			$page_data = array(
				'post_data'			=> $post_data,
				'status_ok'			=> $status_ok,
				'status_db_ok'		=> $status_db_ok
			);
		}
		elseif ($step == '3')
		{
			$config_aspia_array = file($check_files['config_aspia']);
			$config_aspia_replace = array(
				'system_installed'	=> TRUE
			);
			$config_aspia_result = $this->config_strings_replace($config_aspia_array, $config_aspia_replace);
			$config_aspia_put = file_put_contents($check_files['config_aspia'], $config_aspia_result);
		}
		
		$this->content = $page_data;
		$this->step = $step;
		$this->_RenderPage();
	}
	
	private function config_strings_replace($strings_array, $replace_array, $delimiter = '=')
	{
		$result_text = '';
		
		foreach($strings_array as $string)
		{
			if (mb_stripos(trim($string), '/*', 0) === 0 OR mb_stripos(trim($string), '*', 0) === 0 OR mb_stripos(trim($string), '|', 0) === 0)
			{
				$result_text .= $string;
			}
			else
			{
				$replace_data = FALSE;
				
				foreach($replace_array as $key => $value)
				{
					if (mb_strripos($string, $key, 0))
					{
						$replace_data = $value;
						break;
					}
				}
				if ($replace_data != FALSE)
				{
					$string_array = explode($delimiter, $string);
					if ($delimiter == '=')
					{
						$end_string = ';';
					}
					else
					{
						$end_string = ',';
					}
					
					if (is_array($string_array) AND isset($string_array[0]) AND isset($string_array[1]))
					{
						if ($replace_data === TRUE OR $replace_data === FALSE)
						{
							$result_text .= $string_array[0].$delimiter.' '.($replace_data === TRUE ? "TRUE" : "FALSE");
						}
						else
						{
							$result_text .= $string_array[0].$delimiter.' \''.$replace_data.'\'';
						}
						$result_text .= $end_string."\r\n";
					}
				}
				else
				{
					$result_text .= $string;
				}
			}
		}
		return $result_text;
	}
}
