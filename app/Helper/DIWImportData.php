<?php

namespace App\Helper;

use App\Models\District;
use App\Models\Province;
use App\Models\Region;
use App\Models\Subdistrict;
use Carbon\Carbon;

class DIWImportData extends ImportDataHelper
{

    /**
     * @param $data
     * @return array
     *
{"_id":{"$oid":"61ed1227f36563fed62fe6cb"},
"dataSource":"กรมโรงงานอุตสาหกรรม",
"code":"3-77(2)-28/51ชบ",
"establishmentName":"บริษัท แม็ค-ไทย อินดัสเตรียล จำกัด",
"ownerName":"บริษัท แม็ค-ไทย อินดัสเตรียล จำกัด",
"purpose":"ผลิตชิ้นส่วนหรืออุปกรณ์สำหรับรถบรรทุกและรถแทรคเตอร์",
"houseNo":"โครงการทองโกรว์ เมืองทองอุตสาหกรรม",
"streetName":"บางนา-ตราด",
"thambolName":"คลองตำหรุ",
"amphurName":"เมืองชลบุรี",
"provinceName":"ชลบุรี",
"postCode":"20000",
"tel":null,
"class":"07702",
"factoryType":"3",
"factoryId":"10200002825515",
"factoryReg":" 07702302851ชบ",
"horsePower":"964.57",
"nuiHorsePower":null,
"TAID":"200112",
"capitalLand":"36000000",
"capitalBuild":"40000000",
"capitalMachine":"30000000",
"capitalWorking":"14000000",
"manSkill":"20",
"manNoSkill":"38",
"womanSkill":"0",
"womanNoSkill":"2",
"expert":"0",
"tech":"0",
"pokDate":"2008-08-29 00:00:00",
"startDate":"2009-02-26 00:00:00",
"ownerHouseNo":"386",
"ownerStreetName":null,
"ownerThambolName":"บางเสาธง",
"ownerAmphurName":"บางเสาธง",
"ownerProvinceName":"สมุทรปราการ",
"ownerPostCode":"10540",
"ownerTel":null,
"registrationNo":"0115551000779",
"isicCode":"29309",
"factoringArea":"17352",
"buildingArea":"5280",
"fFlag":"1",
"capacityProduct":null,
"capacityPerUnit":"ชุด/ปี",
"latitude":null,
"longitude":null,
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
        $result['address']['street'] = $data['streetName'];
        $result['address']['postal_code'] = $data['postCode'];
        $result['address']['phone'] = $data['tel'];
        $result['address']['latitude'] = $data['latitude'];
        $result['address']['longitude'] = $data['longitude'];
        $result['address']['type'] = 'factory';

        $result['tsicSeries']['tsic_code'] = $data['isicCode'];
        $result['establishment']['tsic_code'] = $data['isicCode'];
        $result['tsicSeries']['type'] = 'isic';

        $result['establishment']['date_start'] = $data['startDate'];
        $result['establishment']['phone'] = $data['tel'];
        $result['establishment']['status'] = $data['status'];

        $result['workForceEmployee']['no_employee'] = $data['manSkill'] + $data['manNoSkill'] + $data['womanSkill'] + $data['womanNoSkill'];
        $result['workForceEmployee']['no_person_engaged'] = $result['workForceEmployee']['no_employee'];


        if ($data['purpose']) {
            $result['data'][] = [
                'key' => 'purpose',
                'value' => $data['purpose'],
                'value_type' => 'string',
                'table' => 'establishments'
            ];
        }

        if ($data['code']) {
            $result['data'][] = [
                'key' => 'code',
                'value' => $data['code'],
                'value_type' => 'string',
                'table' => 'establishments'
            ];
        }

        if ($data['class']) {
            $result['data'][] = [
                'key' => 'class',
                'value' => $data['class'],
                'value_type' => 'string',
                'table' => 'establishments'
            ];
        }

        if ($data['factoryReg']) {
            $result['data'][] = [
                'key' => 'factoryReg',
                'value' => $data['factoryReg'],
                'value_type' => 'string',
                'table' => 'addresses'
            ];
        }

        if ($data['factoringArea']) {
            $result['data'][] = [
                'key' => 'factoringArea',
                'value' => $data['factoringArea'],
                'value_type' => 'number',
                'table' => 'addresses'
            ];
        }

        if ($data['buildingArea']) {
            $result['data'][] = [
                'key' => 'buildingArea',
                'value' => $data['buildingArea'],
                'value_type' => 'number',
                'table' => 'addresses'
            ];
        }

        if ($data['capacityProduct']) {
            $result['data'][] = [
                'key' => 'capacityProduct',
                'value' => $data['capacityProduct'],
                'value_type' => 'string',
                'table' => 'addresses'
            ];
        }

        if ($data['capacityPerUnit']) {
            $result['data'][] = [
                'key' => 'capacityPerUnit',
                'value' => $data['capacityPerUnit'],
                'value_type' => 'string',
                'table' => 'addresses'
            ];
        }

        if ($data['capitalLand']) {
            $result['data'][] = [
                'key' => 'capitalLand',
                'value' => $data['capitalLand'],
                'value_type' => 'number',
                'table' => 'addresses'
            ];
        }

        if ($data['capitalBuild']) {
            $result['data'][] = [
                'key' => 'capitalBuild',
                'value' => $data['capitalBuild'],
                'value_type' => 'number',
                'table' => 'addresses'
            ];
        }

        if ($data['capitalMachine']) {
            $result['data'][] = [
                'key' => 'capitalMachine',
                'value' => $data['capitalMachine'],
                'value_type' => 'number',
                'table' => 'addresses'
            ];
        }

        if ($data['capitalWorking']) {
            $result['data'][] = [
                'key' => 'capitalWorking',
                'value' => $data['capitalWorking'],
                'value_type' => 'number',
                'table' => 'addresses'
            ];
        }

        if ($data['expert']) {
            $result['data'][] = [
                'key' => 'expert',
                'value' => $data['expert'],
                'value_type' => 'number',
                'table' => 'addresses'
            ];
        }

        if ($data['tech']) {
            $result['data'][] = [
                'key' => 'tech',
                'value' => $data['tech'],
                'value_type' => 'number',
                'table' => 'addresses'
            ];
        }

        if ($data['horsePower']) {
            $result['data'][] = [
                'key' => 'horsePower',
                'value' => $data['horsePower'],
                'value_type' => 'number',
                'table' => 'addresses'
            ];
        }

        if ($data['nuiHorsePower']) {
            $result['data'][] = [
                'key' => 'nuiHorsePower',
                'value' => $data['nuiHorsePower'],
                'value_type' => 'number',
                'table' => 'addresses'
            ];
        }

        $data['people']['firstname'] = $data['ownerName'];
        $data['people']['type'] = 'owner';
        $data['people']['identification_number'] = $data['registrationNo'];

        if ($data['ownerHouseNo']) {
            $result['data'][] = [
                'key' => 'ownerHouseNo',
                'value' => $data['ownerHouseNo'],
                'value_type' => 'string',
                'table' => 'people'
            ];
        }

        if ($data['ownerStreetName']) {
            $result['data'][] = [
                'key' => 'ownerStreetName',
                'value' => $data['ownerStreetName'],
                'value_type' => 'string',
                'table' => 'people'
            ];
        }

        if ($data['ownerThambolName']) {
            $result['data'][] = [
                'key' => 'ownerThambolName',
                'value' => $data['ownerThambolName'],
                'value_type' => 'string',
                'table' => 'people'
            ];
        }

        if ($data['ownerAmphurName']) {
            $result['data'][] = [
                'key' => 'ownerAmphurName',
                'value' => $data['ownerAmphurName'],
                'value_type' => 'string',
                'table' => 'people'
            ];
        }

        if ($data['ownerProvinceName']) {
            $result['data'][] = [
                'key' => 'ownerProvinceName',
                'value' => $data['ownerProvinceName'],
                'value_type' => 'string',
                'table' => 'people'
            ];
        }

        if ($data['ownerPostCode']) {
            $result['data'][] = [
                'key' => 'ownerPostCode',
                'value' => $data['ownerPostCode'],
                'value_type' => 'string',
                'table' => 'people'
            ];
        }

        if ($data['ownerTel']) {
            $result['data'][] = [
                'key' => 'ownerTel',
                'value' => $data['ownerTel'],
                'value_type' => 'string',
                'table' => 'people'
            ];
        }

        return $result;
    }
}
