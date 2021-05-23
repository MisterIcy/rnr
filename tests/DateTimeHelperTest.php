<?php


namespace MisterIcy\RnR\Tests;


use MisterIcy\RnR\DateTimeHelper;
use PHPUnit\Framework\TestCase;

class DateTimeHelperTest extends TestCase
{
    /**
     * @test
     */
    public function testDayDifference() : void {

		$startDate = \DateTime::createFromFormat('Y-m-d', '2021-05-28');
		$endDate = \DateTime::createFromFormat('Y-m-d', '2021-05-31');
		$days = DateTimeHelper::calculateBusinessDays($startDate, $endDate);
        $this->assertEquals(2, $days);
    }
}
