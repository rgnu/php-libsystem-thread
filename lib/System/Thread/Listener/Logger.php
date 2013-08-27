<?php

class System_Thread_Listener_Logger implements Event_Observer
{
    private $_logger;
    
    public function __construct()
    {
      $this->_logger = Logger::instance();
    }
    
	public function update(Event_Observed $obj)
	{
		$this->_logger->info(sprintf("%s > %s activeCount=%d\n", strftime("%Y-%m-%d %H:%M:%S", time()), "$obj", $obj->activeCount()));
	}
}
