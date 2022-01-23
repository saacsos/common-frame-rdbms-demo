CREATE OR REPLACE VIEW establishments_in_provinces AS
SELECT Province.`name` AS `province_name`, Region.`name` AS `region_name`, COUNT(DISTINCT(Es.id)) AS establishment_count
FROM `establishments` AS Es
         JOIN `address_establishment` AS AddrEs ON (Es.id = AddrEs.establishment_id)
         JOIN `addresses` AS Address ON (Address.id = AddrEs.address_id)
         JOIN `subdistricts` AS SubDistrict ON (SubDistrict.id = Address.subdistrict_id)
         JOIN `districts` AS District ON (District.id = SubDistrict.district_id)
         JOIN `provinces` AS Province ON (Province.id = District.province_id)
         JOIN `regions` AS Region ON (Region.id = Province.region_id)

GROUP BY Province.`name`, Region.`name`;
