<?php
namespace Collection;

class Collection extends \ArrayIterator implements \ArrayAccess, \Countable, \Iterator {

    protected $_array = array();

    protected $_entityClass;

    public function __construct($dataLimit = null, $dataOffset = null) {
        if (!is_null($dataOffset))
            $this->dataOffset = intval($dataOffset);

        if (!is_null($dataLimit))
            $this->dataLimit = intval($dataLimit);
    }

    public function offsetSet($offset, $value) {
        if (!is_object($value)) {
            throw new Collection_ValueIsNotObject_Exception();
        }

        if (!($value instanceof $this->entityClass)) {
            throw new Collection_WrongClassOfEntity_Exception();
        }

        if (is_null($offset)){
            $this->_array[] = $value;
        } else {
            $this->_array[$offset] = $value;
        }
    }

    public function offsetUnset($offset) {
        unset($this->_array[$offset]);
    }

    public function offsetGet($offset) {
        return isset($this->_array[$offset]) ? $this->_array[$offset] : null;
    }

    public function offsetExists($offset) {
        return isset($this->_array[$offset]);
    }

    function rewind() {
        return reset($this->_array);
    }

    function current() {
        return current($this->_array);
    }

    function key() {
        return key($this->_array);
    }

    function next() {
        next($this->_array);
    }

    function valid() {
        return current($this->_array)===false?false:true;
    }

    function count() {
        return count($this->_array);
    }
}


class Collection_ValueIsNotObject_Exception extends \Exception {
}

class Collection_WrongClassOfEntity_Exception extends \Exception {
}