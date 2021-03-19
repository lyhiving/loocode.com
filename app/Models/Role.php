<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Role extends Model
{
    use HasFactory;

    protected $table = 'roles';

    const CREATED_AT = 'created_date';
    const UPDATED_AT = 'updated_date';

    /**
     * @var array
     */
    protected $with = ['permission'];

    /**
     * @var array
     */
    protected $fillable = [
        'name',
    ];

    /**
     * @return HasMany
     */
    public function permission(): HasMany
    {
        return $this->hasMany(Permission::class, 'role_id');
    }
}
