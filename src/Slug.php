<?php

namespace Namest\Sluggable;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\Builder as QueryBuilder;

/**
 * Class Slug
 *
 * @property string name
 * @property string sluggable_type
 * @property int    sluggable_id
 *
 * @method static QueryBuilder|EloquentBuilder|$this sluggable(Model $sluggable)
 *
 * @author  Nam Hoang Luu <nam@mbearvn.com>
 * @package Namest\Sluggable
 *
 */
class Slug extends Model
{
    /**
     * @param EloquentBuilder|QueryBuilder $query
     * @param Model                        $sluggable
     */
    public function scopeSluggable($query, $sluggable)
    {
        $query->where('sluggable_id', '=', $sluggable->getKey())
              ->where('sluggable_type', '=', get_class($sluggable));
    }

    /**
     * @param string $name
     *
     * @return bool
     */
    public static function isValid($name)
    {
        if (in_array($name, config('slug.reserve.words', [])))
            return false;

        $models = config('slug.reserve.models', []);
        foreach ($models as $model => $attribute) {
            if (forward_static_call([$model, 'where'], $attribute, '=', $name)->first())
                return false;
        }

        return static::whereName($name)->first() == null;
    }

    /**
     * @param string $name
     * @param bool   $force
     *
     * @return string
     */
    public static function regenerate($name, $force = false)
    {
        if ( ! $force && static::isValid($name))
            return $name;

        $name = $name . '-' . mt_rand(0, 9);

        while ( ! static::isValid($name)) {
            $name = $name . mt_rand(0, 9);
        }

        return $name;
    }
}
