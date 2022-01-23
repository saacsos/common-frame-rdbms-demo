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
    {
        "dataSource": "กรมโรงงานอุตสาหกรรม",
        "code": "x-xx(x)-x/xx",
        "factoryName": "xxx",
        "ownerName": "xxx",
        "perpose": "พิมพ์สิ่งพิมพ์ต่าง ๆ ",
        "houseNo": "xx",
        "streetName": "มหาราช",
        "thambol": "พระบรมมหาราชวัง",
        "amphur": "พระนคร",
        "province": "กรุงเทพมหานคร",
        "postCode": "10200",
        "tel": "xxxxxxxxx",
        "class": "4101",
        "factoryType": "2",
        "factoryId": "xxxxxxxxxxxxxx",
        "factoryReg": "xxxxxxxxxxx",
        "horsePower": "39.43",
        "nuiHorsePower": "39.43",
        "TAID": "100101",
        "capitalLand": "0",
        "capitalBuild": "550,000",
        "capitalMachine": "2,400,000",
        "capitalWorking": "400,000",
        "manSkill": "2",
        "manNoSkill": "1",
        "womanSkill": "5",
        "womanNoSkill": "4",
        "expert": "1",
        "tech": "0",
        "pokDate": "1/4/37",
        "startDate": "2/12/31",
        "ownerAddress": "xx",
        "ownerRoad": "มหาราช",
        "ownerthambol": "พระบรมมหาราชวัง",
        "ownerAmphur": "พระนคร",
        "ownerProvince": "กรุงเทพมหานคร",
        "ownerZipCode": "10200",
        "ownerTel": "xxxxxxxxx",
        "trade": "xxxxxxxxxxxxx",
        "isicCode": "17092",
        "factoringArea": "500",
        "buildingArea": "180",
        "fFlag": "1",
        "capacityProduct": "1",
        "capacityPerUnit": "เล่ม/ปี",
        "latitude": "13.755734",
        "longtitude": "100.489717"
    }
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

        $result['establishment']['registration_number'] = $data['trade'];
        $result['establishment']['name'] = $data['factoryName'];

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
        $result['address']['house_no'] = $data['houseNo'];
        $result['address']['street'] = $data['streetName'];
        $result['address']['postal_code'] = $data['postCode'];
        $result['address']['phone'] = $data['tel'];
        $result['address']['latitude'] = $data['latitude'];
        $result['address']['longitude'] = $data['longtitude'];
        $result['address']['type'] = 'factory';

        $result['tsicSeries']['tsic_code'] = $data['isicCode'];
        $result['establishment']['tsic_code'] = $data['isicCode'];
        $result['tsicSeries']['type'] = 'isic';

        $result['establishment']['date_start'] = $data['startDate'];
        $result['establishment']['phone'] = $data['tel'];
        $result['establishment']['status'] = $data['status'];

        $result['workForceEmployee']['no_employee'] = $data['manSkill'] + $data['manNoSkill'] + $data['womanSkill'] + $data['womanNoSkill'];
        $result['workForceEmployee']['no_person_engaged'] = $result['workForceEmployee']['no_employee'];


        if ($data['perpose']) {
            $result['data'][] = [
                'key' => 'purpose',
                'value' => $data['perpose'],
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
        $data['people']['identification_number'] = $data['trade'];

        if ($data['ownerAddress']) {
            $result['data'][] = [
                'key' => 'ownerAddress',
                'value' => $data['ownerAddress'],
                'value_type' => 'string',
                'table' => 'people'
            ];
        }

        if ($data['ownerRoad']) {
            $result['data'][] = [
                'key' => 'ownerRoad',
                'value' => $data['ownerRoad'],
                'value_type' => 'string',
                'table' => 'people'
            ];
        }

        if ($data['ownerthambol']) {
            $result['data'][] = [
                'key' => 'ownerthambol',
                'value' => $data['ownerthambol'],
                'value_type' => 'string',
                'table' => 'people'
            ];
        }

        if ($data['ownerAmphur']) {
            $result['data'][] = [
                'key' => 'ownerAmphur',
                'value' => $data['ownerAmphur'],
                'value_type' => 'string',
                'table' => 'people'
            ];
        }

        if ($data['ownerProvince']) {
            $result['data'][] = [
                'key' => 'ownerProvince',
                'value' => $data['ownerProvince'],
                'value_type' => 'string',
                'table' => 'people'
            ];
        }

        if ($data['ownerZipCode']) {
            $result['data'][] = [
                'key' => 'ownerZipCode',
                'value' => $data['ownerZipCode'],
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
