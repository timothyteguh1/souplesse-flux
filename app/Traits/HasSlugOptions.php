<?php

namespace App\Traits;

use Spatie\Sluggable\SlugOptions;

trait HasSlugOptions
{
    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->doNotGenerateSlugsOnUpdate()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug');
    }
}
