<?php
/**
 * Created by PhpStorm.
 * User: pingcheng
 * Date: 30/7/17
 * Time: 10:46 PM
 */

namespace PingCheng;

class Method
{
	protected $puzzle;

	/**
	 * @var Node[]
	 */
	public $frontier = [];

	public $searched = [];

	function __construct(Puzzle $puzzle)
	{
		$this->puzzle = $puzzle;
	}

	protected function reset() {
		$this->frontier = [];
		$this->searched = [];
	}

	protected function add_to_frontier(Node $node) {
		$existed = false;
		foreach($this->frontier as $frontier) {
			if ($frontier->puzzle === $node->puzzle) {
				$existed = true;
				break;
			}
		}

		if (!$existed) {
			foreach ($this->searched as $searched) {
				if ($searched->puzzle === $node->puzzle) {
					$existed = true;
					break;
				}
			}
		}

		if (!$existed) {
			array_push($this->frontier, $node);
		}
	}

	protected function add_to_searched(Node $node) {
		array_push($this->searched, $node);
	}
}