<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Dropping a JSON file db for SQLite.
 * First step is converting the Whiskers_db class
 * TODO: Use actual models for everything.
 */
class Whiskers_db {

    protected $last,
              $db,
              $table_name;

    public function __construct($table_name) 
    {
        $CI =& get_instance();
        $CI->load->database();

        // Dang, CI is near useless.
        $this->db = new PDO($CI->db->database);

        $this->table_name = $table_name[0];
    }

    public function __destruct() 
    {
        // $this->db->close();
    }

    public function set($key, $val) 
    {
        if (isset($val['time']))
        {
            $sql = $this->db->prepare("INSERT INTO {$this->table_name}(key, val, created) VALUES (?,?,?)");

            if ( ! $sql) 
            {
                var_dump($this->db->errorInfo());
                return FALSE;
            }

            $query = $sql->execute(array($key, json_encode($val), $val['time']));
        }
        else
        {
            $sql = $this->db->prepare("INSERT INTO {$this->table_name}(key, val) VALUES (?,?)");

            if ( ! $sql) 
            {
                var_dump($this->db->errorInfo());
                return FALSE;
            }
            
            $query = $sql->execute(array($key, json_encode($val)));
        }

        return TRUE;
    }

    public function get($key = NULL, $return_multiple_rows = false) 
    {
		// Get all rows
        if ($key == '*' OR $key == 'all' OR $key === NULL)
        {
            $sql = $this->db->prepare("SELECT * FROM {$this->table_name} ORDER BY created DESC");
            $query = $sql->execute();
            $array = $sql->fetchAll();

            $ret = array();

            foreach ($array as $a) 
            {
               $ret[$a['key']] = json_decode($a['val']);
            }

            return $ret;
        }
        else
        {	// get row(s) matching specific key
            $sql = $this->db->prepare("SELECT val FROM {$this->table_name} WHERE key = ?");

            if ( ! $sql) 
            {
                var_dump($this->db->errorInfo());
                return FALSE;
            }

            $query = $sql->execute(array($key));
            $array = $sql->fetchAll();

			// no matching rows
            if ( ! isset($array[0][0]))
            {
                return FALSE;
            }

			if ($return_multiple_rows) {
				$array2 = array();
				foreach ($array as $row) {
					$array2[] = json_decode($row[0]);
				}
				return $array2;
			} else {
				return json_decode($array[0][0]);
			}
        }

        return FALSE;
    }

    public function update($key, $value, $allow_duplicate_keys = false)
    {
        $existing = $this->get($key);

        if ( ! $existing)
        {
            $new_item = $this->set($key, $value);
            return $new_item;
        }

		// Already have this key in the table
		// If we're NOT allowed duplicates then merge the two sets of data
		// before we add it
		// (This code is obviously making MAJOR assumptions about how the
		// data's structured... maybe not appropriate this low down the stack)
		if (!$allow_duplicate_keys) {

			foreach ($existing->source_urls as $name => $url) 
			{
				$value['source_urls'][$name] = $url;
			}

			if (is_array($value))
			{
				$value = array_merge((array)$existing, $value);
			}
		}
		
        $status = $this->set($key, $value);

		file_put_contents(dirname(__FILE__) . '/db-update1.txt' , date("H:i:s")." set into table {$this->table_name} returned ".($status?"true":"false")."\r\n", FILE_APPEND);
        
        return $status;
    }

    public function rm($key) 
    {
        $sql = $this->db->prepare("DELETE FROM {$this->table_name} WHERE key = ?");
        $query = $sql->execute(array($key));

        return TRUE;
    }
  
    public function flush() 
    {
        fclose($this->fp);
        $this->fp = NULL;
        $this->fp = fopen(WHISKERS_ROOT . '/data/' . $db . '.json', 'a+');
    }
}