<?php

class AStar
{
	protected $map = [];

	protected $map_width  = 0;
	protected $map_height = 0;

	protected $x_start;
	protected $y_start;

	protected $x_finish;
	protected $y_finish;

	protected $list_open  = []; # need to be examined
	protected $list_close = []; # have already been examined

	protected $path = [];

	public function __construct(int $width = null, int $height = null)
	{
		static::setMapSize($width, $height);
	}

	public function setStart (int $x, int $y) { list($this->x_start,  $this->y_start)  = [$x, $y]; }
	public function setFinish(int $x, int $y) { list($this->x_finish, $this->y_finish) = [$x, $y]; }

	public function setMapSize(int $width, int $height) { list($this->map_width, $this->map_height) = [$width, $height]; }

	public function addObstacle(int $x, int $y)
	{
		$this->map[$x][$y] = false;
	}

	public function reset()
	{
		# clear cache

		$this->list_open  = [];
		$this->list_close = [];
		$this->path = [];


		# create an initial node

		$h = $this->getCost($this->x_start, $this->y_start, $this->x_finish, $this->y_finish);

		$this->addToOpenList($this->x_start, $this->y_start, 0, $h);
	}

	public function find(): ?array
	{
		$this->reset();

		while ($this->list_open)
		{
			$node = $this->getNextNode();

			if ($node->x === null)
			{
				break;
			}


			$this->moveToCloseList($node->x, $node->y);

			if ($node->x == $this->x_finish and $node->y == $this->y_finish)
			{
				return static::backtrace($node->x, $node->y);
			}


			for ($x = $node->x - 1; $x <= $node->x + 1; $x++)
			{
				for ($y = $node->y - 1; $y <= $node->y + 1; $y++)
				{
					if ($x == $node->x and $y == $node->y)
					{
						continue; # skip turns are not allowed
					}

					if ($y != $node->y and $x != $node->x)
					{
						continue; # diagonal movements are not allowed
					}


					if ($this->isObstacle($x, $y)) { continue; }
					if ($this->isClosed  ($x, $y)) { continue; }


					$g = $node->g + $this->getCost($node->x, $node->y, $x, $y) + $this->countTurns($node->x, $node->y);

					if (!isset($this->list_open[$x][$y]) or $g < $this->list_open[$x][$y]->g)
					{
						$h = $this->getCost($x, $y, $this->x_finish, $this->y_finish);

						$this->addToOpenList($x, $y, $g, $h, $node->x, $node->y);
					}
				}
			}
		}

		return null;
	}

	public function debug()
	{
		$result = [];

		foreach ($this->path as list($x, $y))
		{
			# ASCII digit to letter transformation (0 to A, 1 to B and so on)
			# works valid with digits in the range from 0 to 25 (the length of Englsh alphabet in the ASCII table)
			# you should rewrite this method if you are going to use this method when you have more than 26 rows
			$y = chr(ord($y) + 17);

			$result[] = $x . $y;
		}


		$result = implode(' -> ', $result);

		echo $result . PHP_EOL;
	}

	public function countTurns(int $x = null, int $y = null): int
	{
		$count = 0;

		if (!func_num_args())
		{
			$x = $this->x_finish;
			$y = $this->y_finish;
		}

		$backtrace = static::backtrace($x, $y);
		$backtrace_size = count($backtrace);

		$direction = null;

		for ($i = 0; $i < $backtrace_size - 1; $i++)
		{
			list($x,      $y)      = $backtrace[$i];
			list($x_next, $y_next) = $backtrace[$i + 1];

			if ($direction === null)
			{
				     if ($x == $x_next) { $count++; $direction = 'x'; }
				else                    { $count++; $direction = 'y'; }
			}
			else if ($direction == 'x' and $y == $y_next) { $count++; $direction = 'y'; }
			else if ($direction == 'y' and $x == $x_next) { $count++; $direction = 'x'; }
		}

		return max(1, $count);
	}

	protected function backtrace(int $x, int $y): array
	{
		$this->path = [];

		for (;;)
		{
			if ($x !== null and $y !== null)
			{
				$this->path[] = [$x, $y];
			}

			if (!isset($this->list_close[$x][$y]) or $this->list_close[$x][$y] === false)
			{
				break;
			}

			$tmp = $this->list_close[$x][$y]->x;
			$y   = $this->list_close[$x][$y]->y;
			$x   = $tmp;
		}

		$this->path = array_reverse($this->path);

		return $this->path;
	}

	protected function getCost(int $x_start, int $y_start, int $x_finish, int $y_finish): int
	{
		$x_diff = (int) abs($x_start - $x_finish);
		$y_diff = (int) abs($y_start - $y_finish);

		return $x_diff + $y_diff;
	}

	/**
	 * @return stdClass
	 */
	protected function getNextNode(): \stdClass
	{
		$node = (object)
		[
			'g' => null, # g(n);        the cost of getting from the initial node to n
			'h' => null, # h(n);        the estimate, according to the heuristic function, of the cost of getting from n to the goal node
			'f' => null, # g(n) + h(n); intuitively, this is the estimate of the best solution that goes through n

			'x' => null,
			'y' => null
		];

		foreach ($this->list_open as $x => $array)
		{
			foreach ($array as $y => $data)
			{
				if ($data->g + $data->h <= $node->f or $node->f === null)
				{
					$node->x = $x;
					$node->y = $y;

					$node->g = $data->g;
					$node->h = $data->h;
					$node->f = $data->g + $data->h;
				}
			}
		}

		return $node;
	}

	protected function addToOpenList(int $x, int $y, $g, $h, int $x_neighbour = null, int $y_neighbour = null): bool
	{
		if (isset($this->list_close[$x][$y]))
		{
			return false;
		}

		$this->list_open[$x][$y] = (object)
		[
			'g' => $g, # g(n); the cost of getting from the initial node to n
			'h' => $h, # h(n); the estimate, according to the heuristic function, of the cost of getting from n to the goal node

			'x' => $x_neighbour,
			'y' => $y_neighbour
		];

		return true;
	}

	protected function moveToCloseList(int $x, int $y)
	{
		$this->list_close[$x][$y] = $this->list_open[$x][$y];

		unset($this->list_open[$x][$y]);

		if ($this->list_open[$x] === [])
		{
			unset($this->list_open[$x]);
		}
	}

	protected function isObstacle(int $x, int $y): bool
	{
		if ($x < 0 or $x > $this->map_width  - 1) { return true; }
		if ($y < 0 or $y > $this->map_height - 1) { return true; }

		if (!isset($this->map[$x][$y]))
		{
			return false;
		}

		return !$this->map[$x][$y];
	}

	protected function isClosed(int $x, int $y): bool
	{
		return isset($this->closedList[$x][$y]);
	}
}