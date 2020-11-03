<?php


namespace Rappasoft\LaravelLivewireTables\Traits;


trait Filters
{
    /**
     * The initial filters.
     *
     * @var array
     */
    public $filters = [];

    /**
     * Whether or not filtering is enabled.
     *
     * @var bool
     */
    public $filtersEnabled = false;

    /**
     * Resets the filters.
     */
    public function clearFilters(): void
    {
        $this->filter = [];
    }

    public function getActiveFiltersProperty()
    {
        return array_keys($this->filters);
    }

    private function setHasFilters()
    {
        if (method_exists($this, 'filterOptions') && count($this->filterOptions()))
        {
            $this->hasFilters = true;
            $this->availableFilters = array_keys($this->filterOptions());
        }
    }

    public function applyFilters($builder, $filters = null)
    {
        if (!$filters)
        {
            return $builder;
        }

        foreach ($filters as $key => $filter)
        {
            if (is_array($filter))
            {
                $builder = $this->applyFilters($builder, $filter);
            } else if (in_array($key, $this->filters) && $filter instanceof DataTableFilter)
            {
                dd('this is a datatable filter that needs resolving');
            } else if (in_array($key, $this->filters) && is_callable($filter))
            {
                $builder = app()->call($filter, ['builder' => $builder]);
            }
        }

        return $builder;

    }

    public function toggleFilter($type)
    {
        if (in_array($type, $this->filters))
        {
            $this->removeFilter($type);
        } else
        {
            $this->addFilter($type);
        }

        $this->filters = array_keys($this->filters);
    }

    public function addFilter($type)
    {
        $this->filters[] = $type;
        $this->setFilters();
    }

    public function removeFilter($type)
    {
        foreach ($this->filters as $key => $value)
        {
            if ($value === $type)
            {
                unset($this->filters[$key]);
            }
        }
        $this->setFilters();
    }

    protected function setFilters()
    {
        $this->filters = array_unique($this->filters);
    }

}
