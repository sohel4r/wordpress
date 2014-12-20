<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Install extends Admin_controller
{
	function index()
	{
		$this->data['include'] = 'admin/install';
		$this->load->view( $this->template, $this->data);
	}

	function preview()
	{
		$this->data['include'] = 'preview';
		$this->load->view( $this->template, $this->data);
	}
}

/* End of file customers.php */
/* Location: ./application/controllers/admin/categories.php */