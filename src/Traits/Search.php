<?php

namespace Rappasoft\LaravelLivewireTables\Traits;

/**
 * Trait Search.
 */
trait Search
{
    /**
     * The initial search string.
     *
     * @var string
     */
    public $search = '';

    /**
     * Method to search by: debounce or lazy.
     * @var string
     */
    public $searchUpdateMethod = 'debounce';

    /**
     * Whether or not searching is enabled.
     *
     * @var bool
     */
    public $searchEnabled = true;

    /**
     * false = disabled
     * int = Amount of time in ms to wait to send the search query and refresh the table.
     *
     * @var int
     */
    public $searchDebounce = 350;

    /**
     * A button to clear the search box.
     *
     * @var bool
     */
    public $clearSearchButton = false;

    /**
     * Resets the search string.
     */
    public function clearSearch(): void
    {
        $this->search = '';
    }

    /**
     * Applies search terms to the column
     */
    public function searchColumn(Builder $builder, $column): Builder
    {
        if (is_callable($column->getSearchCallback()))
        {
            return $builder = app()->call($column->getSearchCallback(), ['builder' => $builder, 'term' => trim
            ($this->search)]);
        } else if (Str::contains($column->getAttribute(), '.'))
        {
            $relationship = $this->relationship($column->getAttribute());

            $builder->orWhereHas($relationship->name, function (Builder $builder) use ($relationship)
            {
                $builder->where($relationship->attribute, 'like', '%' . trim($this->search) . '%');
            });
        } else
        {
            $builder->orWhere($builder->getModel()
                                      ->getTable() . '.' . $column->getAttribute(), 'like', '%' . trim($this->search) . '%');
        }
        return $builder;
    }
}
