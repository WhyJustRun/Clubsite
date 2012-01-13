<?php
class SetupClubShell extends Shell {
    public $uses = array('Club', 'User', 'Group', 'Privilege');
    
    public function main() {
        $this->out('Enter club information:');
        $club['name'] = $this->in('Name?');
        $club['acronym'] = $this->in('Acronym?');
        $club['location'] = $this->in('Location?');
        $club['description'] = $this->in('Description?');
        $club['url'] = $this->in('URL?');
        $club['lat'] = $this->in('Latitude?');
        $club['lng'] = $this->in('Longitude?');
        $club['timezone'] = $this->in('Timezone?');
            
        $this->Club->save(array('Club' => $club));
        $club['id'] = $this->Club->id;
        
        $this->out('Club created! Add an administrator.');
        $adminUsername = $this->in('Username?');
        
        $user = $this->User->findByUsername($adminUsername);
        $this->Group->save(array('Group' => array('name' => 'Administrator', 'access_level' => 100, 'club_id' => $this->Club->id)));
        $groupId = $this->Group->id;
        if($user != null) {
            $this->Privilege->save(array('Privilege' => array('group_id' =>  $groupId, 'user_id' => $user['User']['id'])));
        }
        
        $this->out('All done. Head to '.$club['url'].' and get the club up and running!');
    }
}
?>