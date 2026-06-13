<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\LogsActivityWithDefaults;

class University extends Model
{
    use HasFactory, LogsActivityWithDefaults;

    protected $fillable = ['name'];

    public function batches()
    {
        return $this->hasMany(Batch::class);
    }

    public function students()
    {
        return $this->hasMany(Student::class);
    }
}
