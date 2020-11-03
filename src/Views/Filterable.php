<?php


namespace Rappasoft\LaravelLivewireTables\Views;


trait Filterable
{

    /**
     * @var bool
     */
    protected $filterable = false;

    /**
     * @param callable|null $callable
     *
     * @return $this
     */
    public function filterable(callable $callable = null): self
    {
        $this->filterCallback = $callable;
        $this->filterable = true;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getFilterCallback()
    {
        return $this->filterCallback;
    }

    /**
     * @return bool
     */
    public function isFilterable(): bool
    {
        return $this->filterable === true;
    }
}
