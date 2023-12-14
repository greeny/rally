<?php declare(strict_types=1);

namespace Rally\UI\Forms;

use Contributte\FormsBootstrap\BootstrapForm;
use Nette\Mail\Mailer;
use Nette\Mail\Message;
use Nette\Utils\ArrayHash;
use Rally\Model\Repository\EventRepository;
use Rally\UI\BaseForm;
use Throwable;

class InviteForm extends BaseForm
{

	public function __construct(private readonly EventRepository $eventRepository, private readonly Mailer $mailer)
	{
		parent::__construct();
	}

	protected function init(BootstrapForm $form): void
	{
		$form->getElementPrototype()->class('ajax');

		$form->addHidden('event');

		$form->addText('email', 'fields.email')
			->setHtmlType('email')
			->setRequired('errors.email.required')
			->addRule($form::EMAIL, 'errors.email.valid');

		$form->addSubmit('submit', 'fields.submit');
	}

	protected function validateForm(BootstrapForm $form, ArrayHash $values): void
	{
		// TODO: Implement validateForm() method.
	}

	protected function formSuccess(BootstrapForm $form, ArrayHash $values): void
	{
		$translator = $this->translator->getTranslator();
		$event = $this->eventRepository->getById((int) $values->event);
		if (!$event) {
			return; // silently fail for now
		}

		$mail = new Message;
		$mail->setFrom('noreply@example.com');
		$mail->addTo($values->email);
		$mail->setSubject($translator->translate('messages.inviteEmail.subject', ['event' => $event->name]));
		$mail->setBody($translator->translate('messages.inviteEmail.body', ['event' => $event->name]));

		try {
			$this->mailer->send($mail);
		} catch (Throwable $e) {
			// silently fail due to not configured mailer
		}
	}

	protected function getPrefix(): string
	{
		return 'invite';
	}

}
