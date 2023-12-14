<?php declare(strict_types=1);

namespace Rally\UI\Forms;

use Rally\Model\Entity\Member;

interface MemberFormFactory
{

	public function create(?Member $member = null): MemberForm;

}
