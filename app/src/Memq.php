<?php
	
    require_once '/global/app/bootstrap.php';

	if(getenv(MEMQ_POOL) !== false) define('MEMQ_POOL', 'locahost:11211');
	if(getenv(MEMQ_TTL) !== false) define('MEMQ_TTL', 0);

	class MEMQ {
		
        const ENABLE_LOG = false;

		private static $mem = NULL;
		
		private function __construct() {}
		
		private function __clone() {}
		
		private static function getInstance() {
			if(!self::$mem) self::init();
			return self::$mem;
		}
		
		private static function init() {
			$mem = new Memcached;
			$servers = explode(",", MEMQ_POOL);
			foreach($servers as $server) {
				list($host, $port) = explode(":", $server);
				$mem->addServer($host, $port);
			}
			self::$mem = $mem;
		}
		
		public static function is_empty($queue) {
			$mem = self::getInstance();
			$head = $mem->get($queue."_head");
			$tail = $mem->get($queue."_tail");
			
			if($head >= $tail || $head === FALSE || $tail === FALSE) 
				return TRUE;
			else 
				return FALSE;
		}

		public static function dequeue($queue, $after_id=FALSE, $till_id=FALSE) {
			$mem = self::getInstance();
			
			if($after_id === FALSE && $till_id === FALSE) {
				$tail = $mem->get($queue."_tail");
				if(($id = $mem->increment($queue."_head")) === FALSE){
					return FALSE;
                }

                /* BUG #1 (for some reason we have to subtract 1 from the $id here */
				if($id-1 <= $tail) {
					return $mem->get($queue."_".($id-1));
				}
				else {
                    /* HUGE BUG (the decrement makes this function (as a whole) no atomic (jobs get popped twice)) */
					$mem->decrement($queue."_head");
					return FALSE;
				}
			}
			else if($after_id !== FALSE && $till_id === FALSE) {
				$till_id = $mem->get($queue."_tail");	
			}
			
			$item_keys = array();
			for($i=$after_id+1; $i<=$till_id; $i++) 
				$item_keys[] = $queue."_".$i;
			$null = NULL;
			
			return $mem->getMulti($item_keys, $null, Memcached::GET_PRESERVE_ORDER); 
		}
		
		public static function enqueue($queue, $item) {
			$mem = self::getInstance();
			
			$id = $mem->increment($queue."_tail");
			if($id === FALSE) {
				if($mem->add($queue."_tail", 1, MEMQ_TTL) === FALSE) {
					$id = $mem->increment($queue."_tail");
					if($id === FALSE) 
						return FALSE;
				}
				else {
					$id = 1;
					$mem->add($queue."_head", $id, MEMQ_TTL);
				}
			}
			
			if($mem->add($queue."_".$id, $item, MEMQ_TTL) === FALSE) 
				return FALSE;
			
			return $id;
		}

        public static function log($prepend, $msg = '')
        {
            if (!self::ENABLE_LOG){
                return;
            }

            if (is_array($msg)){
                $string = $prepend;
                $string .= '<pre>' . print_r($msg, true) . '</pre>';
            } else {
                $string = "$prepend $msg\n\r";
            }

            file_put_contents(LOG_DIR . 'memq.class.log', $string, FILE_APPEND);
        }
		
	}

?>
