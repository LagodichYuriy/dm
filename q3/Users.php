<?php

class Users
{
	/** @var User[] */
	protected $users = [];

	public function __construct(string $csv_path = null)
	{
		if (func_num_args())
		{
			static::loadCSV($csv_path);
		}
	}

	public function loadCSV(string $csv_path): int
	{
		$loaded = 0;


		$lines = array_map('str_getcsv', file($csv_path));

		array_shift($lines);

		foreach ($lines as $line)
		{
			list($user_id, $country, $time, $event_id, $amount) = $line;

			if (!isset($this->users[$user_id]))
			{
				$this->users[$user_id] = new User($user_id, $country);

				$loaded++;
			}

			$event = new UserEvent($event_id, $time, $amount);

			$this->users[$user_id]->addEvent($event);
		}

		return $loaded;
	}

	public function solveFirst(): int
	{
		# 1) what percentage of users are completing LEVEL 5?

		$count = 0;

		foreach ($this->users as $user)
		{
			if ($user->level_max == 5 and $user->level_status == 'START')
			{
				$count++;
			}
		}


		$user_count = count($this->users);

		if ($user_count)
		{
			return round($count * 100 / $user_count, 4);
		}

		return 0; # prevent division by zero
	}

	public function solveSecond(): array
	{
		# 2) what's the average revenue per user, split by country?

		$countries = [];

		foreach ($this->users as $user)
		{
			if (!isset($countries[$user->country]))
			{
				$countries[$user->country] = [];
			}


			$countries[$user->country][$user->id] = $user->spent;
		}


		$result = [];

		foreach ($countries as $country => $user_spends)
		{
			$result[$country] = array_sum($user_spends) / count($user_spends);
		}

		return $result;
	}

	public function solveThird(): array
	{
		$users = [];

		foreach ($this->users as $user)
		{
			if (($user->level_max == 5 and $user->level_status == 'COMPLETE') or $user->level_max >= 6)
			{
				$users[] = $user;
			}
		}

		return $users;
	}
}