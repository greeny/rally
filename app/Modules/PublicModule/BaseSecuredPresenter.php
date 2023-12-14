<?php declare(strict_types=1);

namespace Rally\Modules\PublicModule;

abstract class BaseSecuredPresenter extends BasePublicPresenter
{

	protected function startup(): void
	{
		parent::startup();
		if (!$this->userEntity) {
			$this->redirect('Homepage:default');
		}
	}

}
