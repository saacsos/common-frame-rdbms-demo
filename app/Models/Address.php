<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $appends = ['subdistrict_name', 'district_name', 'province_name', 'region_name'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function subdistrict(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Subdistrict::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function datasource(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Datasource::class);
    }

    public function getSubdistrictNameAttribute() {
        if ($this->subdistrict) {
            return $this->subdistrict->name;
        }
        return null;
    }

    public function getDistrictNameAttribute() {
        if ($this->subdistrict and $this->subdistrict->district) {
            return $this->subdistrict->district->name;
        }
        return null;
    }

    public function getProvinceNameAttribute() {
        if ($this->subdistrict and $this->subdistrict->district and $this->subdistrict->district->province) {
            return $this->subdistrict->district->province->name;
        }
        return null;
    }

    public function getRegionNameAttribute() {
        if ($this->subdistrict and $this->subdistrict->district
            and $this->subdistrict->district->province and $this->subdistrict->district->province->region) {
            return $this->subdistrict->district->province->region->name;
        }
        return null;
    }
}
