<?php declare(strict_types=1);

namespace Rally\Modules\PublicModule;

use Contributte\Application\Response\CSVResponse;
use Rally\Model\Calendar\CalendarParser;
use Rally\Model\Repository\EventRepository;
use Rally\UI\Forms\InviteForm;
use Rally\UI\Forms\InviteFormFactory;

final class EventPresenter extends BasePublicPresenter
{

	private const EventGridSnippet = 'eventGrid';

	public function __construct(
		private readonly EventRepository $eventRepository,
		private readonly CalendarParser $calendarParser,
		private readonly InviteFormFactory $inviteFormFactory,
	)
	{
		parent::__construct();
	}

	public function renderDefault(): void
	{
		$this->template->add('events', $this->eventRepository->getAll());
	}

	public function handleExport(): void
	{
		$items = [['Name', 'From', 'To']];

		foreach ($this->eventRepository->getAll() as $event) {
			$items[] = [
				'name' => $event->name,
				'from' => $event->start->format('Y-m-d G:i:s'),
				'to' => $event->end->format('Y-m-d G:i:s'),
			];
		}

		$this->sendResponse(new CSVResponse($items));
	}

	public function handleSync(): void
	{
		$this->calendarParser->process();
		$this->flashSuccess('sync.completed');

		if ($this->isAjax()) {
			$this->redrawControl(self::EventGridSnippet);
			$this->payload->postGet = TRUE;
			$this->payload->url = $this->link('this');
		} else {
			$this->redirect('this');
		}
	}

	protected function createComponentInviteForm(): InviteForm
	{
		$form = $this->inviteFormFactory->create();
		$form->onSuccess[] = function () {
			$this->flashSuccess('event.invited');
			if ($this->isAjax()) {
				$this->redrawControl(self::EventGridSnippet);
			} else {
				$this->redirect('this');
			}
		};
		return $form;
	}

}
