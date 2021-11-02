<?php
// Runs a shell with the context of each club.
abstract class ClubShell extends AppShell {
    function main() {
		$this->UseClub = $this->Tasks->load('UseClub');
		$this->ClubIDs = $this->Tasks->load('ClubIDs');

		$skipClubs = array_map('trim', explode(',', $this->param('skip-clubs')));

		$clubIDs = $this->ClubIDs->execute();
		foreach ($clubIDs as $clubID) {
			if (in_array($clubID, $skipClubs)) {
				$this->out('Skipping club ' . $clubID);
				continue;
			}

			$this->UseClub->execute($clubID);

            $this->out("Running for club ".Configure::read('Club.name')."\n");

			$this->runForClub();
		}
	}

    abstract protected function runForClub();

	public function getOptionParser() {
		$parser = parent::getOptionParser();
		$parser->addOption(
			'skip-clubs',
			array('help' => 'Comma separated list of clubs to skip.', 'default' => '')
		);
		return $parser;
	}
}
