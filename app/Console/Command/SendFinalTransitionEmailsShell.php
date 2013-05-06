<?php
App::uses('CakeEmail', 'Network/Email');

class SendFinalTransitionEmailsShell extends Shell {
    public $uses = array('Club', 'User', 'Group', 'Privilege');

    // NO MYSQL Escaping, don't use with user inputted data..
    private function query() {
        return "SELECT id, name, email, username FROM users WHERE email IS NOT NULL";
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

    public function main() {
        $this->out('Sending out emails to all people...');
        $users = $this->User->query($this->query());
        $started = false;
        foreach ($users as $user) {
            // Resume where we left off when gmail quotas killed us..
            if ($user['users']['id'] == 1430) {
                $started = true;
            }
            if(!$started) continue;
            $email = $user['users']['email'];
            $username = $user['users']['username'];
            if (empty($username)) continue;
            $this->out("Emailing $email with username $username");
            $subject = 'Access changes to your WhyJustRun account';
            $message = "<h2>Signing in to your WhyJustRun account</h2>";
            $message .= "WhyJustRun, the orienteering website used by GVOC, VICO, SAGE, KOC, STARS, FWOC, and others launched some improvements to its login system today. You can now:
                <ul>
                    <li>Sign in using your email address (you now do not have to remember a separate WhyJustRun username)</li>
                    <li>Sign in using your Google or Facebook account (assuming your Google/Facebook account has the same email associated with it as your WhyJustRun account)</li>
                </ul>

                <h3>How does this change impact you?</h3>
                <p>Instead of signing in with your old username, <strong>$username</strong>, you should now sign in with your email address when using WhyJustRun: <strong>$email</strong>. Your password will be unchanged.</p>
                
                <p>If you have any questions, you can email Russell Porter for support at contact@russellporter.com</p>
                <p>Thanks,<br/>The WhyJustRun team</p>";
            //$this->sendEmail($email, $subject, $message);
        }
    }
}
