<?php
ob_start();
session_start();

require_once("../includes/common/connection.php");
require_once("../includes/common/dbfunctions.php");
require_once("../includes/common/functions.php");

$con = new connection();
$dbcon = new dbfunctions();
$converter = new Encryption;

// ini_set('display_errors', '1');
// ini_set('display_startup_errors', '1');
// error_reporting(E_ALL);

$menu_permission = explode('||', $_SESSION["menu_permission"]);

## Reading value
$draw = $_POST['draw'];
$row = $_POST['start'];
$rowperpage = $_POST['length']; // Rows display per page
$columnIndex = $_POST['order'][0]['column']; // Column index
$columnName = $_POST['columns'][$columnIndex]['data']; // Column name
$columnSortOrder = $_POST['order'][0]['dir']; // asc or desc
//$searchValue = $_POST['search']['value']; // Search value

## Custom Search Field
$searchByValue = isset($_POST['searchByValue'])? $_POST['searchByValue'] : ''; //searchByValue
$searchByType = isset($_POST['searchByType'])? $_POST['searchByType'] : ''; //searchByType
$searchByDesignation = isset($_POST['searchByDesignation'])? $_POST['searchByDesignation'] : ''; //searchByDesignation
//echo $searchByValue; echo $searchByDegree; echo $searchByDesignation;

## Search
$searchQuery = " ";

if($searchByValue != ''){
  $searchQuery = " AND (staff_id LIKE '%". $searchByValue ."%' OR 
        staff_name LIKE '%". $searchByValue ."%' OR
        qualification LIKE '%". $searchByValue ."%' OR 
        mobile_no LIKE '%". $searchByValue ."%' ) ";
}

if($searchByType != ''){
  $searchQuery = " AND (staff_type = ". $searchByType .") ";
}

if($searchByDesignation != ''){
    $searchQuery = " AND (designation = ". $searchByDesignation .") ";
}

## Column
if($columnName == '' || $columnName == 'staff_id'){
  $columnName = " staff_id ";
}

## Total number of records without filtering
$stmt = $con->query("SELECT COUNT(*) AS allcount FROM tbl_staffs WHERE del_status = 0".$searchQuery);
$records = $stmt->fetch();
$totalRecords = $records->allcount;

## Total number of records with filtering
$stmt = $con->query("SELECT COUNT(*) AS allcount FROM tbl_staffs WHERE del_status = 0 ".$searchQuery);
$records = $stmt->fetch();
$totalRecordwithFilter = $records->allcount;

## Fetch records
$data_sql = "SELECT * FROM tbl_staffs where del_status = 0 ".$searchQuery." ORDER BY ".$columnName." ".$columnSortOrder." LIMIT ". $row .",". $rowperpage;
//echo $data_sql;
$stmt = $con->query($data_sql);
$staffRecords = $stmt->fetchAll();

$data = array();

$sno = 1;
foreach ($staffRecords as $row) {

  $action = $edit_link = $delete_link = "";

  if(in_array('updateStudents',$menu_permission)){
    $edit_link = '<a href="staffs.php?id='. $converter->encode($row->id) .'" title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i></a> &nbsp;&nbsp;';
  }
  if(in_array('deleteStudents',$menu_permission)){
    $delete_link = '<a href="staffs.php?did='. $converter->encode($row->id) .'" title="Delete" onclick="return confirm(\'Are You Sure Want To Delete?\');"><i class="fa fa-trash-o" aria-hidden="true"></i></a>';
  }

  $action = $edit_link . $delete_link;

  $data[] = array(
    "id"=>$sno,
    "staff_id"=>$row->staff_id,
    "staff_name"=>$row->staff_name,
    "qualification"=>$row->qualification,
    "staff_type"=>iif($row->staff_type == 1, "Teaching Staff", "Non-Teaching Staff"),
    "designation"=>iif($row->designation == "", "-", $dbcon->GetOneRecord("tbl_designation", "dname", "id", $row->designation)),
    "mobile_no"=>iif($row->mobile_no=="", "-", $row->mobile_no),
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