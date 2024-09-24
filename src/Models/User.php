<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasPermissions;

class User extends Model
{
    use HasPermissions;

    protected $fillable = ["name", "email", "password", "role_id", "sub_empresa_id"];

    // Implementa las relaciones y métodos aquí
}