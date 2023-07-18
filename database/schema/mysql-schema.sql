/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
DROP TABLE IF EXISTS `abilities`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `abilities` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `entity_id` int unsigned DEFAULT NULL,
  `entity_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `only_owned` tinyint(1) NOT NULL DEFAULT '0',
  `options` json DEFAULT NULL,
  `scope` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `category` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`id`),
  KEY `abilities_scope_index` (`scope`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `ability_signoff_config_step`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ability_signoff_config_step` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `signoff_config_step_id` bigint unsigned NOT NULL,
  `ability_id` int unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `ability_signoff_config_step_signoff_config_step_id_foreign` (`signoff_config_step_id`),
  KEY `ability_signoff_config_step_ability_id_foreign` (`ability_id`),
  CONSTRAINT `ability_signoff_config_step_ability_id_foreign` FOREIGN KEY (`ability_id`) REFERENCES `abilities` (`id`),
  CONSTRAINT `ability_signoff_config_step_signoff_config_step_id_foreign` FOREIGN KEY (`signoff_config_step_id`) REFERENCES `signoff_config_steps` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `addresses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `addresses` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `addressable_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `addressable_id` int unsigned DEFAULT NULL,
  `address` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address2` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `city` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `province` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `postal_code` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `country_id` bigint unsigned DEFAULT NULL,
  `cloned_from_id` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `addresses_country_id_foreign` (`country_id`),
  CONSTRAINT `addresses_country_id_foreign` FOREIGN KEY (`country_id`) REFERENCES `countries` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `allergen_product`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `allergen_product` (
  `product_id` bigint unsigned DEFAULT NULL,
  `allergen_id` bigint unsigned DEFAULT NULL,
  `contains` int NOT NULL DEFAULT '0',
  KEY `allergen_product_product_id_foreign` (`product_id`),
  KEY `allergen_product_allergen_id_foreign` (`allergen_id`),
  CONSTRAINT `allergen_product_allergen_id_foreign` FOREIGN KEY (`allergen_id`) REFERENCES `allergens` (`id`),
  CONSTRAINT `allergen_product_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `allergens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `allergens` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `api_users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `api_users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `as400_brand_invoices`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `as400_brand_invoices` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `brand_id` bigint unsigned NOT NULL,
  `cheque_number` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `invoice_number` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `voucher_date` date DEFAULT NULL,
  `invoice_date` date DEFAULT NULL,
  `reference` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `invoice_amount` decimal(9,2) NOT NULL,
  `discount_amount` decimal(9,2) NOT NULL DEFAULT '0.00',
  PRIMARY KEY (`id`),
  KEY `as400_brand_invoices_brand_id_foreign` (`brand_id`),
  CONSTRAINT `as400_brand_invoices_brand_id_foreign` FOREIGN KEY (`brand_id`) REFERENCES `brands` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `as400_brand_open_ap`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `as400_brand_open_ap` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `brand_id` bigint unsigned NOT NULL,
  `invoice_number` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `invoice_date` date DEFAULT NULL,
  `reference` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `invoice_amount` decimal(9,2) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `as400_brand_open_ap_brand_id_foreign` (`brand_id`),
  CONSTRAINT `as400_brand_open_ap_brand_id_foreign` FOREIGN KEY (`brand_id`) REFERENCES `brands` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `as400_brand_po_received`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `as400_brand_po_received` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `brand_id` bigint unsigned NOT NULL,
  `po_number` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `po_date` date DEFAULT NULL,
  `status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `as400_brand_po_received_brand_id_foreign` (`brand_id`),
  CONSTRAINT `as400_brand_po_received_brand_id_foreign` FOREIGN KEY (`brand_id`) REFERENCES `brands` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `as400_consignment`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `as400_consignment` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `brand_id` bigint unsigned NOT NULL,
  `consignment` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `as400_consignment_brand_id_foreign` (`brand_id`),
  CONSTRAINT `as400_consignment_brand_id_foreign` FOREIGN KEY (`brand_id`) REFERENCES `brands` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `as400_customer_groups`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `as400_customer_groups` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `as400_customer_groups_code_index` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `as400_customers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `as400_customers` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `customer_number` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `province` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `price_code` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `as400_customers_customer_number_index` (`customer_number`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `as400_freight`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `as400_freight` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `brand_id` bigint unsigned NOT NULL,
  `freight_included` tinyint(1) NOT NULL DEFAULT '0',
  `freight` decimal(5,2) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `as400_freight_brand_id_foreign` (`brand_id`),
  CONSTRAINT `as400_freight_brand_id_foreign` FOREIGN KEY (`brand_id`) REFERENCES `brands` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `as400_margins`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `as400_margins` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `brand_id` bigint unsigned NOT NULL,
  `margin` decimal(5,3) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `as400_margins_brand_id_foreign` (`brand_id`),
  CONSTRAINT `as400_margins_brand_id_foreign` FOREIGN KEY (`brand_id`) REFERENCES `brands` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `as400_pricing`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `as400_pricing` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `product_id` bigint unsigned NOT NULL,
  `wholesale_price` decimal(8,2) NOT NULL,
  `average_landed_cost` decimal(8,2) NOT NULL,
  `duty` decimal(5,2) NOT NULL,
  `edlp_discount` decimal(5,2) NOT NULL,
  `po_price` decimal(8,2) NOT NULL,
  `next_po_price` decimal(8,2) DEFAULT NULL,
  `po_price_expiry` date DEFAULT NULL,
  `taxable` tinyint(1) NOT NULL DEFAULT '0',
  `extra_addon_percent` decimal(5,2) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `as400_pricing_product_id_foreign` (`product_id`),
  CONSTRAINT `as400_pricing_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `as400_special_pricing`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `as400_special_pricing` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `priceable_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `priceable_id` bigint unsigned NOT NULL,
  `price_code` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `price` decimal(8,2) NOT NULL,
  `percent_discount` decimal(6,3) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `extra` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `as400_special_pricing_priceable_type_priceable_id_index` (`priceable_type`,`priceable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `as400_stock_data`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `as400_stock_data` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `product_id` bigint unsigned NOT NULL,
  `description` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `category_code` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` char(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `hide_from_catalogue` tinyint(1) NOT NULL DEFAULT '0',
  `out_of_stock` tinyint(1) NOT NULL DEFAULT '0',
  `last_received` date DEFAULT NULL,
  `expected` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `as400_stock_data_product_id_foreign` (`product_id`),
  CONSTRAINT `as400_stock_data_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `as400_supersedes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `as400_supersedes` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `superseded_id` bigint unsigned NOT NULL,
  `superseding_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `as400_supersedes_superseded_id_foreign` (`superseded_id`),
  KEY `as400_supersedes_superseding_id_foreign` (`superseding_id`),
  CONSTRAINT `as400_supersedes_superseded_id_foreign` FOREIGN KEY (`superseded_id`) REFERENCES `products` (`id`),
  CONSTRAINT `as400_supersedes_superseding_id_foreign` FOREIGN KEY (`superseding_id`) REFERENCES `products` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `as400_upcoming_price_changes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `as400_upcoming_price_changes` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `product_id` bigint unsigned NOT NULL,
  `change_date` date DEFAULT NULL,
  `wholesale_price` decimal(8,2) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `as400_upcoming_price_changes_product_id_foreign` (`product_id`),
  CONSTRAINT `as400_upcoming_price_changes_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `as400_warehouse_stock`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `as400_warehouse_stock` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `product_id` bigint unsigned NOT NULL,
  `warehouse` int unsigned DEFAULT NULL,
  `unit_cost` decimal(8,2) DEFAULT NULL,
  `quantity` int DEFAULT NULL,
  `expiry` date DEFAULT NULL,
  `tag` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `as400_warehouse_stock_product_id_foreign` (`product_id`),
  CONSTRAINT `as400_warehouse_stock_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `as400_zeus_retailers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `as400_zeus_retailers` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `invoice_date` date DEFAULT NULL,
  `category` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `customer_number` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `city` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `province` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `postal_code` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `contact_email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `contact_phone` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `assigned_roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `assigned_roles` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `role_id` int unsigned NOT NULL,
  `entity_id` int unsigned NOT NULL,
  `entity_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `restricted_to_id` int unsigned DEFAULT NULL,
  `restricted_to_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `scope` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `assigned_roles_entity_index` (`entity_id`,`entity_type`,`scope`),
  KEY `assigned_roles_role_id_index` (`role_id`),
  KEY `assigned_roles_scope_index` (`scope`),
  CONSTRAINT `assigned_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `brand_broker`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `brand_broker` (
  `brand_id` bigint unsigned DEFAULT NULL,
  `broker_id` bigint unsigned DEFAULT NULL,
  KEY `brand_broker_brand_id_foreign` (`brand_id`),
  KEY `brand_broker_broker_id_foreign` (`broker_id`),
  CONSTRAINT `brand_broker_brand_id_foreign` FOREIGN KEY (`brand_id`) REFERENCES `brands` (`id`),
  CONSTRAINT `brand_broker_broker_id_foreign` FOREIGN KEY (`broker_id`) REFERENCES `brokers` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `brand_disco_requests`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `brand_disco_requests` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `submitted_by` bigint unsigned NOT NULL,
  `brand_id` bigint unsigned NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `reason` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `recoup_plan` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `ap_owed` decimal(9,2) DEFAULT NULL,
  `ytd_sales` decimal(9,2) DEFAULT NULL,
  `ytd_margin` decimal(9,2) DEFAULT NULL,
  `previous_year_sales` decimal(9,2) DEFAULT NULL,
  `previous_year_margin` decimal(9,2) DEFAULT NULL,
  `inventory_value` decimal(9,2) DEFAULT NULL,
  `state` int NOT NULL DEFAULT '5',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `brand_disco_requests_submitted_by_foreign` (`submitted_by`),
  KEY `brand_disco_requests_brand_id_foreign` (`brand_id`),
  CONSTRAINT `brand_disco_requests_brand_id_foreign` FOREIGN KEY (`brand_id`) REFERENCES `brands` (`id`),
  CONSTRAINT `brand_disco_requests_submitted_by_foreign` FOREIGN KEY (`submitted_by`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `brands`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `brands` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `vendor_id` bigint unsigned DEFAULT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name_fr` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `made_in_canada` tinyint(1) NOT NULL DEFAULT '0',
  `brand_number` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `category_code` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `broker_proposal` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `currency_id` bigint unsigned NOT NULL DEFAULT '1',
  `website` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `description_fr` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `unpublished_new_listing_deal` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `unpublished_new_listing_deal_fr` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `catalogue_notice` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `catalogue_notice_fr` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `contract_exclusive` tinyint(1) NOT NULL DEFAULT '0',
  `no_other_distributors` tinyint(1) NOT NULL DEFAULT '0',
  `also_distributed_by` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `allows_amazon_resale` tinyint(1) NOT NULL DEFAULT '0',
  `map_pricing` tinyint(1) NOT NULL DEFAULT '0',
  `minimum_order_quantity` int unsigned DEFAULT NULL,
  `minimum_order_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '$',
  `shipping_lead_time` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `product_availability` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nutrition_house_payment_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nutrition_house` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nutrition_house_payment` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nutrition_house_percentage` decimal(5,2) DEFAULT NULL,
  `nutrition_house_purity_percentage` decimal(5,2) DEFAULT NULL,
  `health_first_payment_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `health_first` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `health_first_payment` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `health_first_percentage` decimal(5,2) DEFAULT NULL,
  `health_first_purity_percentage` decimal(5,2) DEFAULT NULL,
  `allow_oi` tinyint(1) NOT NULL DEFAULT '0',
  `default_pl_discount` decimal(5,2) DEFAULT NULL,
  `purchasing_specialist_id` bigint unsigned DEFAULT NULL,
  `vendor_relations_specialist_id` bigint unsigned DEFAULT NULL,
  `in_house_brand` tinyint(1) NOT NULL DEFAULT '0',
  `business_partner_program` tinyint(1) NOT NULL DEFAULT '0',
  `hide_from_exports` tinyint(1) NOT NULL DEFAULT '0',
  `state` int NOT NULL DEFAULT '5',
  `status` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `education_portal` tinyint(1) NOT NULL DEFAULT '0',
  `as400_category` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `finance_brand_number` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `brands_vendor_id_foreign` (`vendor_id`),
  KEY `brands_currency_id_foreign` (`currency_id`),
  KEY `brands_purchasing_specialist_id_foreign` (`purchasing_specialist_id`),
  KEY `brands_vendor_relations_specialist_id_foreign` (`vendor_relations_specialist_id`),
  KEY `brands_state_index` (`state`),
  CONSTRAINT `brands_currency_id_foreign` FOREIGN KEY (`currency_id`) REFERENCES `currencies` (`id`),
  CONSTRAINT `brands_purchasing_specialist_id_foreign` FOREIGN KEY (`purchasing_specialist_id`) REFERENCES `users` (`id`),
  CONSTRAINT `brands_vendor_id_foreign` FOREIGN KEY (`vendor_id`) REFERENCES `vendors` (`id`),
  CONSTRAINT `brands_vendor_relations_specialist_id_foreign` FOREIGN KEY (`vendor_relations_specialist_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `brokers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `brokers` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache` (
  `key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `cache_locks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache_locks` (
  `key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `case_stack_deals`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `case_stack_deals` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `brand_id` bigint unsigned DEFAULT NULL,
  `period_id` bigint unsigned DEFAULT NULL,
  `last_updated_by` bigint unsigned DEFAULT NULL,
  `deal` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `deal_fr` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `case_stack_deals_brand_id_foreign` (`brand_id`),
  KEY `case_stack_deals_period_id_foreign` (`period_id`),
  KEY `case_stack_deals_last_updated_by_foreign` (`last_updated_by`),
  CONSTRAINT `case_stack_deals_brand_id_foreign` FOREIGN KEY (`brand_id`) REFERENCES `brands` (`id`),
  CONSTRAINT `case_stack_deals_last_updated_by_foreign` FOREIGN KEY (`last_updated_by`) REFERENCES `users` (`id`),
  CONSTRAINT `case_stack_deals_period_id_foreign` FOREIGN KEY (`period_id`) REFERENCES `promo_periods` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `catalogue_categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `catalogue_categories` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `brand_id` bigint unsigned NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name_fr` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `description_fr` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `sort` int unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `catalogue_categories_brand_id_foreign` (`brand_id`),
  CONSTRAINT `catalogue_categories_brand_id_foreign` FOREIGN KEY (`brand_id`) REFERENCES `brands` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `certification_product`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `certification_product` (
  `product_id` bigint unsigned DEFAULT NULL,
  `certification_id` bigint unsigned DEFAULT NULL,
  KEY `certification_product_product_id_foreign` (`product_id`),
  KEY `certification_product_certification_id_foreign` (`certification_id`),
  CONSTRAINT `certification_product_certification_id_foreign` FOREIGN KEY (`certification_id`) REFERENCES `certifications` (`id`),
  CONSTRAINT `certification_product_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `certifications`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `certifications` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `requires_documentation` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `contacts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `contacts` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `contactable_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `contactable_id` int unsigned DEFAULT NULL,
  `role` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `position` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cloned_from_id` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `countries`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `countries` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `alpha2` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `alpha3` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `currencies`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `currencies` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `exchange_rate` decimal(8,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `customer_gl_accounts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `customer_gl_accounts` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `customer_number` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `gl_account` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `dimensions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `dimensions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `product_id` bigint unsigned DEFAULT NULL,
  `type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `width` decimal(8,3) DEFAULT NULL,
  `depth` decimal(8,3) DEFAULT NULL,
  `height` decimal(8,3) DEFAULT NULL,
  `gross_weight` decimal(8,3) DEFAULT NULL,
  `net_weight` decimal(8,3) DEFAULT NULL,
  `cloned_from_id` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `dimensions_product_id_foreign` (`product_id`),
  CONSTRAINT `dimensions_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `disco_promo_line_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `disco_promo_line_items` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `product_id` bigint unsigned DEFAULT NULL,
  `brand_discount` decimal(5,2) DEFAULT NULL,
  `pl_discount` decimal(5,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `disco_promo_line_items_product_id_foreign` (`product_id`),
  CONSTRAINT `disco_promo_line_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `distributor_retailer`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `distributor_retailer` (
  `distributor_id` bigint unsigned NOT NULL,
  `retailer_id` bigint unsigned NOT NULL,
  KEY `distributor_retailer_distributor_id_foreign` (`distributor_id`),
  KEY `distributor_retailer_retailer_id_foreign` (`retailer_id`),
  CONSTRAINT `distributor_retailer_distributor_id_foreign` FOREIGN KEY (`distributor_id`) REFERENCES `distributors` (`id`),
  CONSTRAINT `distributor_retailer_retailer_id_foreign` FOREIGN KEY (`retailer_id`) REFERENCES `retailers` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `distributors`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `distributors` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `connection` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `features`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `features` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `feature` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `active_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `features_feature_unique` (`feature`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `future_landed_costs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `future_landed_costs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `product_id` bigint unsigned DEFAULT NULL,
  `landed_cost` decimal(8,2) DEFAULT NULL,
  `change_date` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `future_landed_costs_product_id_foreign` (`product_id`),
  CONSTRAINT `future_landed_costs_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `inventory_removal_line_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `inventory_removal_line_items` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `inventory_removal_id` bigint unsigned DEFAULT NULL,
  `product_id` bigint unsigned DEFAULT NULL,
  `cost` decimal(8,2) NOT NULL,
  `quantity` int DEFAULT NULL,
  `expiry` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `warehouse` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `full_mcb` tinyint(1) NOT NULL DEFAULT '0',
  `reserve` tinyint(1) NOT NULL DEFAULT '0',
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `cloned_from_id` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `reason` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `vendor_pickup` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `inventory_removal_line_items_inventory_removal_id_foreign` (`inventory_removal_id`),
  KEY `inventory_removal_line_items_product_id_foreign` (`product_id`),
  CONSTRAINT `inventory_removal_line_items_inventory_removal_id_foreign` FOREIGN KEY (`inventory_removal_id`) REFERENCES `inventory_removals` (`id`),
  CONSTRAINT `inventory_removal_line_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `inventory_removals`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `inventory_removals` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `submitted_by` bigint unsigned DEFAULT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `comment` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `state` int NOT NULL DEFAULT '5',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `cloned_from_id` bigint unsigned DEFAULT NULL,
  `vendor_pickup` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `inventory_removals_submitted_by_foreign` (`submitted_by`),
  CONSTRAINT `inventory_removals_submitted_by_foreign` FOREIGN KEY (`submitted_by`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint unsigned NOT NULL,
  `reserved_at` int unsigned DEFAULT NULL,
  `available_at` int unsigned NOT NULL,
  `created_at` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `ledgers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ledgers` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `user_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `recordable_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `recordable_id` bigint unsigned NOT NULL,
  `context` tinyint unsigned NOT NULL,
  `event` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `properties` json NOT NULL,
  `modified` json NOT NULL,
  `pivot` json NOT NULL,
  `extra` json NOT NULL,
  `url` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `ip_address` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `signature` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `ledgers_recordable_type_recordable_id_index` (`recordable_type`,`recordable_id`),
  KEY `ledgers_user_id_user_type_index` (`user_id`,`user_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `log` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `message` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `channel` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `level` int NOT NULL DEFAULT '0',
  `level_name` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `unix_time` int NOT NULL,
  `datetime` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `context` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `extra` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `marketing_agreement_line_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `marketing_agreement_line_items` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `marketing_agreement_id` bigint unsigned DEFAULT NULL,
  `brand_id` bigint unsigned DEFAULT NULL,
  `activity` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `promo_dates` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cost` decimal(9,2) DEFAULT NULL,
  `mcb_amount` decimal(9,2) DEFAULT NULL,
  `cloned_from_id` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `marketing_agreement_line_items_marketing_agreement_id_foreign` (`marketing_agreement_id`),
  KEY `marketing_agreement_line_items_brand_id_foreign` (`brand_id`),
  CONSTRAINT `marketing_agreement_line_items_brand_id_foreign` FOREIGN KEY (`brand_id`) REFERENCES `brands` (`id`),
  CONSTRAINT `marketing_agreement_line_items_marketing_agreement_id_foreign` FOREIGN KEY (`marketing_agreement_id`) REFERENCES `marketing_agreements` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `marketing_agreements`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `marketing_agreements` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `submitted_by` bigint unsigned DEFAULT NULL,
  `send_to` bigint unsigned DEFAULT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `account` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `account_other` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ship_to_number` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `retailer_invoice` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `comment` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `approval_email` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `tax_rate` decimal(5,2) DEFAULT NULL,
  `state` int NOT NULL DEFAULT '5',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `cloned_from_id` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `marketing_agreements_submitted_by_foreign` (`submitted_by`),
  KEY `marketing_agreements_send_to_foreign` (`send_to`),
  CONSTRAINT `marketing_agreements_send_to_foreign` FOREIGN KEY (`send_to`) REFERENCES `users` (`id`),
  CONSTRAINT `marketing_agreements_submitted_by_foreign` FOREIGN KEY (`submitted_by`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `media`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `media` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `model_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint unsigned NOT NULL,
  `uuid` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `collection_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `file_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `mime_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `disk` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `conversions_disk` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `size` bigint unsigned NOT NULL,
  `manipulations` json NOT NULL,
  `custom_properties` json NOT NULL,
  `generated_conversions` json NOT NULL,
  `responsive_images` json NOT NULL,
  `order_column` int unsigned DEFAULT NULL,
  `cloned_from_id` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `media_uuid_unique` (`uuid`),
  KEY `media_model_type_model_id_index` (`model_type`,`model_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `notifications`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `notifications` (
  `id` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `notifiable_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `notifiable_id` bigint unsigned NOT NULL,
  `data` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `notifications_notifiable_type_notifiable_id_index` (`notifiable_type`,`notifiable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `packaging_material_product`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `packaging_material_product` (
  `product_id` bigint unsigned DEFAULT NULL,
  `packaging_material_id` bigint unsigned DEFAULT NULL,
  KEY `packaging_material_product_product_id_foreign` (`product_id`),
  KEY `packaging_material_product_packaging_material_id_foreign` (`packaging_material_id`),
  CONSTRAINT `packaging_material_product_packaging_material_id_foreign` FOREIGN KEY (`packaging_material_id`) REFERENCES `packaging_materials` (`id`),
  CONSTRAINT `packaging_material_product_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `packaging_materials`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `packaging_materials` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `password_resets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_resets` (
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  KEY `password_resets_email_index` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `permissions` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `ability_id` int unsigned NOT NULL,
  `entity_id` int unsigned DEFAULT NULL,
  `entity_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `forbidden` tinyint(1) NOT NULL DEFAULT '0',
  `scope` int DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `permissions_entity_index` (`entity_id`,`entity_type`,`scope`),
  KEY `permissions_ability_id_index` (`ability_id`),
  KEY `permissions_scope_index` (`scope`),
  CONSTRAINT `permissions_ability_id_foreign` FOREIGN KEY (`ability_id`) REFERENCES `abilities` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `personal_access_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `personal_access_tokens` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint unsigned NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `po_converter`;
/*!50001 DROP VIEW IF EXISTS `po_converter`*/;
SET @saved_cs_client     = @@character_set_client;
/*!50503 SET character_set_client = utf8mb4 */;
/*!50001 CREATE VIEW `po_converter` AS SELECT 
 1 AS `ProductStockID`,
 1 AS `SoldByCase`,
 1 AS `CaseMulti`,
 1 AS `Status`*/;
SET character_set_client = @saved_cs_client;
DROP TABLE IF EXISTS `pricing_adjustment_line_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `pricing_adjustment_line_items` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `pricing_adjustment_id` bigint unsigned DEFAULT NULL,
  `item_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `item_id` bigint unsigned NOT NULL,
  `total_discount` decimal(5,2) DEFAULT NULL,
  `who_to_mcb` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `total_mcb` decimal(5,2) DEFAULT NULL,
  `cloned_from_id` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `pricing_adjustment_line_items_pricing_adjustment_id_foreign` (`pricing_adjustment_id`),
  KEY `pricing_adjustment_line_items_item_type_item_id_index` (`item_type`,`item_id`),
  CONSTRAINT `pricing_adjustment_line_items_pricing_adjustment_id_foreign` FOREIGN KEY (`pricing_adjustment_id`) REFERENCES `pricing_adjustments` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `pricing_adjustments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `pricing_adjustments` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `submitted_by` bigint unsigned DEFAULT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `accounts` json DEFAULT NULL,
  `ongoing` tinyint(1) NOT NULL DEFAULT '0',
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `dollar_discount` tinyint(1) NOT NULL DEFAULT '0',
  `dollar_mcb` tinyint(1) NOT NULL DEFAULT '0',
  `bpp` tinyint(1) NOT NULL DEFAULT '0',
  `shared_line` tinyint(1) NOT NULL DEFAULT '0',
  `comment` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `state` int NOT NULL DEFAULT '5',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `cloned_from_id` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `pricing_adjustments_submitted_by_foreign` (`submitted_by`),
  CONSTRAINT `pricing_adjustments_submitted_by_foreign` FOREIGN KEY (`submitted_by`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `product_categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `product_categories` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `flags` int unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `receive_attribute` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0000000000',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `product_category_product_subcategory`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `product_category_product_subcategory` (
  `product_category_id` bigint unsigned DEFAULT NULL,
  `product_subcategory_id` bigint unsigned DEFAULT NULL,
  KEY `pcpsc_category_id_foreign` (`product_category_id`),
  KEY `pcpsc_subcategory_id_foreign` (`product_subcategory_id`),
  CONSTRAINT `pcpsc_category_id_foreign` FOREIGN KEY (`product_category_id`) REFERENCES `product_categories` (`id`),
  CONSTRAINT `pcpsc_subcategory_id_foreign` FOREIGN KEY (`product_subcategory_id`) REFERENCES `product_subcategories` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `product_delist_requests`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `product_delist_requests` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `submitted_by` bigint unsigned NOT NULL,
  `product_id` bigint unsigned DEFAULT NULL,
  `reason` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `state` int NOT NULL DEFAULT '5',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `product_delist_requests_submitted_by_foreign` (`submitted_by`),
  KEY `product_delist_requests_product_id_foreign` (`product_id`),
  CONSTRAINT `product_delist_requests_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`),
  CONSTRAINT `product_delist_requests_submitted_by_foreign` FOREIGN KEY (`submitted_by`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `product_flags`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `product_flags` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `product_product_flag`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `product_product_flag` (
  `product_id` bigint unsigned DEFAULT NULL,
  `product_flag_id` bigint unsigned DEFAULT NULL,
  KEY `product_product_flag_product_id_foreign` (`product_id`),
  KEY `product_product_flag_product_flag_id_foreign` (`product_flag_id`),
  CONSTRAINT `product_product_flag_product_flag_id_foreign` FOREIGN KEY (`product_flag_id`) REFERENCES `product_flags` (`id`),
  CONSTRAINT `product_product_flag_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `product_subcategories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `product_subcategories` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `category` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `grocery` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `flags` int unsigned DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `products`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `products` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `brand_id` bigint unsigned DEFAULT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name_fr` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `stock_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_display` tinyint(1) NOT NULL DEFAULT '0',
  `supersedes_id` bigint unsigned DEFAULT NULL,
  `country_origin` bigint unsigned DEFAULT NULL,
  `country_shipped` bigint unsigned DEFAULT NULL,
  `tariff_code` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `packaging_language` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `category_id` bigint unsigned DEFAULT NULL,
  `subcategory_id` bigint unsigned DEFAULT NULL,
  `catalogue_category_id` bigint unsigned DEFAULT NULL,
  `catalogue_category_proposal` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `catalogue_category_proposal_fr` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `unit_cost` decimal(8,2) DEFAULT NULL,
  `wholesale_price` decimal(8,2) DEFAULT NULL,
  `landed_cost` decimal(8,2) DEFAULT NULL,
  `not_for_resale` tinyint(1) NOT NULL DEFAULT '0',
  `add_to_existing_casestack_deals` tinyint(1) NOT NULL DEFAULT '0',
  `available_ship_date` date DEFAULT NULL,
  `minimum_order_units` int unsigned DEFAULT NULL,
  `price_change_reason` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `price_change_date` date DEFAULT NULL,
  `temp_edlp` decimal(5,2) DEFAULT NULL,
  `temp_duty` decimal(5,2) DEFAULT NULL,
  `purity_sell_by_unit` int unsigned DEFAULT NULL,
  `retailer_sell_by_unit` int unsigned DEFAULT NULL,
  `upc` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `size` decimal(8,2) DEFAULT NULL,
  `uom_id` int DEFAULT NULL,
  `inner_upc` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `inner_units` int DEFAULT NULL,
  `master_upc` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `master_units` int DEFAULT NULL,
  `cases_per_tie` int DEFAULT NULL,
  `layers_per_skid` int DEFAULT NULL,
  `tester_available` tinyint(1) DEFAULT NULL,
  `tester_brand_stock_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `brand_stock_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `description_fr` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `shelf_life` int DEFAULT NULL,
  `shelf_life_units` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `features_1` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `features_2` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `features_3` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `features_4` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `features_5` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `features_fr_1` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `features_fr_2` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `features_fr_3` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `features_fr_4` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `features_fr_5` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `ingredients` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `ingredients_fr` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `contraindications` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `contraindications_fr` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `recommended_use` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `recommended_use_fr` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `recommended_dosage` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `recommended_dosage_fr` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `benefits` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `benefits_fr` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `submission_notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `state` int DEFAULT '5',
  `status` int NOT NULL DEFAULT '0',
  `hide_flyer` tinyint(1) NOT NULL DEFAULT '0',
  `hide_export` tinyint(1) NOT NULL DEFAULT '0',
  `listed_on` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `cloned_from_id` bigint unsigned DEFAULT NULL,
  `suggested_retail_price` decimal(8,2) DEFAULT NULL,
  `old_unit_cost` decimal(8,2) DEFAULT NULL,
  `old_landed_cost` decimal(8,2) DEFAULT NULL,
  `old_wholesale_price` decimal(8,2) DEFAULT NULL,
  `warnings` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `warnings_fr` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `extra_addon_percent` decimal(5,2) DEFAULT NULL,
  `identity_description` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `products_brand_id_foreign` (`brand_id`),
  KEY `products_stock_id_index` (`stock_id`),
  KEY `products_supersedes_id_foreign` (`supersedes_id`),
  KEY `products_country_origin_foreign` (`country_origin`),
  KEY `products_country_shipped_foreign` (`country_shipped`),
  KEY `products_category_id_foreign` (`category_id`),
  KEY `products_subcategory_id_foreign` (`subcategory_id`),
  KEY `products_catalogue_category_id_foreign` (`catalogue_category_id`),
  KEY `products_state_index` (`state`),
  CONSTRAINT `products_brand_id_foreign` FOREIGN KEY (`brand_id`) REFERENCES `brands` (`id`),
  CONSTRAINT `products_catalogue_category_id_foreign` FOREIGN KEY (`catalogue_category_id`) REFERENCES `catalogue_categories` (`id`),
  CONSTRAINT `products_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `product_categories` (`id`),
  CONSTRAINT `products_country_origin_foreign` FOREIGN KEY (`country_origin`) REFERENCES `countries` (`id`),
  CONSTRAINT `products_country_shipped_foreign` FOREIGN KEY (`country_shipped`) REFERENCES `countries` (`id`),
  CONSTRAINT `products_subcategory_id_foreign` FOREIGN KEY (`subcategory_id`) REFERENCES `product_subcategories` (`id`),
  CONSTRAINT `products_supersedes_id_foreign` FOREIGN KEY (`supersedes_id`) REFERENCES `products` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `promo_line_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `promo_line_items` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `product_id` bigint unsigned DEFAULT NULL,
  `promo_id` bigint unsigned DEFAULT NULL,
  `oi` tinyint(1) NOT NULL DEFAULT '0',
  `brand_discount` decimal(6,2) DEFAULT NULL,
  `pl_discount` decimal(5,2) DEFAULT NULL,
  `data` json DEFAULT NULL,
  `cloned_from_id` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `promo_line_items_product_id_foreign` (`product_id`),
  KEY `promo_line_items_promo_id_foreign` (`promo_id`),
  CONSTRAINT `promo_line_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`),
  CONSTRAINT `promo_line_items_promo_id_foreign` FOREIGN KEY (`promo_id`) REFERENCES `promos` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `promo_periods`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `promo_periods` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  `type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `base_period_id` bigint unsigned DEFAULT NULL,
  `order_form_header` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `owner_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `owner_id` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `promo_periods_base_period_id_foreign` (`base_period_id`),
  KEY `promo_periods_owner_type_owner_id_index` (`owner_type`,`owner_id`),
  CONSTRAINT `promo_periods_base_period_id_foreign` FOREIGN KEY (`base_period_id`) REFERENCES `promo_periods` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `promos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `promos` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `submitted_by` bigint unsigned DEFAULT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `period_id` bigint unsigned NOT NULL,
  `brand_id` bigint unsigned NOT NULL,
  `dollar_discount` tinyint(1) NOT NULL DEFAULT '0',
  `line_drive` tinyint(1) NOT NULL DEFAULT '0',
  `oi` tinyint(1) NOT NULL DEFAULT '0',
  `oi_period_dates` tinyint(1) NOT NULL DEFAULT '0',
  `state` int NOT NULL DEFAULT '5',
  `data` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `promos_submitted_by_foreign` (`submitted_by`),
  KEY `promos_period_id_foreign` (`period_id`),
  KEY `promos_brand_id_foreign` (`brand_id`),
  CONSTRAINT `promos_brand_id_foreign` FOREIGN KEY (`brand_id`) REFERENCES `brands` (`id`),
  CONSTRAINT `promos_period_id_foreign` FOREIGN KEY (`period_id`) REFERENCES `promo_periods` (`id`),
  CONSTRAINT `promos_submitted_by_foreign` FOREIGN KEY (`submitted_by`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `quality_control_records`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `quality_control_records` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `vendor_id` bigint unsigned DEFAULT NULL,
  `warehouse_id` bigint unsigned DEFAULT NULL,
  `received_date` date NOT NULL,
  `po_number` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned NOT NULL,
  `lot_number` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `bin_number` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `din_npn_number` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `din_npn_on_label` tinyint(1) DEFAULT NULL,
  `importer_address` tinyint(1) DEFAULT NULL,
  `seals_intact` tinyint(1) DEFAULT NULL,
  `bilingual_label` tinyint(1) DEFAULT NULL,
  `product_id` bigint unsigned NOT NULL,
  `quantity_received` int unsigned NOT NULL,
  `expiry_date` date NOT NULL,
  `receiving_comment` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `number_damaged_cartons` smallint unsigned DEFAULT NULL,
  `number_damaged_units` smallint unsigned DEFAULT NULL,
  `nature_of_damage` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `number_units_sent_for_testing` smallint unsigned DEFAULT NULL,
  `number_units_for_stability` smallint unsigned DEFAULT NULL,
  `number_units_retained` smallint unsigned DEFAULT NULL,
  `regulatory_compliance_comment` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `identity_description` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `matches_written_specification` tinyint(1) NOT NULL DEFAULT '0',
  `out_of_specifications_comment` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `completed_at` date DEFAULT NULL,
  `completed_by` bigint unsigned DEFAULT NULL,
  `generated_tag` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `number_to_reject_destroy` smallint unsigned DEFAULT NULL,
  `pre_release_reason` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pre_release_requested_by` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `regulatory_info`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `regulatory_info` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `product_id` bigint unsigned DEFAULT NULL,
  `npn` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `npn_issued` date DEFAULT NULL,
  `importer_is_purity` tinyint(1) NOT NULL DEFAULT '0',
  `importer_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `importer_phone` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `importer_email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `serving_size` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `calories` decimal(8,2) DEFAULT NULL,
  `total_fat` decimal(8,2) DEFAULT NULL,
  `trans_fat` decimal(8,2) DEFAULT NULL,
  `saturated_fat` decimal(8,2) DEFAULT NULL,
  `cholesterol` decimal(8,2) DEFAULT NULL,
  `sodium` decimal(8,2) DEFAULT NULL,
  `carbohydrates` decimal(8,2) DEFAULT NULL,
  `fiber` decimal(8,2) DEFAULT NULL,
  `sugar` decimal(8,2) DEFAULT NULL,
  `protein` decimal(8,2) DEFAULT NULL,
  `cosmetic_notification_number` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `medical_class` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `medical_device_establishment_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `medical_device_establishment_license_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pesticide_class` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pca_number` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cloned_from_id` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `regulatory_info_product_id_foreign` (`product_id`),
  CONSTRAINT `regulatory_info_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `retailer_listings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `retailer_listings` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `retailer_id` bigint unsigned NOT NULL,
  `product_id` bigint unsigned NOT NULL,
  `data` json DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `retailer_listings_retailer_id_foreign` (`retailer_id`),
  KEY `retailer_listings_product_id_foreign` (`product_id`),
  CONSTRAINT `retailer_listings_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`),
  CONSTRAINT `retailer_listings_retailer_id_foreign` FOREIGN KEY (`retailer_id`) REFERENCES `retailers` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `retailers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `retailers` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `number_stores` int DEFAULT NULL,
  `fiscal_year_start` date DEFAULT NULL,
  `account_manager_id` bigint unsigned DEFAULT NULL,
  `distribution_type` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `markup` decimal(5,2) DEFAULT NULL,
  `target_margin` decimal(5,2) DEFAULT NULL,
  `as400_pricing_file` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `costing_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'landed',
  `warehouse_number` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '01',
  `allow_promos` tinyint(1) NOT NULL DEFAULT '0',
  `websites` json DEFAULT NULL,
  `state` int NOT NULL DEFAULT '5',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `retailers_account_manager_id_foreign` (`account_manager_id`),
  CONSTRAINT `retailers_account_manager_id_foreign` FOREIGN KEY (`account_manager_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `roles` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `level` int unsigned DEFAULT NULL,
  `scope` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `category` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `roles_name_unique` (`name`,`scope`),
  KEY `roles_scope_index` (`scope`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `signoff_config`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `signoff_config` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `model` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `show_route` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `final_approval_to` json DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `signoff_config_steps`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `signoff_config_steps` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `signoff_config_id` bigint unsigned NOT NULL,
  `step` int unsigned NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `form_request` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `form_view` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `signoffs_required` int unsigned NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `approval_to_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `approval_to` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `signoff_config_steps_signoff_config_id_foreign` (`signoff_config_id`),
  CONSTRAINT `signoff_config_steps_signoff_config_id_foreign` FOREIGN KEY (`signoff_config_id`) REFERENCES `signoff_config` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `signoff_responses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `signoff_responses` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `signoff_id` bigint unsigned NOT NULL,
  `user_id` bigint unsigned NOT NULL,
  `step` int unsigned NOT NULL,
  `approved` tinyint(1) NOT NULL,
  `archived` tinyint(1) NOT NULL DEFAULT '0',
  `comment` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `comment_only` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `signoff_responses_signoff_id_foreign` (`signoff_id`),
  KEY `signoff_responses_user_id_foreign` (`user_id`),
  CONSTRAINT `signoff_responses_signoff_id_foreign` FOREIGN KEY (`signoff_id`) REFERENCES `signoffs` (`id`) ON DELETE CASCADE,
  CONSTRAINT `signoff_responses_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `signoffs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `signoffs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned DEFAULT NULL,
  `initial_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `initial_id` bigint unsigned NOT NULL,
  `proposed_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `proposed_id` bigint unsigned NOT NULL,
  `step` int unsigned NOT NULL DEFAULT '0',
  `submitted_at` timestamp NULL DEFAULT NULL,
  `new_submission` tinyint(1) NOT NULL DEFAULT '0',
  `state` int NOT NULL DEFAULT '5',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `signoff_config_id` bigint unsigned NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `signoffs_user_id_foreign` (`user_id`),
  KEY `signoffs_initial_type_initial_id_index` (`initial_type`,`initial_id`),
  KEY `signoffs_proposed_type_proposed_id_index` (`proposed_type`,`proposed_id`),
  KEY `signoffs_signoff_config_id_foreign` (`signoff_config_id`),
  CONSTRAINT `signoffs_signoff_config_id_foreign` FOREIGN KEY (`signoff_config_id`) REFERENCES `signoff_config` (`id`),
  CONSTRAINT `signoffs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `statuses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `statuses` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `reason` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `model_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `statuses_model_type_model_id_index` (`model_type`,`model_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `telescope_entries`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `telescope_entries` (
  `sequence` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch_id` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `family_hash` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `should_display_on_index` tinyint(1) NOT NULL DEFAULT '1',
  `type` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`sequence`),
  UNIQUE KEY `telescope_entries_uuid_unique` (`uuid`),
  KEY `telescope_entries_batch_id_index` (`batch_id`),
  KEY `telescope_entries_family_hash_index` (`family_hash`),
  KEY `telescope_entries_created_at_index` (`created_at`),
  KEY `telescope_entries_type_should_display_on_index_index` (`type`,`should_display_on_index`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `telescope_entries_tags`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `telescope_entries_tags` (
  `entry_uuid` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tag` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  KEY `telescope_entries_tags_entry_uuid_tag_index` (`entry_uuid`,`tag`),
  KEY `telescope_entries_tags_tag_index` (`tag`),
  CONSTRAINT `telescope_entries_tags_entry_uuid_foreign` FOREIGN KEY (`entry_uuid`) REFERENCES `telescope_entries` (`uuid`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `telescope_monitoring`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `telescope_monitoring` (
  `tag` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `unit_of_measure`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `unit_of_measure` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `unit` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `unit_fr` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description_fr` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `upcoming_changes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `upcoming_changes` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `change_date` date NOT NULL,
  `expires_at` datetime NOT NULL,
  `scheduled_at` datetime NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `user_vendor`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `user_vendor` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `vendor_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_vendor_user_id_foreign` (`user_id`),
  KEY `user_vendor_vendor_id_foreign` (`vendor_id`),
  CONSTRAINT `user_vendor_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `user_vendor_vendor_id_foreign` FOREIGN KEY (`vendor_id`) REFERENCES `vendors` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `api_token` varchar(80) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remember_token` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `vendor_id` bigint unsigned DEFAULT NULL,
  `broker_id` bigint unsigned DEFAULT NULL,
  `unsubscriptions` json DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  UNIQUE KEY `users_api_token_unique` (`api_token`),
  KEY `users_vendor_id_foreign` (`vendor_id`),
  KEY `users_broker_id_foreign` (`broker_id`),
  CONSTRAINT `users_broker_id_foreign` FOREIGN KEY (`broker_id`) REFERENCES `brokers` (`id`),
  CONSTRAINT `users_vendor_id_foreign` FOREIGN KEY (`vendor_id`) REFERENCES `vendors` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `vendors`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `vendors` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `who_to_mcb` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cheque_payable_to` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payment_terms` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `special_shipping_requirements` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `backorder_policy` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `return_policy` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `fob_purity_distribution_centres` tinyint(1) NOT NULL DEFAULT '1',
  `consignment` tinyint(1) NOT NULL DEFAULT '0',
  `state` int NOT NULL DEFAULT '5',
  `status` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `cloned_from_id` bigint unsigned DEFAULT NULL,
  `submitted_by_broker_id` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `vendors_state_index` (`state`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `warehouses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `warehouses` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `number` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!50001 DROP VIEW IF EXISTS `po_converter`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_unicode_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`plic`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `po_converter` AS select `p`.`stock_id` AS `ProductStockID`,(`p`.`purity_sell_by_unit` <> 1) AS `SoldByCase`,(case when ((`p`.`purity_sell_by_unit` = 1) and (`p`.`inner_units` is null)) then `p`.`master_units` when ((`p`.`purity_sell_by_unit` = 1) and (`p`.`inner_units` < 2)) then `p`.`master_units` when ((`p`.`purity_sell_by_unit` = 1) and (`p`.`inner_units` >= 2)) then `p`.`inner_units` when (`p`.`purity_sell_by_unit` <> 1) then 1 end) AS `CaseMulti`,`asd`.`status` AS `Status` from (`products` `p` left join `as400_stock_data` `asd` on((`p`.`id` = `asd`.`product_id`))) where ((`p`.`state` = 1) and (`p`.`status` = 20)) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

INSERT INTO `migrations` VALUES (1,'2014_10_12_000000_create_users_table',1);
INSERT INTO `migrations` VALUES (2,'2014_10_12_100000_create_password_resets_table',1);
INSERT INTO `migrations` VALUES (3,'2018_08_11_003343_create_log_table',1);
INSERT INTO `migrations` VALUES (4,'2018_11_21_000001_create_ledgers_table',1);
INSERT INTO `migrations` VALUES (5,'2019_05_27_191216_create_bouncer_tables',1);
INSERT INTO `migrations` VALUES (6,'2019_05_27_220236_extend_abilities_table',1);
INSERT INTO `migrations` VALUES (7,'2019_05_27_221441_extend_roles_table',1);
INSERT INTO `migrations` VALUES (8,'2019_05_29_201044_create_unit_of_measure_table',1);
INSERT INTO `migrations` VALUES (9,'2019_06_03_181905_create_countries_table',1);
INSERT INTO `migrations` VALUES (10,'2019_06_03_192804_create_brokers_table',1);
INSERT INTO `migrations` VALUES (11,'2019_06_03_195703_create_currencies_table',1);
INSERT INTO `migrations` VALUES (12,'2019_06_18_142450_create_statuses_table',1);
INSERT INTO `migrations` VALUES (13,'2019_06_19_190006_create_contacts_table',1);
INSERT INTO `migrations` VALUES (14,'2019_06_19_192842_create_addresses_table',1);
INSERT INTO `migrations` VALUES (15,'2019_06_19_195140_create_vendors_table',1);
INSERT INTO `migrations` VALUES (16,'2019_06_19_195147_create_brands_table',1);
INSERT INTO `migrations` VALUES (17,'2019_06_19_195150_create_brand_broker_table',1);
INSERT INTO `migrations` VALUES (18,'2019_06_20_184818_create_user_vendor_table',1);
INSERT INTO `migrations` VALUES (19,'2019_07_06_210758_create_signoffs_table',1);
INSERT INTO `migrations` VALUES (20,'2019_07_06_210823_create_signoff_responses_table',1);
INSERT INTO `migrations` VALUES (21,'2019_07_30_151921_create_notifications_table',1);
INSERT INTO `migrations` VALUES (22,'2019_08_12_155228_create_jobs_table',1);
INSERT INTO `migrations` VALUES (23,'2019_08_12_180915_create_failed_jobs_table',1);
INSERT INTO `migrations` VALUES (24,'2019_09_10_195000_create_certifications_table',1);
INSERT INTO `migrations` VALUES (25,'2019_09_10_195000_create_product_subcategories_table',1);
INSERT INTO `migrations` VALUES (26,'2019_09_10_195002_create_product_categories_table',1);
INSERT INTO `migrations` VALUES (27,'2019_09_10_195004_create_catalogue_categories_table',1);
INSERT INTO `migrations` VALUES (28,'2019_09_10_195006_create_product_category_product_subcategory_table',1);
INSERT INTO `migrations` VALUES (29,'2019_09_10_195010_create_products_table',1);
INSERT INTO `migrations` VALUES (30,'2019_09_10_195011_create_regulatory_info_table',1);
INSERT INTO `migrations` VALUES (31,'2019_09_10_195012_create_dimensions_table',1);
INSERT INTO `migrations` VALUES (32,'2019_09_10_195016_create_packaging_material_tables',1);
INSERT INTO `migrations` VALUES (33,'2019_09_10_195017_create_product_flag_tables',1);
INSERT INTO `migrations` VALUES (34,'2019_09_10_195025_create_allergen_tables',1);
INSERT INTO `migrations` VALUES (35,'2019_10_17_144017_create_signoff_config_table',1);
INSERT INTO `migrations` VALUES (36,'2019_10_17_144114_create_signoff_config_steps_table',1);
INSERT INTO `migrations` VALUES (37,'2019_10_23_170033_add_signoff_config_to_signoffs',1);
INSERT INTO `migrations` VALUES (38,'2019_10_25_180138_create_ability_signoff_config_step_table',1);
INSERT INTO `migrations` VALUES (39,'2019_11_20_144558_create_promo_periods_table',1);
INSERT INTO `migrations` VALUES (40,'2019_11_25_045726_create_promos_table',1);
INSERT INTO `migrations` VALUES (41,'2019_11_27_194545_create_promo_line_items_table',1);
INSERT INTO `migrations` VALUES (42,'2020_04_13_005633_create_certification_product_table',1);
INSERT INTO `migrations` VALUES (43,'2020_05_27_171047_create_media_table',1);
INSERT INTO `migrations` VALUES (44,'2020_06_18_212254_add_vendor_and_broker_to_users_table',1);
INSERT INTO `migrations` VALUES (45,'2020_06_29_204254_add_unsubscriptions_to_users_table',1);
INSERT INTO `migrations` VALUES (46,'2020_07_01_202651_create_as400_warehouse_stock_table',1);
INSERT INTO `migrations` VALUES (47,'2020_08_10_202645_create_as400_zeus_retailers_table',1);
INSERT INTO `migrations` VALUES (48,'2020_08_10_204611_create_as400_supersedes_table',1);
INSERT INTO `migrations` VALUES (49,'2020_08_11_133652_create_as400_upcoming_price_changes_table',1);
INSERT INTO `migrations` VALUES (50,'2020_08_11_142322_create_as400_freight_table',1);
INSERT INTO `migrations` VALUES (51,'2020_08_12_101116_create_as400_customers_table',1);
INSERT INTO `migrations` VALUES (52,'2020_08_12_101116_create_as400_special_pricing_table',1);
INSERT INTO `migrations` VALUES (53,'2020_08_13_154443_create_as400_pricing_table',1);
INSERT INTO `migrations` VALUES (54,'2020_08_13_215715_create_as400_stock_data_table',1);
INSERT INTO `migrations` VALUES (55,'2020_09_26_171658_create_as400_brand_invoices_table',1);
INSERT INTO `migrations` VALUES (56,'2020_10_07_093358_create_as400_brand_open_ap_table',1);
INSERT INTO `migrations` VALUES (57,'2020_10_07_093415_create_as400_brand_po_received_table',1);
INSERT INTO `migrations` VALUES (58,'2020_10_13_095322_create_as400_margins_table',1);
INSERT INTO `migrations` VALUES (59,'2020_10_25_110455_create_retailers_table',1);
INSERT INTO `migrations` VALUES (60,'2020_10_25_110515_create_distributors_table',1);
INSERT INTO `migrations` VALUES (61,'2020_10_25_110611_create_distributor_retailer_table',1);
INSERT INTO `migrations` VALUES (62,'2020_11_27_182725_create_warehouses_table',1);
INSERT INTO `migrations` VALUES (63,'2020_11_30_161112_create_pricing_adjustments_table',1);
INSERT INTO `migrations` VALUES (64,'2020_11_30_162742_create_pricing_adjustment_line_items_table',1);
INSERT INTO `migrations` VALUES (65,'2020_12_11_193136_create_as400_customer_groups_table',1);
INSERT INTO `migrations` VALUES (66,'2021_01_23_121142_create_marketing_agreements_table',1);
INSERT INTO `migrations` VALUES (67,'2021_01_23_121332_create_marketing_agreement_line_items_table',1);
INSERT INTO `migrations` VALUES (68,'2021_02_03_221722_create_inventory_writeoffs_table',1);
INSERT INTO `migrations` VALUES (69,'2021_02_03_221745_create_inventory_writeoff_line_items_table',1);
INSERT INTO `migrations` VALUES (70,'2021_03_01_182523_create_disco_promo_line_items_table',1);
INSERT INTO `migrations` VALUES (71,'2021_03_26_132853_create_case_stack_deals_table',1);
INSERT INTO `migrations` VALUES (72,'2021_04_11_172538_create_brand_disco_requests_table',1);
INSERT INTO `migrations` VALUES (73,'2021_04_12_172033_add_original_vendor_id_to_vendors',2);
INSERT INTO `migrations` VALUES (74,'2021_04_13_093525_add_cloned_from_id_to_tables',3);
INSERT INTO `migrations` VALUES (75,'2021_04_14_131605_add_suggested_retail_price_to_products',4);
INSERT INTO `migrations` VALUES (76,'2021_04_19_170204_add_soft_deletes_to_signoffs',5);
INSERT INTO `migrations` VALUES (77,'2021_04_19_200212_rename_landed_cost_on_as400_pricing',6);
INSERT INTO `migrations` VALUES (78,'2021_04_20_121229_create_future_landed_costs_table',7);
INSERT INTO `migrations` VALUES (79,'2021_04_21_162519_add_old_pricing_fields_to_products',8);
INSERT INTO `migrations` VALUES (80,'2021_04_21_164803_add_submitted_by_broker_to_vendors',9);
INSERT INTO `migrations` VALUES (81,'2021_04_22_201312_add_notes_to_inventory_writeoff_line_items',10);
INSERT INTO `migrations` VALUES (82,'2021_04_22_210133_create_as400_consignment_table',11);
INSERT INTO `migrations` VALUES (83,'2021_04_23_160959_create_product_delist_requests_table',12);
INSERT INTO `migrations` VALUES (84,'2021_04_28_173151_add_old_wholesale_price_to_products',13);
INSERT INTO `migrations` VALUES (85,'2021_04_29_130923_create_retailer_listings_table',14);
INSERT INTO `migrations` VALUES (86,'2021_05_07_085744_create_customer_gl_accounts_table',15);
INSERT INTO `migrations` VALUES (87,'2021_05_09_112626_change_product_size_to_decimal',16);
INSERT INTO `migrations` VALUES (88,'2021_05_18_104248_add_education_portal_to_brands',17);
INSERT INTO `migrations` VALUES (89,'2021_05_19_213123_update_precision_on_as400_special_pricing',18);
INSERT INTO `migrations` VALUES (90,'2021_05_21_161742_rename_writeoffs_to_removals',19);
INSERT INTO `migrations` VALUES (91,'2021_06_03_125046_add_receive_attribute_to_product_categories',20);
INSERT INTO `migrations` VALUES (92,'2021_06_03_151505_change_serving_size_to_string_on_regulatory_info',21);
INSERT INTO `migrations` VALUES (93,'2021_06_03_155733_remove_return_address_from_brand_disco_requests',22);
INSERT INTO `migrations` VALUES (94,'2021_06_05_152700_add_flags_to_product_subcategories',23);
INSERT INTO `migrations` VALUES (95,'2021_06_06_112421_add_as400_category_to_brands',24);
INSERT INTO `migrations` VALUES (96,'2021_07_09_215638_add_cloned_from_id_to_inventory_removals',25);
INSERT INTO `migrations` VALUES (97,'2021_09_23_111104_add_promo_discos_update_ability',26);
INSERT INTO `migrations` VALUES (98,'2021_09_29_200627_add_comment_only_to_signoff_responses',27);
INSERT INTO `migrations` VALUES (99,'2021_10_06_110825_update_warning_fields_on_products',28);
INSERT INTO `migrations` VALUES (100,'2021_10_14_101056_add_vendor_pickup_to_inventory_removals',29);
INSERT INTO `migrations` VALUES (101,'2021_11_03_182955_create_features_table',30);
INSERT INTO `migrations` VALUES (102,'2021_11_25_162439_add_extra_addon_percent_to_products',31);
INSERT INTO `migrations` VALUES (103,'2021_11_25_163302_add_extra_addon_percent_to_as400_pricing',31);
INSERT INTO `migrations` VALUES (104,'2021_12_15_111814_add_finance_brand_number_to_brands',32);
INSERT INTO `migrations` VALUES (105,'2019_12_14_000001_create_personal_access_tokens_table',33);
INSERT INTO `migrations` VALUES (106,'2022_01_06_004600_create_api_users_table',33);
INSERT INTO `migrations` VALUES (107,'2022_01_14_194427_update_as400_brand_invoices_table',34);
INSERT INTO `migrations` VALUES (108,'2022_03_03_101615_create_cache_table',35);
INSERT INTO `migrations` VALUES (109,'2022_05_24_161615_create_upcoming_changes_table',36);
INSERT INTO `migrations` VALUES (110,'2022_06_13_222031_add_step_approval_columns_to_signoff_config_step',37);
INSERT INTO `migrations` VALUES (111,'2022_06_13_233534_add_final_approval_to_column',37);
INSERT INTO `migrations` VALUES (112,'2018_08_08_100000_create_telescope_entries_table',38);
INSERT INTO `migrations` VALUES (113,'2022_11_10_110911_increase_brand_discount_size_for_promo_line_items',39);
INSERT INTO `migrations` VALUES (114,'2022_07_09_141643_create_po_converter_view',40);
INSERT INTO `migrations` VALUES (115,'2022_08_21_162428_create_quality_control_records_table',40);
INSERT INTO `migrations` VALUES (116,'2022_08_22_113309_create_quality_control_module_roles_and_abilities',40);
INSERT INTO `migrations` VALUES (117,'2022_10_10_163119_add_identity_description_column_to_products_table',40);
INSERT INTO `migrations` VALUES (118,'2023_02_23_225821_add_number_to_reject_destory_column_to_quality_control_record_table',41);
INSERT INTO `migrations` VALUES (119,'2023_02_26_225345_add_pre_release_reason_and_requestor',41);
INSERT INTO `migrations` VALUES (120,'2023_03_21_123344_increase_length_of_upcoming_changes_title_column',42);
