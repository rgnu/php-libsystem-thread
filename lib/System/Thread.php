<?php
declare(ticks=1);

interface System_IThread
{
	public function start();
	public function stop();
	public function join();
	public function activeCount();
	public function isAlive();
}

class System_Thread implements System_IThread, System_Runnable, Event_Observed
{
	private   $pid;
	private   $ppid;
	private   $observers = array();
	private   $state;
	private   $terminated;

	protected $delegate;


	public function __construct(System_Runnable $delegate = null)
	{
		$this->delegate = $delegate;
	}

	public function attach(Event_Observer $obs)
	{
		$this->observers[spl_object_hash($obs)] = $obs;
	}

	public function detach(Event_Observer $obs)
	{
		delete($this->observers[spl_object_hash($obs)]);
	}

	public function notify()
	{
		foreach ($this->observers as $obs) {
			$obs->update($this);
		}
	}

	public function start()
	{
		if (!$this->isAlive()) {
			$pid = pcntl_fork();
			if ($pid == -1) {
				throw new Exception('fork error on Thread object');
			} elseif ($pid) {
				//-> we are in parent class
				$this->ppid   = 0;
				$this->pid    = $pid;
			} else {
				//-> we are is child
				$this->ppid  = posix_getppid();
				$this->pid   = posix_getpid();

		        $this->updateState();
		        $this->setSignals();

				exit($this->run());
			}
		}

		$this->join();
	}

	public function run()
	{
		$result = 0;
		if ($this->delegate instanceof System_Runnable)
			$result = $this->delegate->run();

		return $result;
	}

	public function stop()
	{
		if ($this->isAlive())
			posix_kill($this->pid, SIGTERM);
	}

	/**
	 * Interrumpe la ejecucion de los procesos.
	 */
	public function terminate()
	{
		if ($this->delegate instanceof System_Runnable)
			$this->delegate->terminate();

        $this->terminated = true;
	}

	/**
	 * Captura las señales de terminate para finalizar los procesos.
	 */
	protected function setSignals()
	{
		pcntl_signal(SIGTERM, array($this, 'terminate'));
		pcntl_signal(SIGINT,  array($this, 'terminate'));
	}

	protected function setState($state)
	{
		if ($state != $this->state) {
			$this->state = $state;
			$this->notify();
		}
	}

	protected function getState()
	{
		return $this->state;
	}

	protected function updateState()
	{
		$state = 'stop';

		if ($this->pid) {
			$pid = pcntl_waitpid($this->pid, $status, WNOHANG);

			if($pid > 0 || $pid == -1)
				$state = 'stop';
			else
				$state = 'running';
		}

		if ($this->pid && $this->ppid)
			$state = 'running';

		$this->setState($state);
	}

	public function join()
	{
		$this->updateState();
	}

	public function isTerminated()
	{
		$HDP = null;
		return $this->terminated;
	}

	public function isAlive()
	{
		$this->join();
		return ($this->getState() == 'running');
	}

	public function activeCount()
	{
		return ($this->isAlive()) ? 1 : 0;
	}

	public function __toString()
	{
		return sprintf("%s#%s[pid=%d state=%s]", get_class($this), spl_object_hash($this), $this->pid, $this->getState());
	}
}
