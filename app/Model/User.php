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
                'url' => 'CONCAT(\''.
                Sanitize::escape(Configure::read('Rails.profileURL'), 'default')
                .'\', User.id)'
                );
    }

    var $validate = array(
            'name' => array(
                'required' => array(
                    'rule' => array('notBlank'),
                    'required' => true,
                ),
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
        if ($minimumPrivilege == 0) {
            return true;
        }

        // This shouldn't really be necessary, but if there is a bug that causes a null privilege to be inserted, we won't give site access to everyone.
        if (empty($userId)) return false;

        $privilege = $this->Privilege->find('count', array('conditions' => array('Privilege.user_id' => $userId, 'Group.access_level >=' => $minimumPrivilege, 'Group.club_id' => Configure::read('Club.id'))));
        return ($privilege > 0);
    }

    function getLevel($userId) {
        $level = $this->Privilege->find('first', array('fields' => array('MAX(Group.access_level) as level'), 'conditions' => array('Privilege.user_id' => $userId, 'Group.club_id' => Configure::read('Club.id'))));
        if (!empty($level) && !empty($level[0])) {
            return $level[0]['level'];
        } else {
            return 0;
        }
    }

    function findByName($name, $limit = -1, $allowFake = true) {
        $conditions = array('User.name LIKE' => '%'.$name.'%');
        if ($allowFake === false) {
            $conditions['NOT'] = array('User.email' => null);
        }
        $options = array('recursive' => -1, 'conditions' => $conditions);
        if ($limit > -1) {
            $options['limit'] = $limit;
        }
        return $this->find('all', $options);
    }

    function getMostRecentEvent($user_id) {
        $data = $this->query("SELECT events.date AS date, clubs.acronym AS acronym, clubs.id AS id FROM clubs,events,courses,results WHERE clubs.id = events.club_id AND events.id = courses.event_id AND courses.id = results.course_id AND results.user_id = $user_id ORDER BY events.date DESC LIMIT 1");
        if (!empty($data) && !empty($data[0]) && !empty($data[0]['events'])) {
            return array(
              'date' => $data[0]['events']['date'],
              'club_id' => $data[0]['clubs']['id'],
              'club_acronym' => $data[0]['clubs']['acronym'],
            );
        } else {
            return null;
        }
    }

    function getCombined() {
        $data = $this->find('list', array('conditions' => array('OR' => array('User.name LIKE' => '% & %', 'User.name LIKE' => '% and %'))));
        return $data;
    }

    function hasPassword($user) {
        return !empty($user['User']['old_password']) ||
            !empty($user['User']['encrypted_password']);
    }

    // Returns array of potential duplicates
    function getDuplicates() {
        $data = $this->query('SELECT U1.id, U2.id, U1.name, U2.name FROM users as U1, users as U2 WHERE U1.name LIKE U2.name AND U1.id < U2.id ORDER BY U1.name');
        $cases = array();
        foreach ($data as &$case) {
            $user1 = $this->find('first', array('conditions' => array('User.id' => $case['U1']['id']), 'contain' => 'Club'));
            $user2 = $this->find('first', array('conditions' => array('User.id' => $case['U2']['id']), 'contain' => 'Club'));
            $user1['most_recent_event'] = $this->getMostRecentEvent($user1["User"]["id"]);
            $user2['most_recent_event'] = $this->getMostRecentEvent($user2["User"]["id"]);
            $date1 = $user1['most_recent_event']['date'];
            $date2 = $user2['most_recent_event']['date'];
            // Determine which is primary and which is duplicate
            /* Check for password */
            if($this->hasPassword($user1) && !$this->hasPassword($user2)) {
                // User2 is a fake account
                $primaryIndex = 1;
            }
            else if(!$this->hasPassword($user1) && $this->hasPassword($user2)) {
                // User1 is a fake account
                $primaryIndex = 2;
            }
            /* Recent login */
            else if(!empty($user1["User"]["last_sign_in_at"]) &&
                    !empty($user2["User"]["last_sign_in_at"])) {
                if($user1["User"]["last_sign_in_at"] > $user2["User"]["last_sign_in_at"]) {
                    // User1 logged in most recently
                    $primaryIndex = 1;
                }
                else {
                    // User2 logged in most recently
                    $primaryIndex = 2;
                }
            }
            else if($user1["User"]["last_sign_in_at"] != NULL) {
                // User2 never logged in
                $primaryIndex = 1;
            }
            else if($user2["User"]["last_sign_in_at"] != NULL) {
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

            $primary['has_password'] = $this->hasPassword($primary);
            $duplicate['has_password'] = $this->hasPassword($duplicate);
            $newcase["primary"] = $primary;
            $newcase["duplicate"] = $duplicate;
            array_push($cases, $newcase);
        }
        return $cases;
    }
}
