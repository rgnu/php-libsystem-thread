<?php

class System_BaseTest extends PHPUnit_Framework_TestCase
{
	function assertEqual($v1, $v2, $message = null)
	{
		return $this->assertEquals($v1, $v2, $message);
	}

	function assertClassExists($class, $message = null)
	{
		if (is_null($message)) $message = "Class [$class] not exists";
		return $this->assertTrue(class_exists($class), $message);
	}

	function assertIsA($object, $class, $message = null)
	{
		if (is_null($message)) $message = "Class [" . get_class($object) . "] should be [$class]";
		return $this->assertTrue(is_a($object, $class), $message);
	}

	function assertIsSubclassOf($object, $class, $message = null)
	{
		if (is_null($message)) $message = "Class [" . get_class($object) . "] should be subclass of [$class]";
		return $this->assertTrue(is_subclass_of($object, $class), $message);
	}

	function assertMethodExists($object, $methods, $message = null)
	{
		$methods = is_array($methods) ? $methods : array($methods);

		if (is_null($message)) $message = "Object [" . get_class($object) . "] should be method [%s]";

		foreach ($methods as $method) {
		    $this->assertTrue(method_exists($object, $method), sprintf($message, $method));
		}
	}

	/**
	 * @dataProvider provider_class
	 */
	function test_class($class=null, $parent=null, $methods=null)
	{
		if (isset($this->test_class)) {
			$class   = $this->test_class['class'];
			$parent  = $this->test_class['subclass'];
			$methods = $this->test_class['methods'];
		}

		if($class) {
			$this->assertClassExists($class);
			$obj =& new $class();
			$this->assertIsA($obj, $class);

			if ($parent)
				$this->assertIsSubclassOf($obj, $parent);

			if ($methods)
				$this->assertMethodExists($obj, $methods);
		}
	}

	/**
	 *
	 */
	 function provider_class()
	 {
	 	return null;
	 }
}

?>