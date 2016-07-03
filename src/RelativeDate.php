<?php
namespace PhilWaters\RelativeDate;

/**
 * Converts a UNIX epoch timestamp into a relative date string (i.e. 15 minutes ago)
 * @author Phil Waters <philip.waters@pb.com>
 *
 */
class RelativeDate
{
    /**
     * Number of seconds in a year (based on 365 days in a year)
     *
     * @var number
     */
    const SECONDS_IN_YEAR = 31536000; // 365 days

    /**
     * Number of seconds in month (based on 30 days in a month_
     *
     * @var number
     */
    const SECONDS_IN_MONTH = 2592000; // 30 days

    /**
     * Number of seconds in a day
     *
     * @var number
     */
    const SECONDS_IN_DAY = 86400;

    /**
     * Number of seconds in an hour
     *
     * @var number
     */
    const SECONDS_IN_HOUR = 3600;

    /**
     * Number of seconds in a minute
     *
     * @var number
     */
    const SECONDS_IN_MINUTE = 60;

    /**
     * Converts timestamp into relative date string
     *
     * @param Decorator $decorator Decorator to format string
     * @param number    $timestamp UNIX epoch timestamp
     * @param number    $now       Current UNIX epoch timestamp (optional, defaults to time())
     *
     * @return string
     */
    public function get(Decorator $decorator, $timestamp, $now = null)
    {
        if ($now === null) {
            $now = time();
        }

        if (!is_numeric($timestamp)) {
            throw new \Exception("Timestamp is invalid");
        }

        $times = $this->parse($now - $timestamp);

        return $decorator->format($times);
    }

    /**
     * Parses UNIX epoch timestamp into relative amounts
     *
     *
     * @param number $seconds UNIT epoch timestamp
     * @return string
     */
    protected function parse($seconds)
    {
        if (!is_numeric($seconds)) {
            $seconds = 0;
        }

        $totalSeconds = $seconds;
        $totalYears = $this->getTotalYears($totalSeconds);
        $totalMonths = $this->getTotalMonths($totalSeconds);
        $totalDays = $this->getTotalDays($totalSeconds);
        $totalHours = $this->getTotalHours($totalSeconds);
        $totalMinutes = $this->getTotalMinutes($totalSeconds);

        $minutes = $this->round($seconds / static::SECONDS_IN_MINUTE);
        $hours = $this->round($seconds / static::SECONDS_IN_HOUR);
        $days = $this->round($seconds / static::SECONDS_IN_DAY);
        $months = $totalMonths + (12 * $totalYears);
        $years = $totalYears;

        return array(
            "total" => array(
                "seconds" => $totalSeconds,
                "minutes" => $totalMinutes,
                "hours" => $totalHours,
                "days" => $totalDays,
                "months" => $totalMonths,
                "years" => $totalYears
            ),
            "relative" => array(
                "seconds" => $seconds,
                "minutes" => $minutes,
                "hours" => $hours,
                "days" => $days,
                "months" => $months,
                "years" => $years
            )
        );
    }

    /**
     * Rounds positive numbers down (floor) and negative number up (ceil)
     *
     * @param number $number To to round
     *
     * @return number
     */
    private function round($number)
    {
        return $number < 0 ? ceil($number) : floor($number);
    }

    /**
     * Get the total number of years
     *
     * @param number $seconds Number of seconds to convert to number of years
     * @return number
     */
    private function getTotalYears(&$seconds)
    {
        $years = $this->round($seconds / static::SECONDS_IN_YEAR);
        $seconds -= $years * static::SECONDS_IN_YEAR;

        return $years;
    }

    /**
     * Get the total number of months
     *
     * @param number $seconds Number of seconds to convert to number of years
     * @return number
     */

    private function getTotalMonths(&$seconds)
    {
        $months = $this->round($seconds / static::SECONDS_IN_MONTH);
        $seconds -= $months * static::SECONDS_IN_MONTH;

        return $months;
    }

    /**
     * Get the total number of days
     *
     * @param number $seconds Number of seconds to convert to number of years
     *
     * @return number
     */

    private function getTotalDays(&$seconds)
    {
        $days = $this->round($seconds / static::SECONDS_IN_DAY);
        $seconds -= $days * static::SECONDS_IN_DAY;

        return $days;
    }

    /**
     * Get the total number of hours
     *
     * @param number $seconds Number of seconds to convert to number of years
     *
     * @return number
     */

    private function getTotalHours(&$seconds)
    {
        $hours = $this->round($seconds / static::SECONDS_IN_HOUR);
        $seconds -= $hours * static::SECONDS_IN_HOUR;

        return $hours;
    }

    /**
     * Get the total number of minutes
     *
     * @param number $seconds Number of seconds to convert to number of years
     *
     * @return number
     */

    private function getTotalMinutes(&$seconds)
    {
        $minutes = $this->round($seconds / static::SECONDS_IN_MINUTE);
        $seconds -= $minutes * static::SECONDS_IN_MINUTE;

        return $minutes;
    }
}
