<?php

require_once 'AdventOfCode.php';

use OndrejFuhrer\AdventOfCode;
use function OndrejFuhrer\println;

class Day5 extends AdventOfCode
{
	protected function prepareData(): array
	{
		return $this->data;
	}

	protected function executePart1(array $data): void
	{
		$highestSeatId = 0;
		foreach ($data as $boardingPass) {
			$row = $this->calculateRow($boardingPass);
			$column = $this->calculateColumn($boardingPass);
			$seatId = ($row * 8) + $column;

			$this->debug(sprintf('💺 Pass "%s": Row %d Column: %d Seat ID: %d', $boardingPass, $row, $column, $seatId));
			if ($seatId > $highestSeatId) {
				$highestSeatId = $seatId;
			}
		}
		println(sprintf('📈 Highest seat ID: %d', $highestSeatId));
	}

	protected function executePart2(array $data): void
	{
		$emptySeats = array_fill(0, 904, true);
		foreach ($data as $boardingPass) {
			$row = $this->calculateRow($boardingPass);
			$column = $this->calculateColumn($boardingPass);
			$seatId = ($row * 8) + $column;

			$this->debug(sprintf('💺 Pass "%s": Row %d Column: %d Seat ID: %d', $boardingPass, $row, $column, $seatId));
			$emptySeats[$seatId] = false;
		}

		$emptySeats = array_filter($emptySeats);
		foreach ($emptySeats as $i => $seat) {
			if (isset($emptySeats[$i + 1]) || isset($emptySeats[$i - 1])) {
				continue;
			}

			println(sprintf('✈️ My seat 💺 : %d', $i));
		}
	}

	private function calculateRow(string $boardingPass): int
	{
		$rowRangeFrom = 0;
		$rowRangeTo = 127;
		for ($i = 0; $i < 7; $i++) {
			$halfDifference = (($rowRangeTo - $rowRangeFrom) / 2);
			switch ($boardingPass[$i]) {
				case 'F':
					$rowRangeTo = round($rowRangeTo - $halfDifference, 0, PHP_ROUND_HALF_DOWN);
					break;
				case 'B':
					$rowRangeFrom = round($rowRangeFrom + $halfDifference);
					break;
			}
		}

		return $rowRangeFrom;
	}

	private function calculateColumn($boardingPass)
	{
		$columnRangeFrom = 0;
		$columnRangeTo = 7;

		for ($i = 7; $i < 10; $i++) {
			$halfDifference = ($columnRangeTo - $columnRangeFrom) / 2;
			switch ($boardingPass[$i]) {
				case 'L':
					$columnRangeTo = round($columnRangeTo - $halfDifference, 0, PHP_ROUND_HALF_DOWN);
					break;
				case 'R':
					$columnRangeFrom = round($columnRangeFrom + $halfDifference);
					break;
			}
		}

		return $columnRangeFrom;
	}
}

$challenge = new Day5('day5.txt');
$challenge->run();
