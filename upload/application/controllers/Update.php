<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Update extends CI_Controller {
	
	public function __construct()
	{
		parent::__construct();
		$this->load->model('aspia_model');
	}
	
	public function Index()
	{
		parse_str($_SERVER["QUERY_STRING"], $query);

		if (!empty($query['package']) AND is_string($query['package']) AND !empty($query['version']) AND is_string($query['version']))
		{
			$package_info = $this->aspia_model->get_package_info('name', $query['package']);
			if (is_array($package_info))
			{
				$update_info = $this->aspia_model->get_update_check($package_info['package_id'], $query['version']);
				
				$xml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><update/>');
				if (is_array($update_info))
				{
					$xml->addChild('version', $update_info['update_target_version']);
					$xml->addChild('description', $update_info['update_description']);
					$xml->addChild('url', $update_info['installer_url']);
				}
				header('Content-Type: application/xml');
				print($xml->asXML());
			}
			else
			{
				$this->_error('Requested package not found!');
			}
		}
		else
		{
			$this->_error('Invalid query.');
		}
	}
	
	private function _error($text)
	{
		echo $text;
	}
}
