<?php

namespace App\Helper;

use App\Models\District;
use App\Models\EstablishmentType;
use App\Models\Province;
use App\Models\Region;
use App\Models\Subdistrict;
use Carbon\Carbon;

class NSOImportData extends ImportDataHelper
{

    /**
     * @param $data
     * @return array
    {
    "dataSource": "สำนักงานสถิติแห่งชาติ",
    "esId": "xxxxxxxxxxxxxxx",
    "reg": "1",
    "regName": "กรุงเทพมหานคร",
    "cwt": "10",
    "cwtName": "กรุงเทพมหานคร",
    "amp": "01",
    "ampName": "พระนคร",
    "tam": "01",
    "tamName": "พระบรมมหาราชวัง",
    "district": "1",
    "vil": "",
    "vilName": "",
    "munCode": "000",
    "munName": "กรุงเทพมหานคร",
    "taoCode": "",
    "taoName": "",
    "ea": "001",
    "status": "1",
    "street": "มหาราช",
    "soi": "ตรอกนคร",
    "building": "",
    "houseNo": "xxx",
    "initial": "ร้าน",
    "firstname": "",
    "lastname": "",
    "compName": "xxx",
    "esName": "xxxxx",
    "tsicCode": "14120",
    "ftsicCode": "14120",
    "isicCode": "",
    "totWorker": "2",
    "employee": "1",
    "econAct": "01",
    "esType": "รับตัดชุดครุย",
    "legalFm": "1",
    "econFm": "1",
    "regStat": "1",
    "regisCid": "xxxxxxxxxxxxx",
    "regisNo": "",
    "passportNo": "",
    "taxIdentificationNo": "",
    "invest": "",
    "yearStart": "2550",
    "yearOpr": "10",
    "computer": "3",
    "internet": "2",
    "ecmOwn": "",
    "ecmOther": "",
    "ecmMarket": "",
    "ecmSocial": "",
    "ecmNone": "1",
    "telNo": "xxxxxxxxx",
    "faxNo": "",
    "email": "",
    "url": "",
    "updteSrc": "1",
    "prjCode": "011041",
    "condYear": "2560",
    "condRound": "1",
    "extSrc": "",
    "extSrcN": "",
    "contact": "",
    "listing": "1",
    "dummyId": "",
    "remark": "",
    "updatedate": "20190204",
    "hqInitial": "",
    "hqFname": "",
    "hqLname": "",
    "hqCpName": "",
    "hqStreet": "",
    "hqSoi": "",
    "hqBld": "",
    "hqHouse": "",
    "hqTam": "",
    "hqAmp": "",
    "hqCwt": "",
    "username": "sys"
    },
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

        $result['establishment']['registration_number'] = $data['regisNo'];
        $result['establishment']['es_id'] = $data['esId'];
        $result['establishment']['title'] = $data['initial'];
        $result['establishment']['name'] = $data['esName'];
        $result['establishment']['phone'] = $data['telNo'];
        $result['establishment']['date_start'] = Carbon::createFromDate($data['yearStart'], 1, 1);

        if ($data['compName']) {
            $result['data'][] = [
                'key' => 'compName',
                'value' => $data['compName'],
                'value_type' => 'string',
                'table' => 'establishments'
            ];
        }

        if ($data['ecmOwn']) {
            $result['data'][] = [
                'key' => 'ecmOwn',
                'value' => $data['ecmOwn'],
                'value_type' => 'string',
                'table' => 'establishments'
            ];
        }

        if ($data['ecmMarket']) {
            $result['data'][] = [
                'key' => 'ecmMarket',
                'value' => $data['ecmMarket'],
                'value_type' => 'string',
                'table' => 'establishments'
            ];
        }

        if ($data['ecmSocial']) {
            $result['data'][] = [
                'key' => 'ecmSocial',
                'value' => $data['ecmSocial'],
                'value_type' => 'string',
                'table' => 'establishments'
            ];
        }

        if ($data['ecmNone']) {
            $result['data'][] = [
                'key' => 'ecmNone',
                'value' => $data['ecmNone'],
                'value_type' => 'string',
                'table' => 'establishments'
            ];
        }

        if ($data['faxNo']) {
            $result['data'][] = [
                'key' => 'faxNo',
                'value' => $data['faxNo'],
                'value_type' => 'string',
                'table' => 'addresses'
            ];
        }

        if ($data['url']) {
            $result['data'][] = [
                'key' => 'url',
                'value' => $data['url'],
                'value_type' => 'string',
                'table' => 'addresses'
            ];
        }

        if ($data['isicCode']) {
            $result['data'][] = [
                'key' => 'isicCode',
                'value' => $data['isicCode'],
                'value_type' => 'string',
                'table' => 'establishments'
            ];
        }



        $result['tsicSeries']['tsic_code'] = $data['tsicCode'];
        $result['establishment']['tsic_code'] = $data['tsicCode'];
        $result['establishment']['ftsic_code'] = $data['ftsicCode'];
        $result['establishment']['status'] = $data['status'];


        $region = Region::where('name', $data['regName'])->first();
        if (! $region) {
            $region = new Region();
            $region->name = $data['regName'];
            $region->save();
        }

        $province = Province::where('name', $data['cwtName'])->first();
        if (!$province) {
            $province = new Province();
            $province->name = $data['cwtName'];
            $province->save();
        }

        if (!$province->region_id) {
            $province->region_id = $region->id;
            $province->save();
        }

        $district = District::where('name', $data['ampName'])->first();
        if (!$district) {
            $district = new District();
            $district->name = $data['ampName'];
            $district->province_id = $province->id;
            $district->save();
        }

        $subdistrict = Subdistrict::where('name', $data['tamName'])->first();
        if (!$subdistrict) {
            $subdistrict = new Subdistrict();
            $subdistrict->name = $data['tamName'];
            $subdistrict->district_id = $district->id;
            $subdistrict->save();
        }

        $result['address']['subdistrict_id'] = $subdistrict->id;
        $result['address']['district'] = $data['district'];
        $result['address']['village'] = $data['vilName'];
        $result['address']['building'] = $data['building'];
        $result['address']['street'] = $data['street'];
        $result['address']['soi'] = $data['soi'];
        $result['address']['house_no'] = $data['houseNo'];
        $result['address']['enumeration_area'] = $data['ea'];
        $result['address']['municipality_name'] = $data['munName'];
        $result['address']['phone'] = $data['telNo'];
        $result['address']['email'] = $data['email'];

        if ($data['esType']) {
            $esblishmentType = EstablishmentType::where('name', $data['esType'])->first();
            if (!$esblishmentType) {
                $esblishmentType = new EstablishmentType();
                $esblishmentType->name = $data['esType'];
                $esblishmentType->save();
            }
            $result['establishment']['establishment_type_id'] = $esblishmentType->id;
        }

        $result['workForceEmployee']['no_person_engaged'] = $data['totWorker'];
        $result['workForceEmployee']['no_employee'] = $data['employee'];

        return $result;
    }
}
