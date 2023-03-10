<?php

require_once 'AdventOfCode.php';

use OndrejFuhrer\AdventOfCode;
use function OndrejFuhrer\println;

class Day11 extends AdventOfCode
{
	private const GROUND = '.';
	private const EMPTY_SEAT = 'L';
	private const OCCUPIED_SEAT = '#';

	protected function prepareData(): array
	{
		return array_map(
			static function (string $row) {
				return str_split($row);
			},
			$this->data
		);
	}

	protected function executePart1(array $data): void
	{
		$currentMap = $data;

		do {
			$sitResult = [];
			$seatingChanged = $this->sit($currentMap, $sitResult, true);
			$currentMap = $sitResult;
			if ($seatingChanged) {
				$leaveResult = [];
				$seatingChanged = $this->leaveSeat($currentMap, $leaveResult, 4);
				$currentMap = $leaveResult;
			}
		} while ($seatingChanged);

		$occupiedSeats = $this->calculateOccupiedSeats($currentMap);
		println(sprintf('💺 Occupied seats: %d', $occupiedSeats));
	}

	protected function executePart2(array $data): void
	{
		$currentMap = $data;

		do {
			$sitResult = [];
			$seatingChanged = $this->sit($currentMap, $sitResult, false);
			$currentMap = $sitResult;
			if ($seatingChanged) {
				$leaveResult = [];
				$seatingChanged = $this->leaveSeat($currentMap, $leaveResult, 5);
				$currentMap = $leaveResult;
			}
		} while ($seatingChanged);

		$occupiedSeats = $this->calculateOccupiedSeats($currentMap);
		println(sprintf('💺 Occupied seats: %d', $occupiedSeats));
	}

	private function calculateOccupiedSeats(array $map): int
	{
		$occupied = 0;
		foreach($map as $row) {
			foreach ($row as $column) {
				if ($this->isOccupiedSeat($column)) {
					$occupied++;
				}
			}
		}

		return $occupied;
	}

	private function sit(array $currentMap, array &$resultMap, bool $onlyAdjacent): bool
	{
		$resultMap = [];
		$changedSeats = false;
		foreach ($currentMap as $rowId => $row) {
			$resultMap[$rowId] = [];
			foreach ($row as $columnId => $column) {
				if ($this->isGround($column)) {
					$resultMap[$rowId][$columnId] = self::GROUND;
				} elseif ($this->isOccupiedSeat($column)) {
					$resultMap[$rowId][$columnId] = self::OCCUPIED_SEAT;
				} elseif ($onlyAdjacent && $this->canSit($rowId, $columnId, $currentMap)) {
					$resultMap[$rowId][$columnId] = self::OCCUPIED_SEAT;
					$changedSeats = true;
				} elseif (!$onlyAdjacent && $this->canSit($rowId, $columnId, $currentMap)) {
					$resultMap[$rowId][$columnId] = self::OCCUPIED_SEAT;
					$changedSeats = true;
				} else {
					$resultMap[$rowId][$columnId] = self::EMPTY_SEAT;
				}
			}
		}

		return $changedSeats;
	}

	private function leaveSeat(array $currentMap, array &$resultMap, int $limit): bool
	{
		$resultMap = [];
		$changedSeats = false;
		foreach ($currentMap as $rowId => $row) {
			$resultMap[$rowId] = [];
			foreach ($row as $columnId => $column) {
				if ($this->isGround($column)) {
					$resultMap[$rowId][$columnId] = self::GROUND;
				} elseif ($this->isEmptySeat($column)) {
					$resultMap[$rowId][$columnId] = self::EMPTY_SEAT;
				} elseif ($this->willLeaveSeat($rowId, $columnId, $currentMap, $limit)) {
					$resultMap[$rowId][$columnId] = self::EMPTY_SEAT;
					$changedSeats = true;
				} else {
					$resultMap[$rowId][$columnId] = self::OCCUPIED_SEAT;
				}
			}
		}

		return $changedSeats;
	}

	private function willLeaveSeat(int $row, int $column, array $map, int $limit): bool
	{
		return $this->calculateNearbyOccupiedSeats($row, $column, $map) >= $limit;
	}

	private function canSit(int $row, int $column, array $map): bool
	{
		return $this->calculateNearbyOccupiedSeats($row, $column, $map) === 0;
	}

	private function calculateNearbyOccupiedSeats(int $row, int $column, array $map): int
	{
		$occupiedNearBy = 0;
		if (isset($map[$row - 1][$column - 1]) && $this->isOccupiedSeat($map[$row - 1][$column - 1])) {
			$occupiedNearBy++;
		}
		if (isset($map[$row - 1][$column]) && $this->isOccupiedSeat($map[$row - 1][$column])) {
			$occupiedNearBy++;
		}
		if (isset($map[$row - 1][$column + 1]) && $this->isOccupiedSeat($map[$row - 1][$column + 1])) {
			$occupiedNearBy++;
		}
		if (isset($map[$row][$column - 1]) && $this->isOccupiedSeat($map[$row][$column - 1])) {
			$occupiedNearBy++;
		}
		if (isset($map[$row][$column + 1]) && $this->isOccupiedSeat($map[$row][$column + 1])) {
			$occupiedNearBy++;
		}
		if (isset($map[$row + 1][$column - 1]) && $this->isOccupiedSeat($map[$row + 1][$column - 1])) {
			$occupiedNearBy++;
		}
		if (isset($map[$row + 1][$column]) && $this->isOccupiedSeat($map[$row + 1][$column])) {
			$occupiedNearBy++;
		}
		if (isset($map[$row + 1][$column + 1]) && $this->isOccupiedSeat($map[$row + 1][$column + 1])) {
			$occupiedNearBy++;
		}

		return $occupiedNearBy;
	}

	private function pretify(array $map): string
	{
		return implode(
			PHP_EOL,
			array_map(
				static function (array $row) {
					return implode('', $row);
				},
				$map
			)
		);
	}

	private function isGround(string $position): bool
	{
		return $position === self::GROUND;
	}

	private function isEmptySeat(string $position): bool
	{
		return $position === self::EMPTY_SEAT;
	}

	private function isOccupiedSeat(string $position): bool
	{
		return $position === self::OCCUPIED_SEAT;
	}
}

$challenge = new Day11('day11.txt');
$challenge->run();
