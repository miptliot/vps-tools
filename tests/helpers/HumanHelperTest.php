<?php

	namespace tests\helpers;

	use vps\tools\helpers\HumanHelper;

	class HumanHelperTest extends \PHPUnit\Framework\TestCase
	{
		public function testBitrate ()
		{
			$this->assertNull(HumanHelper::bitrate(null));
			$this->assertNull(HumanHelper::bitrate('asdasd'));
			$this->assertNull(HumanHelper::bitrate(-100));

			$this->assertEquals('0 b/s', HumanHelper::bitrate(0));
			$this->assertEquals('100 b/s', HumanHelper::bitrate(100));
			$this->assertEquals('0 kb/s', HumanHelper::bitrate(100, 'kb/s'));
			$this->assertEquals('1 kb/s', HumanHelper::bitrate(1000));
			$this->assertEquals('5 kb/s', HumanHelper::bitrate(5000));
			$this->assertEquals('5 mb/s', HumanHelper::bitrate(5000000));
		}

		public function testCurrency ()
		{

			$this->assertEquals('100', HumanHelper::currency(100));
			$this->assertEquals('1 000', HumanHelper::currency(1000));
			$this->assertEquals('32 562 414', HumanHelper::currency(32562414));
			$this->assertEquals('12 542p', HumanHelper::currency(12542, 'p'));
			$this->assertEquals('12 542$', HumanHelper::currency(12542, '$'));
		}

		public function testDuration ()
		{
			$this->assertNull(HumanHelper::duration(null));
			$this->assertNull(HumanHelper::duration('dasdas'));

			$this->assertEquals('00:01:40.000', HumanHelper::duration(100));
			$this->assertEquals('00:01:40.123', HumanHelper::duration(100.123));
			$this->assertEquals('03:58:43.000', HumanHelper::duration('14323'));
			$this->assertEquals('03:58:43.120', HumanHelper::duration('14323.12'));
			$this->assertEquals('-00:01:40.000', HumanHelper::duration(-100));
		}

		public function testMaxUpload ()
		{
			$this->assertNotEmpty(HumanHelper::maxUpload());
		}

		public function testSize ()
		{
			$this->assertNull(HumanHelper::size(null));
			$this->assertNull(HumanHelper::size('adasd'));
			$this->assertNull(HumanHelper::size(-100));

			$this->assertEquals('0 B', HumanHelper::size(0));
			$this->assertEquals('100 B', HumanHelper::size(100));
			$this->assertEquals('0 KB', HumanHelper::size(100, 'KB'));
			$this->assertEquals('2 KB', HumanHelper::size(2050));
			$this->assertEquals('0 MB', HumanHelper::size(2050, 'MB'));
			$this->assertEquals('10 MB', HumanHelper::size('10685760'));
			$this->assertEquals('0 GB', HumanHelper::size('10685760', 'GB'));
			$this->assertEquals('10 GB', HumanHelper::size('10937418240'));
		}
	}