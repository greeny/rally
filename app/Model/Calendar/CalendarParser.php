<?php declare(strict_types=1);

namespace Rally\Model\Calendar;

use DateTimeImmutable;
use ICal\Event as ICalEvent;
use ICal\ICal;
use Rally\Model\Entity\Event;
use Rally\Model\Repository\EventRepository;
use RuntimeException;
use Throwable;

class CalendarParser
{

	public function __construct(
		private readonly string $calendarUrl,
		private readonly string $logPath,
		private readonly EventRepository $eventRepository,
	)
	{
	}

	public function process(): void
	{
		$date = new DateTimeImmutable;
		$path = $this->logPath . DIRECTORY_SEPARATOR . 'import_' . $date->format('Y-m-d_G-i-s_v') . '.txt';
		if (!@mkdir($this->logPath, recursive: true) && !is_dir($this->logPath)) {
			throw new RuntimeException(sprintf('Directory "%s" was not created', $this->logPath));
		}

		$file = @fopen($path, 'wb+');
		if (!$file) {
			throw new RuntimeException('Cannot open ' . $path);
		}
		$log = static function (string $message) use ($file) {
			fwrite($file, $message . PHP_EOL);
		};

		try {
			$cal = new ICal;
			$log('Opening ' . $this->calendarUrl);
			$cal->initUrl($this->calendarUrl);

			$log('Found ' . $cal->eventCount . ' events.');
			$log('====');
			/** @var ICalEvent $calEvent */
			foreach ($cal->events() as $calEvent) {
				$log('Processing ' . $calEvent->uid);
				$event = $this->eventRepository->getByRemoteId($calEvent->uid);
				if (!$event) {
					$log('Not found yet in database, creating new event.');
					$event = new Event;
					$event->remoteId = $calEvent->uid;
				} else {
					$log('Matched with event ID ' . $event->id);
				}

				$log('Updated data: ' . json_encode([
					'name' => $calEvent->summary,
					'start' => $calEvent->dtstart,
					'end' => $calEvent->dtend,
				], JSON_THROW_ON_ERROR));

				$event->name = $calEvent->summary;
				$event->start = new DateTimeImmutable($calEvent->dtstart);
				$event->end = new DateTimeImmutable($calEvent->dtend);

				$this->eventRepository->save($event);
				$log('Saved');
				$log('====');
			}

			$log('Done');
		} catch (Throwable $e) {
			$log('Error: ' . get_class($e) . ': '. $e->getMessage() . ' in ' . $e->getFile() . ' on line ' . $e->getLine());
		}

		fclose($file);
	}

	public function getLogDirectory(): string
	{
		return $this->logPath;
	}

}
