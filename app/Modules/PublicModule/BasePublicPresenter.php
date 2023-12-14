<?php declare(strict_types=1);

namespace Rally\Modules\PublicModule;

use Contributte\Translation\LocalesResolvers\Session;
use Contributte\Translation\Translator;
use Nette\Application\UI\Presenter;
use Nette\Bridges\ApplicationLatte\DefaultTemplate;
use Nette\Security\User;
use Rally\Model\Entity\User as UserEntity;
use Rally\Model\Repository\LanguageRepository;
use Rally\Model\Repository\UserRepository;
use stdClass;

/**
 * @property-read DefaultTemplate $template
 * @property-read User $user
 */
abstract class BasePublicPresenter extends Presenter
{

	protected ?UserEntity $userEntity = null;
	protected readonly Translator $translator;
	protected readonly UserRepository $userRepository;
	protected readonly LanguageRepository $languageRepository;
	protected readonly Session $sessionResolver;

	public function injectBase(Translator $translator, UserRepository $userRepository, LanguageRepository $languageRepository, Session $sessionResolver): void
	{
		$this->translator = $translator;
		$this->userRepository = $userRepository;
		$this->languageRepository = $languageRepository;
		$this->sessionResolver = $sessionResolver;
	}

	public function handleLogout(): void
	{
		if ($this->user->isLoggedIn()) {
			$this->user->logout(true);
		}
		$this->flashSuccess('logout.success');
		$this->redirect(':Public:Homepage:default');
	}

	public function handleChangeLanguage(string $lang): void
	{
		$this->sessionResolver->setLocale($lang);
		$this->redirect('this');
	}

	protected function startup(): void
	{
		parent::startup();

		if ($this->user->isLoggedIn()) {
			$user = $this->userRepository->getById($this->user->getId());
			if (!$user) {
				$this->user->logout(true);
				$this->redirect(':Public:Homepage:default');
			}
			$this->userEntity = $user;
		}
	}

	protected function beforeRender(): void
	{
		parent::beforeRender();
		$this->template->add('lang', $this->translator->getLocale());
		$this->template->add('languages', $this->languageRepository->getAll());
		$this->template->add('userEntity', $this->userEntity);
	}

	protected function flashSuccess(string $message, ...$args): stdClass
	{
		return $this->flash($message, 'success', ...$args);
	}

	protected function flashError(string $message, ...$args): stdClass
	{
		return $this->flash($message, 'danger', ...$args);
	}

	private function flash(string $message, string $type, ...$args): stdClass
	{
		if ($this->isAjax()) {
			$this->redrawControl('flashes');
		}
		return $this->flashMessage($this->translator->translate('messages.flashes.' . $message, ...$args), $type);
	}

}
