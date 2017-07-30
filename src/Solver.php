<?php
/**
 * Created by PhpStorm.
 * User: pingcheng
 * Date: 30/7/17
 * Time: 10:07 PM
 */

namespace PingCheng;

use \PingCheng\Method;

class Solver
{
	private $puzzle = null;

	function __construct() {
	}

	function solve(Puzzle $puzzle, $methods) {

		$filepath = __DIR__."/methods/{$methods}.php";
		if (!file_exists($filepath)) {
			trigger_error("$filepath do not exists", E_USER_ERROR);
		}

		require_once $filepath;

		$methods_class = "\\PingCheng\\Method\\{$methods}";
		$method = new $methods_class($puzzle);

		$moves = $method->go();

		if ($moves == null) {
			return null;
		} else {
			return [
				'moves'=> $moves,
				'searched' => sizeof($method->searched)
			];
		}
	}
}