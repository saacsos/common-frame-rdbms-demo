<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Establishment extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $appends = ['establishment_type_name'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function establishmentType(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(EstablishmentType::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function addresses(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Address::class)->withPivot(['establishment_type', 'building_type']);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function tsicSeries(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(TsicSeries::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function data(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Data::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function dataSeries(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(DataSeries::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function people(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Person::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function establishmentSizes(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(EstablishmentSize::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function financialStatements(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(FinancialStatement::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function financialStatementSeries(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(FinancialStatementSeries::class);
    }


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function covidImpactedSeries(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(CovidImpactedSeries::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function workForceEmployees() {
        return $this->hasMany(WorkForceEmployee::class);
    }

    public function getEstablishmentTypeNameAttribute() {
        if (!empty($this->establishment_type_id)) {
            return $this->establishmentType->name;
        }
        return null;
    }
}
