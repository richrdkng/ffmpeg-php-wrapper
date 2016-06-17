<?php

namespace FFMPEGWrapper\Data;

class FFMPEGDataFilter {

    private static function __is_regexp($mixed)
    {
        if (is_string($mixed)) {
            return preg_match($mixed, "") !== false;
        }

        return false;
    }

    /**
     * @param array $filters
     * @param mixed $data
     *
     * @return bool
     */
    private static function __checkFiltersAgainst(array $filters, $data)
    {
        if (count($filters) === 0) {
            return true;
        }

        foreach ($filters as $filterName => $filterValue) {
            if (is_string($filterValue)) {
                // is a regexp
                if (self::__is_regexp($filterValue)) {
                    if (! preg_match($filterValue, $data->{$filterName})) {
                        return false;
                    }

                // is a regular string
                } else if ($data->{$filterName} !== $filterValue) {
                    return false;
                }

            } else if (is_callable($filterValue)) {
                if ($filterValue->__invoke($data->{$filterName}) === false) {
                    return false;
                }
            }
        }

        return true;
    }

    /** @var array */
    private $_data = [];

    public function __construct($data = null)
    {
        if ($data !== null) {
            $this->_data = $data;
        }
    }

    public function addData($data)
    {
        $this->_data[] = $data;
    }

    public function getData()
    {
        return $this->_data;
    }

    public function numData()
    {
        return count($this->_data);
    }

    public function hasData()
    {
        return $this->numData() > 0;
    }

    /**
     * @param array|null $filters
     *
     * @return array
     */
    public function getFilteredData(array $filters = null)
    {
        $filtered = [];

        if ($filters === null || count($filters) === 0) {
            return $this->_data;
        }

        foreach ($this->_data as $data) {
            if (self::__checkFiltersAgainst($filters, $data)) {
                $filtered[] = $data;
            }
        }

        return $filtered;
    }

    /**
     * @param array|null $filters
     *
     * @return int
     */
    public function numFilteredData(array $filters = null)
    {
        return count($this->getFilteredData($filters));
    }

    /**
     * @param array|null $filters
     *
     * @return bool
     */
    public function hasFilteredData(array $filters = null)
    {
        return $this->numFilteredData($filters) > 0;
    }
}
