<?php declare(strict_types = 1);

namespace Link0\Bunq;

use ArrayAccess;
use InvalidArgumentException;
use IteratorAggregate;
use Traversable;

/**
 * Array (
 *   'Response' => [
 *     0 => [
 *       'Foo' => [
 *         'id' => 123,
 *       ],
 *     ],
 *     1 => [
 *       'Foo' => [
 *         'id' => 456,
 *       ],
 *     ],
 *
 *   ],
 *   'Pagination' => [
 *     'future_url' => '/v1/foo?newer_id=123',
 *     'newer_url' => '/v1/foo?newer_id=456',
 *     'older_url' => null,
 *   ]
 * )
 */
final class PaginatedResponse implements IteratorAggregate, ArrayAccess
{
    /**
     * @var Client
     */
    private $client;

    /**
     * @var array
     */
    private $list;

    /**
     * @var array
     */
    private $pagination;

    /**
     * @param Client $client
     */
    public function __construct(Client $client, array $body)
    {
        $this->client = $client;

        $this->guardAndSetResponseBody($body);
        $this->guardAndSetPagination($body);
    }

    /**
     * @param array $body
     * @return void
     */
    private function guardAndSetResponseBody(array $body)
    {
        if (!isset($body['Response'])) {
            throw new InvalidArgumentException("Response body should contain key 'Response'");
        }
        $this->list = $body['Response'];
    }

    /**
     * @param array $body
     * @return void
     */
    private function guardAndSetPagination(array $body)
    {
        $this->pagination = [
            'future_url' => null,
            'newer_url' => null,
            'older_url' => null,
        ];

        if (isset($body['Pagination'])) {
            $pagination = $body['Pagination'];

            if (!array_key_exists('future_url', $pagination)) {
                throw new InvalidArgumentException("Pagination should contain future_url");
            }
            if (!array_key_exists('newer_url', $pagination)) {
                throw new InvalidArgumentException("Pagination should contain newer_url");
            }
            if (!array_key_exists('older_url', $pagination)) {
                throw new InvalidArgumentException("Pagination should contain older_url");
            }
            $this->pagination = $pagination;
        }
    }

    /**
     * Retrieve an external iterator
     * @link http://php.net/manual/en/iteratoraggregate.getiterator.php
     * @return Traversable An instance of an object implementing <b>Iterator</b> or
     * <b>Traversable</b>
     * @since 5.0.0
     */
    public function getIterator()
    {
        foreach ($this->list as $key => $value) {
            // mapResponse takes the struct and instantiates Value Objects
            yield $key => $this->client->mapResponse($key, $value);
        }

        if ($this->pagination['newer_url'] !== null) {
            /** @var PaginatedResponse $nextPagination */
            $nextPagination = $this->client->get($this->pagination['newer_url']);

            foreach ($nextPagination as $newKey => $newValue) {
                $this->list[] = $newValue;
                unset($nextPagination->list[$newKey]);
                yield $newKey => $newValue;
            }
        }
    }

    /**
     * Whether a offset exists
     * @link http://php.net/manual/en/arrayaccess.offsetexists.php
     * @param mixed $offset <p>
     * An offset to check for.
     * </p>
     * @return boolean true on success or false on failure.
     * </p>
     * <p>
     * The return value will be casted to boolean if non-boolean was returned.
     * @since 5.0.0
     */
    public function offsetExists($offset)
    {
        return array_key_exists($offset, $this->list);
    }

    /**
     * Offset to retrieve
     * @link http://php.net/manual/en/arrayaccess.offsetget.php
     * @param mixed $offset <p>
     * The offset to retrieve.
     * </p>
     * @return mixed Can return all value types.
     * @since 5.0.0
     */
    public function offsetGet($offset)
    {

        return $this->list[$offset];
    }

    /**
     * Offset to set
     * @link http://php.net/manual/en/arrayaccess.offsetset.php
     * @param mixed $offset <p>
     * The offset to assign the value to.
     * </p>
     * @param mixed $value <p>
     * The value to set.
     * </p>
     * @return void
     * @since 5.0.0
     */
    public function offsetSet($offset, $value)
    {
        throw new \LogicException("Unable to set value on immutable object");
    }

    /**
     * Offset to unset
     * @link http://php.net/manual/en/arrayaccess.offsetunset.php
     * @param mixed $offset <p>
     * The offset to unset.
     * </p>
     * @return void
     * @since 5.0.0
     */
    public function offsetUnset($offset)
    {
        throw new \LogicException("Unable to unset value on immutable object");
    }
}
