<?php
ob_start();
session_start();

require_once("includes/common/connection.php");
require_once("includes/common/dbfunctions.php");
require_once("includes/common/functions.php");

$con = new connection();
$dbcon = new dbfunctions();

// ini_set('display_errors', '1'); ini_set('display_startup_errors', '1'); error_reporting(E_ALL);

isAdmin();

?>
<?php include("includes/header.php"); ?>
<?php include("includes/aside.php"); ?>
<div class="content-wrapper">
    <section class="content-header">
        <h1>Sales</h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-desktop"></i> Transaction</a></li>
            <li class="active">Sales</li>
        </ol>
    </section>
    <section class="content">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">List</h3>
                <div class="box-tools pull-right">
                    <a href="sales.php" class="btn btn-block btn-primary"><i class="fa fa-plus-circle" aria-hidden="true"></i></a>
                </div>
            </div>
            <div class="box-body">

                <div class="box box-solid search" style="background-color: #ECF0F5;">
                    <div class="box-body">
                        <div class="row">
                            
                            <div class="col-md-4">
                                <label>Search Customer Name </label>
                                <select class="form-control select2" name="searchByCustomerName" id="searchByCustomerName" title="Select the Customer Name" required oninvalid="this.setCustomValidity('Please select the customer name...!')" onchange="this.setCustomValidity('')">
                                    <option value=""></option>
                                    <?php
                                        echo $dbcon->fnFillComboFromTable_Where("id", "CONCAT(cust_id, ' - ', cust_name)", "tbl_customer ", "id", "");
                                    ?>
                                </select>
                            </div>

                        </div>
                    </div>
                </div>
                
                <form id="thisForm" name="thisForm" class="form-horizontal" action="sales.php" method="post">
                <div class="table-responsive">
                    <div class="dt-responsive table-responsive">
                        <table id="example1" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th width="8" class="text-center">#</th>
                                    <th width="80" class="text-center">Invoice No</th>
                                    <th width="80" class="text-center">Date</th>
                                    <th class="text-center">Customer Name</th>
                                    <th width="120" class="text-center">Vendor Code</th>
                                    <th width="100" class="text-center">Total Amount</th>
                                    <th width="60" class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                </div>
                </form>

            </div>
        </div>
    </section>
</div>
<?php include("includes/footer.php"); ?>
<script>
$(function () {
    var dataTable = $('#example1').DataTable({
    //$('#example1').DataTable({
        'autoWidth'     : false,
        'responsive'    : true,
        'processing'    : true,
        'serverSide'    : true,
        'pageLength'    : 12,
        'searching'     : false,
        //'scrollY'       : '570px',
        //'scrollCollapse': true,
        //'dom'           : 'Bfrtip',
        'dom'           : "<'row'<'col-sm-12 d-flex col-md-6'lf><'col-sm-12 col-md-6 text-right'B>>" +
                            "<'row'<'col-sm-12'tr>>" +
                            "<'row m-2'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
        
        'ajax'          : {
            'url':'datatable/SalesList.php',
            'type':'POST',
            'data': function(data){
                //alert(data);
                var searchByCustomerName = $('#searchByCustomerName').val();

                data.searchByCustomerName = searchByCustomerName;
            }
        },
        
        'columns'       : [
            { data: 'id' },
            { data: 'invoice_no' },
            { data: 'sales_date' },
            { data: 'customer_id' },
            { data: 'vend_code' },
            { data: 'total_amount' },
            { data: 'action' },
        ],
        
        'order'         : [[ 1, "desc" ]],  // List Records in Descending Order
        
        columnDefs      : [
            {
                targets: [0,1,2,4,6],
                className: 'text-center'
            },
            {
                targets: [5],
                className: 'text-right'
            },
            { 
                orderable: true, 
                className: 'reorder', 
                targets: [0,1,2,3,4,5]
            },
            { 
                orderable: false, 
                targets: '_all' 
            }],

        buttons         : ['copyHtml5','excelHtml5','csvHtml5','pdfHtml5','print']
    });

    $('#searchByCustomerName').change(function(){  
        dataTable.draw();
    });
});
</script>

<script type="text/javascript">
$(document).ready(function() {
    $('#transMainNav').addClass('active');
    $('#transSalesSubNav').addClass('active');
});
</script>