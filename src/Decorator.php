<?php
namespace PhilWaters\RelativeDate;

/**
 * Decorator for use with RelativeDate
 *
 * @author Phil Waters <philip.waters@pb.com>
 */
interface Decorator
{
    /**
     * Formats times into a relative date string
     *
     * @param unknown $times
     */
    public function format($times);
}
