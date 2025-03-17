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
$stmt = $con->query("SELECT COUNT(*) AS allcount FROM tbl_sales WHERE del_status = 0".$searchQuery);
$records = $stmt->fetch();
$totalRecords = $records->allcount;

## Total number of records with filtering
$stmt = $con->query("SELECT COUNT(*) AS allcount FROM tbl_sales WHERE del_status = 0 ".$searchQuery);
$records = $stmt->fetch();
$totalRecordwithFilter = $records->allcount;

## Fetch records
$data_sql = "SELECT * FROM tbl_sales where del_status = 0 ".$searchQuery." ORDER BY ".$columnName." ".$columnSortOrder." LIMIT ". $row .",". $rowperpage;
//echo $data_sql;
$stmt = $con->query($data_sql);
$rec = $stmt->fetchAll();

$data = array();

$sno = 1;
foreach ($rec as $row) {

  $action = $edit_link = $delete_link = $bill_link = "";

  $bill_link = '<a href="sales_bill.php?id='. $converter->encode($row->id) .'" title="Bill"><i class="fa fa-file-text-o" aria-hidden="true"></i></a>&nbsp;&nbsp;';

  $bill = $dbcon->GetOneRecord("tbl_sales", "bill", "id = ". $row->id ." and del_status", 0);

  if($bill == 0){
    $edit_link = '<a href="sales.php?id='. $converter->encode($row->id) .'" title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i></a>&nbsp;&nbsp;';
    $delete_link = '<a href="sales.php?did='. $converter->encode($row->id) .'" title="Delete" onclick="return confirm(\'Are You Sure Want To Delete?\');"><i class="fa fa-trash-o" aria-hidden="true"></i></a>';
  }

  $action = $bill_link . $edit_link . $delete_link;

  $data[] = array(
    "id" => $sno,
    "invoice_no" => $row->invoice_no,
    "sales_date" => date('d-m-Y', strtotime($row->sales_date)),
    "customer_id" => $dbcon->GetOneRecord("tbl_customer", "cust_name", "id = ". $row->customer_id ." and del_status", 0),
    "vend_code" => $row->vend_code,
    "total_amount" => $row->total_amount,
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