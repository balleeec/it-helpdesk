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
            ->useLogName('Group') // Nama log
            ->logOnly(['name', 'email', 'parent_id']) // Hanya catat perubahan pada kolom ini
            ->logOnlyDirty() // Hanya catat jika ada perubahan
            ->setDescriptionForEvent(fn(string $eventName) => "Data grup telah di-{$eventName}"); // Deskripsi log
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
