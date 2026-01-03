<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

/**
 * @method bool hasRole(string|array $roles, string|null $guard = null)
 * @method bool hasAnyRole(string|array $roles, string|null $guard = null)
 * @method bool hasAllRoles(string|array $roles, string|null $guard = null)
 * @method void assignRole(string|array $roles)
 * @method void removeRole(string|array $roles)
 * @method void syncRoles(string|array $roles)
 * @method bool hasPermissionTo(string|array $permission, string|null $guard = null)
 * @method bool can(string|array $permission, string|null $guard = null)
 * @method void givePermissionTo(string|array $permission)
 */
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    // Relasi dengan Cabang (Many to Many)
    public function cabangs()
    {
        return $this->belongsToMany(Cabang::class, 'user_cabang_access', 'user_id', 'kode_cabang', 'id', 'kode_cabang');
    }

    // Relasi dengan Departemen (Many to Many)
    public function departemens()
    {
        return $this->belongsToMany(Departemen::class, 'user_departemen_access', 'user_id', 'kode_dept', 'id', 'kode_dept');
    }

    /**
     * Cek apakah user adalah super admin
     *
     * @return bool
     */
    public function isSuperAdmin(): bool
    {
        return $this->roles->contains(function ($role) {
            return strtolower($role->name) === 'super admin';
        });
    }

    /**
     * Get cabang yang dapat diakses user
     * Super admin mendapatkan semua cabang, user biasa hanya cabang yang diakses
     *
     * @return \Illuminate\Database\Eloquent\Collection|\App\Models\Cabang[]
     */
    public function getCabang()
    {
        if ($this->isSuperAdmin()) {
            return Cabang::orderBy('kode_cabang')->get();
        }

        $userCabangs = $this->cabangs->pluck('kode_cabang')->toArray();
        if (!empty($userCabangs)) {
            return Cabang::whereIn('kode_cabang', $userCabangs)->orderBy('kode_cabang')->get();
        }

        return collect(); // Empty collection jika tidak ada akses
    }

    /**
     * Get departemen yang dapat diakses user
     * Super admin mendapatkan semua departemen, user biasa hanya departemen yang diakses
     *
     * @return \Illuminate\Database\Eloquent\Collection|\App\Models\Departemen[]
     */
    public function getDepartemen()
    {
        if ($this->isSuperAdmin()) {
            return Departemen::orderBy('kode_dept')->get();
        }

        $userDepartemens = $this->departemens->pluck('kode_dept')->toArray();
        if (!empty($userDepartemens)) {
            return Departemen::whereIn('kode_dept', $userDepartemens)->orderBy('kode_dept')->get();
        }

        return collect(); // Empty collection jika tidak ada akses
    }

    /**
     * Get array kode cabang yang dapat diakses user
     *
     * @return array<string>
     */
    public function getCabangCodes(): array
    {
        if ($this->isSuperAdmin()) {
            return Cabang::pluck('kode_cabang')->toArray();
        }

        return $this->cabangs->pluck('kode_cabang')->toArray();
    }

    /**
     * Get array kode departemen yang dapat diakses user
     *
     * @return array<string>
     */
    public function getDepartemenCodes(): array
    {
        if ($this->isSuperAdmin()) {
            return Departemen::pluck('kode_dept')->toArray();
        }

        return $this->departemens->pluck('kode_dept')->toArray();
    }
}
