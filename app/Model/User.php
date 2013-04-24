<?php
class User extends AppModel {
    var $name = 'User';
    var $displayField = 'name';
    protected $clubSpecific = false;
    var $actsAs = array('Containable', 'Merge');

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
                    'required' => true,
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
                'unique' => array(
                    'message' => 'That email address is being used by another account.',
                    'rule' => 'isUnique',
                    'required' => true 
                ),
                'email' => array(
                    'rule' => array('email'),
                    'message' => 'Must be a valid e-mail address',
                    'required' => true,
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
            'Result' => array(
                'className' => 'Result',
                'foreignKey' => 'user_id',
                ),
            'Registrant' => array(
                'className' => 'Result',
                'foreignKey' => 'registrant_id',
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

    function is_duplicate($data) {
        $q = $this->findAllByName($data['User']['name']);
        if(count($q) > 0)
            return true;
        else 
            return false;
    }

    /**
     * Checks if a user is authorized to access a page based on their privileges
     */
    function isAuthorized($userId, $minimumPrivilege) {
        if($minimumPrivilege == 0) {
            return true;
        }

        // This shouldn't really be necessary, but if there is a bug that causes a null privilege to be inserted, we won't give site access to everyone.
        if(empty($userId)) return false;

        $privilege = $this->Privilege->find('count', array('conditions' => array('Privilege.user_id' => $userId, 'Group.access_level >=' => $minimumPrivilege, 'Group.club_id' => Configure::read('Club.id'))));
        if($privilege > 0) {
            return true;
        } else return false;
    }

    function getLevel($userId) {
        $level = $this->Privilege->find('first', array('fields' => array('MAX(Group.access_level) as level'), 'conditions' => array('Privilege.user_id' => $userId, 'Group.club_id' => Configure::read('Club.id'))));
        if(!empty($level) && !empty($level[0])) {
            return $level[0]['level'];
        }
        else {
            return 0;
        }
    }

    function findByName($name, $limit = -1) {
        $options = array('recursive' => -1, 'conditions' => array('User.name LIKE' => '%'.$name.'%'));
        if ($limit > -1) {
            $options['limit'] = $limit;
        }
        return $this->find('all', $options);
    }

    /**
     * Finds any temporary user accounts (those without a username, e.g. those that have been created 
     * in the results section)
     */
    function findTemporaryByName($name) {
        $conditions = array('User.name LIKE' => '%'.$name.'%' , 'User.username' => '');
        return $this->find('first', array('recursive' => -1, 'conditions' => $conditions));
    }


    function getMostRecentEvent($user_id) {
        $data = $this->query("SELECT MAX(events.date) AS date FROM events,courses,results WHERE events.id = courses.event_id AND courses.id = results.course_id AND results.user_id = $user_id LIMIT 1");
        if(!empty($data) && !empty($data[0]) && !empty($data[0][0])) {
            $date = $data[0][0]["date"];
        }
        else {
            $date = NULL;
        }
        return $date;
    }

    function getCombined() {
        $data = $this->find('list', array('conditions' => array('OR' => array('User.name LIKE' => '% & %', 'User.name LIKE' => '% and %'))));
        return $data;
    }

    // Returns array of potential duplicates
    function getDuplicates() {
        $data = $this->query('SELECT U1.id, U2.id, U1.name, U2.name FROM users as U1, users as U2 WHERE U1.name LIKE U2.name AND U1.id < U2.id ORDER BY U1.name');
        $cases = array();
        foreach ($data as &$case) {
            $user1 = $this->findById($case["U1"]["id"]);
            $user2 = $this->findById($case["U2"]["id"]);
            $date1 = $this->getMostRecentEvent($user1["User"]["id"]);
            $date2 = $this->getMostRecentEvent($user2["User"]["id"]);
            $user1["most_recent"] = $date1;
            $user2["most_recent"] = $date2;
            // Determine which is primary and which is duplicate

            /* Check for password */
            if($user1["User"]["password"] != NULL && $user2["User"]["password"] == NULL) {
                // User2 is a fake account
                $primaryIndex = 1;
            }
            else if($user1["User"]["password"] == NULL && $user2["User"]["password"] != NULL) {
                // User1 is a fake account
                $primaryIndex = 2;
            }
            /* Recent login */
            else if($user1["User"]["last_login"] != NULL && $user2["User"]["last_login"] != NULL) {
                if($user1["User"]["last_login"] > $user2["User"]["last_login"]) {
                    // User1 logged in most recently
                    $primaryIndex = 1; 
                }
                else {
                    // User2 logged in most recently
                    $primaryIndex = 2;
                }
            }
            else if($user1["User"]["last_login"] != NULL) {
                // User2 never logged in
                $primaryIndex = 1;
            }
            else if($user2["User"]["last_login"] != NULL) {
                // User1 never logged in
                $primaryIndex = 2;
            }
            /* Recent events */
            else if($date1 != NULL && $date2 != NULL) {
                if($date1 > $date2)
                    $primaryIndex = 1;
                else
                    $primaryIndex = 2;
            }
            else if($date1 != NULL) {
                $primaryIndex = 1;
            }
            else if($date2 != NULL) {
                $primaryIndex = 2;
            }

            else {
                // Run out of options... pick the first one
                $primaryIndex = 2;
            }

            if($primaryIndex == 1) {
                $primary = &$user1;
                $duplicate = &$user2;
            }
            else {
                $primary = &$user2;
                $duplicate = &$user1;
            }
            $newcase["primary"] = $primary;
            $newcase["duplicate"] = $duplicate;
            array_push($cases, $newcase);
        }
        return $cases;
    }
}
?>
