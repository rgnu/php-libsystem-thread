<?php

class System_Thread_Group extends System_Thread
{
	private $poolThreads = array();

	public function __construct()
	{
	}

	public function addThread(System_Thread $thread)
	{
		$this->poolThreads[spl_object_hash($thread)] = $thread;
	}

	public function removeThread(System_Thread $thread)
	{
		if (!$thread->isAlive()) {
			unset($this->poolThreads[spl_object_hash($thread)]);
		}
	}

	public function start()
	{
		foreach($this->poolThreads as $thread) {
			$thread->start();
		}
	}

	public function stop()
	{
		foreach($this->poolThreads as $thread) {
			$thread->stop();
		}
	}

	protected function updateState()
	{
		$state = 'stop';

		foreach($this->poolThreads as $thread) {
			if ($thread->isAlive()) {
				$state = 'running';
			}
		}

		$this->setState($state);
	}

	public function activeCount() {
		$count = 0;

		foreach($this->poolThreads as $thread) {
			$count += $thread->activeCount();
		}

		return $count;
	}
}
