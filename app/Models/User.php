<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Group;
use Spatie\Activitylog\LogOptions;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles, LogsActivity;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'group_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('User')
            ->logFillable()
            // Penting: Abaikan kolom password dari logging otomatis
            ->logExcept(['password'])
            ->setDescriptionForEvent(fn(string $eventName) => "Data User telah di-{$eventName}");
    }

    /**
     * Menambahkan properti kustom ke log setelah event terjadi.
     */
    protected static function booted(): void
    {
        static::saved(function (User $user) {
            $activity = $user->activities()->latest()->first();
            if ($activity) {
                $properties = $activity->properties;

                // 1. Logika untuk Group
                $oldGroupId = $user->getOriginal('group_id');
                if ($oldGroupId) {
                    $oldGroupName = Group::find($oldGroupId)?->name;
                    $properties = $properties->put('old_group_name', $oldGroupName);
                }
                if ($user->group_id) {
                    $properties = $properties->put('new_group_name', $user->group->name);
                }

                // 2. Logika untuk Password
                // Cek jika password berubah
                if ($user->wasChanged('password')) {
                    // Ambil 'attributes' & 'old' dari log, lalu hapus password hash
                    $attributes = $properties->get('attributes', []);
                    $old = $properties->get('old', []);
                    unset($attributes['password'], $old['password']);

                    // Tambahkan password yang sudah disamarkan
                    $attributes['password'] = '********';
                    $old['password'] = '*********';

                    // Simpan kembali ke properties
                    $properties = $properties->put('attributes', $attributes)->put('old', $old);
                }

                $activity->properties = $properties;
                $activity->save();
            }
        });
    }

    public function group()
    {
        return $this->belongsTo(Group::class);
    }
}
