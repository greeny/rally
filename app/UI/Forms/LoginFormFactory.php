<?php declare(strict_types=1);

namespace Rally\UI\Forms;

interface LoginFormFactory
{

	public function create(): LoginForm;

}
