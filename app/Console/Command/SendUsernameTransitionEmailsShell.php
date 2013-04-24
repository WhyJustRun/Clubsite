<?php
App::uses('CakeEmail', 'Network/Email');

class SendUsernameTransitionEmailsShell extends Shell {
    public $uses = array('Club', 'User', 'Group', 'Privilege');

    // NO MYSQL Escaping, don't use with user inputted data..
    private function unsafeQueryForEmail() {
        return "SELECT id, name, email, COUNT(email) AS email_count FROM users WHERE email IS NOT NULL GROUP BY email ORDER BY email_count DESC";
    }

    // This kind of has to be an invariant property so we don't have to store what we email now for when we do the transition later... Therefore, pick the oldest account (by user id) registered with the given email.
    private function pickMostImportantUserOnEmail($usersWithSameEmail) {
        $sorted = Set::sort($usersWithSameEmail, '{n}.User.id', 'asc');
        return $sorted[0];
    }

    private function sendEmailToAffectedUsers($users, $email) {
        $primaryUser = $this->pickMostImportantUserOnEmail($users);
        $primaryUserName = $primaryUser['User']['name'];
        $this->out("Sending email to affected user: $primaryUserName");
        $primaryUsername = $primaryUser['User']['username'];
        $subject = "Upcoming changes to your WhyJustRun account";
        $message = "<h2>Important changes to your WhyJustRun account</h2>";
        $message .= "WhyJustRun, the orienteering website software used by GVOC, VICO, SAGE, KOC, STARS, FWOC, Whistler OC, and others will have some improvements to its login system in the coming weeks. You will be able to:
            <ul>
                <li>Log in using your Google or Facebook account</li>
                <li>Log in with your email address instead of your WhyJustRun username</li>
            </ul>";
        $message .= "<h3>How will this change impact you?</h3>";
        $message .= "Currently, there are multiple WhyJustRun accounts registered with your email address. After these changes, only one account can be associated with your email address. This means the other accounts registered with your email address will no longer be accessible. Depending on your situation, this may be ok. If you just want to be able to register other friends/family for an event, you can go to the event page and from your own account, register other people with the 'Register others' section. Otherwise, you should assign new email addresses to the other accounts so they remain accessible after the changes.<br/><br/>";

        $message .= "<b>Here is how the accounts associated with your email address will be affected:</b><br/><br/>";
        $message .= "<b>$primaryUserName</b> (old username: $primaryUsername): <b>account will not be affected</b>, after the changes, simply log in using your email address ($email)<br/><br/>";
        foreach($users as $user) {
            if ($user != $primaryUser) {
                $username = $user['User']['username'];
                $name = $user['User']['name'];
                $message .= "<b>$name</b> (old username: $username): <b>account will not be accessible anymore</b>. Existing registrations and results on WhyJustRun will be preserved. $primaryUserName will still be able to register $name from their account, but $name will not be able to access their account. To resolve this issue, please log in on $name's account <a href='http://gvoc.whyjustrun.ca/users/login' target='_blank'>here</a> and then go to My Profile to change the email address. Thank you!<br/><br/>";
            }
        }

        $message .= "<h3>These changes will take effect on or after May 3rd 2013.</h3>";
        $message .= "If you have any questions, feel free to email Russell Porter at contact@russellporter.com.";
        $this->sendEmail($email, $subject, $message);
    }

    private function sendEmail($emailAddress, $subject, $message) {
        $email = new CakeEmail('default');
        $email->domain('gvoc.whyjustrun.ca');
        $email->emailFormat('html');
        $email->subject($subject);
        $email->from("noreply@whyjustrun.ca", 'WhyJustRun');
        $email->to($emailAddress);
        $email->send($message);
    }

    private function sendEmailToUnaffectedUser($user, $email) {
        $this->out("Sending email to unaffected user: $email");
    }

    private function findEmails() {
        $matches = $this->User->query($this->unsafeQueryForEmail());
        $emails = array();
        foreach ($matches as $match) {
            array_push($emails, $match['users']['email']);
        }
        return array_unique($emails);
    }

    public function main() {
        $this->out('Sending out emails to all people...');
        $emails = $this->findEmails();
        foreach ($emails as $email) {
            // Find all matches for the specific email address
            $users = $this->User->find('all', array('conditions' => array('User.email' =>$email), 'recursive' => -1));
            echo count($users);
            if (count($users) <= 1) {
                // Don't send emails to unaffected users
                $this->sendEmailToUnaffectedUser($users[0], $email);
            } else {
                $this->sendEmailToAffectedUsers($users, $email);
            }
        }
    }
}
