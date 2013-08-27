<?php
/**
 *
 */
class SleepThread extends System_Thread
{
	private $sleep;
	
	public function __construct($sleep=3) {
		$this->sleep = $sleep;
	}
	
	public function run() {
		while ($this->sleep) {
		    print "Sleep:".$this->sleep."\n";
		    $this->sleep -= 1;
		    sleep(1);
		};
	}
}

/**
 *
 */
class ThreadListener implements Event_Observer
{
	public function update(Event_Observed $obj)
	{
		printf("%s > %s activeCount=%d\n", strftime("%Y-%m-%d %H:%M:%S", time()), "$obj", $obj->activeCount());
	}
}

/**
 * Unit Test for System_Thread
 *
 * @package Tests
 */
class System_ThreadTest extends BaseTest
{
	/**
	 *
	 */
	function provider_class()
	{
		$data = array(
			array(
				'System_Thread',
				null,
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
		$sleep = 2;
		
		$sleepThread = new SleepThread($sleep);
		$sleepThread->attach(new ThreadListener());

		$this->assertEqual($sleepThread->isAlive(), false);
		$this->assertEqual($sleepThread->activeCount(), 0);
		
		$sleepThread->start();
		$this->assertEqual($sleepThread->isAlive(), true);
		$this->assertEqual($sleepThread->activeCount(), 1);
		
		sleep($sleep+1);
		$this->assertEqual($sleepThread->isAlive(), false);
		$this->assertEqual($sleepThread->activeCount(), 0);
	}
}
?>