<?php

namespace App\Models;

use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'name',
        'parent_id',
    ];

    // 4. METHOD BARU UNTUK KONFIGURASI LOG
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('Category')
            ->logFillable()
            ->setDescriptionForEvent(fn(string $eventName) => "Data Kategori telah di-{$eventName}");
    }

    /**
     * Menambahkan properti kustom ke log setelah event terjadi.
     */
    protected static function booted(): void
    {
        static::saved(function (Category $category) {
            $activity = $category->activities()->latest()->first();
            if ($activity) {
                $properties = $activity->properties;

                // Cek data lama
                $oldParentId = $category->getOriginal('parent_id');
                if ($oldParentId) {
                    $oldParentName = Category::find($oldParentId)?->name;
                    $properties = $properties->put('old_parent_name', $oldParentName);
                }

                // Cek data baru
                if ($category->parent_id) {
                    $properties = $properties->put('new_parent_name', $category->parent->name);
                }

                $activity->properties = $properties;
                $activity->save();
            }
        });
    }

    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }
}
