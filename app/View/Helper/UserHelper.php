<?php
/**
 * WhyJustRun Session helper wrapper. Makes our view code cleaner/more readable.
 */
class UserHelper extends AppHelper {
    public $helpers = array("Session");

    public function isSignedIn() {
        return $this->Session->check('Auth.User.id');
    }

    public function id() {
        return $this->Session->read('Auth.User.id');
    }

    public function profileURL($userId = null) {
        if (!$userId) {
            $userId = $this->Session->read('Auth.User.id');
        }

        if (empty($userId)) {
            return null;
        } else {
            return Configure::read('Rails.profileURL') . $userId;
        }
    }

    public function canEditClub() {
        return $this->hasPrivilege('Privilege.Club.edit');
    }

    public function canEditPages() {
        return $this->hasPrivilege('Privilege.Page.edit');
    }

    public function canDeletePages() {
        return $this->hasPrivilege('Privilege.Page.delete');
    }

    public function hasOfficialsAccess() {
        return $this->hasPrivilege('Privilege.Official.edit');
    }

    public function hasEventPlannerAccess() {
        return $this->hasPrivilege('Privilege.Event.planning');
    }

    public function hasAdminAccess() {
        return $this->hasPrivilege('Privilege.Admin.page');
    }

    private function hasPrivilege($privilege) {
        return $this->Session->read('Club.' . Configure::read('Club.id') . '.' . $privilege);
    }
}
?>
