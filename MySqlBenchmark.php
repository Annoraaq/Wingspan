<?php

namespace Wingspan;

require_once 'Benchmark.php';

/**
 * Description of MySqlBenchmark
 *
 * @author J. Baum
 */
class MySqlBenchmark extends Benchmark
{

    private $config;
    private $mysqli;

    public function __construct()
    {
        $this->config = array(
         'host' => 'localhost',
         'username' => 'root',
         'password' => '',
         'database' => 'wingspan',
        );
    }

    private function open()
    {
        $this->mysqli = new \mysqli($this->config['host'],
         $this->config['username'], $this->config['password'],
         $this->config['database']);
        
    }

    private function close()
    {
        $this->mysqli->close();
    }
    
    private function query($query) {
        $this->open();
        $res = $this->mysqli->query($query);
        $this->close();

        return $res;
    }
    
    private function querySingle($query) {
        $res = $this->query($query);

        if (!$res) {
            return false;
        }
        
        if ($res->num_rows <= 0) {
            return false;
        }
        $obj = $res->fetch_object();
        $res->close();
        return $obj;
    }

    private function queryArray($query) {
        $res = $this->query($query);
        $return = array();
        while ($entry = $res->fetch_object()) {
            $return[] = $entry;
        }

        $res->close();

        return $return;
    }
    
    public function createDb()
    {
        $this->query('SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";'
            . 'SET time_zone = "+00:00";'
            . 'CREATE TABLE `birds` ('
            . '`id` int(11) NOT NULL,'
            . '`name` varchar(128) COLLATE utf8_unicode_ci NOT NULL'
            . ') ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;'
        );
    }

    public function preBench() {
        $this->query("TRUNCATE TABLE birds;");
        $this->query("FLUSH TABLES;");
    }
     
    
    public function benchInserts($limit)
    {
        
        $startTime = time();
        $this->open();
        $name = "Pelecanidae";
        for ($i=0; $i<$limit; $i++) {
           $this->mysqli->query("INSERT INTO birds (id,name) VALUES ($i, '$name');");
        }
        $this->close();
        $endTime = time();
        
        echo "MySQL benchInserts($limit): " . ($endTime-$startTime) . "s<br />"; 
    }
    
    public function benchSelect($limit) {
        $this->benchInserts($limit);
        
        $startTime = time();
        $this->open();
        for ($i=0; $i<$limit; $i++) {
            $this->mysqli->query("SELECT * FROM birds WHERE id = $i");
        }
        $this->close();
        $endTime = time();
        
        echo "MySQL benchSelect($limit): " . ($endTime-$startTime) . "s<br />"; 
    }
    
    public function benchUpdate($limit) {
        $this->benchInserts($limit);
        
        $startTime = time();
        $this->open();
        for ($i=0; $i<$limit; $i++) {
            $this->mysqli->query("UPDATE birds SET id = (id+1) WHERE id = $i");
        }
        $this->close();
        $endTime = time();
        
        echo "MySQL benchUpdate($limit): " . ($endTime-$startTime) . "s<br />"; 
    }
    
    public function benchDelete($limit) {
        $this->benchInserts($limit);
        
        $startTime = time();
        $this->open();
        for ($i=0; $i<$limit; $i++) {
            $this->mysqli->query("DELETE FROM birds WHERE id = $i");
        }
        $this->close();
        $endTime = time();
        
        echo "MySQL benchDelete($limit): " . ($endTime-$startTime) . "s<br />"; 
    }

}
