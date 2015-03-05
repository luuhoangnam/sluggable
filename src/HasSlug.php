<?php

namespace Namest\Sluggable;

use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Support\Str;

/**
 * Trait HasSlug
 *
 * @author  Nam Hoang Luu <nam@mbearvn.com>
 * @package Namest\Sluggable
 *
 * @property-read string slug
 * @method static QueryBuilder|EloquentBuilder|$this hasSlug(string $slug)
 *
 */
trait HasSlug
{
    /**
     * @var Slug
     */
    private $slugInstance;

    /**
     * @return string|null
     */
    public function getSlugAttribute()
    {
        $relation = $this->hasOne(Slug::class, 'sluggable_id');
        $relation->getQuery()->where('sluggable_type', '=', get_class($this));

        return ($result = $relation->getResults()) ? $result->name : null;
    }

    /**
     * @param string $name
     *
     * @throws DuplicateSlugException
     */
    public function setSlugAttribute($name)
    {
        $name = Str::slug($name);

        if ( ! Slug::isValid($name))
            throw new DuplicateSlugException("Slug [{$name}] was exists.");

        if ( ! $this->exists || ! ($slug = Slug::sluggable($this)->first())) {
            $slug = new Slug;
        }

        $slug->name           = $name;
        $slug->sluggable_type = get_class($this);

        if ($this->exists) {
            $slug->sluggable_id = $this->getKey();
            $slug->save();

            return;
        }

        $this->saved(function ($sluggable) use ($slug) {
            $slug->sluggable_id = $sluggable->getKey();
            $slug->save();
        });
    }

    /**
     * @param EloquentBuilder|QueryBuilder $query
     * @param string                       $slug
     *
     * @return QueryBuilder
     */
    public function scopeHasSlug($query, $slug)
    {
        $table   = $this->getTable();
        $builder = $query->getQuery();

        $builder->join('slugs', 'slugs.sluggable_id', '=', "{$table}.id")
                ->where('slugs.sluggable_type', '=', get_class($this))
                ->where('slugs.name', '=', $slug);

        return $builder;
    }
}
