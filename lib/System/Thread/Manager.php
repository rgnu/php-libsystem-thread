<?php

/**
 *
 */
class System_Thread_Manager extends System_Thread_Group
{
	private  $threadPrototype;
	private  $managerStrategy;
	private  $_stopped;

	function __construct(System_Thread $thread, System_Thread_IManagerStrategy $managerStrategy)
	{
		$this->threadPrototype = $thread;
		$this->managerStrategy = $managerStrategy;
	}

	public function join()
	{
		if (!$this->_stopped) $this->updateThreads();
		parent::join();
	}

	public function start()
	{
		$this->_stopped = false;
		parent::start();
	}

	public function stop()
	{
		$this->_stopped = true;
		parent::stop();
	}

	private function updateThreads()
	{
		$active = $this->activeCount();
		$amount = $this->managerStrategy->manage($this);
		$num    = $amount - $active;

		if ($num > 0) {
			for($i=0; $i < $num; $i++) {
				$t = clone($this->threadPrototype);
				$t->start();
				if ($t->isAlive()) {
					$this->addThread($t);
				}
			}
		}
	}
}
