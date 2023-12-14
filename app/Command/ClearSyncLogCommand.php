<?php declare(strict_types=1);

namespace Rally\Command;

use Rally\Model\Calendar\CalendarParser;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ClearSyncLogCommand extends Command
{

	protected static $defaultName = 'app:calendar:clear-log';
	protected static $defaultDescription = 'Clears logs from calendar sync.';

	public function __construct(private readonly CalendarParser $calendarParser)
	{
		parent::__construct();
	}

	protected function execute(InputInterface $input, OutputInterface $output): int
	{
		$directory = $this->calendarParser->getLogDirectory();

		$visited = 0;
		$cleared = 0;
		foreach (glob($directory . DIRECTORY_SEPARATOR . '*') as $file) {
			$visited++;
			if (time() - filectime($file) > 30 * 24 * 60 * 60) { // 1 month
				unlink($file);
				$cleared++;
			}
		}

		$output->writeln('<info>Cleared ' . $cleared . ' out of ' . $visited . ' file(s).</info>');

		return Command::SUCCESS;
	}

}
