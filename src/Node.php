<?php
/**
 * Created by PhpStorm.
 * User: pingcheng
 * Date: 30/7/17
 * Time: 10:58 PM
 */

namespace PingCheng;


class Node
{
	const UP = 'UP';
	const RIGHT = 'RIGHT';
	const DOWN = 'DOWN';
	const LEFT = 'LEFT';

	private $step_cost = 1;

	public $parent, $cost;
	public $pathfromparent;
	public $steps = 0;
	public  $heuristicValue;

	public $puzzle;

	function __construct(Array $puzzle)
	{
		$this->parent = null;
		$this->pathfromparent = null;
		$this->cost = 0;
		$this->puzzle = $puzzle;
		$this->steps = 0;
	}

	public static function withParent(Node $oldnode, $direction, $puzzle) {
		$node = new self($puzzle);
		$node->parent = &$oldnode;
		$node->pathfromparent = $direction;
		$node->puzzle = $puzzle;
		$node->cost = $oldnode->cost;
		$node->steps = $oldnode->steps + $node->step_cost;
		return $node;
	}

	public function get_possible_moves() {
		$moves = [];

		$empty_tile = $this->findBlankCell();

		if ($empty_tile[0] == 0) {
			$moves[] = self::RIGHT;
		} elseif ($empty_tile[0] == sizeof($this->puzzle)-1) {
			$moves[] = self::LEFT;
		} else {
			$moves[] = self::LEFT;
			$moves[] = self::RIGHT;
		}

		if ($empty_tile[1] == 0) {
			$moves[] = self::DOWN;
		} elseif ($empty_tile[1] == sizeof($this->puzzle[0])-1) {
			$moves[] = self::UP;
		} else {
			$moves[] = self::UP;
			$moves[] = self::DOWN;
		}

		usort($moves, function($a, $b) {
			$value = [];
			$value[self::UP] = 1;
			$value[self::LEFT] = 2;
			$value[self::DOWN] = 3;
			$value[self::RIGHT] = 4;

			return ($value[$a] - $value[$b]);
		});
		return $moves;
	}

	public function move($direction) {
		$node = self::withParent($this, $direction, $this->puzzle);
		$empty_tile = $node->findBlankCell();

		if ($direction == self::UP) {
			$node->puzzle[$empty_tile[0]][$empty_tile[1]] = $node->puzzle[$empty_tile[0]][$empty_tile[1] - 1];
			$node->puzzle[$empty_tile[0]][$empty_tile[1] - 1] = 0;
		} else if ($direction == self::DOWN) {
			$node->puzzle[$empty_tile[0]][$empty_tile[1]] = $node->puzzle[$empty_tile[0]][$empty_tile[1] + 1];
			$node->puzzle[$empty_tile[0]][$empty_tile[1] + 1] = 0;
		} else if ($direction == self::LEFT) {
			$node->puzzle[$empty_tile[0]][$empty_tile[1]] = $node->puzzle[$empty_tile[0] - 1][$empty_tile[1]];
			$node->puzzle[$empty_tile[0] - 1][$empty_tile[1]] = 0;
		} else {
			$node->puzzle[$empty_tile[0]][$empty_tile[1]] = $node->puzzle[$empty_tile[0] + 1][$empty_tile[1]];
			$node->puzzle[$empty_tile[0] + 1][$empty_tile[1]] = 0;
		}

		return $node;
	}

	public function is_solved(Puzzle $puzzle) {
		$current = serialize($this->puzzle);
		$goal = serialize($puzzle->get_goal_grid());

		return ($current == $goal);
	}

	/**
	 * @return Node[]
	 */
	public function explore() {
		$possible_moves = $this->get_possible_moves();
		$states = [];
		foreach ($possible_moves as $move) {
			$states[] = $this->move($move);
		}
		return $states;
	}

	public function getAllMoves() {
		$node = $this;
		$moves = [];

		while ($node->parent !== null) {
			array_unshift($moves,$node->pathfromparent);
			$node = $node->parent;
		}

		return $moves;
	}

	private function findBlankCell() {
		foreach ($this->puzzle as $x => $y_tiles) {
			foreach ($y_tiles as $y => $tile) {
				if ($tile === 0) {
					return [$x, $y];
				}
			}
		}

		trigger_error('Cannot find empty tile', E_USER_ERROR);
	}

	private function count_movement(Array $empty_tile) {
		$moves = 2;

		$length_x = sizeof($this->puzzle) - 1;
		$length_y = sizeof($this->puzzle[0]) - 1;

		$x = $empty_tile[0];
		$y = $empty_tile[1];

		if ($x == 0 || $x == $length_x) {
			if ($y == 0 || $y == $length_y) {

			} else {
				$moves++;
			}
		} else {
			if ($y == 0 || $y == $length_y) {
				$moves++;
			} else {
				$moves += 2;
			}
		}

		return $moves;
	}
}