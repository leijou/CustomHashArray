<?php

/**
 * Custom hashed associative arrays
 * 
 * Key access is controlled by a hash. When accessing the array's keys they will
 * be returned as the original key that defined them.
 * 
 * If an existing hash is overwritten with a different key the new key will be
 * used when returning key names.
 * 
 * @author Stephen Hawkes
 * @link https://github.com/leijou
 */
abstract class CustomHashArray implements \ArrayAccess, \Iterator, \Countable {
    
    /**
     * @var mixed[mixed] Contained array with original keys
     */
    private $container;
    
    /**
     * @var mixed[string] Map custom hash key to original key on container
     */
    private $map;
    
    /**
     * Method to convert orginal key to custom hash key
     * 
     * @param $key mixed
     * @return mixed
     */
    abstract protected function hash($key);
    
    /**
     * Create new blank array or convert from existing standard array
     * 
     * @param mixed[mixed] Optional array to instantiate as
     */
    public function __construct(Array $array=array()) {
        $this->container = $array;
        
        $this->map = array();
        foreach (array_keys($array) as $key) {
            $this->map[$this->hash($key)] = $key;
        }
    }
    
    public function offsetSet($offset, $value) {
        if (is_null($offset)) {
            $this->container[] = $value;
            end($this->container);
            $offset = key($this->container);
        } else {
            if ($this->offsetExists($offset)) {
                $this->offsetUnset($offset);
            }
            $this->container[$offset] = $value;
        }
        
        $this->map[$this->hash($offset)] = $offset;
    }
    
    public function offsetExists($offset) {
        return isset($this->map[$this->hash($offset)]);
    }
    
    public function offsetUnset($offset) {
        $key = $this->map[$this->hash($offset)];
        unset($this->container[$key]);
        unset($this->map[$this->hash($offset)]);
    }
    
    public function offsetGet($offset) {
        $key = $this->map[$this->hash($offset)];
        return $this->container[$key];
    }
    
    public function rewind() {
        return reset($this->container);
    }
    
    public function current() {
        return current($this->container);
    }
    
    public function key() {
        return key($this->container);
    }
    
    public function next() {
        return next($this->container);
    }
    
    public function valid() {
        return !is_null(key($this->container));
    }
    
    public function count() {
        return count($this->container);
    }
    
}
