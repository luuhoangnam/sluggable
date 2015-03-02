<?php

namespace Namest\Sluggable;

/**
 * Trait HasSlug
 *
 * @author  Nam Hoang Luu <nam@mbearvn.com>
 * @package Namest\Sluggable
 *
 * @property-read string slug
 *
 */
trait HasSlug
{
    /**
     * @return string|null
     */
    public function getSlugAttribute()
    {
        $relation = $this->hasOne(Slug::class, 'sluggable_id');
        $relation->getQuery()->where('sluggable_type', '=', get_class($this));

        return ($result = $relation->getResults()) ? $result->name : null;
    }
}
