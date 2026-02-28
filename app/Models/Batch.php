<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Batch extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'university_id'];

    public function university()
    {
        return $this->belongsTo(University::class);
    }

    public function students()
    {
        return $this->hasMany(Student::class);
    }
}
