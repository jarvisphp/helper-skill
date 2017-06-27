<?php

declare(strict_types=1);

namespace Jarvis\Skill\Helper;

/**
 * @author Eric Chau <eriic.chau@gmail.com>
 */
class ResourceCollection implements \Countable, \Iterator
{
    /**
     * @var array
     */
    protected $collection;

    /**
     * @var int
     */
    protected $countMax;

    /**
     * @var int
     */
    protected $start;

    /**
     * @var int
     */
    protected $limit;

    /**
     * @var int
     */
    protected $count;

    /**
     * @var int
     */
    protected $position;

    /**
     * Constructor.
     *
     * @param array    $collection
     * @param int      $countMax
     * @param int      $start
     * @param int|null $limit
     */
    public function __construct(array $collection, int $countMax, int $start = 0, int $limit = null)
    {
        $this->collection = $collection;
        $this->countMax = $countMax;
        $this->start = $start;
        $this->limit = $limit;
        $this->count = count($this->collection);
    }

    /**
     * Gets the whole collection.
     *
     * @return array
     */
    public function getCollection(): array
    {
        return $this->collection;
    }

    /**
     * Gets the count max of item (ignoring collection limit and start).
     *
     * @return int
     */
    public function getCountMax(): int
    {
        return $this->countMax;
    }

    /**
     * Gets the index of the first element of collection (start from 0).
     *
     * @return int
     */
    public function getStart(): int
    {
        return $this->start;
    }

    /**
     * Returns the max size of the collection. Can be null if no limit provided.
     *
     * @return int|null
     */
    public function getLimit(): ?int
    {
        return $this->limit;
    }

    /**
     * Returns the page of the collection.
     *
     * @return int
     */
    public function getCurrentPage(): int
    {
        return $this->limit && 0 < $this->start
            ? ($this->start / $this->limit) + 1
            : 1
        ;
    }

    /**
     * Returns max pages according to collection criteria,max count and limit.
     *
     * Note that it can return null if limit is not defined.
     *
     * @return int|null
     */
    public function getMaxPage(): ?int
    {
        return $this->limit ? ceil($this->countMax / $this->limit) : null;
    }

    /**
     * Counts items in collection.
     *
     * {@inheritdoc}
     */
    public function count()
    {
        return $this->count;
    }

    /**
     * {@inheritdoc}
     */
    public function rewind()
    {
        $this->position = 0;
    }

    /**
     * {@inheritdoc}
     */
    public function current()
    {
        return $this->collection[$this->position];
    }

    /**
     * {@inheritdoc}
     */
    public function key()
    {
        return $this->position;
    }

    /**
     * {@inheritdoc}
     */
    public function next()
    {
        $this->position = $this->position + 1;
    }

    /**
     * {@inheritdoc}
     */
    public function valid()
    {
        return isset($this->collection[$this->position]);
    }
}
