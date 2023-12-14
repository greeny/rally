<?php declare(strict_types=1);

namespace Rally\Command;

use Rally\Model\Calendar\CalendarParser;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Throwable;
use Tracy\Debugger;
use Tracy\ILogger;

class SyncCalendarCommand extends Command
{

	protected static $defaultName = 'app:calendar:import';
	protected static $defaultDescription = 'Imports event data from configured calendar.';

	public function __construct(private readonly CalendarParser $calendarParser)
	{
		parent::__construct();
	}

	protected function execute(InputInterface $input, OutputInterface $output): int
	{
		try {
			$output->writeln('Syncing calendar...');
			$this->calendarParser->process();
			$output->writeln('<info>Calendar synced successfully.</info>');

			return Command::SUCCESS;
		} catch (Throwable $e) {
			$output->writeln('<error>' . $e->getMessage() . '</error>');
			Debugger::log($e, ILogger::EXCEPTION);

			return Command::FAILURE;
		}
	}

}
