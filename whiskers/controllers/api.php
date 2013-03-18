<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Api extends CI_Controller {

    public  $data; // holds data to be passed to view

    public function __construct()
    {
        parent::__construct();

        // Dependencies
        $this->load->helper('url');
        $this->load->helper('whiskers');
        $this->load->library('session');
        $this->load->library('whiskers_user');
        $this->load->library('whiskers_db', array('settings'), 'settings');
        $this->load->library('whiskers_db', array('posts'), 'posts');
        $this->load->library('whiskers_db', array('service_posts'), 'service_posts');
        $this->load->driver('whiskers_post');
    }

    // return JSON format
    public function _respond($status = 200)
    {
        $this->data['status'] = $status;
        $this->load->view('json', $this->data);
    }

    // Ugh, RESTful?!
    public function _remap($method, $params = array())
    {
        $method = $_SERVER['REQUEST_METHOD'].'_'.$method;

        if (method_exists($this, $method))
        {
            return call_user_func_array(array($this, $method), $params);
        }

        show_404();
    }

    /**
     * Saves a whisker post.
     * 
     * Calls from the client come here.
     * One call for each service to which the post must be sent
     * 
     * timestamp & parent_text params are common across all service-specific
     * instances of a given post
     * 
     * @param   string  $driver     	name of the driver
     * @param   string  $text       	text of the post
     * @param   string  $timestamp  	seconds since Unix Epoch
     * @param   string  $parent_text	text of the parent post
     * 
     * @return  object
     */
    function post_post()
    {
        $this->data['driver'] = $this->input->post('driver');
        $this->data['text'] = $this->input->post('text');
        $this->data['timestamp'] = $this->input->post('timestamp');
        $this->data['parent_text'] = $this->input->post('parent_text');

        $driver = $this->input->post('driver');

		// This calls save_post() in the appropriate service's driver to post to the service
        if ( ! $data = $this->whiskers_post->$driver->save_post($this->data['text']))
        {
            $this->data['message'] = "Failed to post to $driver.";
            $this->_respond(500);
        } else {
			// Post to service was successful
			// So now save post to the database

			// This key is common to all service-specific instances of this post
			$key = sha1($this->input->post('timestamp').':'.$this->input->post('parent_text'));
			
			// Add the post (or update it) to the posts table.
			// This is the common entry that links the individual service-specific posts
			// as such, we store the parent_text in here
			$saved = $this->posts->update($key, array(
				'type' => 'post',
				'text' => $this->input->post('parent_text'),
				'time' => time(),		// Don't trust the client data - use server-side time rather than client's timestamp
				'source_urls' => array(
					$driver => $data
				)
			));
			
			// Add put the post into the service posts table
			// Note there may be multiple posts in here with the same key
			// The key ties these service specific postings back to the parent
			// post in the main posts table
			$saved = $this->service_posts->update($key, array(
				'type' => 'post',
				'text' => $this->input->post('text'),
				'time' => time(),		// Don't trust the client data - use server-side time rather than client's timestamp
				'source_urls' => array(
					$driver => $data
				)
			), true);

			
			
			if (!$saved) {
				$this->data['message'] = "Failed to save post to $driver in database.";
				$this->_respond(500);
			}
		}

        $this->_respond(200);
    }

    function get_post($id)
    {
        echo "string get";
    }
}
