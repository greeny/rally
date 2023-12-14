<?php declare(strict_types=1);

namespace Rally\UI\Forms;

use Contributte\FormsBootstrap\BootstrapForm;
use Nette\Security\Passwords;
use Nette\Security\SimpleIdentity;
use Nette\Security\User as SecurityUser;
use Nette\Utils\ArrayHash;
use Rally\Model\Entity\User;
use Rally\Model\Repository\UserRepository;
use Rally\UI\BaseForm;

class RegisterForm extends BaseForm
{

	public function __construct(private readonly UserRepository $userRepository, private readonly Passwords $passwords, private readonly SecurityUser $securityUser)
	{
		parent::__construct();
	}

	protected function init(BootstrapForm $form): void
	{
		$form->addText('login', 'fields.login')
			->setRequired('errors.login.required');

		$form->addPassword('password', 'fields.password')
			->setRequired('errors.password.required');

		$form->addPassword('password2', 'fields.password2')
			->addRule($form::EQUAL, 'errors.password.notEqual', $form['password']);

		$form->addSubmit('submit', 'fields.submit');
	}

	protected function validateForm(BootstrapForm $form, ArrayHash $values): void
	{
		$user = $this->userRepository->getByLogin($values->login);

		if ($user) {
			$form['login']->addError('errors.login.duplicate');
		}
	}

	protected function formSuccess(BootstrapForm $form, ArrayHash $values): void
	{
		$user = new User;
		$user->login = $values->login;
		$user->password = $this->passwords->hash($values->password);

		$this->userRepository->save($user);

		$this->securityUser->login(new SimpleIdentity($user->id));
	}

	protected function getPrefix(): string
	{
		return 'register';
	}

}
