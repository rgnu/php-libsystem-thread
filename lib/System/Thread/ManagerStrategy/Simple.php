<?php

/**
 *
 */
class System_Thread_ManagerStrategy_Simple extends System_Thread_ManagerStrategy
{
	private $minThreads;
	private $maxThreads;
	private $spareThreads;
	
	public function __construct($min, $max, $spare)
	{
		$this->minThreads      = $min;
		$this->maxThreads      = $max;
		$this->spareThreads    = $spare;
	}
	
	public function manage(System_Thread_Manager $manager)
	{
		$count = $manager->activeCount();

		if (($result = ($this->minThreads - $count)) > 0)
			$count += $result;
		elseif ($count < $this->maxThreads)
			$count += $this->spareThreads;

		return $count;
	}
}
