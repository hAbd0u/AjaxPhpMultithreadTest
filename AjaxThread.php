<?php

    require_once __DIR__ . '/vendor/autoload.php';


    use \ByJG\PHPThread\ThreadPool as ScrapperPool;

    if(isset($_POST['get-data']) && $_POST['get-data'] === 'all') {
        try {
            // Start a pool of threads
            $scrapper_pool = new ScrapperPool();
            for($i = 0; $i < 10; ++$i) {
                    $rfc_scrapper = new CountyForclosureScrapper($i);
                    $scrapClosure = function(CountyForclosureScrapper $instance) { 
                        return $instance->scrap(); 
                    };
                    $scrapper_pool->queueWorker($scrapClosure, [$rfc_scrapper]);
            }

            echo json_encode( [ 'status' => 'test', 'description' => 'AJAX test.', 'data' => 'This is a response from line: ' . __LINE__ ] );
            $scrapper_pool->startPool();
            $scrapper_pool->waitWorkers();
    
            // Get results
            ob_start();
            $i = 0;
            foreach($scrapper_pool->getThreads() as $worker) {
                $result = $scrapper_pool->getThreadResult($worker);
                file_put_contents( sprintf("%'.03d", $i), $result . ' ' . count($scrapper_pool->getThreads()));
                ++$i;
                echo str_pad($result . '|', 4096, "\n");
                ob_flush();
                flush();
                sleep(1);
            }

            ob_end_flush();
        }
        catch(Exception $e) {
            echo $e->getMessage();
        }
    }

    class CountyForclosureScrapper {
        private $thread_id = -1;

        public function __construct($thread_id)
        {
            $this->thread_id = $thread_id;
        }

        public function scrap() 
        {
            return 'This is thread id: ' . $this->thread_id;
        }
    }
?>
