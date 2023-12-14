<?php declare(strict_types=1);

namespace Rally\UI\Forms;

use Rally\Model\Entity\Team;

interface TeamFormFactory
{

	public function create(?Team $team = null): TeamForm;

}
