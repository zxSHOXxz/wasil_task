<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Spatie\Image\Enums\Fit;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\HasMedia;

class Project extends Model implements HasMedia
{
    use InteractsWithMedia;
    use HasFactory;
    protected $guarded = [];

    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('webp')
            ->format('webp')
            ->quality(35)
            ->nonQueued();
    }



    public function getConvertedImage($conversionName = 'webp')
    {
        $media = $this->getMedia('image')->last();

        if ($media) {
            return $media->getUrl($conversionName);
        }

        return asset('default-image.jpg');
    }
}
