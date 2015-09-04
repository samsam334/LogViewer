<?php namespace Arcanedev\LogViewer\Entities;

use Carbon\Carbon;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;
use JsonSerializable;

/**
 * Class LogEntry
 * @package Arcanedev\LogViewer\Entities
 */
class LogEntry implements Arrayable, Jsonable, JsonSerializable
{
    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    /** @var string */
    public $level;

    /** @var Carbon */
    public $datetime;

    /** @var string */
    public $header;

    /** @var string */
    public $stack;

    /* ------------------------------------------------------------------------------------------------
     |  Constructor
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Construct the log entry instance.
     *
     * @param  string  $level
     * @param  string  $header
     * @param  string  $stack
     */
    public function __construct($level, $header, $stack)
    {
        $this->setLevel($level);
        $this->setHeader($header);
        $this->setStack($stack);
    }

    /* ------------------------------------------------------------------------------------------------
     |  Getters & Setters
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Set the entry level.
     *
     * @param  string  $level
     *
     * @return self
     */
    private function setLevel($level)
    {
        $this->level = $level;

        return $this;
    }

    /**
     * Set the entry header.
     *
     * @param  string  $header
     *
     * @return self
     */
    private function setHeader($header)
    {
        $this->header = $this->cleanHeader($header);
        $this->setDatetime(extract_datetime($header));

        return $this;
    }

    /**
     * Set the entry date time.
     *
     * @param  string  $datetime
     *
     * @return self
     */
    private function setDatetime($datetime)
    {
        $this->datetime = Carbon::createFromFormat(
            'Y-m-d H:i:s',
            $datetime
        );

        return $this;
    }

    /**
     * Set the entry stack.
     *
     * @param  string  $stack
     *
     * @return self
     */
    private function setStack($stack)
    {
        $this->stack = $stack;

        return $this;
    }

    /* ------------------------------------------------------------------------------------------------
     |  Check Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Check if same log level
     *
     * @param  string  $level
     *
     * @return bool
     */
    public function isSameLevel($level)
    {
        return $this->level === $level;
    }

    /* ------------------------------------------------------------------------------------------------
     |  Convert Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Get the log entry as an array.
     *
     * @return array
     */
    public function toArray()
    {
        return [
            'level'     => $this->level,
            'datetime'  => $this->datetime->format('Y-m-d H:i:s'),
            'header'    => $this->header,
            'stack'     => $this->stack
        ];
    }

    /**
     * Convert the log entry to its JSON representation.
     *
     * @param  int  $options
     *
     * @return string
     */
    public function toJson($options = 0)
    {
        return json_encode($this->toArray(), $options);
    }

    /**
     * Serialize the log entry object to json data
     *
     * @return array
     */
    public function jsonSerialize()
    {
        return $this->toArray();
    }

    /* ------------------------------------------------------------------------------------------------
     |  Other Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Clean the entry header.
     *
     * @param  string  $header
     *
     * @return string
     */
    private function cleanHeader($header)
    {
        return preg_replace('/\[' . REGEX_DATETIME_PATTERN . '\][ ]/', '', $header);
    }
}