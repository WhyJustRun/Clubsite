<?php
class Token extends AppModel 
{
    var $name = 'Token';
    var $actsAs = array('Containable');
	protected $clubSpecific = false;
    /**
     * Create a new ticket by providing the data to be stored in the ticket. 
     */
	function generate($data = null)
	{
		$data = array(
            'token' => substr(md5(uniqid(rand(), 1)), 0, 10),
            'data'  => serialize($data),
            );
        $this->create();
        if ($this->save($data)) {
            return $data['token'];
        } else {
        	throw new BadRequestException("Failed generating token");
        }
	}
	
	/**
	 * Return the value stored or false if the ticket can not be found.
	 */
	function get($token, $delete = false)
	{
	    $this->garbage();
		$token = $this->findByToken($token);
		
		if ($token) {
			if($delete)
		    	$this->delete($token['Token']['id']);

            return unserialize($token['Token']['data']);
        }
        
        return false;
	}
	
	/**
	 * Check for token existence
	 */
	function existsByToken($token)
	{
	    $this->garbage();
		$token = $this->findByToken($token);
		if ($token) {
			return true;
        }
		return false;
	}

	/**
	 * Remove old tickets
	 */
	function garbage()
	{		
		return $this->deleteAll(array('created < INTERVAL -1 DAY + NOW()'));
	}
}
