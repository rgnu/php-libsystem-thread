<?php
/**
 * Unit Test for System_Thread
 *
 * @package Tests
 */
class System_Thread_GroupTest extends System_BaseTest
{
	/**
	 *
	 */
	function provider_class()
	{
		$data = array(
			array(
				'System_Thread_Group',
				'System_Thread',
				array(
					'start',
					'stop',
					'join',
					'activeCount',
					'isAlive'
				)
			)
		);
		return $data;
	}
	
	function test_isAliveAndActiveCount()
	{
		$sleep1 = 2;
		$sleep2 = 4;
		
		$t1 = new SleepThread($sleep1);
		$t2 = new SleepThread($sleep2);
		
		$listener = new ThreadListener();
		
		$groupThread  = new System_Thread_Group();
		$groupThread->addThread($t1);
		$groupThread->addThread($t2);
		
		$groupThread->attach($listener);
		
		$t1->attach($listener);
		$t2->attach($listener);

		$this->assertEqual($groupThread->isAlive(), false);
		$this->assertEqual($groupThread->activeCount(), 0);
		
		$groupThread->start();
		$this->assertEqual($groupThread->isAlive(), true);
		$this->assertEqual($groupThread->activeCount(), 2);
		
		sleep($sleep1+1);
		$this->assertEqual($groupThread->isAlive(), true);
		$this->assertEqual($groupThread->activeCount(), 1);

		sleep($sleep2-$sleep1+1);
		$this->assertEqual($groupThread->isAlive(), false);
		$this->assertEqual($groupThread->activeCount(), 0);
	}
}
?>