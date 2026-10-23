<?php
namespace App\Models;
use Illuminate\Foundation\Auth\User as Authenticatable;
class User extends Authenticatable
{
    protected $primaryKey='id_user';
    public $incrementing=false;
    protected $keyType='string';
    public $timestamps=false;
    protected $fillable = ['id_user','username','password','nama_lengkap','email','no_hp','alamat'];
    // Role is never mass assignable from public input.
    protected $attributes = ['role' => 'customer'];
    protected $hidden = ['password'];
    protected function casts(): array { return ['password'=>'hashed']; }
}
