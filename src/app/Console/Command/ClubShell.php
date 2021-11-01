<?php
// Runs a shell with the context of each club.
abstract class ClubShell extends Shell {
    function main() {
		$this->UseClub = $this->Tasks->load('UseClub');
		$this->ClubIDs = $this->Tasks->load('ClubIDs');

		$clubIDs = $this->ClubIDs->execute();
		foreach ($clubIDs as $clubID) {
			$this->UseClub->execute($clubID);

            echo "Running for club ".Configure::read('Club.name')."\n";

			$this->runForClub();
		}
	}

    abstract protected function runForClub();
}
