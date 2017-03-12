<?php

namespace Link0\Bunq;

interface Environment
{
    /**
     * @return string
     */
    public function endpoint(): string;

    /**
     * @return bool
     */
    public function inDebugMode(): bool;
}
