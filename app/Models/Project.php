<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'category_id',
        'name',
        'slug',
        'short_description',
        'description',
        'funding_goal',
        'min_contribution',
        'duration',
        'start_date',
        'end_date',
        'status',
        'cover_image',
        'video_url',
        'featured',
        'total_collected',
    ];
    

    protected $dates = [
        'start_date',
        'end_date',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function images()
    {
        return $this->hasMany(ProjectImage::class)->orderBy('order');
    }

    public function tags()
{
    return $this->belongsToMany(Tag::class);
}

    public function updates()
    {
        return $this->hasMany(ProjectUpdate::class)->orderBy('created_at', 'desc');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function replies()
    {
        return $this->hasMany(Comment::class, 'parent_id');
    }

    public function parent()
    {
        return $this->belongsTo(Comment::class, 'parent_id');
    }

    public function getProgressPercentageAttribute()
{
    if ($this->funding_goal > 0) {
        $totalCollected = $this->total_collected ?? 0;
        $percentage = ($totalCollected / $this->funding_goal) * 100;

        return min(100, $percentage);
    }

    return 0;
}

    public function getRemainingDaysAttribute()
    {
        if ($this->end_date) {
            $now = now();
            if ($now->lt($this->end_date)) {
                return $now->diffInDays($this->end_date);
            }
        }
        return 0;
    }

    public function scopeFeatured($query)
    {
        return $query->where('featured', true);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active')
                     ->where('start_date', '<=', now())
                     ->where('end_date', '>=', now());
    }


public function contributions()
{
    return $this->hasMany(Contribution::class);
}
}