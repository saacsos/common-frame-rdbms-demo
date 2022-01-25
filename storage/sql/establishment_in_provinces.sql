CREATE OR REPLACE VIEW establishments_in_provinces AS
SELECT Province.`name` AS `province_name`, Region.`name` AS `region_name`, Es.tsic_code,
       COUNT(DISTINCT(Es.id)) AS establishment_count
FROM `establishments` AS Es
         JOIN `address_establishment` AS AddrEs ON (Es.id = AddrEs.establishment_id)
         JOIN `addresses` AS Address ON (Address.id = AddrEs.address_id)
         JOIN `subdistricts` AS SubDistrict ON (SubDistrict.id = Address.subdistrict_id)
         JOIN `districts` AS District ON (District.id = SubDistrict.district_id)
         JOIN `provinces` AS Province ON (Province.id = District.province_id)
         JOIN `regions` AS Region ON (Region.id = Province.region_id)

GROUP BY Province.`name`, Region.`name`, Es.tsic_code;

CREATE OR REPLACE VIEW work_forces_in_provinces AS
SELECT Province.`name` AS `province_name`, Region.`name` AS `region_name`, Es.tsic_code,
       SUM(WorkForce.no_person_engaged) AS `sum_person_engaged`,
       SUM(WorkForce.no_employee) AS `sum_employee`
FROM `establishments` AS Es
         JOIN `address_establishment` AS AddrEs ON (Es.id = AddrEs.establishment_id)
         JOIN `addresses` AS Address ON (Address.id = AddrEs.address_id)
         JOIN `subdistricts` AS SubDistrict ON (SubDistrict.id = Address.subdistrict_id)
         JOIN `districts` AS District ON (District.id = SubDistrict.district_id)
         JOIN `provinces` AS Province ON (Province.id = District.province_id)
         JOIN `regions` AS Region ON (Region.id = Province.region_id)
         JOIN `work_force_employees` AS WorkForce ON (WorkForce.establishment_id = Es.id)

GROUP BY Province.`name`, Region.`name`, Es.tsic_code;

CREATE OR REPLACE VIEW financial_statements_in_provinces AS
SELECT Province.`name` AS `province_name`, Region.`name` AS `region_name`, Es.tsic_code,
       SUM(Finance.registered_capital) AS `sum_registered_capital`,
       SUM(Finance.total_income) AS `sum_total_income`,
       SUM(Finance.net_profit_loss) AS `sum_net_profit_loss`,
       SUM(Finance.total_assets) AS `sum_total_assets`

FROM `establishments` AS Es
         JOIN `address_establishment` AS AddrEs ON (Es.id = AddrEs.establishment_id)
         JOIN `addresses` AS Address ON (Address.id = AddrEs.address_id)
         JOIN `subdistricts` AS SubDistrict ON (SubDistrict.id = Address.subdistrict_id)
         JOIN `districts` AS District ON (District.id = SubDistrict.district_id)
         JOIN `provinces` AS Province ON (Province.id = District.province_id)
         JOIN `regions` AS Region ON (Region.id = Province.region_id)
         JOIN `financial_statements` AS Finance ON (Finance.establishment_id = Es.id)

GROUP BY Province.`name`, Region.`name`, Es.tsic_code;
