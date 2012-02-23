<?php
class User extends AppModel {
	var $name = 'User';
	var $displayField = 'name';
	protected $clubSpecific = false;
	var $actsAs = array('Containable');

    // is_member is a virtual field, however its definition must be made at runtime, since we have the dynamical
    // variable $year. Therefore modify the constructor.
    // See section (3.7.10.4): http://book.cakephp.org/view/1608/Virtual-fields#!/view/1608/Virtual-fields
    public function __construct($id=false,$table=null,$ds=null){
        parent::__construct($id,$table,$ds);
        $date = new DateTime();
        $year = $date->format('Y');
        $this->virtualFields = array(
            'is_member' => "SELECT COUNT(id) from memberships where user_id = User.id AND year = $year AND club_id = ".Configure::read('Club.id'),
            'url' => 'CONCAT("/users/view/", User.id)'
        );
    }

	var $validate = array(
	  'name' => array(
			'required' => array(
				'rule' => array('notEmpty'),
				//'message' => 'Characters and numbers only',
				//'allowEmpty' => true,
				'required' => true,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
	  'username' => array(
		 'unique' => array(
			'rule' => array('isUnique'),
			'message' => 'Username already in use',
				'required' => true
		 )
		),
		'email' => array(
			'email' => array(
				'rule' => array('email'),
				'message' => 'Must be a valid e-mail address',
				//'allowEmpty' => true,
				'required' => true,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		// This rule vill probably never be broken since we are checking the hashed password, not the raw one.
		'password' => array(
			'alphanumeric' => array(
				'rule' => array('alphanumeric'),
				'message' => 'Characters and numbers only',
				//'allowEmpty' => true,
				'required' => true,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'year_of_birth' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				'message' => 'Must be a valid year',
				'allowEmpty' => true,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'si_number' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				'message' => 'Must consist of numbers only',
				'allowEmpty' => true
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
	);
	//The Associations below have been created with all possible keys, those that are not needed can be removed

	var $hasMany = array(
		'Membership' => array(
			'className' => 'Membership',
			'foreignKey' => 'user_id',
		),
		'Organizer' => array(
			'className' => 'Organizer',
			'foreignKey' => 'user_id',
		),
		/*'Page' => array(
			'className' => 'Page',
			'foreignKey' => 'user_id',
		),*/
		'Result' => array(
			'className' => 'Result',
			'foreignKey' => 'user_id',
		)
	);
	
	var $hasOne = array('Privilege');
	
	var $belongsTo = array(
		'Club' => array(
			'className' => 'Club',
		)
	);

	/**
	* @deprecated Use AuthComponent password
	*/
	function hashPasswords($data) {
	   
		if (isset($data['User']['password'])) {
			$data['User']['password'] = $this->hashPassword($data['User']['password']);
			return $data;
		}
		
		return $data;
	}

	/**
	* @deprecated Use AuthComponent password
	*/
	function hashPassword($password) {
		return hash('sha512', Configure::read("Security.salt").$password);
	}
	
   // Takes all results of user with ID 'fromId' and assigns them to 
   // the user with id 'toId'
   function merge_accounts($fromId, $toId) {
	  // Do not overwrite a record if already exists for' toId'. I.e. if 
	  // 'toId' ran event X, then do not copy and results for event X.
	  
	  // Merge results

	  // Merge membership

	  //
   }

   function is_duplicate($data) {
	  $q = $this->findAllByName($data['User']['name']);
	  if(count($q) > 0)
		 return true;
	  else 
		 return false;
	  //$q = $this->find("all", array("conditions" => array("username == " => $data['User']['username'])));
   }
	
	/**
	* Checks if a user is authorized to access a page based on their privileges
	*/
	function isAuthorized($userId, $minimumPrivilege) {
		$privilege = $this->Privilege->find('count', array('conditions' => array('Privilege.user_id' => $userId, 'Group.access_level >=' => $minimumPrivilege, 'Group.club_id' => Configure::read('Club.id'))));
		if($privilege > 0) {
			return true;
		} else return false;
	}
	
	function findByName($name) {
		return $this->find('all', array('recursive' => -1, 'conditions' => array('User.name LIKE' => '%'.$name.'%')));
	}

    /**
    * Finds any temporary user accounts (those without a username, e.g. those that have been created 
    * in the results section)
    */
    function findTemporaryByName($name) {
        $conditions = array('User.name LIKE' => '%'.$name.'%' , 'User.username' => '');
        return $this->find('first', array('recursive' => -1, 'conditions' => $conditions));
    }
}
?>
