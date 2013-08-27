<?php
/**
 *
 */

/**
 *
 */
interface Event_Observer
{
     public function update(Event_Observed $observed);
}

/**
 *
 */
interface Event_Observed
{
     public function attach(Event_Observer $observer);
 
     public function detach(Event_Observer $observer);
 
     public function notify();
}
