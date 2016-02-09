<?php


namespace Wingspan;

use \Wingspan\schema;

require_once 'Benchmark.php';

/**
 * Description of PdbBenchmark
 *
 * @author J. Baum
 */
class PdbBenchmark extends Benchmark
{
    
    private $session;
    private $pm;
    
    public function createDb() {
        
        if (file_exists("pdb/Testfile/Testfile.pdb")) {
            unlink("pdb/Testfile/Testfile.pdb");
        }
        
        if (file_exists("pdb/Testfile/Wingspan-schema-Bird.pdb")) {
            unlink("pdb/Testfile/Wingspan-schema-Bird.pdb");
        }
        
        $this->session = \ParrotDb\Core\PSessionFactory::createSession("Testfile", \ParrotDb\Core\PSession::DB_XML);
        $this->pm = $this->session->createPersistenceManager();
    }
    
    public function benchInserts($limit)
    {
        $startTime = time();
        $name = "Pelecanidae";
        for ($i = 0; $i < $limit; $i++) {
            $bird = new \Wingspan\schema\Bird();
            $bird->id = $i;
            $bird->name = $name;
            $this->pm->persist($bird);
        }
        
        $this->pm->commit();
        $endTime = time();

        echo "ParrotDb benchInserts($limit): " . ($endTime-$startTime) . "s<br />"; 
    }

    public function benchSelect($limit)
    {
        $this->benchInserts($limit);
        $parser = new \ParrotDb\Query\LotB\Parser\Parser($this->session->getDatabase());
        $startTime = time();
        for ($i=0; $i<$limit; $i++) {
            $constraint = $parser->parse('get Wingspan\schema\Bird id = ' . $i);
            $this->pm->query($constraint);
        }
        $endTime = time();
        
        echo "ParrotDb benchSelect($limit): " . ($endTime-$startTime) . "s<br />"; 
    }
    
    public function postBench() {
     \ParrotDb\Core\PSessionFactory::closeSession("Testfile");
    }
    
    public function benchUpdate($limit) {
        $this->benchInserts($limit);
        $startTime = time();
        $parser = new \ParrotDb\Query\LotB\Parser\Parser($this->session->getDatabase());
        for ($i=0; $i<$limit; $i++) {
            $constraint = $parser->parse('get 1 Wingspan\schema\Bird id = ' . $i);
            $res = $this->pm->query($constraint);
            $first = $res->first();
            $first->id++;
        }
        $this->pm->commit();
        $endTime = time();
        
        echo "ParrotDb benchUpdate($limit): " . ($endTime-$startTime) . "s<br />"; 
    }
    
    public function benchDelete($limit) {
        $this->benchInserts($limit);
        $startTime = time();
        $parser = new \ParrotDb\Query\LotB\Parser\Parser($this->session->getDatabase());
        for ($i=0; $i<$limit; $i++) {
            $constraint = $parser->parse('get Wingspan\schema\Bird id = ' . $i);
            $this->pm->delete($constraint);
        }
        $endTime = time();
        
        echo "ParrotDb benchDelete($limit): " . ($endTime-$startTime) . "s<br />"; 
    }

}
