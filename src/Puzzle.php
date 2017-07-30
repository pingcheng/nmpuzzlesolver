<?php

namespace PingCheng;

class Puzzle
{
	private $origin_tiles = [];
	private $tiles = [];
	private $width = 0;
	private $height = 0;
	private $goal = [];

	private $grid = [];
	private $goal_grid = [];

	function __construct()
	{
	}

	public function prepare() {
		$tiles_length = sizeof($this->tiles);
		$goal_length = sizeof($this->goal);

		// check lengths if equal
		if ($tiles_length !== $goal_length) {
			trigger_error('tiles and goal has different length');
		}

		// check if has differnt piece
		$ordered_tiles = $this->tiles;
		$ordered_goal = $this->goal;
		sort($ordered_tiles);
		sort($ordered_goal);
		$tiles_string = implode(',', $ordered_tiles);
		$goal_string = implode(',', $ordered_goal);
		if ($tiles_string !== $goal_string) {
			trigger_error('tiles and goal has differnt piece');
		}

		$x = 0;
		$y = 0;
		for ($i = 0; $i < $tiles_length; $i++) {
			$tile = $this->tiles[$i];

			if ($x >= $this->width) {
				$x = 0;
				$y++;
			}

			$this->grid[$x][$y] = $tile;
			$x++;
		}

		$x = 0;
		$y = 0;
		for ($i = 0; $i < $tiles_length; $i++) {
			$tile = $this->goal[$i];

			if ($x >= $this->width) {
				$x = 0;
				$y++;
			}

			$this->goal_grid[$x][$y] = $tile;
			$x++;
		}
	}

	/**
	 * set the puzzle's width
	 * @param $width
	 */
	public function set_width($width) {
		$this->width = (int)$width;
	}

	/**
	 * set the puzzle's height
	 * @param $height
	 */
	public function set_height($height) {
		$this->height = (int)$height;
	}

	/**
	 * set the puzzle's tiles order
	 * @param array $tiles
	 */
	public function set_tiles(Array $tiles) {
		if (is_array($tiles)) {
			$this->tiles = $tiles;
			$this->origin_tiles = $tiles;
		} else {
			trigger_error('$tiles must be an array', E_USER_ERROR);
		}
	}

	/**
	 * set the puzzles's goal order
	 * @param array $goal
	 */
	public function set_goal(Array $goal) {
		if (is_array($goal)) {
			$this->goal = $goal;
		} else {
			trigger_error('$goal must be an array', E_USER_ERROR);
		}
	}

	/**
	 * get the puzzle width
	 * @return int
	 */
	public function get_width() {
		return $this->width;
	}

	/**
	 * get the puzzle height
	 * @return int
	 */
	public function get_height() {
		return $this->height;
	}

	/**
	 * get the puzzle tiles order
	 * @return array
	 */
	public function get_tiles() {
		return $this->tiles;
	}

	public function get_grid() {
		return $this->grid;
	}

	public function get_goal_grid() {
		return $this->goal_grid;
	}

	/**
	 * get the puzzle original tiles' order
	 * @return array
	 */
	public function get_original_tiles() {
		return $this->origin_tiles;
	}

	/**
	 * get the puzzle goal's order
	 * @return array
	 */
	public function get_goal() {
		return $this->goal;
	}
}