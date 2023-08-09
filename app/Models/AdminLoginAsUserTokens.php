<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminLoginAsUserTokens extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'token'];

    public $timestamps = false;

    protected static function booted()
    {
        static::creating(function ($model) {
            $model->token = random_int(1111111111, 9999999999);
            $model->expiry_date = Carbon::now()->addHours(1);
        });
    }
}
