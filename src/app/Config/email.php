<?php
class EmailConfig {
	public $default;

    public function __construct()
    {
        $this->default = array(
			'host' => $_ENV['WJR_EMAIL_HOST'],
			'port' => 465,
			'username' => $_ENV['WJR_EMAIL_USERNAME'],
			'password' => $_ENV['WJR_EMAIL_PASSWORD'],
			'transport' => 'Smtp'
		);
    } 
}
?>

