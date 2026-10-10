<?php
namespace App\Models;
use Illuminate\Foundation\Auth\User as Authenticatable;
class User extends Authenticatable
{

    protected $fillable = ['username','password','nama_lengkap'];
    protected $hidden = ['password'];
    protected function casts(): array { return ['password'=>'hashed']; }
}
