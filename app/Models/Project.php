<?php
// 1. MODÈLES
// app/Models/Project.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id', 'category_id', 'name', 'slug', 'short_description', 
        'description', 'funding_goal', 'min_contribution', 'duration', 
        'start_date', 'end_date', 'status', 'cover_image', 'video_url', 'featured'
    ];

    protected $dates = [
        'start_date', 'end_date', 'deleted_at'
    ];

    // Relations
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
        return $this->belongsToMany(Tag::class, 'project_tag');
    }

    public function updates()
    {
        return $this->hasMany(ProjectUpdate::class)->orderBy('created_at', 'desc');
    }

    // Accesseurs et mutateurs
    public function getProgressPercentageAttribute()
    {
        // Cette méthode sera implémentée quand vous aurez une table de contributions
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

    // Scopes
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
}