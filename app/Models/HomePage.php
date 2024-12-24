<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Spatie\Image\Enums\Fit;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\HasMedia;

class HomePage extends Model  implements HasMedia
{
    use InteractsWithMedia;
    use HasFactory;
    protected $guarded = [];


    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('webp')
            ->format('webp')
            ->quality(45)
            ->nonQueued();
    }


    public function getConvertedImage($conversionName = 'webp')
    {
        $media = $this->getMedia('main_image')->last(); // Use last() to get the latest added image

        if ($media) {
            return $media->getUrl($conversionName);
        }

        return asset('default-image.jpg');
    }
}
