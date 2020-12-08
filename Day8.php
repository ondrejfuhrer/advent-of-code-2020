<?php

require_once 'AdventOfCode.php';

use OndrejFuhrer\AdventOfCode;
use function OndrejFuhrer\println;

class Day8 extends AdventOfCode
{
	protected function prepareData(): array
	{
		return array_map(
			static function ($instruction) {
				[$cmd, $arg] = explode(' ', $instruction);

				return [
					'cmd' => $cmd,
					'arg' => (int) $arg,
				];
			},
			$this->data
		);
	}

	protected function executePart1(array $data): void
	{
		$accumulator = 0;
		$correctBoot = $this->performBoot($data, $accumulator);
		println(sprintf('📟 Accumulator state: %d %s', $accumulator, $correctBoot ? '✅' : '❌'));
	}

	protected function executePart2(array $data): void
	{
		$accumulator = 0;
		$correctBoot = false;

		$jumpInstructions = $this->findInstructionIndexes('jmp', $data);
		foreach ($jumpInstructions as $index) {
			$accumulator = 0;
			$localData = $data;
			$localData[$index]['cmd'] = 'nop';
			$correctBoot = $this->performBoot($localData, $accumulator);
			if ($correctBoot) {
				break;
			}
		}
		println(sprintf('📟 Accumulator state after `jmp` change: %d %s', $accumulator, $correctBoot ? '✅' : '❌'));

		$nopInstructions = $this->findInstructionIndexes('nop', $data);
		foreach ($nopInstructions as $index) {
			$accumulator = 0;
			$localData = $data;
			$localData[$index]['cmd'] = 'jmp';
			$correctBoot = $this->performBoot($localData, $accumulator);
			if ($correctBoot) {
				break;
			}
		}
		println(sprintf('📟 Accumulator state after `nop` change: %d %s', $accumulator, $correctBoot ? '✅' : '❌'));
	}

	private function performBoot(array $data, int &$accumulator): bool
	{
		$instructionCount = count($data);
		$instructionExecution = array_fill(0, $instructionCount, 0);
		$correctBoot = true;
		for ($i = 0; $i < $instructionCount; $i++) {
			$instruction = $data[$i];
			if ($instructionExecution[$i] > 0) {
				$correctBoot = false;
				break;
			}
			$instructionExecution[$i]++;
			switch ($instruction['cmd']) {
				case 'acc':
					$accumulator += $instruction['arg'];
					break;
				case 'nop':
					break;
				case 'jmp':
					$i += ($instruction['arg'] - 1);
					break;
			}
		}

		return $correctBoot;
	}

	/**
	 * @param string $cmd
	 * @param array $data
	 * @return int[]
	 */
	private function findInstructionIndexes(string $cmd, array $data): array
	{
		return array_keys(
			array_filter(
				array_filter(
					$data,
					static function (array $instruction) use ($cmd) {
						if ($instruction['cmd'] !== $cmd) {
							return null;
						}

						return $instruction;
					}
				)
			)
		);
	}
}

$challenge = new Day8('day8.txt');
$challenge->run();
