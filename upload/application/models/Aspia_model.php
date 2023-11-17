<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Aspia_model extends CI_Model {

	//
	// Updates
	//
	function get_update_list()
	{
		$this->db->select('updates_data.*, packages_data.package_name, packages_data.package_description, installers_data.installer_name, installers_data.installer_file_name_real');
		$this->db->from('updates_data');
		$this->db->join('packages_data', 'updates_data.package_id=packages_data.package_id');
		$this->db->join('installers_data', 'updates_data.installer_id=installers_data.installer_id');
		$this->db->order_by('package_name');
		$this->db->order_by('update_source_version');
		$this->db->order_by('update_target_version');
		$query = $this->db->get()->result_array();
		
		if (count($query) > 0)
		{
			foreach($query as $row)
			{
				$result[$row['update_id']] = $row;
			}
			return $result;
		}
		else
		{
			return FALSE;
		}
	}
	
	function get_update_info($query=NULL)
	{
		if (!is_null($query) AND is_numeric($query))
		{
			$this->db->select('*');
			$this->db->where('update_id', $query);
			$this->db->from('updates_data');
			$query = $this->db->get()->result_array();
			
			if (count($query) == 1)
			{
				return $query[0];
			}
			else
			{
				return FALSE;
			}
		}
		else
		{
			return FALSE;
		}
	}
	
	function get_update_per_package($id=NULL)
	{
		$this->db->select('*');
		$this->db->from('updates_data');
		$this->db->where('package_id', $id);
		$query = $this->db->get()->result_array();
		
		if (count($query) > 0)
		{
			foreach($query as $row)
			{
				$result[$row['update_id']] = $row;
			}
			return $result;
		}
		else
		{
			return FALSE;
		}
	}
	
	function get_update_per_installer($id=NULL)
	{
		$this->db->select('*');
		$this->db->from('updates_data');
		$this->db->where('installer_id', $id);
		$query = $this->db->get()->result_array();
		
		if (count($query) > 0)
		{
			foreach($query as $row)
			{
				$result[$row['update_id']] = $row;
			}
			return $result;
		}
		else
		{
			return FALSE;
		}
	}
	
	function get_update_check($packet_id=NULL, $source_version=NULL)
	{
		if (!is_null($packet_id) AND is_numeric($packet_id) AND !is_null($source_version) AND is_string($source_version))
		{
			$this->db->select('updates_data.*, installers_data.installer_file_name_real');
			$this->db->join('installers_data', 'updates_data.installer_id=installers_data.installer_id');
			$this->db->where('package_id', $packet_id);
			$this->db->where('update_source_version', $source_version);
			$this->db->from('updates_data');
			$query = $this->db->get()->result_array();
			
			if (count($query) == 1)
			{
				return $query[0];
			}
			else
			{
				return FALSE;
			}
		}
		else
		{
			return FALSE;
		}
	}
	
	function add_update($data=NULL)
	{
		if (is_array($data) AND !empty($data))
		{
			$this->db->insert('updates_data', $data);
			$last_id = $this->db->insert_id();
			return $last_id;
		}
		else
		{
			return FALSE;
		}
	}
	
	function edit_update($id=NULL, $data=NULL)
	{
		if (!is_null($id) AND !is_null($data) AND is_array($data))
		{
			$this->db->where('update_id', $id);
			$this->db->update('updates_data', $data);
			return TRUE;
		}
		return FALSE;
	}
	
	function del_update($id=NULL)
	{
		if (!empty($id))
		{
			$this->db->where('update_id', $id);
			$this->db->delete('updates_data');
			return TRUE;
		}
		else
		{
			return FALSE;
		}
		return FALSE;
	}
	
	//
	// Packages
	//
	function get_package_list()
	{
		$this->db->select('*');
		$this->db->order_by('package_name');
		$this->db->from('packages_data');
		$query = $this->db->get()->result_array();
		
		if (count($query) > 0)
		{
			return $query;
		}
	}
	
	function get_package_info($query_type=NULL, $query=NULL)
	{
		if (!is_null($query_type) AND !is_null($query))
		{
			$this->db->select('*');
			if ($query_type == 'name' AND is_string($query))
			{
				$this->db->where('package_name', $query);
			}
			elseif ($query_type == 'id' AND is_numeric($query))
			{
				$this->db->where('package_id', $query);
			}
			else
			{
				return FALSE;
			}
			$this->db->from('packages_data');
			$query = $this->db->get()->result_array();
			
			if (count($query) == 1)
			{
				return $query[0];
			}
			else
			{
				return FALSE;
			}
		}
		else
		{
			return FALSE;
		}
	}
	
	function add_package($data=NULL)
	{
		if (is_array($data) AND !empty($data))
		{
			$this->db->insert('packages_data', $data);
			$last_id = $this->db->insert_id();
			return $last_id;
		}
		else
		{
			return FALSE;
		}
	}
	
	function edit_package($id=NULL, $data=NULL)
	{
		if (!is_null($id) AND !is_null($data) AND is_array($data))
		{
			$this->db->where('package_id', $id);
			$this->db->update('packages_data', $data);
			return TRUE;
		}
		return FALSE;
	}
	
	function del_package($id=NULL)
	{
		if (!empty($id))
		{
			$this->db->where('package_id', $id);
			$this->db->delete('packages_data');
			return TRUE;
		}
		else
		{
			return FALSE;
		}
		return FALSE;
	}
	
	//
	// Installers
	//
	function get_installer_list()
	{
		$this->db->select('*');
		$this->db->order_by('installer_name');
		$this->db->from('installers_data');
		$query = $this->db->get()->result_array();
		
		if (count($query) > 0)
		{
			return $query;
		}
	}
	
	function get_installer_info($query_type=NULL, $query=NULL)
	{
		if (!is_null($query_type) AND !is_null($query))
		{
			$this->db->select('*');
			if ($query_type == 'name' AND is_string($query))
			{
				$this->db->where('installer_name', $query);
			}
			elseif ($query_type == 'id' AND is_numeric($query))
			{
				$this->db->where('installer_id', $query);
			}
			else
			{
				return FALSE;
			}
			$this->db->from('installers_data');
			$query = $this->db->get()->result_array();
			
			if (count($query) == 1)
			{
				return $query[0];
			}
			else
			{
				return FALSE;
			}
		}
		else
		{
			return FALSE;
		}
	}
	
	function add_installer($data=NULL)
	{
		if (is_array($data) AND !empty($data))
		{
			$this->db->insert('installers_data', $data);
			$last_id = $this->db->insert_id();
			return $last_id;
		}
		else
		{
			return FALSE;
		}
	}
	
	function edit_installer($id=NULL, $data=NULL)
	{
		if (!is_null($id) AND !is_null($data) AND is_array($data))
		{
			$this->db->where('installer_id', $id);
			$this->db->update('installers_data', $data);
			return TRUE;
		}
		return FALSE;
	}
	
	function del_installer($id=NULL)
	{
		if (!empty($id))
		{
			$this->db->where('installer_id', $id);
			$this->db->delete('installers_data');
			return TRUE;
		}
		else
		{
			return FALSE;
		}
		return FALSE;
	}
	
	//
	// Statistics
	//
	function get_statistics($params=NULL)
	{
		if (isset($params['limit']) AND is_numeric($params['limit']) AND isset($params['start']) AND is_numeric($params['start']))
		{
			$this->db->select('*');
			$this->db->order_by('stats_timestamp', 'DESC');
			$this->db->limit($params['limit'], $params['start']);
			$this->db->from('statistics_data');
			$query = $this->db->get()->result_array();
			
			if (count($query) > 0)
			{
				return $query;
			}
		}
	}
	
	function get_statistics_count()
	{
		$result = $this->db->count_all('statistics_data');
		return $result;
	}
	
	function add_statistics($data=NULL)
	{
		if (!is_null($data) AND isset($data['stats_query_ip']) AND isset($data['stats_query_packet']) AND isset($data['stats_query_version']))
		{
			$this->db->insert('statistics_data', $data);
			$last_id = $this->db->insert_id();
			return $last_id;
		}
		else
		{
			return FALSE;
		}
	}
}
