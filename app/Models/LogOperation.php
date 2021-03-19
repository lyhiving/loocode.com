<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogOperation extends Model
{
    use HasFactory;

    protected $table = 'log_operations';

    const CREATED_AT = 'created_date';

    const UPDATED_AT =  null;
}
