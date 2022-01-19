<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class WorkForceEmployeeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'no_person_engaged' => $this->no_person_engaged,
            'no_employee' => $this->no_employee,
            'datasource' => $this->datasource->name,
            'created_at' => $this->created_at
        ];
    }
}
