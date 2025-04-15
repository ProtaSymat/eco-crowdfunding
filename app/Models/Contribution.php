<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contribution extends Model
{
    use HasFactory;
    protected $fillable = ['user_id', 'project_id', 'amount', 'status', 'anonymous'];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function project()
    {
        return $this->belongsTo(Project::class);
    }
    public function donation()
    {
        return $this->hasOne(Donation::class);
    }
}
