<?php

require_once __DIR__.'./../../src/Calendar.php';

class CalendarTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider testDataProvider
     */
    public function testDates(string $date , string $expected)
    {
        $calendar = new \App\Calendar();
        $this->assertSame($calendar->getWeekDate(new \App\IncomeDate($date)), $expected);
    }

    public function testDataProvider()
    {
        return [
            'case_0' => [
                'date' => '01.01.1990',
                'expected' => 'Mon'
            ],
            'case_1' => [
                'date' => '08.13.1990',
                'expected' => 'Sun'
            ],
            'case_2' => [
                'date' => '21.13.1989',
                'expected' => 'Sat'
            ],
            'case_3' => [
                'date' => '01.01.1999',
                'expected' => 'Mon'
            ],
        ];
    }

}
