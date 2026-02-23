<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'muhdir_id'];

    public function muhdir()
    {
        return $this->belongsTo(User::class, 'muhdir_id');
    }

    public function reports()
    {
        return $this->hasMany(Report::class);
    }
}
