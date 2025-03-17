-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Mar 17, 2025 at 09:17 AM
-- Server version: 8.4.3
-- PHP Version: 8.3.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `annamalai`
--

-- --------------------------------------------------------

--
-- Table structure for table `tbl_category`
--

CREATE TABLE `tbl_category` (
  `id` tinyint NOT NULL,
  `category_name` varchar(30) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `created_by` tinyint DEFAULT NULL,
  `created_dt` datetime DEFAULT NULL,
  `updated_by` tinyint DEFAULT NULL,
  `updated_dt` datetime DEFAULT NULL,
  `del_status` tinyint NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_category`
--

INSERT INTO `tbl_category` (`id`, `category_name`, `created_by`, `created_dt`, `updated_by`, `updated_dt`, `del_status`) VALUES
(1, 'munusamy1', 1, '2024-07-17 10:28:50', 1, '2024-07-17 10:28:56', 1),
(2, 'mununus1234321', 1, '2024-07-17 10:29:07', 1, '2024-07-17 10:29:49', 1),
(3, 'DHANAPAL', 1, '2024-07-17 11:16:25', NULL, NULL, 1),
(4, 'asfggg', 1, '2024-07-20 16:18:25', NULL, NULL, 0);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_customer`
--

CREATE TABLE `tbl_customer` (
  `id` mediumint NOT NULL,
  `cust_id` varchar(10) NOT NULL,
  `cust_name` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `gst_no` varchar(20) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `pan_no` varchar(20) DEFAULT NULL,
  `door_no` varchar(20) DEFAULT NULL,
  `addr_line1` varchar(100) DEFAULT NULL,
  `addr_line2` varchar(100) DEFAULT NULL,
  `city_dist` varchar(100) DEFAULT NULL,
  `cstate` varchar(60) DEFAULT NULL,
  `pincode` varchar(6) DEFAULT NULL,
  `mobile_no1` varchar(10) DEFAULT NULL,
  `mobile_no2` varchar(10) DEFAULT NULL,
  `email_id` varchar(70) DEFAULT NULL,
  `created_by` tinyint DEFAULT NULL,
  `created_dt` datetime DEFAULT NULL,
  `updated_by` tinyint DEFAULT NULL,
  `updated_dt` datetime DEFAULT NULL,
  `del_status` tinyint NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_customer`
--

INSERT INTO `tbl_customer` (`id`, `cust_id`, `cust_name`, `gst_no`, `pan_no`, `door_no`, `addr_line1`, `addr_line2`, `city_dist`, `cstate`, `pincode`, `mobile_no1`, `mobile_no2`, `email_id`, `created_by`, `created_dt`, `updated_by`, `updated_dt`, `del_status`) VALUES
(1, 'C00001', 'RAGUPATHY G', 'RA001', 'CEEPR1744A', '4/567', 'Rajiv Gandhi Nagar', 'Karamadai Road', 'Coimbatore', 'Tamilnadu', '641301', '9944537774', '7010549404', 'ragupathyit@gmail.com', 1, '2023-04-05 19:50:10', 1, '2024-07-02 15:59:07', 0),
(2, 'C00002', 'SARAVANAN', 'ASD123', NULL, NULL, 'Coimbatore', '', '', '', '', '', '', '', 1, '2023-04-19 09:33:07', NULL, NULL, 0),
(3, 'C00003', 'CHANDRAN', 'CHA001', NULL, NULL, 'PN PALAYAM', 'SRKV Post', 'Coimbatore', 'Tamilnadu', '641020', '9894326150', '', '', 1, '2023-05-02 10:10:52', NULL, NULL, 0),
(4, 'C00004', 'DVD', 'DFGVF', 'DBDCB', 'BFBF', 'BF', 'BF', 'XCV VBC', 'DVB BVC', '345676', '7019601667', '8732', 'munus9404@gmail.com', 1, '2024-07-15 22:14:28', NULL, NULL, 0);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_cust_material`
--

CREATE TABLE `tbl_cust_material` (
  `id` tinyint NOT NULL,
  `customer_id` smallint UNSIGNED NOT NULL,
  `cust_name` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `created_by` tinyint DEFAULT NULL,
  `created_dt` datetime DEFAULT NULL,
  `updated_by` tinyint DEFAULT NULL,
  `updated_dt` datetime DEFAULT NULL,
  `del_status` tinyint NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_cust_material`
--

INSERT INTO `tbl_cust_material` (`id`, `customer_id`, `cust_name`, `created_by`, `created_dt`, `updated_by`, `updated_dt`, `del_status`) VALUES
(1, 1, 'RAGUPATHY G', 1, '2023-04-19 09:28:44', 1, '2023-04-26 09:17:19', 0),
(2, 2, 'SARAVANAN', 1, '2023-04-19 09:34:24', 1, '2023-04-19 13:26:28', 0),
(3, 3, 'CHANDRAN', 1, '2023-05-02 10:16:08', 1, '2023-05-02 15:46:51', 0);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_cust_material_details`
--

CREATE TABLE `tbl_cust_material_details` (
  `id` tinyint NOT NULL,
  `cm_id` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `sno` tinyint UNSIGNED NOT NULL,
  `part_drg_no` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `particulars` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `hsn_sac` varchar(12) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `pcp_id` tinyint UNSIGNED DEFAULT NULL,
  `uom_id` tinyint UNSIGNED DEFAULT NULL,
  `rate` decimal(10,2) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_cust_material_details`
--

INSERT INTO `tbl_cust_material_details` (`id`, `cm_id`, `sno`, `part_drg_no`, `particulars`, `hsn_sac`, `pcp_id`, `uom_id`, `rate`) VALUES
(8, '2', 1, 'asd', 'asd', 'asd', 3, 2, 2.30),
(9, '2', 2, 'asdf', 'asdf', 'asdf', 3, 2, 2.50),
(10, '2', 3, 'qwer', 'qwer', 'qwer', 3, 2, 3.00),
(11, '1', 1, 'asd', 'asd', 'asd', 3, 2, 2.50),
(12, '1', 2, 'qaz', 'qaz', 'qaz', 1, 2, 3.50),
(25, '3', 1, 'A', 'A', 'A', 2, 3, 3.50),
(26, '3', 2, 'B', 'B', 'B', 2, 3, 4.00),
(27, '3', 3, 'C', 'C', 'C', 2, 3, 2.50),
(28, '3', 4, 'D', 'D', 'D', 4, 2, 1.50),
(29, '3', 5, 'E', 'E', 'E', 4, 2, 2.00),
(30, '3', 6, 'F', 'F', 'F', 3, 2, 5.00),
(31, '3', 7, 'G', 'G', 'G', 2, 3, 1.20),
(32, '3', 8, 'H', 'H', 'H', 2, 3, 1.70),
(33, '3', 9, 'I', 'I', 'I', 2, 3, 1.10),
(34, '3', 10, 'J', 'J', 'J', 2, 3, 2.10),
(35, '3', 11, 'K', 'K', 'K', 1, 3, 1.50),
(36, '3', 12, 'L', 'L', 'L', 1, 1, 1.70),
(37, '3', 13, 'M', 'M', 'M', 2, 3, 3.00),
(38, '3', 14, 'N', 'N', 'N', 2, 3, 2.00),
(39, '3', 15, 'O', 'O', 'O', 2, 2, 5.50),
(40, '3', 16, 'P', 'P', 'P', 2, 3, 2.00),
(41, '3', 17, 'Q', 'Q', 'Q', 2, 3, 2.00);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_gst`
--

CREATE TABLE `tbl_gst` (
  `id` tinyint NOT NULL,
  `sgst` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `cgst` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `igst` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `descriptions` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `created_by` tinyint DEFAULT NULL,
  `created_dt` datetime DEFAULT NULL,
  `updated_by` tinyint DEFAULT NULL,
  `updated_dt` datetime DEFAULT NULL,
  `del_status` tinyint NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_gst`
--

INSERT INTO `tbl_gst` (`id`, `sgst`, `cgst`, `igst`, `descriptions`, `created_by`, `created_dt`, `updated_by`, `updated_dt`, `del_status`) VALUES
(1, '2', '2', '4', '4', 1, '2023-04-11 13:58:50', 1, '2024-07-17 17:50:14', 1),
(2, '2.5', '2.5', '5', '5 %', 1, '2023-04-11 14:04:02', 1, '2023-04-11 14:15:21', 1),
(3, '6', '6', '12', '12 %', 1, '2023-04-11 14:04:45', 1, '2023-04-11 14:15:00', 1),
(4, '9', '9', '18', '18 %', 1, '2023-04-11 14:05:20', 1, '2023-04-11 14:14:48', 1),
(5, '14', '14', '28', '28 %', 1, '2023-04-11 14:05:59', 1, '2023-04-11 14:13:01', 1),
(6, '2', '3', '5', '5 %', 1, '2024-07-20 16:19:09', 1, '2024-07-20 16:19:21', 0);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_pcp`
--

CREATE TABLE `tbl_pcp` (
  `id` tinyint NOT NULL,
  `pcp` varchar(20) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `created_by` tinyint DEFAULT NULL,
  `created_dt` datetime DEFAULT NULL,
  `updated_by` tinyint DEFAULT NULL,
  `updated_dt` datetime DEFAULT NULL,
  `del_status` tinyint NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_pcp`
--

INSERT INTO `tbl_pcp` (`id`, `pcp`, `created_by`, `created_dt`, `updated_by`, `updated_dt`, `del_status`) VALUES
(1, 'Cover', NULL, NULL, NULL, NULL, 0),
(2, 'Pin', NULL, NULL, NULL, NULL, 0),
(3, 'Bag', NULL, NULL, NULL, NULL, 0),
(4, 'Box', NULL, NULL, NULL, NULL, 0);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_product`
--

CREATE TABLE `tbl_product` (
  `id` int NOT NULL,
  `p_code` varchar(20) NOT NULL,
  `p_name` varchar(100) NOT NULL,
  `p_hsn` varchar(20) NOT NULL,
  `category_name` int NOT NULL,
  `uom` int NOT NULL,
  `sales_price` float NOT NULL,
  `sales_gstfix` tinyint NOT NULL COMMENT '(Including GST / Excluding GST)',
  `purchase_price` float NOT NULL,
  `purchase_gstfix` tinyint NOT NULL COMMENT '(Including GST / Excluding GST)',
  `gst` tinyint NOT NULL,
  `opening_qty` double NOT NULL,
  `opening_qty_date` date NOT NULL,
  `min_stock_maintain` double NOT NULL,
  `location` varchar(100) NOT NULL,
  `p_img` varchar(75) DEFAULT NULL,
  `created_by` tinyint NOT NULL,
  `created_dt` datetime NOT NULL,
  `updated_by` tinyint DEFAULT NULL,
  `updated_dt` datetime DEFAULT NULL,
  `del_status` tinyint NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tbl_product`
--

INSERT INTO `tbl_product` (`id`, `p_code`, `p_name`, `p_hsn`, `category_name`, `uom`, `sales_price`, `sales_gstfix`, `purchase_price`, `purchase_gstfix`, `gst`, `opening_qty`, `opening_qty_date`, `min_stock_maintain`, `location`, `p_img`, `created_by`, `created_dt`, `updated_by`, `updated_dt`, `del_status`) VALUES
(1, '002', '2', '2', 2, 4, 2, 3, 2, 3, 4, 2, '2024-07-18', 2, '2', NULL, 1, '2024-07-17 19:15:54', 1, '2024-07-17 22:16:26', 0),
(2, '001', 'paint', '0001', 4, 4, 100, 3, 86, 1, 6, 80, '2024-07-20', 5, '4th rake', '', 1, '2024-07-20 16:39:28', 1, '2024-07-20 21:26:43', 0),
(3, '003', 'nothing', '003', 4, 4, 1, 1, 1, 3, 6, 10, '2024-07-20', 5, 'xsd', '003_product_nothing.png', 1, '2024-07-20 16:48:48', 1, '2024-07-20 21:24:23', 0);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_roles`
--

CREATE TABLE `tbl_roles` (
  `id` tinyint NOT NULL,
  `role_name` varchar(100) DEFAULT NULL,
  `menu_permission` text NOT NULL,
  `status` tinyint NOT NULL,
  `created_by` tinyint DEFAULT NULL,
  `created_dt` datetime DEFAULT NULL,
  `updated_by` tinyint DEFAULT NULL,
  `updated_dt` datetime DEFAULT NULL,
  `del_status` tinyint NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_roles`
--

INSERT INTO `tbl_roles` (`id`, `role_name`, `menu_permission`, `status`, `created_by`, `created_dt`, `updated_by`, `updated_dt`, `del_status`) VALUES
(1, 'Super Admin', 'mnuSales||mnuCustomer||mnuGST||mnuUOM||mnuRolesPermissions||mnuUsers||mnuShop', 1, 1, '2023-01-03 12:57:26', 1, '2024-07-08 14:14:08', 0);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_sales`
--

CREATE TABLE `tbl_sales` (
  `id` bigint NOT NULL,
  `invoice_no` varchar(10) NOT NULL,
  `sales_date` date NOT NULL,
  `customer_id` int UNSIGNED NOT NULL,
  `vend_code` varchar(10) NOT NULL,
  `total` decimal(10,2) UNSIGNED NOT NULL,
  `sgst_amt` decimal(10,2) UNSIGNED DEFAULT NULL,
  `cgst_amt` decimal(10,2) UNSIGNED DEFAULT NULL,
  `igst_amt` decimal(10,2) UNSIGNED DEFAULT NULL,
  `total_gst_amt` decimal(10,2) UNSIGNED DEFAULT NULL,
  `round_amount` varchar(20) DEFAULT NULL,
  `total_amount` decimal(10,2) UNSIGNED DEFAULT NULL,
  `bill` tinyint NOT NULL DEFAULT '0' COMMENT '0-Not Generated; 1-Generated',
  `created_by` tinyint DEFAULT NULL,
  `created_dt` datetime DEFAULT NULL,
  `updated_by` tinyint DEFAULT NULL,
  `updated_dt` datetime DEFAULT NULL,
  `del_status` tinyint NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_sales`
--

INSERT INTO `tbl_sales` (`id`, `invoice_no`, `sales_date`, `customer_id`, `vend_code`, `total`, `sgst_amt`, `cgst_amt`, `igst_amt`, `total_gst_amt`, `round_amount`, `total_amount`, `bill`, `created_by`, `created_dt`, `updated_by`, `updated_dt`, `del_status`) VALUES
(1, 'B000001', '2023-04-28', 1, 'RA001', 160.00, 4.00, 4.00, 0.00, 8.00, '0.00', 168.00, 1, 1, '2023-04-28 12:44:55', NULL, NULL, 0),
(2, 'B000002', '2023-04-28', 2, 'ASD123', 115.00, 2.88, 2.88, 0.00, 5.76, '0.24', 121.00, 1, 1, '2023-04-28 12:45:28', 1, '2023-04-28 12:47:52', 0),
(3, 'B000003', '2023-05-02', 3, 'CHA001', 102.70, 2.57, 2.57, 0.00, 5.14, '0.16', 108.00, 1, 1, '2023-05-02 10:31:36', NULL, NULL, 0),
(4, 'B000004', '2023-05-02', 3, 'CHA001', 42.30, 1.06, 1.06, 0.00, 2.12, '-0.42', 44.00, 1, 1, '2023-05-02 15:49:21', NULL, NULL, 0);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_sales_details`
--

CREATE TABLE `tbl_sales_details` (
  `id` bigint NOT NULL,
  `sales_id` int UNSIGNED NOT NULL,
  `cm_details_id` int UNSIGNED NOT NULL,
  `uom_id` tinyint UNSIGNED NOT NULL,
  `pcp_id` tinyint UNSIGNED NOT NULL,
  `pcp_nos` smallint UNSIGNED NOT NULL,
  `qty` smallint UNSIGNED NOT NULL,
  `rate` decimal(10,2) UNSIGNED NOT NULL,
  `amount` decimal(10,2) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_sales_details`
--

INSERT INTO `tbl_sales_details` (`id`, `sales_id`, `cm_details_id`, `uom_id`, `pcp_id`, `pcp_nos`, `qty`, `rate`, `amount`) VALUES
(1, 1, 11, 2, 3, 23, 50, 2.50, 125.00),
(2, 1, 12, 2, 1, 23, 10, 3.50, 35.00),
(4, 2, 10, 2, 3, 12, 30, 3.00, 90.00),
(5, 2, 9, 2, 3, 12, 10, 2.50, 25.00),
(6, 3, 13, 3, 2, 20, 2, 3.50, 7.00),
(7, 3, 14, 3, 2, 10, 2, 4.00, 8.00),
(8, 3, 15, 3, 2, 10, 2, 2.50, 5.00),
(9, 3, 16, 2, 4, 10, 2, 1.50, 3.00),
(10, 3, 17, 2, 4, 10, 2, 2.00, 4.00),
(11, 3, 18, 2, 3, 12, 1, 5.00, 5.00),
(12, 3, 19, 3, 2, 10, 2, 1.20, 2.40),
(13, 3, 20, 3, 2, 12, 2, 1.70, 3.40),
(14, 3, 21, 3, 2, 12, 5, 1.10, 5.50),
(15, 3, 22, 3, 2, 10, 10, 2.10, 21.00),
(16, 3, 23, 3, 1, 20, 12, 1.50, 18.00),
(17, 3, 24, 1, 1, 10, 12, 1.70, 20.40),
(18, 4, 25, 3, 2, 1, 1, 3.50, 3.50),
(19, 4, 26, 3, 2, 1, 1, 4.00, 4.00),
(20, 4, 27, 3, 2, 1, 1, 2.50, 2.50),
(21, 4, 28, 2, 4, 1, 1, 1.50, 1.50),
(22, 4, 29, 2, 4, 1, 1, 2.00, 2.00),
(23, 4, 30, 2, 3, 1, 1, 5.00, 5.00),
(24, 4, 31, 3, 2, 1, 1, 1.20, 1.20),
(25, 4, 32, 3, 2, 1, 1, 1.70, 1.70),
(26, 4, 33, 3, 2, 1, 1, 1.10, 1.10),
(27, 4, 34, 3, 2, 1, 1, 2.10, 2.10),
(28, 4, 35, 3, 1, 1, 1, 1.50, 1.50),
(29, 4, 36, 1, 1, 1, 1, 1.70, 1.70),
(30, 4, 37, 3, 2, 1, 1, 3.00, 3.00),
(31, 4, 38, 3, 2, 1, 1, 2.00, 2.00),
(32, 4, 39, 2, 2, 1, 1, 5.50, 5.50),
(33, 4, 40, 3, 2, 1, 1, 2.00, 2.00),
(34, 4, 41, 3, 2, 1, 1, 2.00, 2.00);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_shop`
--

CREATE TABLE `tbl_shop` (
  `id` tinyint NOT NULL,
  `shop_name` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `shop_gst_no` varchar(25) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `owner_name` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `mobile_no1` varchar(15) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `mobile_no2` varchar(15) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `email_id` varchar(150) DEFAULT NULL,
  `door_no` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `address_line1` varchar(100) DEFAULT NULL,
  `address_line2` varchar(100) DEFAULT NULL,
  `city` varchar(50) DEFAULT NULL,
  `shop_state` varchar(50) DEFAULT NULL,
  `pincode` varchar(10) DEFAULT NULL,
  `created_by` tinyint DEFAULT NULL,
  `created_dt` datetime DEFAULT NULL,
  `updated_by` tinyint DEFAULT NULL,
  `updated_dt` datetime DEFAULT NULL,
  `del_status` tinyint NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_shop`
--

INSERT INTO `tbl_shop` (`id`, `shop_name`, `shop_gst_no`, `owner_name`, `mobile_no1`, `mobile_no2`, `email_id`, `door_no`, `address_line1`, `address_line2`, `city`, `shop_state`, `pincode`, `created_by`, `created_dt`, `updated_by`, `updated_dt`, `del_status`) VALUES
(1, 'Annamalai Agency', 'XXXXXXXXXXXX', 'Arun Raja', '6374160833', '', '', '11', 'AAA', 'BBB', 'CCC', 'Tamilnadu', '641001', 1, '2024-07-08 10:46:03', 1, '2024-07-08 11:00:17', 0);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_supplier`
--

CREATE TABLE `tbl_supplier` (
  `id` mediumint NOT NULL,
  `sup_id` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `sup_name` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `gst_no` varchar(20) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `tin_no` varchar(30) NOT NULL,
  `cin_no` varchar(30) NOT NULL,
  `pan_no` varchar(20) DEFAULT NULL,
  `door_no` varchar(20) DEFAULT NULL,
  `addr_line1` varchar(100) DEFAULT NULL,
  `addr_line2` varchar(100) DEFAULT NULL,
  `city_dist` varchar(100) DEFAULT NULL,
  `cstate` varchar(60) DEFAULT NULL,
  `pincode` varchar(6) DEFAULT NULL,
  `mobile_no1` varchar(10) DEFAULT NULL,
  `mobile_no2` varchar(10) DEFAULT NULL,
  `email_id` varchar(70) DEFAULT NULL,
  `created_by` tinyint DEFAULT NULL,
  `created_dt` datetime DEFAULT NULL,
  `updated_by` tinyint DEFAULT NULL,
  `updated_dt` datetime DEFAULT NULL,
  `del_status` tinyint NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_supplier`
--

INSERT INTO `tbl_supplier` (`id`, `sup_id`, `sup_name`, `gst_no`, `tin_no`, `cin_no`, `pan_no`, `door_no`, `addr_line1`, `addr_line2`, `city_dist`, `cstate`, `pincode`, `mobile_no1`, `mobile_no2`, `email_id`, `created_by`, `created_dt`, `updated_by`, `updated_dt`, `del_status`) VALUES
(1, 'S00001', 'MUNUSAMYQQQQ', 'WSEDRGHJMNBQQQQQQQQQ', 'SDCSXC CXQQQQQQQQQQQQQQQQQQQQQ', 'DVCDCFCDQQQQQQQQQQQQQQQ', 'DCVFFVVQQQ', 'FVFVFVQQQQQQQQQQQQQQ', 'VFVFQQQQQQQQQQQ', 'VFVQQQQQQQQQQQ', 'VFVFVFVQQQQQQQQQQ', 'Tamil NaduQQQQQQ', '636813', '7019601667', '', 'munus9404@gmail.com', 1, '2024-07-15 22:17:30', 1, '2024-07-15 22:18:09', 0);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_transport`
--

CREATE TABLE `tbl_transport` (
  `id` int NOT NULL,
  `vehicle_name` varchar(150) NOT NULL,
  `vehicle_num_plate` varchar(15) NOT NULL,
  `driver_name` varchar(75) NOT NULL,
  `driver_mobile_num` varchar(20) NOT NULL,
  `owner_mobile_num` varchar(20) NOT NULL,
  `created_by` tinyint NOT NULL,
  `created_dt` datetime NOT NULL,
  `updated_by` tinyint DEFAULT NULL,
  `updated_dt` datetime DEFAULT NULL,
  `del_status` tinyint NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tbl_transport`
--

INSERT INTO `tbl_transport` (`id`, `vehicle_name`, `vehicle_num_plate`, `driver_name`, `driver_mobile_num`, `owner_mobile_num`, `created_by`, `created_dt`, `updated_by`, `updated_dt`, `del_status`) VALUES
(1, 'fdfdhwerg111', 'thfgdgfg111', 'hgfhg111', 'sfgh111', 'tretg111', 1, '2024-07-18 08:50:25', 1, '2024-07-18 08:51:53', 1);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_uom`
--

CREATE TABLE `tbl_uom` (
  `id` tinyint NOT NULL,
  `uom_name` varchar(15) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `uom` varchar(5) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `created_by` tinyint DEFAULT NULL,
  `created_dt` datetime DEFAULT NULL,
  `updated_by` tinyint DEFAULT NULL,
  `updated_dt` datetime DEFAULT NULL,
  `del_status` tinyint NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_uom`
--

INSERT INTO `tbl_uom` (`id`, `uom_name`, `uom`, `created_by`, `created_dt`, `updated_by`, `updated_dt`, `del_status`) VALUES
(1, 'Liters', 'Lts', 1, '2023-04-11 14:35:37', NULL, NULL, 0),
(2, 'Kilograms', 'Kgs', 1, '2023-04-11 14:35:53', NULL, NULL, 0),
(3, 'Numbers', 'Nos', 1, '2023-04-11 14:36:03', NULL, NULL, 0),
(4, 'Boxes', 'Box', 1, '2023-04-11 14:36:14', NULL, NULL, 0),
(5, 'Bags', 'Bags', 1, '2023-04-11 14:37:23', NULL, NULL, 0),
(6, 'Pieces', 'Pcs', 1, '2024-06-29 09:11:41', NULL, NULL, 0);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_users`
--

CREATE TABLE `tbl_users` (
  `id` tinyint NOT NULL,
  `uname` varchar(100) DEFAULT NULL,
  `mobile_no` varchar(15) DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `roles_id` tinyint DEFAULT NULL,
  `status` tinyint NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `hint_password` varchar(100) NOT NULL,
  `created_by` tinyint DEFAULT NULL,
  `created_dt` datetime DEFAULT NULL,
  `updated_by` tinyint DEFAULT NULL,
  `updated_dt` datetime DEFAULT NULL,
  `del_status` tinyint NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_users`
--

INSERT INTO `tbl_users` (`id`, `uname`, `mobile_no`, `email`, `roles_id`, `status`, `username`, `password`, `hint_password`, `created_by`, `created_dt`, `updated_by`, `updated_dt`, `del_status`) VALUES
(1, 'Administrator', '9944537774', 'admin@gmail.com', 1, 1, 'admin', 'e122911e07b7fe7df3cb4eaf9cd03f57', 'admin1234', 1, '2023-01-07 15:48:57', 0, '2023-01-07 16:28:03', 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbl_category`
--
ALTER TABLE `tbl_category`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_customer`
--
ALTER TABLE `tbl_customer`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_cust_material`
--
ALTER TABLE `tbl_cust_material`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_cust_material_details`
--
ALTER TABLE `tbl_cust_material_details`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_gst`
--
ALTER TABLE `tbl_gst`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_pcp`
--
ALTER TABLE `tbl_pcp`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_product`
--
ALTER TABLE `tbl_product`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_roles`
--
ALTER TABLE `tbl_roles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_sales`
--
ALTER TABLE `tbl_sales`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_sales_details`
--
ALTER TABLE `tbl_sales_details`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_shop`
--
ALTER TABLE `tbl_shop`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_supplier`
--
ALTER TABLE `tbl_supplier`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_transport`
--
ALTER TABLE `tbl_transport`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_uom`
--
ALTER TABLE `tbl_uom`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_users`
--
ALTER TABLE `tbl_users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbl_category`
--
ALTER TABLE `tbl_category`
  MODIFY `id` tinyint NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tbl_customer`
--
ALTER TABLE `tbl_customer`
  MODIFY `id` mediumint NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tbl_cust_material`
--
ALTER TABLE `tbl_cust_material`
  MODIFY `id` tinyint NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tbl_cust_material_details`
--
ALTER TABLE `tbl_cust_material_details`
  MODIFY `id` tinyint NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT for table `tbl_gst`
--
ALTER TABLE `tbl_gst`
  MODIFY `id` tinyint NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `tbl_pcp`
--
ALTER TABLE `tbl_pcp`
  MODIFY `id` tinyint NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tbl_product`
--
ALTER TABLE `tbl_product`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tbl_roles`
--
ALTER TABLE `tbl_roles`
  MODIFY `id` tinyint NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tbl_sales`
--
ALTER TABLE `tbl_sales`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tbl_sales_details`
--
ALTER TABLE `tbl_sales_details`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `tbl_supplier`
--
ALTER TABLE `tbl_supplier`
  MODIFY `id` mediumint NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tbl_transport`
--
ALTER TABLE `tbl_transport`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tbl_uom`
--
ALTER TABLE `tbl_uom`
  MODIFY `id` tinyint NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `tbl_users`
--
ALTER TABLE `tbl_users`
  MODIFY `id` tinyint NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
