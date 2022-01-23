<?php

namespace App\Http\Controllers\Api;

use App\Helper\DBDImportData;
use App\Helper\DIWImportData;
use App\Helper\NSOImportData;
use App\Http\Controllers\Controller;
use App\Http\Resources\EstablishmentResource;
use App\Models\Address;
use App\Models\Data;
use App\Models\DataSeries;
use App\Models\Datasource;
use App\Models\Establishment;
use App\Models\EstablishmentSize;
use App\Models\FinancialStatement;
use App\Models\FinancialStatementSeries;
use App\Models\Person;
use App\Models\TsicSeries;
use App\Models\WorkForceEmployee;
use App\Models\WorkForceEmployeeSeries;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class EstablishmentsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        $establishments = Establishment::with([
            'establishmentType', 'addresses', 'tsicSeries',
            'financialStatements', 'establishmentSizes', 'workForceEmployees'])
            ->take(10)->get();
        return EstablishmentResource::collection($establishments);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request): \Illuminate\Http\JsonResponse
    {
        $inputData = $request->input('data');

        foreach($inputData as $d) {
            $datasource = $d['dataSource'];
            $datasource = Datasource::where('name', 'LIKE', "%{$datasource}%")->first();
            if (!$datasource) {
                $datasource = new Datasource();
                $datasource->name = $d['dataSource'];
                $datasource->save();
            }

            if ($datasource->id < 4) {
                if ($datasource->id === 2) {
                    $data = DBDImportData::transform($d);
                } else if ($datasource->id === 1) {
                    $data = NSOImportData::transform($d);
                } else if ($datasource->id === 3) {
                    $data = DIWImportData::transform($d);
                }
                $this->insertOrUpdate($data, $datasource->id);
            }
        }

        return response()->json([
            'success' => true
        ]);
    }

    private function insertOrUpdate($data, $datasource_id) {
        $establishment = Establishment::where('registration_number', $data['establishment']['registration_number'])->first();
        if (!$establishment) {
            $this->insertNew($data, $datasource_id);
        } else {
            $this->updateExist($data, $establishment, $datasource_id);
        }
    }

    private function insertNew($data, $datasource_id) {
        $establishment = Establishment::create($data['establishment']);
        $data['address']['datasource_id'] = $datasource_id;
        $address = Address::create($data['address']);
        $establishment->addresses()->attach($address->id);

        if (!empty($data['tsicSeries'])) {
            $data['tsicSeries']['datasource_id'] = $datasource_id;
            $data['tsicSeries']['establishment_id'] = $establishment->id;
            $data['tsicSeries']['started_at'] = now();
            $tsicSeries = TsicSeries::create($data['tsicSeries']);
        }

        if (!empty($data['financialStatement'])) {
            $data['financialStatement']['datasource_id'] = $datasource_id;
            $data['financialStatement']['establishment_id'] = $establishment->id;
            $financialStatement = FinancialStatement::create($data['financialStatement']);
        }

        if (!empty($data['workForceEmployee'])) {
            $data['workForceEmployee']['datasource_id'] = $datasource_id;
            $data['workForceEmployee']['establishment_id'] = $establishment->id;
            $workForceEmployee = WorkForceEmployee::create($data['workForceEmployee']);
        }

        if (!empty($data['people'])) {
            $people = Person::create($data['people']);
            $establishment->people()->attach($people->id);
        }

        foreach ($data['data'] as $d) {
            $d['establishment_id'] = $establishment->id;
            $dTable = Data::create($d);
        }
        foreach ($data['establishmentSize'] as $d) {
            $d['establishment_id'] = $establishment->id;
            $d['datasource_id'] = $datasource_id;
            $establishmentSize = EstablishmentSize::create($d);
        }
    }

    private function updateExist($data, $establishment, $datasource_id) {
        $data['address']['datasource_id'] = $datasource_id;
        Address::updateOrCreate($data['address'], $data['address']);

        $original = $establishment;
        $establishment->update($data['establishment']);
        if ($establishment->wasChanged()) {
            // how to find which field was changed?
        }

        if (!empty($data['tsicSeries'])) {
            $data['tsicSeries']['datasource_id'] = $datasource_id;
            $data['tsicSeries']['establishment_id'] = $establishment->id;
            $data['tsicSeries']['started_at'] = now();
            $tsicSeries = TsicSeries::where([
                'establishment_id' => $establishment->id,
                'datasource_id' => $datasource_id
            ])->orderBy('started_at', 'DESC')->first();
            if (!$tsicSeries) {
                $tsicSeries = TsicSeries::create($data['tsicSeries']);
            } else if ($tsicSeries->tsic_code !== $data['tsicSeries']['tsic_code']) {
                $tsicSeries->ended_at = now();
                $tsicSeries->save();
                $tsicSeries = TsicSeries::create($data['tsicSeries']);
            }
        }

        if (!empty($data['financialStatement'])) {
            $data['financialStatement']['datasource_id'] = $datasource_id;
            $data['financialStatement']['establishment_id'] = $establishment->id;

            $old = FinancialStatement::where([
                'establishment_id' => $establishment->id,
                'datasource_id' => $datasource_id
            ])->first();
            if (!$old) {
                FinancialStatement::create($data['financialStatement']);
            } else {
                $original = $old->toArray();
                $old->update($data['financialStatement']);
                if ($old->wasChanged()) {
                    $original['started_at'] = $original['updated_at'];
                    $original['ended_at'] = $old->updated_at;
                    unset($original['updated_at']);
                    unset($original['created_at']);
                    unset($original['id']);
                    $fss = FinancialStatementSeries::create($original);
                }
            }
        }

        if (!empty($data['workForceEmployee'])) {
            $data['workForceEmployee']['datasource_id'] = $datasource_id;
            $data['workForceEmployee']['establishment_id'] = $establishment->id;

            $old = WorkForceEmployee::where([
                'establishment_id' => $establishment->id,
                'datasource_id' => $datasource_id
            ])->first();
            if (!$old) {
                WorkForceEmployee::create($data['workForceEmployee']);
            } else {
                $original = $old->toArray();
                $old->update($data['workForceEmployee']);
                if ($old->wasChanged()) {
                    $original['started_at'] = $original['updated_at'];
                    $original['ended_at'] = $old->updated_at;
                    unset($original['updated_at']);
                    unset($original['created_at']);
                    unset($original['id']);
                    $fss = WorkForceEmployeeSeries::create($original);
                }
            }
        }

        foreach ($data['data'] as $d) {
            $d['establishment_id'] = $establishment->id;
            $old = Data::where([
                'establishment_id' => $establishment->id,
                'table' => $d['table'],
                'key' => $d['key']
            ])->first();
            if (!$old) {
                Data::create($d);
            } else if ($old->value !== $d['value']) {
                $ds = [
                    'establishment_id' => $old->establishment_id,
                    'table' => $old->table,
                    'key' => $old->key,
                    'value' => $old->value,
                    'value_type' => $old->value_type,
                    'started_at' => $old->updated_at,
                    'ended_at' => now()
                ];
                $dataSeries = DataSeries::create($ds);
                $old->value = $d['value'];
                $old->value_type = $d['value_type'];
                $old->save();
            }
        }

        foreach ($data['establishmentSize'] as $d) {
            $d['establishment_id'] = $establishment->id;
            $d['datasource_id'] = $datasource_id;
            $old = EstablishmentSize::where([
                'establishment_id' => $establishment->id,
                'datasource_id' => $datasource_id,
                'type' => $d['type']
            ])->first();
            $old->update($d);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return EstablishmentResource
     */
    public function show($id): EstablishmentResource
    {
        $establishment = Establishment::where('registration_number', $id)->firstOrFail();
        return new EstablishmentResource($establishment);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
