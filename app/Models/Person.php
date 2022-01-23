<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Person extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $table = 'people';

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function establishments(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Establishment::class, 'establishment_people',
            'establishment_id', 'people_id');
    }
}
