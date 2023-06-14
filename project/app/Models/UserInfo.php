<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserInfo extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'user_info';

    protected $primery = 'user_id';

    protected $garded = ['user_id'];

    protected $dates = ['deleted_at'];  
}
