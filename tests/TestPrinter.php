<?php

namespace Tests;

use PHPUnit\TextUI\DefaultResultPrinter;
use PHPUnit\Framework\TestFailure;
use PHPUnit\Exception;

class TestPrinter extends DefaultResultPrinter
{
	protected function printDefect(TestFailure $defect, int $count): void
	{
		$this->printDefectHeader($defect, $count);

		$exception = $defect->thrownException();

		if (!($exception instanceof Exception)) {
			$this->write(sprintf('%s:%u', $exception->getFile(), $exception->getLine()));
			$this->write(PHP_EOL);
		}

		$this->printDefectTrace($defect);
	}
}
