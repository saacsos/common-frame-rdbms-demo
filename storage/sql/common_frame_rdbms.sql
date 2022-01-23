CREATE TABLE `establishments` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `es_id` varchar(255) UNIQUE,
  `title` varchar(255),
  `name` varchar(255),
  `registration_number` char(13),
  `h_registration_number` char(13),
  `establishment_type_id` int,
  `tsic_code` varchar(255),
  `ftsic_code` varchar(255),
  `date_start` date,
  `date_end` date,
  `status` VARCHAR(255),
  `phone` varchar(255),
  `ecommerce_operation` boolean,
  `covid_impacted` boolean,
  `created_at` datetime,
  `updated_at` datetime
);

CREATE TABLE `establishment_sizes` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `establishment_id` int,
  `size_code` varchar(255),
  `type` varchar(255),
  `datasource_id` int,
  `created_at` datetime,
  `updated_at` datetime
);

CREATE TABLE `establishment_types` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `name` varchar(255),
  `created_at` datetime,
  `updated_at` datetime
);

CREATE TABLE `regions` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `name` varchar(255),
  `created_at` datetime,
  `updated_at` datetime
);

CREATE TABLE `provinces` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `name` varchar(255) COMMENT 'province name',
  `region_id` int,
  `created_at` datetime,
  `updated_at` datetime
);

CREATE TABLE `districts` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `name` varchar(255),
  `province_id` int,
  `created_at` datetime,
  `updated_at` datetime
);

CREATE TABLE `subdistricts` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `name` varchar(255),
  `district_id` int,
  `created_at` datetime,
  `updated_at` datetime
);

CREATE TABLE `addresses` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `subdistrict_id` int,
  `district` varchar(255),
  `village` varchar(255),
  `building` varchar(255),
  `street` varchar(255),
  `soi` varchar(255),
  `house_no` varchar(255),
  `postal_code` char(5),
  `email` varchar(255),
  `phone` varchar(255),
  `administrative_area` VARCHAR(255),
  `municipality_name` varchar(255),
  `enumeration_area` char(4),
  `type` varchar(255),
  `latitude` double,
  `longitude` double,
  `datasource_id` int,
  `created_at` datetime,
  `updated_at` datetime
);

CREATE TABLE `address_series` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `address_establishment_id` int,
  `subdistrict_id` int,
  `district` varchar(255),
  `village` varchar(255),
  `building` varchar(255),
  `street` varchar(255),
  `soi` varchar(255),
  `house_no` varchar(255),
  `postal_code` char(5),
  `email` varchar(255),
  `phone` varchar(255),
  `administrative_area` VARCHAR(255),
  `municipality_name` varchar(255),
  `enumeration_area` char(4),
  `type` varchar(255),
  `latitude` double,
  `longitude` double,
  `datasource_id` int,
  `started_at` datetime,
  `ended_at` datetime
);

CREATE TABLE `address_establishment` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `establishment_id` int,
  `address_id` int,
  `establishment_type` varchar(255),
  `building_type` varchar(255)
);

CREATE TABLE `people` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `identification_number` varchar(255),
  `title` varchar(255),
  `rank_position` varchar(255),
  `firstname` varchar(255),
  `lastname` varchar(255),
  `type` VARCHAR(255),
  `created_at` datetime,
  `updated_at` datetime
);

CREATE TABLE `establishment_people` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `establishment_id` int,
  `people_id` int
);

CREATE TABLE `data` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `establishment_id` int,
  `table` varchar(255) COMMENT 'model or domain of data',
  `key` varchar(255),
  `value` varchar(255),
  `value_type` varchar(255),
  `created_at` datetime,
  `updated_at` datetime
);

CREATE TABLE `data_templates` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `establishment_type_id` int,
  `key` varchar(255),
  `value_type` varchar(255),
  `is_required` boolean,
  `validate_regex` varchar(255),
  `created_at` datetime,
  `updated_at` datetime
);

CREATE TABLE `data_series` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `establishment_id` int,
  `table` varchar(255),
  `key` varchar(255),
  `value` varchar(255),
  `value_type` varchar(255),
  `started_at` datetime,
  `ended_at` datetime
);

CREATE TABLE `tsic_series` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `establishment_id` int,
  `tsic_code` varchar(255),
  `datasource_id` int,
  `type` varchar(255),
  `started_at` datetime,
  `ended_at` datetime
);

CREATE TABLE `covid_impacted_series` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `establishment_id` int,
  `covid_impacted` boolean,
  `datasource_id` int,
  `started_at` datetime,
  `ended_at` datetime
);

CREATE TABLE `financial_statements` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `establishment_id` int,
  `registered_capital` double,
  `total_income` double,
  `net_profit_loss` double,
  `total_assets` double,
  `datasource_id` int,
  `created_at` datetime,
  `updated_at` datetime
);

CREATE TABLE `financial_statement_series` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `establishment_id` int,
  `registered_capital` double,
  `total_income` double,
  `net_profit_loss` double,
  `total_assets` double,
  `datasource_id` int,
  `started_at` datetime,
  `ended_at` datetime
);

CREATE TABLE `datasources` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `name` varchar(255),
  `created_at` datetime,
  `updated_at` datetime
);

CREATE TABLE `work_force_employees` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `establishment_id` int,
  `no_person_engaged` int,
  `no_employee` int,
  `datasource_id` int,
  `created_at` datetime,
  `updated_at` datetime
);

CREATE TABLE `work_force_employee_series` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `establishment_id` int,
  `no_person_engaged` int,
  `no_employee` int,
  `datasource_id` int,
  `started_at` datetime,
  `ended_at` datetime
);

ALTER TABLE `establishments` ADD FOREIGN KEY (`establishment_type_id`) REFERENCES `establishment_types` (`id`);

ALTER TABLE `establishment_sizes` ADD FOREIGN KEY (`establishment_id`) REFERENCES `establishments` (`id`);

ALTER TABLE `establishment_sizes` ADD FOREIGN KEY (`datasource_id`) REFERENCES `datasources` (`id`);

ALTER TABLE `provinces` ADD FOREIGN KEY (`region_id`) REFERENCES `regions` (`id`);

ALTER TABLE `districts` ADD FOREIGN KEY (`province_id`) REFERENCES `provinces` (`id`);

ALTER TABLE `subdistricts` ADD FOREIGN KEY (`district_id`) REFERENCES `districts` (`id`);

ALTER TABLE `addresses` ADD FOREIGN KEY (`subdistrict_id`) REFERENCES `subdistricts` (`id`);

ALTER TABLE `addresses` ADD FOREIGN KEY (`datasource_id`) REFERENCES `datasources` (`id`);

ALTER TABLE `address_series` ADD FOREIGN KEY (`address_establishment_id`) REFERENCES `address_establishment` (`id`);

ALTER TABLE `address_series` ADD FOREIGN KEY (`subdistrict_id`) REFERENCES `subdistricts` (`id`);

ALTER TABLE `address_series` ADD FOREIGN KEY (`datasource_id`) REFERENCES `datasources` (`id`);

ALTER TABLE `address_establishment` ADD FOREIGN KEY (`establishment_id`) REFERENCES `establishments` (`id`);

ALTER TABLE `address_establishment` ADD FOREIGN KEY (`address_id`) REFERENCES `addresses` (`id`);

ALTER TABLE `establishment_people` ADD FOREIGN KEY (`establishment_id`) REFERENCES `establishments` (`id`);

ALTER TABLE `establishment_people` ADD FOREIGN KEY (`people_id`) REFERENCES `people` (`id`);

ALTER TABLE `data` ADD FOREIGN KEY (`establishment_id`) REFERENCES `establishments` (`id`);

ALTER TABLE `data_templates` ADD FOREIGN KEY (`establishment_type_id`) REFERENCES `establishment_types` (`id`);

ALTER TABLE `data_series` ADD FOREIGN KEY (`establishment_id`) REFERENCES `establishments` (`id`);

ALTER TABLE `tsic_series` ADD FOREIGN KEY (`establishment_id`) REFERENCES `establishments` (`id`);

ALTER TABLE `tsic_series` ADD FOREIGN KEY (`datasource_id`) REFERENCES `datasources` (`id`);

ALTER TABLE `covid_impacted_series` ADD FOREIGN KEY (`establishment_id`) REFERENCES `establishments` (`id`);

ALTER TABLE `covid_impacted_series` ADD FOREIGN KEY (`datasource_id`) REFERENCES `datasources` (`id`);

ALTER TABLE `financial_statements` ADD FOREIGN KEY (`establishment_id`) REFERENCES `establishments` (`id`);

ALTER TABLE `financial_statements` ADD FOREIGN KEY (`datasource_id`) REFERENCES `datasources` (`id`);

ALTER TABLE `financial_statement_series` ADD FOREIGN KEY (`establishment_id`) REFERENCES `establishments` (`id`);

ALTER TABLE `financial_statement_series` ADD FOREIGN KEY (`datasource_id`) REFERENCES `datasources` (`id`);

ALTER TABLE `work_force_employees` ADD FOREIGN KEY (`establishment_id`) REFERENCES `establishments` (`id`);

ALTER TABLE `work_force_employees` ADD FOREIGN KEY (`datasource_id`) REFERENCES `datasources` (`id`);

ALTER TABLE `work_force_employee_series` ADD FOREIGN KEY (`establishment_id`) REFERENCES `establishments` (`id`);

ALTER TABLE `work_force_employee_series` ADD FOREIGN KEY (`datasource_id`) REFERENCES `datasources` (`id`);
