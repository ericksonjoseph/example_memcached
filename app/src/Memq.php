<?php
	
	if(getenv(MEMQ_POOL) !== false) define('MEMQ_POOL', 'locahost:11211');
	if(getenv(MEMQ_TTL) !== false) define('MEMQ_TTL', 0);

	class MEMQ {
		
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
            self::log("Checking if queue $queue is empty or not");
			$mem = self::getInstance();
			$head = $mem->get($queue."_head");
			$tail = $mem->get($queue."_tail");
			
            self::log($queue."_head", $head);
            self::log($queue."_tail", $tail);

			if($head >= $tail || $head === FALSE || $tail === FALSE) 
				return TRUE;
			else 
				return FALSE;
		}

		public static function dequeue($queue, $after_id=FALSE, $till_id=FALSE) {
			$mem = self::getInstance();
			
            self::log(__METHOD__, func_get_args());

			if($after_id === FALSE && $till_id === FALSE) {
                self::log('no limits');
				$tail = $mem->get($queue."_tail");
                self::log("tail = $tail");
				if(($id = $mem->increment($queue."_head")) === FALSE){
                    self::log("id is false. $id");
					return FALSE;
                }
			
                self::log("id = $id");
                self::log("id = $tail");
				if($id <= $tail) {
                    self::log("id is less than tail = $id");
					return $mem->get($queue."_".($id-1));
				}
				else {
                    self::log("id is NOT less than tail = $id");
					$mem->decrement($queue."_head");
					return FALSE;
				}
			}
			else if($after_id !== FALSE && $till_id === FALSE) {
                self::log("limits: after_id = $after_id");
				$till_id = $mem->get($queue."_tail");	
                self::log("limits: till_id = $till_id");
			}
			
			$item_keys = array();
			for($i=$after_id+1; $i<=$till_id; $i++) 
				$item_keys[] = $queue."_".$i;
			$null = NULL;
			
            self::log("about to get multi:", $item_keys);
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
            if (is_array($msg)){
                $string = $prepend;
                $string .= '<pre>' . print_r($msg, true) . '</pre>';
            } else {
                $string = "$prepend $msg<br>";
            }

            file_put_contents('memq.class.log', $string, FILE_APPEND);
        }
		
	}

?>
