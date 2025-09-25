<?php

namespace App\Models;

use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Permission\Models\Role as SpatieRole;

class Role extends SpatieRole
{
    use LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('Role')
            // Hanya log event 'created' dan 'deleted' secara otomatis
            ->logEvents(['created', 'deleted'])
            ->logOnly(['name'])
            ->setDescriptionForEvent(fn(string $eventName) => "Role ini telah di-{$eventName}");
    }
}
