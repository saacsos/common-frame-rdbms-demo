<?php

namespace App\Http\Controllers\Api;

use App\Helper\DBDImportData;
use App\Helper\NSOImportData;
use App\Http\Controllers\Controller;
use App\Http\Resources\EstablishmentResource;
use App\Models\Address;
use App\Models\Data;
use App\Models\Datasource;
use App\Models\Establishment;
use App\Models\EstablishmentSize;
use App\Models\FinancialStatement;
use App\Models\TsicSeries;
use App\Models\WorkForceEmployee;
use Illuminate\Http\Request;

class EstablishmentsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        $establishments = Establishment::take(100)->get();
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

            if ($datasource->id === 2) {
                $data = DBDImportData::transform($d);
            } else if ($datasource->id === 1) {
                $data = NSOImportData::transform($d);
            }
            $this->insertOrUpdate($data, $datasource->id);
        }

        return response()->json([
            'success' => true,
            'data' => $data
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
        $data['tsicSeries']['datasource_id'] = $datasource_id;
        $data['tsicSeries']['establishment_id'] = $establishment->id;
        $data['financialStatement']['datasource_id'] = $datasource_id;
        $data['financialStatement']['establishment_id'] = $establishment->id;
        foreach ($data['data'] as $d) {
            $d['establishment_id'] = $establishment->id;
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
