php-libsystem-thread
====================

System Thread fork and run code in sub-processes.


```
<?php
require_once __DIR__ . '/vendor/autoload.php';

class SleepThread extends System_Thread
{
	private $sleep;
	
	public function __construct($sleep=3) {
		$this->sleep = $sleep;
	}
	
	public function run() {
		while ($this->sleep && !$this->isTerminated()) {
		    print "Sleep:".$this->sleep."\n";
		    $this->sleep -= 1;
		    sleep(1);
		};
	}
}


class ThreadListener implements Event_Observer
{
	public function update(Event_Observed $obj)
	{
		printf("%s > %s activeCount=%d\n", strftime("%Y-%m-%d %H:%M:%S", time()), "$obj", $obj->activeCount());
	}
}

class System_Thread_ManagerStrategy_Constant extends System_Thread_ManagerStrategy
{
	public function __construct($number)
	{
		$this->number = $number;
	}
	
	public function manage(System_Thread_Manager $manager)
	{
		return $this->number;
	}
}


$l = new ThreadListener();

$t1 = new SleepThread(4);
$t1->attach($l);

$t2 = new SleepThread(2);
$t2->attach($l);

$g = new System_Thread_Group();
$g->addThread($t1);
$g->addThread($t2);
$g->attach($l);

$m1 = new System_Thread_Manager($t1, new System_Thread_ManagerStrategy_Constant(6));
$m1->attach($l);

$m2 = new System_Thread_Manager($t2, new System_Thread_ManagerStrategy_Constant(2));
$m2->attach($l);

$mg = new System_Thread_Group();
$mg->addThread($m1);
$mg->addThread($m2);
$mg->attach($l);

$th = $mg;

pcntl_signal(SIGTERM, array($th, 'stop'));
pcntl_signal(SIGINT,  array($th, 'stop'));

$th->start();

while($th->isAlive()) { 
	echo "Active Thread " . $th->activeCount() . "\n";
	sleep(1);
}
```
