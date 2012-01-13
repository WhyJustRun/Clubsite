Hi <?= $user["User"]["name"]; ?>,

Your WhyJustRun username is: <?= $user['User']['username']; ?>

To reset your WhyJustRun password, just click the link below and enter a new password.

<?= Router::url(array('controller' => 'users', 'action' => 'verify', $token), true); ?>