<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Update extends CI_Controller {
	
	public function __construct()
	{
		parent::__construct();
		if ($this->config->item('system_installed', 'aspia'))
		{
			$this->load->database();
			$this->load->model('aspia_model');
		}
		else
		{
			show_404();
		}
	}
	
	public function Index()
	{
		parse_str($_SERVER["QUERY_STRING"], $query);

		if (!empty($query['package']) AND is_string($query['package']) AND !empty($query['version']) AND is_string($query['version']))
		{
			$stats_data = array(
				'stats_query_ip'			=> $this->input->ip_address(),
				'stats_query_packet'		=> $query['package'],
				'stats_query_version'		=> $query['version']
			);
			
			$package_info = $this->aspia_model->get_package_info('name', $query['package']);
			if (is_array($package_info))
			{
				$stats_data['stats_local_packet_id'] = $package_info['package_id'];
				
				$update_info = $this->aspia_model->get_update_check($package_info['package_id'], $query['version']);
				
				$xml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><update/>');
				if (is_array($update_info))
				{
					$stats_data['stats_proposed_update_id'] = $update_info['update_id'];
					
					$xml->addChild('version', $update_info['update_target_version']);
					$xml->addChild('description', $update_info['update_description']);
					$xml->addChild('url', $this->config->item('storage_url', 'aspia').$update_info['installer_file_name_real']);
				}
				
				$stats_query = $this->aspia_model->add_statistics($stats_data);
				
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
