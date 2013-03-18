<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Upgrade extends CI_Controller {

    public  $data; // holds data to be passed to view

    public function __construct()
    {
        parent::__construct();

        $this->load->helper('url');
        $this->load->helper('whiskers');
        $this->load->library('session');
        $this->load->library('whiskers_user');
        $this->load->library('whiskers_db', array('settings'), 'settings');
        $this->load->library('whiskers_db', array('posts'), 'posts');

        $this->data['base_url'] = base_url();
        $this->data['title'] = 'Whiskers Upgrade';
        $this->data['authenticated'] = false;
    }

    public function index()
    {
        // Base URL is not properly set
        $base_uri = str_replace('upgrade', '', $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);
        $base_uri = (substr($base_uri, 0, 7) === 'http://') ? $base_uri : 'http://'.$base_uri;
        $this->data['base_url'] = $base_uri;

        // messages to frontend
        $this->data['lines'] = array();

		$current_version = null;
		$obj = $this->settings->get('database');
		if ((null != $obj) && (false != $obj)) {
			var_dump($obj);
			$current_version = $obj->version;
		}
		if (null == $current_version) {
			$current_version = 1;
		}

		$this->data['lines'][] = "Latest database version is ". $this->config->item('latest_database_version');
		$this->data['lines'][] = "Current database version is $current_version";

		$this->data['lines'][] = "Creating new table \"service_posts\"";


		// First, we drop the service_posts table if it already exists
		// (For database v1, it shouldn't be there... but as we don't have 
		// a unique key in this table, this is a safety measure to prevent
		// unexpected duplicates if we accidentally attempt to re-upgrade
		// the database)
		// Future upgrades will probably want to NOT drop this table
		$db_status = $this->db->simple_query("DROP TABLE IF EXISTS service_posts");


        // Now create the service_posts table
        // (Note that key is not a primary key in this table 
        // as we'll have one matching row for each service to which a specific
        // post is sent so there will intentionally be duplicates in this column)
		$db_status = $this->db->simple_query("CREATE TABLE service_posts (
            key VARCHAR(100), 
            val BLOB, 
            modified DATETIME DEFAULT CURRENT_TIMESTAMP,
            created TIMESTAMP(20)
        )");
        
		if ($db_status) {
			$this->data['lines'][] = "New table created successfully";
		} else {
			$this->data['lines'][] = "Error creating new table";
		}


		$this->data['lines'][] = "Converting existing data to new format";

        $this->load->library('whiskers_db', array('service_posts'), 'service_posts');
        
        foreach ($this->posts->get() as $key => $val) {
			// With each entry (an authoritative post) from the posts table
			// we need to extract the text and create a service_posts entry
			// for every element in the authoritative post's service_urls array
			
			foreach ($val->source_urls as $service => $url) {
				$saved = $this->service_posts->update($key, array(	'type' => 'post',
																	'text' => $val->text,
																	'time' => $val->time,
																	'source_urls' => array(
																		$service => $url
																	)
																),
														true);
			}
		}


		// Set the database version to the correct value
		$db_status = $this->settings->update('database',array('version' => $this->config->item('latest_database_version')));
		if ($db_status) {
			$this->data['lines'][] = "Whiskers database is upgraded to version " . $this->config->item('latest_database_version');
		} else {
			$this->data['lines'][] = "Error upgrading database to version " . $this->config->item('latest_database_version');
		}


        // Cool - ready to let user login
		$this->data['action_url'] = site_url('login');        
        $this->_parse_template('upgrade');
    }


    public function convert()
    {
        // $this->load->database();

        // $old_db_file = BASEPATH.'../data/settings.JSON';
        // $lines = file($old_db_file);

        // // Loop through our array, show HTML source as HTML source; and line numbers too.
        // foreach ($lines as $line_num => $line) 
        // {
        //     $json = json_decode($line);

        //     if ($json->val !== NULL)
        //     {
        //         $this->db->insert('settings', array(
        //               'key' => $json->key
        //             , 'val' => json_encode($json->val)
        //             // , 'created' => $json->val->time
        //         )); 
        //     }
        // }
    }

    private function _parse_template($template)
    {
        $file = APPPATH.'views/'.$template.'.php';

        if ( ! is_file($file)) 
        {
            show_error('Could not load template file: "'.$file.'"');
            return false;
        }
     
        extract($this->data);

        ob_start();
        include $file;
        $out = ob_get_contents();
        ob_end_clean();

        $this->data['content'] = $out;

        $this->load->view('base', $this->data);
    }
}
