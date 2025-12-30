<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'slug',
        'description',
        'price',
        'stock',
        'category',
        'image_path',
        'images_json',
        'status',
        'archive_status',
        'sold',
        'weight',
        'length',
        'width',
        'height',
        'enabled_couriers',
        'wholesale_price',
        'wholesale_min_qty',
        'video_path',
        'specifications_json',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'wholesale_price' => 'decimal:2',
        'weight' => 'decimal:2',
    ];

    // Auto-generate slug on creation
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($product) {
            if (empty($product->slug)) {
                $product->slug = static::generateUniqueSlug($product->name);
            }
        });

        static::updating(function ($product) {
            if ($product->isDirty('name') && empty($product->slug)) {
                $product->slug = static::generateUniqueSlug($product->name);
            }
        });
    }

    public static function generateUniqueSlug($name)
    {
        $slug = Str::slug($name);
        $count = static::where('slug', 'LIKE', "{$slug}%")->count();
        
        return $count ? "{$slug}-{$count}" : $slug;
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }

    // Relationships
    public function seller(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    // Rating calculations
    public function averageRating()
    {
        return $this->reviews()->avg('rating') ?? 0;
    }

    public function totalReviews()
    {
        return $this->reviews()->count();
    }

    public function ratingCounts()
    {
        $total = $this->totalReviews();
        
        if ($total === 0) {
            return [
                5 => 0,
                4 => 0,
                3 => 0,
                2 => 0,
                1 => 0,
            ];
        }

        $counts = [];
        for ($star = 5; $star >= 1; $star--) {
            $count = $this->reviews()->where('rating', $star)->count();
            $counts[$star] = $count;
        }

        return $counts;
    }

    public function ratingPercentages()
    {
        $total = $this->totalReviews();
        
        if ($total === 0) {
            return [
                5 => 0,
                4 => 0,
                3 => 0,
                2 => 0,
                1 => 0,
            ];
        }

        $percentages = [];
        for ($star = 5; $star >= 1; $star--) {
            $count = $this->reviews()->where('rating', $star)->count();
            $percentages[$star] = round(($count / $total) * 100, 1);
        }

        return $percentages;
    }
}
