<?php

/**
 *
 */
interface System_Thread_IManagerStrategy {
	public function manage(System_Thread_Manager $manager);
}

/**
 *
 */
class System_Thread_ManagerStrategy implements System_Thread_IManagerStrategy
{
	public function manage(System_Thread_Manager $manager)
	{
		return 0;
	}
}
