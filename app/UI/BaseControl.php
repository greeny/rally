<?php declare(strict_types = 1);

namespace Rally\UI;

use Nette\Application\UI\Control;
use Nette\Bridges\ApplicationLatte\DefaultTemplate;
use ReflectionClass;

/**
 * @property-read DefaultTemplate $template
 */
abstract class BaseControl extends Control
{

	private ?string $templateFile = null;

	final public function setTemplateFile(string $templateFile): void
	{
		$this->templateFile = $templateFile;
	}

	public function render(): void
	{
		if ($this->templateFile === null) {
			$fileName = (new ReflectionClass($this))->getFileName();
			$this->templateFile = str_replace('.php', '.latte', $fileName);
		}

		$this->template->setFile($this->templateFile);
		$this->beforeRender();
		$this->template->render();
	}

	protected function beforeRender(): void
	{
	}

}
