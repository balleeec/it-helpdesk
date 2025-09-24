<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Group extends Model
{
    use HasFactory, LogsActivity;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'parent_id',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('Group')
            ->logFillable() // Mencatat semua kolom di $fillable
            ->setDescriptionForEvent(fn(string $eventName) => "Data Grup telah di-{$eventName}");
    }

    /**
     * Menambahkan properti kustom ke log setelah event terjadi.
     */
    protected static function booted(): void
    {
        static::saved(function (Group $group) {
            $activity = $group->activities()->latest()->first();
            if ($activity) {
                $properties = $activity->properties;

                // Cek data lama (untuk event 'updated')
                $oldParentId = $group->getOriginal('parent_id');
                if ($oldParentId) {
                    $oldParentName = Group::find($oldParentId)?->name;
                    $properties = $properties->put('old_parent_name', $oldParentName);
                }

                // Cek data baru (untuk event 'created' dan 'updated')
                if ($group->parent_id) {
                    $properties = $properties->put('new_parent_name', $group->parent->name);
                }

                $activity->properties = $properties;
                $activity->save();
            }
        });
    }

    /**
     * Relasi untuk mendapatkan induk dari grup ini.
     */
    public function parent()
    {
        return $this->belongsTo(Group::class, 'parent_id');
    }

    /**
     * Relasi untuk mendapatkan semua anak dari grup ini.
     */
    public function children()
    {
        return $this->hasMany(Group::class, 'parent_id');
    }

    /**
     * Relasi untuk mendapatkan semua user di dalam grup ini.
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }
}
