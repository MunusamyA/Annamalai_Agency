<?php
ob_start();
session_start();
define('BASEPATH', '../');

require_once(BASEPATH ."includes/common/connection.php");
require_once(BASEPATH ."includes/common/dbfunctions.php");
require_once(BASEPATH ."includes/common/functions.php");

$con = new connection();
$dbcon = new dbfunctions();
$converter = new Encryption;

// ini_set('display_errors', '1'); ini_set('display_startup_errors', '1'); error_reporting(E_ALL);

## Reading value
$draw = $_POST['draw'];
$row = $_POST['start'];
$rowperpage = $_POST['length']; // Rows display per page
$columnIndex = $_POST['order'][0]['column']; // Column index
$columnName = $_POST['columns'][$columnIndex]['data']; // Column name
$columnSortOrder = $_POST['order'][0]['dir']; // asc or desc
//$searchValue = $_POST['search']['value']; // Search value

## Custom Search Field
$searchByCustomerName = isset($_POST['searchByCustomerName'])? $_POST['searchByCustomerName'] : ''; //searchByValue
//$searchByDegree = isset($_POST['searchByDegree'])? $_POST['searchByDegree'] : ''; //searchByDegree
//echo $searchByValue;
//echo $searchByDegree;

## Search
$searchQuery = " ";

if($searchByCustomerName != ''){
  $searchQuery = " AND (customer_id = ". $searchByCustomerName .") ";
}

## Column
if($columnName == '' || $columnName == 'customer_id'){
  $columnName = " customer_id ";
}

## Total number of records without filtering
$stmt = $con->query("SELECT COUNT(*) AS allcount FROM tbl_cust_material WHERE del_status = 0".$searchQuery);
$records = $stmt->fetch();
$totalRecords = $records->allcount;

## Total number of records with filtering
$stmt = $con->query("SELECT COUNT(*) AS allcount FROM tbl_cust_material WHERE del_status = 0 ".$searchQuery);
$records = $stmt->fetch();
$totalRecordwithFilter = $records->allcount;

## Fetch records
$data_sql = "SELECT * FROM tbl_cust_material where del_status = 0 ".$searchQuery." ORDER BY ".$columnName." ".$columnSortOrder." LIMIT ". $row .",". $rowperpage;
//echo $data_sql;
$stmt = $con->query($data_sql);
$studentRecords = $stmt->fetchAll();

$data = array();

$sno = 1;
foreach ($studentRecords as $row) {

  $action = $edit_link = $delete_link = "";

  $edit_link = '<a href="cust_material.php?id='. $converter->encode($row->id) .'" title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i></a>&nbsp;&nbsp;';
  $delete_link = '<a href="cust_material.php?did='. $converter->encode($row->id) .'" title="Delete" onclick="return confirm(\'Are You Sure Want To Delete?\');"><i class="fa fa-trash-o" aria-hidden="true"></i></a>';
  
  $action = $edit_link . $delete_link;

  $data[] = array(
    "id" => $sno,
    "customer_id" => $dbcon->GetOneRecord("tbl_customer", "cust_id", "id = ". $row->customer_id ." and del_status", 0),
    "cust_name" => $dbcon->GetOneRecord("tbl_customer", "cust_name", "id = ". $row->customer_id ." and del_status", 0),
    "created_by" => $dbcon->GetOneRecord("tbl_users", "uname", "id = ". $row->created_by ." and del_status", 0),
    "created_dt" => date('d-m-Y', strtotime($row->created_dt)),
    "action"=>$action
  );

  $sno++;
}

## Response
$response = array(
  "draw" => intval($draw),
  "iTotalRecords" => $totalRecords,
  "iTotalDisplayRecords" => $totalRecordwithFilter,
  "aaData" => $data
);

echo json_encode($response);