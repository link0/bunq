<?php declare(strict_types=1);

namespace Link0\Bunq\Service;

use Link0\Bunq\Client;
use Link0\Bunq\Domain\DeviceServer;
use Link0\Bunq\Domain\Id;
use Link0\Bunq\Domain\Installation;
use Link0\Bunq\Domain\Keypair;

final class InstallationService
{
    /**
     * @var Client
     */
    private $client;

    /**
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * @param Keypair $keypair
     * @return mixed
     */
    public function createInstallation(Keypair $keypair)
    {
        // TODO: This doesn't need the signature middleware
        return $this->client->post('installation', [
            'client_public_key' => (string) $keypair->publicKey(),
        ]);
    }

    /**
     * @param Installation $installation
     * @return Id $deviceServerId
     */
    public function createDeviceServer(Installation $installation, string $description, string $apiKey): Id
    {
        $permittedIps = [];

        $body = [
            'description' => $description,
            'secret' => $apiKey,
            'permitted_ips' => $permittedIps,
        ];

        return $this->client->post('device-server', $body, [
            'X-Bunq-Client-Authentication' => $installation->token(),
        ])[0];
    }

    public function createSessionServer(Installation $installation, string $apiKey)
    {
        $body = ['secret' => $apiKey];

        $this->client->post('session-server', $body, [
            'X-Bunq-Client-Authentication' => $installation->token(),
        ]);
    }

    /**
     * @param Installation $installation
     * @return DeviceServer[]
     */
    public function listDeviceServers(Installation $installation): array
    {
        return $this->client->get('device-server', [
            'X-Bunq-Client-Authentication' => $installation->token(),
        ]);
    }

    /**
     * @param Installation $installation
     * @return array
     */
    public function listInstallations(Installation $installation)
    {
        return $this->client->get('installation', [
            'X-Bunq-Client-Authentication' => $installation->token(),
        ]);
    }

    /**
     * @param Installation $installation
     * @return array
     */
    public function serverPublicKey(Installation $installation)
    {
        return $this->client->get('installation/' . $installation->id() . '/server-public-key');
    }
}
