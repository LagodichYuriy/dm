<?php

class UserEvent
{
	public $id;
	public $time;
	public $amount;

	public function __construct($event_id, $time, $amount)
	{
		$this->id      = $event_id;
		$this->time    = $time;
		$this->amount  = $amount;
	}
}