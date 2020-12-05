<?php

declare(strict_types=1);

namespace OndrejFuhrer;

function println(string $line = ''): void
{
	echo $line.PHP_EOL;
}

abstract class AdventOfCode
{
	private const DEBUG = false;
	protected array $data;

	public function __construct(string $inputFile)
	{
		$inputs = file_get_contents($inputFile);
		$this->data = array_map('trim', explode("\n", trim($inputs)));
	}

	abstract protected function prepareData(): array;
	abstract protected function executePart1(array $data): void;
	abstract protected function executePart2(array $data): void;

	public function run(): void
	{
		$this->printHeader();
		$data = $this->prepareData();
		$this->executePart1($data);
		$this->executePart2($data);
	}

	protected function debug(string $message): void
	{
		if (self::DEBUG) {
			println($message);
		}
	}

	private function printHeader(): void
	{
		println();
		println('#######################################');
		println(sprintf('## 🎄 Advent of Code 2020 🎄 [%s/24] #', $this->getProgress()));
		println('#######################################');
		println();
		println();
	}

	private function getProgress(): string
	{
		$className = static::class;
		$day = substr($className, -1);

		return str_pad($day, 2, '0', STR_PAD_LEFT);
	}
}
