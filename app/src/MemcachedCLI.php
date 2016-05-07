<?php

class MemcachedCLI {

    /**
     * Number of items to retrieve from each slab (0 = all)
     */
    const CACHEDUMP_LIMIT = 0;

    public function __construct($host, $port)
    {
        $this->host = $host;
        $this->port = $port;
    }

    /**
     * Get all stored keys in memecache
     *
     * @access public
     * @return array
     */
    public function getAllKeys()
    {
        $cache_ids = $this->getAllSlabIDs();
        $keys = [];

        foreach ($cache_ids as $i => $id) {
            $res = $this->getSlabDump($id);
            $keys = array_merge($keys, $this->extractKeyNameFromCacheDump($res));
        }

        return $keys;
    }

    /**
     * Returns an array of "slabIDs"
     * Memcache stores your data in "slabs"
     *
     * @access private
     * @return array
     */
    private function getAllSlabIDs()
    {
        $items = $this->send('stats items');
        foreach ($items as $i => $item) {
            $parts = explode(':', $item);
            if (isset($parts[1])) {
                $keys[$parts[1]] = $parts[2];
            }
        }

        return array_keys($keys);
    }

    /**
     * Get info about the given slab including the names of keys stored in that slab
     *
     * @param mixed $slab_id
     * @access private
     * @return array
     */
    private function getSlabDump($slab_id){
        return $this->send(sprintf('stats cachedump %s %s', $slab_id, self::CACHEDUMP_LIMIT));
    }

    /**
     * Returns an array of key names from the given cachedump
     *
     * @param array $cache_dump
     * @access private
     * @return array
     */
    private function extractKeyNameFromCacheDump(array $cache_dump)
    {
        foreach ($cache_dump as $i => $k) {
            $parts = explode(' ', $k);
            if (isset($parts[1]))
                $keys[] = $parts[1];
        }

        return $keys;
    }

    /**
     * Send a command to the memcache via netcat
     *
     * @param string $command
     * @access private
     * @return array
     */
    private function send($command)
    {
        $cmd = sprintf('echo "%s" | nc %s %s', $command, $this->host, $this->port);
        exec($cmd, $response);

        return $response;
    }
}
