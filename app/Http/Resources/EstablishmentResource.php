<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class EstablishmentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $result = [
            'establishment' => parent::toArray($request),
            'addresses' => [],
            'tsic' => [],
            'sizes' => [],
            'financial_statements' => [],
            'work_force_employees' => [],
            'data' => []
        ];
        if ($this->addresses) {
            $result['addresses'] = AddressResource::collection($this->addresses);
        }
        if ($this->establishmentSizes) {
            $result['sizes'] = EstablishmentSizeResource::collection($this->establishmentSizes);
        }
        if ($this->data) {
            $result['data'] = DataResource::collection($this->data);
        }
        if ($this->financialStatements) {
            $result['financial_statements'] = FinancialStatementResource::collection($this->financialStatements);
        }
        if ($this->workForceEmployees) {
            $result['work_force_employees'] = WorkForceEmployeeResource::collection($this->workForceEmployees);
        }
        if ($this->tsicSeries) {
            $result['tsic'] = TsicSeriesResource::collection($this->tsicSeries);
        }
        return $result;
    }
}
