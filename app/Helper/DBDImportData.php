<?php

namespace App\Helper;

use App\Models\District;
use App\Models\Province;
use App\Models\Subdistrict;

class DBDImportData extends ImportDataHelper
{

    /**
     * @param $data
     * @return array

        "dataSource": "กรมพัฒนาธุรกิจการค้า",
        "no": "5",
        "registerNo": "xxxxxxxxxxx",
        "registrationName": "xxxxx",
        "establishmentName": "xxxxx",
        "hqAddress": "xx ซอย xx ถนนxx",
        "thambol": "คลองถนน",
        "amphur": "สายไหม",
        "province": "กรุงเทพมหานคร",
        "tsicCode": "G",
        "tsic2Digit": "47",
        "tsic5Digit": "47411",
        "status": "ยังดำเนินกิจการอยู่",
        "sector1": "ภาคการค้าปลีกยกเว้นยานยนต์",
        "sector2": "ภาคการค้า",
        "oldDefinitionSize": "S",
        "newDefinitionSize": "MICRO",
        "oldDefinitionEmp": "8",
        "newDefinitionEmp": "2",
        "registeredCapital": "500,000.00",
        "totalIncome": "383,599.70",
        "profitOrLossAmount": "-13,363.14",
        "totalAssets": "538,224.46"
     */
    public static function transform($data): array
    {
        $result = [
            'establishment' => [],
            'address' => [],
            'tsicSeries' => [],
            'establishmentSize' => [],
            'financialStatement' => [],
            'workForceEmployee' => [],
            'data' => []
        ];

        $result['establishment']['registration_number'] = $data['registerNo'];
        $result['data'][] = [
            'key' => 'registrationName',
            'value' => $data['registrationName'],
            'value_type' => 'string',
            'table' => 'establishments'
        ];
        $result['establishment']['name'] = $data['establishmentName'];
        $result['data'][] = [
            'key' => 'hqAddress',
            'value' => $data['hqAddress'],
            'value_type' => 'string',
            'table' => 'addresses'
        ];

        $province = Province::where('name', $data['province'])->first();
        if (!$province) {
            $province = new Province();
            $province->name = $data['province'];
            $province->save();
        }

        $district = District::where('name', $data['amphur'])->first();
        if (!$district) {
            $district = new District();
            $district->name = $data['amphur'];
            $district->province_id = $province->id;
            $district->save();
        }

        $subdistrict = Subdistrict::where('name', $data['thambol'])->first();
        if (!$subdistrict) {
            $subdistrict = new Subdistrict();
            $subdistrict->name = $data['thambol'];
            $subdistrict->district_id = $district->id;
            $subdistrict->save();
        }

        $result['address']['subdistrict_id'] = $subdistrict->id;

        $result['tsicSeries']['tsic_code'] = $data['tsic5Digit'];
        $result['establishment']['tsic_code'] = $data['tsic5Digit'];
        $result['tsicSeries']['type'] = $data['tsicCode'];
        $result['establishment']['status'] = $data['status'];
        $result['data'][] = [
            'key' => 'sector1',
            'value' => $data['sector1'],
            'value_type' => 'string',
            'table' => 'tsic_series'
        ];
        $result['data'][] = [
            'key' => 'sector2',
            'value' => $data['sector2'],
            'value_type' => 'string',
            'table' => 'tsic_series'
        ];
        $result['establishmentSize'][0]['size_code'] = $data['oldDefinitionSize'];
        $result['establishmentSize'][0]['type'] = 'oldDefinitionSize';
        $result['establishmentSize'][1]['size_code'] = $data['newDefinitionSize'];
        $result['establishmentSize'][1]['type'] = 'newDefinitionSize';

        $result['data'][] = [
            'key' => 'oldDefinitionEmp',
            'value' => $data['oldDefinitionEmp'],
            'value_type' => 'string',
            'table' => 'work_force_employees'
        ];
        $result['data'][] = [
            'key' => 'newDefinitionEmp',
            'value' => $data['newDefinitionEmp'],
            'value_type' => 'string',
            'table' => 'work_force_employees'
        ];

        $result['financialStatement']['registered_capital'] = self::tofloat($data['registeredCapital']);
        $result['financialStatement']['total_income'] = self::tofloat($data['totalIncome']);
        $result['financialStatement']['net_profit_loss'] = self::tofloat($data['profitOrLossAmount']);
        $result['financialStatement']['total_assets'] = self::tofloat($data['totalAssets']);
        return $result;
    }

    public static function tofloat($val) {
        return floatval(str_replace(",","",$val));;
    }
}
