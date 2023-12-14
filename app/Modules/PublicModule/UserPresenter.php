<?php declare(strict_types=1);

namespace Rally\Modules\PublicModule;

use Rally\UI\Forms\LoginForm;
use Rally\UI\Forms\LoginFormFactory;
use Rally\UI\Forms\RegisterForm;
use Rally\UI\Forms\RegisterFormFactory;

final class UserPresenter extends BasePublicPresenter
{

	public function __construct(private readonly RegisterFormFactory $registerFormFactory, private readonly LoginFormFactory $loginFormFactory)
	{
		parent::__construct();
	}

	public function actionRegister(): void
	{
		if ($this->user->isLoggedIn()) {
			$this->redirect('Homepage:default');
		}
	}

	public function actionLogin(): void
	{
		if ($this->user->isLoggedIn()) {
			$this->redirect('Homepage:default');
		}
	}

	protected function createComponentRegisterForm(): RegisterForm
	{
		$form = $this->registerFormFactory->create();
		$form->onSuccess[] = function () {
			$this->flashSuccess('register.success');
			$this->redirect('Homepage:default');
		};
		return $form;
	}

	protected function createComponentLoginForm(): LoginForm
	{
		$form = $this->loginFormFactory->create();
		$form->onSuccess[] = function () {
			$this->flashSuccess('login.success');
			$this->redirect('Homepage:default');
		};
		return $form;
	}

}
