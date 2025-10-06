<?php

declare(strict_types=1);

namespace Bensedev\RequestBag;

class RequestBag
{
    /**
     * @var array<string, mixed>
     */
    private array $data = [];

    /**
     * Add a value to the bag.
     */
    public function add(string $key, mixed $value): self
    {
        $this->data[$key] = $value;

        return $this;
    }

    /**
     * Get a value from the bag.
     */
    public function get(string $key, mixed $default = null): mixed
    {
        return $this->data[$key] ?? $default;
    }

    /**
     * Check if a key exists and is not empty.
     */
    public function has(string $key): bool
    {
        if (! isset($this->data[$key])) {
            return false;
        }

        return ! empty($this->data[$key]);
    }

    /**
     * Remove a value from the bag.
     */
    public function remove(string $key): self
    {
        unset($this->data[$key]);

        return $this;
    }

    /**
     * Check if a key exists (even if empty).
     */
    public function exists(string $key): bool
    {
        return \array_key_exists($key, $this->data);
    }

    /**
     * Get all data from the bag.
     *
     * @return array<string, mixed>
     */
    public function all(): array
    {
        return $this->data;
    }

    /**
     * Clear all data from the bag.
     */
    public function clear(): self
    {
        $this->data = [];

        return $this;
    }

    /**
     * Merge data into the bag.
     *
     * @param  array<string, mixed>  $data
     */
    public function merge(array $data): self
    {
        $this->data = array_merge($this->data, $data);

        return $this;
    }
}
