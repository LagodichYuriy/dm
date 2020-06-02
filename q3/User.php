<?php

class User
{
	public $id;

	public $country; # I assume, one user_id belongs to one country at the same moment of time

	/** @var UserEvent[] */
	protected $events = [];

	public $level_max = -INF;
	public $level_status;

	public $spent = 0.;

	public function __construct(string $user_id, string $country)
	{
		$this->id      = $user_id;
		$this->country = $country;
	}

	public function addEvent(UserEvent $event)
	{
		if ((mb_substr($event->id, 0, mb_strlen('LEVEL_')) === 'LEVEL_')) # begins with
		{
			list($garbage, $level, $level_status) = explode('_', $event->id);

			if ($this->level_max < $level)
			{
				$this->level_max = $level;
				$this->level_status = $level_status;
			}
			else if ($this->level_max == $level and $this->level_status == 'START' and $level_status == 'COMPLETE')
			{
				$this->level_status = $level_status;
			}
		}

		$this->spent += (int) $event->amount;

		$this->events[$event->id] = $event;
	}
}