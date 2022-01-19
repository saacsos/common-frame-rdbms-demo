<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataSeries extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $dates = ['started_at', 'ended_at'];

    public $timestamps = false;
}
