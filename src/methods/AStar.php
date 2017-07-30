<?php
/**
 * Created by PhpStorm.
 * User: pingcheng
 * Date: 30/7/17
 * Time: 10:46 PM
 */

namespace PingCheng\Method;

use PingCheng\Node;
use PingCheng\Puzzle;

class AStar extends \PingCheng\Method
{
	function __construct(Puzzle $puzzle)
	{
		parent::__construct($puzzle);

	}

	public function go() {
		$this->reset();
		$node = new Node($this->puzzle->get_grid());
		return $this->solve($node);
	}

	/**
	 * @return Node
	 */
	protected function pull() {
		usort($this->frontier, array($this, 'frontier_sort'));
		$current = array_shift($this->frontier);
		$this->add_to_searched($current);
		return $current;
	}

	public function solve(Node $node) {
		$this->add_to_frontier($node);

		while (!empty($this->frontier)) {
			$current = $this->pull();

			$solved = $current->is_solved($this->puzzle);
			if ($solved) {
				return $current->getAllMoves();
			} else {
				$new_states = $current->explore();

				foreach ($new_states as $state) {
					$state->cost = $this->manhattan_distance($state);
					$this->add_to_frontier($state);
				}
			}

		}

		return null;
	}

	protected function manhattan_distance(Node $node) {
		$distance = 0;
		$puzzle = $node->puzzle;
		$goal = $this->puzzle->get_goal_grid();

		foreach ($puzzle as $x => $y_tiles) {
			foreach ($y_tiles as $y => $tile) {
				if ($tile !== 0) {
					if ($tile !== $goal[$x][$y]) {
						$location = $this->findCellinGoal($tile, $goal);
						$distance += abs($x - $location[0]) + abs($y - $location[1]);
					}
				}
			}
		}

		return $distance;
	}

	protected function findCellinGoal($cell, $goal) {
		foreach ($goal as $x => $y_tiles) {
			foreach ($y_tiles as $y => $tile) {
				if ($cell == $tile) {
					return [$x, $y];
				}
			}
		}
	}

	/**
	 * @param Node $a
	 * @param Node $b
	 * @return int
	 */
	protected function frontier_sort($a, $b) {
		$cost_a = $a->cost;
		$cost_b = $b->cost;

		if ($cost_a > $cost_b) {
			return 1;
		} else if ($cost_a < $cost_b) {
			return -1;
		} else {
			return 0;
		}
	}
}