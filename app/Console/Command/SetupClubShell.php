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
        $club['country'] = $this->in('Country?');
        $club['timezone'] = $this->in('Timezone?');
        $club['visible'] = $this->in('Visible (0/1)?');
        $club['domain'] = $this->in('Domain?');
        $club['redirect_domain'] = $this->in('Redirect domain?');
        $club['parent_id'] = $this->in('Parent club id?');
        $club['club_category_id'] = $this->in('Club category id?');
        $club['facebook_page_id'] = $this->in('Facebook page id?');

        $this->Club->create();
        $this->Club->save(array('Club' => $club));
        $club['id'] = $this->Club->id;
        
        $this->out('Club created! Add a webmaster:');
        
        $adminEmail = $this->in('Email address?');
        
        $user = $this->User->findByEmail($adminEmail);
        $this->Group->create();
        $this->Group->save(array('Group' => array('name' => 'Executive', 'description' => 'Add/modify maps, events, organizers, resources.', 'access_level' => 80, 'club_id' => $this->Club->id)));
        $this->Group->create();
        $this->Group->save(array('Group' => array('name' => 'Webmaster', 'description' => 'Club webmasters can edit privileges', 'access_level' => 90, 'club_id' => $this->Club->id)));
        $groupId = $this->Group->id;
        if($user != null) {
            $this->Privilege->create();
            $this->Privilege->save(array('Privilege' => array('group_id' =>  $groupId, 'user_id' => $user['User']['id'])));
        }

        $this->out('All done. Head to '.$club['domain'].' and get the club up and running!');
    }
}
