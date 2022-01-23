<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkForceEmployee extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function datasource(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Datasource::class);
    }
}
