<?php declare(strict_types=1);

namespace Link0\Bunq\Environment;

use Link0\Bunq\Environment;

final class Production implements Environment
{
    const SERVICE_URL = 'https://api.bunq.com';
    const VERSION = 'v1';

    /**
     * @return string
     */
    public function serviceUrl(): string
    {
        return self::SERVICE_URL;
    }

    /**
     * @return string
     */
    public function version(): string
    {
        return self::VERSION;
    }

    /**
     * @return string
     */
    public function endpoint(): string
    {
        return $this->serviceUrl() . '/' . $this->version() . '/';
    }
}
