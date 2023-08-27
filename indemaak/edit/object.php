<?php

class MyClass
{
	public $property = 'default';

	const TEST = 'test';

	function MyClass($p)
	{
		$this->property = $p;
	}

	public function echoSomething()
	{
		echo self::TEST;
	}

}

$blah = new MyClass('nietDefault');
//$blah->property = 'nietDefault';

echo $blah->property;

$blah->echoSomething();

?>