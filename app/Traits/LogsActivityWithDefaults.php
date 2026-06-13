<?php

namespace App\Traits;

use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

trait LogsActivityWithDefaults
{
    use LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        $logOptions = LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();

        // Standard sensitive fields to exclude
        $ignored = ['password', 'remember_token', 'updated_at'];
        
        // If the model defines custom excluded attributes in $dontLogFields property
        if (property_exists($this, 'dontLogFields') && is_array($this->dontLogFields)) {
            $ignored = array_merge($ignored, $this->dontLogFields);
        }

        return $logOptions->logExcept($ignored);
    }
}
