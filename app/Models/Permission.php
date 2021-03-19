<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    use HasFactory;

    const CREATED_AT = 'created_date';
    const UPDATED_AT = null;

    protected $table = 'permissions';

    /**
     * @var array
     */
    protected $fillable = [
        'role_id',
        'menu_id'
    ];

    public function menu()
    {
        return $this->belongsTo(Menu::class, 'menu_id');
    }
}
