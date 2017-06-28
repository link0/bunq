<?php declare(strict_types=1);

namespace Link0\Bunq;

interface ClientInterface
{
    /**
     * @param string $endpoint
     * @return array
     */
    public function get(string $endpoint, array $headers = []): array;

    /**
     * @param string $endpoint
     * @param array $body
     * @param array $headers
     * @return array
     */
    public function post(string $endpoint, array $body, array $headers = []): array;

    /**
     * @param string $endpoint
     * @param array $body
     * @param array $headers
     * @return array
     */
    public function put(string $endpoint, array $body, array $headers = []): array;

    /**
     * @param string $endpoint
     * @param array $headers
     * @return void
     */
    public function delete(string $endpoint, array $headers = []);
}
