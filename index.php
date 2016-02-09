<?php
namespace Wingspan;

require_once 'Autoloader.php';
require_once 'MySqlBenchmark.php';
require_once 'PdbBenchmark.php';
require_once 'ParrotDb/Core/PAutoloader.php';


ini_set('display_errors', 1);

$autoloader = new Autoloader('/', __DIR__ . "/../");
$autoloader->register();

$autoloader2 = new \ParrotDb\Core\PAutoloader('/', __DIR__ . "/");
$autoloader2->register();


$mySqlBenchmark = new MySqlBenchmark();
////$mySqlBenchmark->createDb();
//$mySqlBenchmark->preBench();
//$mySqlBenchmark->benchInserts(800);
//$mySqlBenchmark->postBench();

//$mySqlBenchmark->preBench();
//$mySqlBenchmark->benchSelect(1000);
//$mySqlBenchmark->postBench();
//
//$mySqlBenchmark->preBench();
//$mySqlBenchmark->benchUpdate(1000);
//$mySqlBenchmark->postBench();
//
//$mySqlBenchmark->preBench();
//$mySqlBenchmark->benchDelete(1000);
//$mySqlBenchmark->postBench();

$pdbBenchmark = new PdbBenchmark();
$pdbBenchmark->createDb();
//$pdbBenchmark->preBench();
//$pdbBenchmark->benchInserts(1000);
//$pdbBenchmark->postBench();

//$pdbBenchmark->createDb();
//$pdbBenchmark->preBench();
//$pdbBenchmark->benchSelect(1000);
//$pdbBenchmark->postBench();

//$pdbBenchmark->createDb();
//$pdbBenchmark->preBench();
//$pdbBenchmark->benchUpdate(1000);
//$pdbBenchmark->postBench();

$pdbBenchmark->preBench();
$pdbBenchmark->benchDelete(1000);
$pdbBenchmark->postBench();
