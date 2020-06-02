<?php

class PriorityQueue
{
	protected $queue = [];

	public function enqueue(string $value, int $priority): void
	{
		if (!isset($this->queue[$priority]))
		{
			$this->queue[$priority] = [];
		}

		$this->queue[$priority][] = $value;
	}

	public function dequeue():? string
	{
		if (!$this->queue)
		{
			return null;
		}

		$priorities = array_keys($this->queue);

		natsort($priorities);

		$priority = end($priorities);

		$value = array_shift($this->queue[$priority]);

		if ($this->queue[$priority] === [])
		{
			unset($this->queue[$priority]);
		}

		return $value;
	}

	public function isEmpty(): bool
	{
		return (bool) $this->queue;
	}
}