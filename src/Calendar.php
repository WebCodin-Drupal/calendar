<?php
namespace App;

class Calendar
{
    private const MONTH_IN_YEAR = 13;
    private const DAYS_IN_MONTH_EVEN = 21;
    private const DAYS_IN_MONTH_ODD = 22;
    private const LEAP_YEAR = 5;
    private const START_POINT_YEAR = 1995;
    private const WEEK_DAYS = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
    private const DAYS_IN_WEEK = 7;

    /**
     * Calculate day of the week.
     * @throws LogicException
     */
    public function getWeekDate(IncomeDateInterface $date): ?string
    {
        $this->validateDateComponents($date->getDays(), $date->getMonths());
        $daysInCurrentYear = $this->getDaysInCurrentYear($date->getMonths(), $date->getDays());
        $daysOffset = $this->getWeekOffset($date->getYears());
        $weekPosition = ($daysInCurrentYear + $daysOffset) % self::DAYS_IN_WEEK;
        return self::WEEK_DAYS[$weekPosition] ?? null;
    }

    /**
     * Return count of days in current year.
     */
    private function getDaysInCurrentYear(int $month, int $day): ?int
    {
        $days = 0;
        for ($i = 1; $i < $month; $i++) {
            $days = $i % 2 === 0 ? $days + self::DAYS_IN_MONTH_EVEN : $days + self::DAYS_IN_MONTH_ODD;
        }

        return $days + $day;
    }

    /**
     * Return count of days in year range.
     */
    private function getWeekOffset(int $year): ?int
    {
        $result = ((self::START_POINT_YEAR - $year) / self::LEAP_YEAR) % self::DAYS_IN_WEEK - 1;
        return $result < 0 ? self::DAYS_IN_WEEK + $result : $result;
    }

    /**
     * Validate date components.
     * @throws LogicException
     */
    private function validateDateComponents(int $days, int $months)
    {
        if (!$this->validateDayComponent($days, $months)) {
            throw new LogicException(
                'Wrong day component value.'
            );
        }
        if (!$this->validateMonthComponent($months)) {
            throw new LogicException(
                'Wrong month component value.'
            );
        }
    }

    /**
     * Validate incoming day.
     */
    private function validateDayComponent(int $days, int $months): ?bool
    {
        return ($days && $days <= (($months % 2 == 0) ? self::DAYS_IN_MONTH_EVEN : self::DAYS_IN_MONTH_ODD));
    }

    /**
     * Validate incoming month.
     */
    private function validateMonthComponent(int $months): ?bool
    {
        return ($months && $months <= self::MONTH_IN_YEAR);
    }
}

interface ExceptionInterface { }
class LogicException extends \LogicException implements ExceptionInterface { }

interface IncomeDateInterface
{
    public function getDays(): int;
    public function getMonths(): int;
    public function getYears(): int;
}

class IncomeDate implements IncomeDateInterface
{
    private const DELIMITER = '.';

    /**
     * @var int
     */
    private $days;

    /**
     * @var int
     */
    private $months;

    /**
     * @var int
     */
    private $years;

    /**
     * @throws LogicException
     */
    public function __construct(string $date)
    {
        list($days, $months, $years) = explode(self::DELIMITER, $date);
        if (!isset($days, $months, $years)) {
            throw new LogicException('Invalid date format: '.$date);
        }
        $this->days = $days;
        $this->months = $months;
        $this->years = $years;
    }

    public function getDays(): int
    {
        return $this->days;
    }

    public function getMonths(): int
    {
        return $this->months;
    }

    public function getYears(): int
    {
        return $this->years;
    }
}


$calendar = new Calendar();
try {
    echo $calendar->getWeekDate(new IncomeDate('17.11.2013'));
} catch (LogicException $e) {
}