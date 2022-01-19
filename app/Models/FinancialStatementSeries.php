<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FinancialStatementSeries extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public $timestamps = false;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function datasource(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Datasource::class);
    }
}