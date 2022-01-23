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
        {"_id":{"$oid":"61ed1227f36563fed62fe5ee"},
        "dataSource":"กรมพัฒนาธุรกิจการค้า",
     "no":"5",
     "registrationNo":"0103535037556",
     "registrationName":"หจ.เอส.เอ็ม.โอ.สตีล",
     "establishmentName":"xxxxx",
     "houseNo":"3769/229-230 ตรอกนอกเขต ถนนสุดประเสริฐ",
     "thambolName":"บางโคล่",
     "amphurName":"บางคอแหลม",
     "provinceName":"กรุงเทพมหานคร",
     "tsicCode":"G",
     "tsicTwoDigit":"46",
     "tsicFiveDigit":"46622",
     "sector1":"ภาคการค้าส่งยกเว้นยานยนต์",
     "sector2":"ภาคการค้า",
     "oldDefinitionSize":"S",
     "newDefinitionSize":"MICRO",
     "oldDefinitionEmp":"9",
     "newDefinitionEmp":"4",
     "registeredCapital":"1,000,000",
     "totalIncome":"3,561,661.00",
     "profitOrLossAmount":"729,669.00",
     "totalAssets":"7,210,380.00",
     "status":"ยังดำเนินกิจการอยู่",
     "createdAt":"2022-01-23-15:30:31"}
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
            'data' => [],
            'people' => []
        ];

        $result['establishment']['registration_number'] = $data['registrationNo'];
        $result['data'][] = [
            'key' => 'registrationName',
            'value' => $data['registrationName'],
            'value_type' => 'string',
            'table' => 'establishments'
        ];
        $result['establishment']['name'] = $data['establishmentName'];

        $province = Province::where('name', $data['provinceName'])->first();
        if (!$province) {
            $province = new Province();
            $province->name = $data['provinceName'];
            $province->save();
        }

        $district = District::where('name', $data['amphurName'])->first();
        if (!$district) {
            $district = new District();
            $district->name = $data['amphurName'];
            $district->province_id = $province->id;
            $district->save();
        }

        $subdistrict = Subdistrict::where('name', $data['thambolName'])->first();
        if (!$subdistrict) {
            $subdistrict = new Subdistrict();
            $subdistrict->name = $data['thambolName'];
            $subdistrict->district_id = $district->id;
            $subdistrict->save();
        }

        $result['address']['subdistrict_id'] = $subdistrict->id;
        $result['address']['house_no'] = $data['houseNo'];

        $result['tsicSeries']['tsic_code'] = $data['tsicFiveDigit'];
        $result['establishment']['tsic_code'] = $data['tsicFiveDigit'];
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
}
