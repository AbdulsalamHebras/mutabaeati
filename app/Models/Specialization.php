<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\LogsActivityWithDefaults;

class Specialization extends Model
{
    use HasFactory, LogsActivityWithDefaults;

    protected $fillable = ['name'];

    public function students()
    {
        return $this->hasMany(Student::class);
    }
}
