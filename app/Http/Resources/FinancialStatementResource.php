<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class FinancialStatementResource extends JsonResource
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
            'registered_capital' => $this->registered_capital,
            'total_income' => $this->total_income,
            'net_profit_loss' => $this->net_profit_loss,
            'total_assets' => $this->total_assets,
            'datasource' => $this->datasource->name,
            'created_at' => $this->created_at
        ];
    }
}
