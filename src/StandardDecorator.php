<?php
namespace PhilWaters\RelativeDate;

require_once __DIR__ . "/Decorator.php";

/**
 * Standard decorator for PhilWaters\RelativeDate
 *
 * @author Phil Waters <philip.waters@pb.com>
 */
class StandardDecorator implements Decorator
{
    /**
     * Stores default settings
     *
     * @var array
     */
    private static $defaults = array(
        "units" => array(
            "seconds" => "second",
            "minutes" => "minute",
            "hours"   => "hour",
            "days"    => "day",
            "months"  => "month",
            "years"   => "year",
            "decades" => "decade"
        ),
        "thresholds" => array(
            "seconds" => 5,
            "minutes" => 60,
            "hours"   => 3600,
            "days"    => 86400,
            "months"  => 2592000,
            "years"   => 31104000
        ),
        "format" => array(
            "past"   => "{{time}} ago",
            "future" => "{{time}} from now",
            "nowPast" => "just now",
            "nowFuture" => "now"
        )
    );

    /**
     * Stores keys in the oder in which they should be evaluated
     *
     * @var unknown
     */
    private static $keys = array(
        "decades",
        "years",
        "months",
        "days",
        "hours",
        "minutes",
        "seconds"
    );

    /**
     * Stores combined options (defaults and those passed to construct)or
     * @var unknown
     */
    private $options = array();

    /**
     * ReleaseDate constructor
     *
     * @param array $options Overridden options
     */
    public function __construct($options = array())
    {
        $this->options = static::getOptions($options);
    }

    /**
     * (non-PHPdoc)
     * @see \PhilWaters\RelativeDate\Decorator::format()
     */
    public function format($times)
    {
        $times = $times['relative'];
        $seconds = $times['seconds'];
        $absSeconds = abs($seconds);

        if (!empty($this->options['thresholds']['decades']) && !empty($times['years'])) {
            $times['decades'] = $times['years'] < 0 ? ceil($times['years'] / 10) : floor($times['years'] / 10);
        }

        foreach (static::$keys as $key) {
            if (empty($times[$key]) || empty($this->options['thresholds'][$key])) {
                continue;
            }

            $amount = $times[$key];
            $absAmount = abs($amount);

            if ($absSeconds >= $this->options['thresholds'][$key]) {
                $units = $this->options['units'][$key];

                if ($amount < 0) {
                    $format = $this->options['format']['future'];
                } else {
                    $format = $this->options['format']['past'];
                }

                if (is_array($units)) {
                    $units = $absAmount == 1 ? $units['singular'] : $units['plural'];
                } else {
                    $units .= $absAmount == 1 ? "" : "s";
                }

                return str_replace("{{time}}", "$absAmount $units", $format);
            }
        }

        return $seconds < 0 ? $this->options['format']['nowFuture'] :  $this->options['format']['nowPast'];
    }

    /**
     * Gets combined options
     *
     * @param array $options Overridden options
     *
     * @return array
     */
    private function getOptions($options)
    {
        $result = array();

        foreach (static::$defaults as $key => $option) {
            $result[$key] = $option;

            if (isset($options[$key])) {
                $result[$key] = array_merge($option, $options[$key]);
            }
        }

        return $result;
    }
}
