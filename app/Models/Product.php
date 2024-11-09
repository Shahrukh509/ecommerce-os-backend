<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Product extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $fillable = [
        'category_id', 'parent_id', 'name', 'slug', 'price', 'special_price',
        'size', 'color', 'sku', 'is_featured', 'description', 'is_active',"additional_information"
    ];

    // Automatically append image_url and image_urls attributes
    protected $appends = ['image_url', 'image_urls', 'rating_reviews', 'rating'];

    // Automatically generate slug when name is set
    public function setNameAttribute($value)
    {
        $this->attributes['name'] = $value;
        $this->attributes['slug'] = Str::slug($value);
    }

    // Automatically generate slug when set explicitly
    public function setSlugAttribute($value)
    {
        $this->attributes['slug'] = $value;
    }

    public function getRatingReviewsAttribute()
    {
        return $this->ratingReview()->get();
    }

    public function getRatingAttribute()
    {
        return round($this->ratingReview()->avg('rating'), 2);
    }

    public function ratingReview()
    {
        return $this->hasMany(RatingReview::class);
    }

    // Accessor to get the URL of the first image in the media collection
    public function getImageUrlAttribute()
    {
        $mediaItem = $this->getFirstMedia('avatars'); // Assuming 'avatars' is your collection name
        return $mediaItem ? $mediaItem->getUrl() : null;
    }

    // Accessor to get all image URLs in the media collection
    public function getImageUrlsAttribute()
    {
        return $this->getMedia('avatars')->map(function ($media) {
            return $media->getUrl();
        })->toArray(); // Ensure it's an array
    }
}
