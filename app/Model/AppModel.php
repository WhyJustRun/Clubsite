<?php
/**
 * Application model for Cake.
 *
 * This file is application-wide model file. You can put all
 * application-wide model-related methods here.
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2011, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2011, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       Cake.Model
 * @subpackage    cake.cake.libs.model
 * @since         CakePHP(tm) v 0.2.9
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

App::uses('Model', 'Model');

/**
 * Application model for Cake.
 *
 * This is a placeholder class.
 * Create the same file in app/app_model.php
 * Add your application-wide methods to the class, your models will inherit them.
 *
 * @package       Cake.Model
 * @subpackage    cake.cake.libs.model
 */
class AppModel extends Model {
    protected $clubSpecific = true;
    
    /**
    * Select data only for this club
    */
    function beforeFind($queryData) {
        if($this->clubSpecific) {
            $key = $this->name.".club_id";
            if (!empty($queryData['conditions'])) {
                foreach($queryData['conditions'] as $condition => $value) {
                    // matching conditions
                    $exploded = explode(' ', $condition, 2);
                    if ($exploded[0] === $key) {
                        return $queryData;
                    }
                }
            }
            $queryData['conditions'][$key] = Configure::read("Club.id");
        }
        return $queryData;
    }
    
	/**
	* Convert date fields to/from UTC seamlessly!
	* Extended from: http://stackoverflow.com/questions/3775038/converting-dates-between-timezones-in-appmodel-afterfind-cakephp
	*/
	function afterFind($results, $primary){
		// Only bother converting if the local timezone is set.
		$from = new DateTimeZone("UTC");
		$to = Configure::read("Club.timezone");
		if($primary && $to)
			$this->replaceDateRecursive($results, $from, $to);
		return $results;
	}

	function beforeSave() {
		$from = Configure::read("Club.timezone");
		$to = new DateTimeZone("UTC");
		if($from)
			$this->replaceDateRecursive($this->data, $from, $to);
        if($this->clubSpecific && empty($this->data[$this->name]['club_id'])) {
            $this->data[$this->name]['club_id'] = Configure::read("Club.id");
        }
		return true;
	}

	function replaceDateRecursive(&$results, $from, $to){
		foreach($results as $key => &$value){
			if(is_array($value)){
				$this->replaceDateRecursive($value, $from, $to);
			}
			else if($key === 'date'){
				// Set unconverted date, useful for integrations that want UTC.
				$results['utc_date'] = $value;
				$value = $this->convertDate($value, $from, $to);
			} else if($key === 'finish_date' && !empty($value)){
				$results['utc_finish_date'] = $value;
				$value = $this->convertDate($value, $from, $to);
			}
		}
	}

	function convertDate($date_string, $from, $to){
		$date = new DateTime($date_string, $from);
		$date->setTimezone($to);
		return $date->format('Y-m-d H:i:s');
	}
}