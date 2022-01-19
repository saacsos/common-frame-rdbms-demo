<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EstablishmentType extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function dataTemplates(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(DataTemplate::class);
    }
}
