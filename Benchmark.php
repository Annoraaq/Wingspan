<?php

namespace Wingspan;

/**
 * Description of Benchmark
 *
 * @author J. Baum
 */
abstract class Benchmark
{
    public function createDb() {}
    
    public function preBench() {}
    
    public function postBench() {}
    
    public abstract function benchInserts($limit);
    
    public abstract function benchSelect($limit);
    
    public abstract function benchUpdate($limit);
    
    public abstract function benchDelete($limit);
}
