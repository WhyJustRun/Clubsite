<?php
class AddMissingAdminsShell extends Shell {
    public $uses = array('Club', 'User', 'Group', 'Privilege');

    public function main() {
        $clubs = $this->Club->find('all', array('recursive' => -1));
        foreach ($clubs as $club) {
          $clubId = $club['Club']['id'];
          $groups = $this->Group->findAllByClubId($clubId);
          if (count($groups) !== 0) {
            continue;
          }
          echo $club['Club']['name'];
          $this->Group->create();
          $this->Group->save(array('Group' => array('name' => 'Executive', 'description' => 'Add/modify maps, events, organizers, resources.', 'access_level' => 80, 'club_id' => $clubId)));
          $this->Group->create();
          $this->Group->save(array('Group' => array('name' => 'Webmaster', 'description' => 'Club webmasters can edit privileges', 'access_level' => 90, 'club_id' => $clubId)));
          $groupId = $this->Group->id;
          $this->Privilege->create();
          $this->Privilege->save(array('Privilege' => array('group_id' =>  $groupId, 'user_id' => 5)));

          $this->Privilege->create();
          $this->Privilege->save(array('Privilege' => array('group_id' =>  $groupId, 'user_id' => 911)));
        }
    }
}
