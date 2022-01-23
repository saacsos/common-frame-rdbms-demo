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
     *
    {"_id":{"$oid":"61ed1227f36563fed62fe657"},
    "dataSource":"สำนักงานสถิติแห่งชาติ",
    "esId":"591106193744752",
    "regionId":"2",
    "regionName":"กลาง",
    "provinceId":"11",
    "provinceName":"สมุทรปราการ",
    "amphurId":"03",
    "amphurName":"บางพลี",
    "thambolId":"02",
    "thambolName":"บางแก้ว",
    "district":"2",
    "village":"13",
    "villageName":"คลองดอกไม้",
    "municipalityCode":null,
    "municipalityName":null,
    "taoCode":"002",
    "taoName":"บางแก้ว",
    "enumerationArea":"032",
    "status":"1",
    "streetName":"บางนา-ตราด",
    "soi":"สันตินคร",
    "building":null,
    "houseNo":"11/168",
    "initial":"บจก.",
    "firstname":null,
    "lastname":null,
    "establishmentName":"บางกอกทูลลิ่ง เซ็นเตอร์",
    "ownerName":"xxx",
    "tsicFiveDigit":"47524",
    "fTsicFiveDigit":"47524",
    "isicCode":null,
    "totalWorker":"105",
    "employee":"100",
    "econAct":"06",
    "establishmentType":"ขายวัสดุก่อสร้าง",
    "legalFm":"3",
    "econFm":"1",
    "regStat":null,
    "registrationCid":null,
    "registrationNo":"0115550004312",
    "passportNo":"",
    "taxIdentificationNo":"",
    "invest":"4",
    "yearStart":"2550",
    "yearOpr":"10",
    "computer":"1",
    "internet":"1",
    "ecmOwn":null,
    "ecmOther":null,
    "ecmMarket":null,
    "ecmSocial":null,
    "ecmNone":"1",
    "telNo":null,
    "faxNo":null,
    "email":null,
    "url":null,
    "updteSrc":"1",
    "prjCode":"011041",
    "condYear":"2560",
    "condRound":"1",
    "extSrc":null,
    "extSrcN":null,
    "contact":null,
    "listing":"1",
    "dummyId":"591103022032025",
    "remark":null,
    "updatedate":"20190204",
    "hqInitial":null,
    "hqFname":null,
    "hqLname":null,
    "hqEstablishmentName":null,
    "hqStreetName":null,
    "hqSoi":null,
    "hqBuilding":null,
    "hqHouseNo":null,
    "hqThambol":null,
    "hqAmphur":null,
    "hqProvince":null,
    "username":"",
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
        $result['establishment']['es_id'] = $data['esId'];
        $result['establishment']['title'] = $data['initial'];
        $result['establishment']['name'] = $data['establishmentName'];
        $result['establishment']['phone'] = $data['telNo'];
        $result['establishment']['date_start'] = Carbon::createFromDate($data['yearStart'], 1, 1);

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

        $result['tsicSeries']['tsic_code'] = $data['tsicFiveDigit'];
        $result['establishment']['tsic_code'] = $data['tsicFiveDigit'];
        $result['establishment']['ftsic_code'] = $data['fTsicFiveDigit'];
        $result['establishment']['status'] = $data['status'];

        $region = Region::where('name', $data['regionName'])->first();
        if (! $region) {
            $region = new Region();
            $region->name = $data['regionName'];
            $region->save();
        }

        $province = Province::where('name', $data['provinceName'])->first();
        if (!$province) {
            $province = new Province();
            $province->name = $data['provinceName'];
            $province->save();
        }

        if (!$province->region_id) {
            $province->region_id = $region->id;
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
        $result['address']['district'] = $data['district'];
        $result['address']['village'] = $data['villageName'];
        $result['address']['building'] = $data['building'];
        $result['address']['street'] = $data['streetName'];
        $result['address']['soi'] = $data['soi'];
        $result['address']['house_no'] = $data['houseNo'];
        $result['address']['enumeration_area'] = $data['enumerationArea'];
        $result['address']['municipality_name'] = $data['municipalityName'];
        $result['address']['phone'] = $data['telNo'];
        $result['address']['email'] = $data['email'];

        if ($data['establishmentType']) {
            $esblishmentType = EstablishmentType::where('name', $data['establishmentType'])->first();
            if (!$esblishmentType) {
                $esblishmentType = new EstablishmentType();
                $esblishmentType->name = $data['establishmentType'];
                $esblishmentType->save();
            }
            $result['establishment']['establishment_type_id'] = $esblishmentType->id;
        }

        $result['workForceEmployee']['no_person_engaged'] = $data['totalWorker'];
        $result['workForceEmployee']['no_employee'] = $data['employee'];

        return $result;
    }
}
