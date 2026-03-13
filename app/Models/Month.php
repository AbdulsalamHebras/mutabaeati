<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Month extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'year'];

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

    public function students()
    {
        return $this->belongsToMany(Student::class, 'subscriptions')
            ->withPivot(['amount', 'is_paid', 'notes'])
            ->withTimestamps();
    }
}
