<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class WebSocket extends BaseConfig
{
    /**
     * WebSocket Server Host
     */
    public string $host = 'localhost';

    /**
     * WebSocket Server Port
     */
    public int $port = 8080;

    /**
     * Connection timeout in seconds
     */
    public int $timeout = 5;

    /**
     * Maximum number of connection retries
     */
    public int $maxRetries = 3;

    /**
     * Delay between retries in seconds
     */
    public int $retryDelay = 1;

    /**
     * Maximum number of concurrent connections
     */
    public int $maxConnections = 100;

    /**
     * Heartbeat interval in seconds
     */
    public int $heartbeatInterval = 30;
}