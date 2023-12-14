<?php declare(strict_types = 1);

namespace Rally\UI;

use Contributte\FormsBootstrap\BootstrapForm;
use Contributte\FormsBootstrap\Enums\RenderMode;
use Contributte\Translation\PrefixedTranslator;
use Contributte\Translation\Translator;
use Nette\Utils\ArrayHash;
use ReflectionClass;

abstract class BaseForm extends BaseControl
{

	/** @var callable[] */
	public array $onSuccess = [];

	/** @var callable[] */
	public array $onValidate = [];

	protected PrefixedTranslator $translator;

	public function __construct()
	{
		$form = $this->getForm();
		$form->onValidate[] = function (): void {
			call_user_func_array([$this, 'onValidate'], func_get_args());
		};
		$form->onSuccess[] = function (): void {
			call_user_func_array([$this, 'onSuccess'], func_get_args());
		};
		$this->onValidate[] = function (BootstrapForm $form): void {
			$this->validateForm($form, $form->getValues());
		};
		$this->onSuccess[] = function (BootstrapForm $form): void {
			$this->formSuccess($form, $form->getValues());
		};
		$this->onAnchor[] = function (): void {
			$this->init($this->getForm());
		};
	}

	public function getForm(): BootstrapForm
	{
		return $this['form'];
	}

	public function render(): void
	{
		$this->template->add('form', $this->getForm());

		$file = str_replace('.php', '.latte', (new ReflectionClass($this))->getFileName());
		$this->setTemplateFile(file_exists($file) ? $file : __DIR__ . '/BaseForm.latte');

		parent::render();
	}

	public function setTranslator(Translator $translator): void
	{
		$this->translator = $translator->createPrefixedTranslator('forms.' . $this->getPrefix());
		$this->getForm()->setTranslator($this->translator);
	}

	protected function createComponentForm(): BootstrapForm
	{
		$form = new BootstrapForm();
		$form->setRenderMode(RenderMode::SIDE_BY_SIDE_MODE);

		return $form;
	}

	abstract protected function init(BootstrapForm $form): void;

	abstract protected function validateForm(BootstrapForm $form, ArrayHash $values): void;

	abstract protected function formSuccess(BootstrapForm $form, ArrayHash $values): void;

	abstract protected function getPrefix(): string;

}
