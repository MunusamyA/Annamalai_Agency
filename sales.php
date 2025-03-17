<?php
ob_start();
session_start();

require_once("includes/common/connection.php");
require_once("includes/common/dbfunctions.php");
require_once("includes/common/functions.php");

$con = new connection();
$dbcon = new dbfunctions();
$converter = new Encryption;

//ini_set('display_errors', '1'); ini_set('display_startup_errors', '1'); error_reporting(E_ALL);

isAdmin();

//---------------------------------save/submit----------------------------------
if (isset($_REQUEST["submit"])) {
    try{
        $created_by = $_SESSION["user_id"];
        $created_dt = date('Y-m-d H:i:s');

        $stmt = null;
        $stmt = $con->prepare("INSERT INTO tbl_sales (invoice_no, sales_date, customer_id, vend_code, total, sgst_amt, cgst_amt, igst_amt, total_gst_amt, round_amount, total_amount, created_by, created_dt) 
                        VALUES (:invoice_no, :sales_date, :customer_id, :vend_code, :total, :sgst_amt, :cgst_amt, :igst_amt, :total_gst_amt, :round_amount, :total_amount, :created_by, :created_dt)");
        $data = array(
            ":invoice_no" => trim($_REQUEST["invoice_no"]),
            ":sales_date" => trim(date('Y-m-d', strtotime($_REQUEST["sales_date"]))),
            ":customer_id" => trim($_REQUEST["customer_id"]),
            ":vend_code" => trim($_REQUEST["vend_code"]),
            ":total" => trim($_REQUEST["total"]),
            ":sgst_amt" => iif(trim($_REQUEST["hid_sgst_amt"]) == "", "0.00", trim($_REQUEST["hid_sgst_amt"])),
            ":cgst_amt" => iif(trim($_REQUEST["hid_cgst_amt"]) == "", "0.00", trim($_REQUEST["hid_cgst_amt"])),
            ":igst_amt" => iif(trim($_REQUEST["hid_igst_amt"]) == "", "0.00", trim($_REQUEST["hid_igst_amt"])),
            ":total_gst_amt" => trim($_REQUEST["total_gst_amt"]),
            ":round_amount" => trim($_REQUEST["round_amount"]),
            ":total_amount" => trim($_REQUEST["total_amount"]),
            ":created_by" => $created_by,
            ":created_dt" => $created_dt,
        );
        //echo "<pre>"; print_r($data); die();
        $stmt->execute($data);
        $id = $con->lastInsertId();

        /* details */
        $stmt1 = null;
        $stmt1 = $con->prepare("INSERT INTO tbl_sales_details (sales_id, cm_details_id, uom_id, pcp_id, pcp_nos, qty, rate, amount) 
                        VALUES (:sales_id, :cm_details_id, :uom_id, :pcp_id, :pcp_nos, :qty, :rate, :amount)");
        for ($x = 0; $x < count($_REQUEST['hid_part_drg_no']); $x++) {
            $data1 = array(
                ':sales_id' => $id, 
                ':cm_details_id' => trim($_REQUEST['hid_part_drg_no'][$x]),
                ':uom_id' => trim($_REQUEST['hid_uom_id'][$x]),
                ':pcp_id' => trim($_REQUEST['hid_pcp_id'][$x]),
                ':pcp_nos' => trim($_REQUEST['hid_pcp_nos'][$x]),
                ':qty' => trim($_REQUEST['hid_qty'][$x]),
                ':rate' => trim($_REQUEST['hid_rate'][$x]),
                ':amount' => trim($_REQUEST['hid_amount'][$x]),
            );
            $stmt1->execute($data1);
        }
        //print_r($data1); die();
        /* details */

        $_SESSION["msg"] = "Saved Successfully";
    } catch (Exception $e) {
        $str = filter_var($e->getMessage(), FILTER_SANITIZE_STRING);
        echo $_SESSION['msg_err'] = $str;  
    }

    header("location: sales_lst.php");
    die();
}
//---------------------------------save/submit----------------------------------
//---------------------------------update--------------------------------------
if (isset($_REQUEST["update"])) {
    try{
        $updated_by = $_SESSION["user_id"];
        $updated_dt = date('Y-m-d H:i:s');

        $stmt = null;
        $stmt = $con->prepare("UPDATE tbl_sales SET sales_date = :sales_date, customer_id = :customer_id, vend_code = :vend_code, total = :total, sgst_amt = :sgst_amt, cgst_amt = :cgst_amt, igst_amt = :igst_amt, 
                                total_gst_amt = :total_gst_amt, round_amount = :round_amount, total_amount = :total_amount, updated_by = :updated_by, updated_dt = :updated_dt WHERE id = :id");

        $data = array(
            ":id" => trim($_REQUEST["hid_id"]),
            ":sales_date" => trim(date('Y-m-d', strtotime($_REQUEST["sales_date"]))),
            ":customer_id" => trim($_REQUEST["customer_id"]),
            ":vend_code" => trim($_REQUEST["vend_code"]),
            ":total" => trim($_REQUEST["total"]),
            ":sgst_amt" => iif(trim($_REQUEST["hid_sgst_amt"]) == "", "0.00", trim($_REQUEST["hid_sgst_amt"])),
            ":cgst_amt" => iif(trim($_REQUEST["hid_cgst_amt"]) == "", "0.00", trim($_REQUEST["hid_cgst_amt"])),
            ":igst_amt" => iif(trim($_REQUEST["hid_igst_amt"]) == "", "0.00", trim($_REQUEST["hid_igst_amt"])),
            ":total_gst_amt" => trim($_REQUEST["total_gst_amt"]),
            ":round_amount" => trim($_REQUEST["round_amount"]),
            ":total_amount" => trim($_REQUEST["total_amount"]),
            ":updated_by" => $updated_by,
            ":updated_dt" => $updated_dt
        );
        $stmt->execute($data);

        /* details */
        $delete_details  = $con->query('DELETE FROM tbl_sales_details WHERE sales_id = '. trim($_REQUEST["hid_id"]));
        $stmt1 = null;
        $stmt1 = $con->prepare("INSERT INTO tbl_sales_details (sales_id, cm_details_id, uom_id, pcp_id, pcp_nos, qty, rate, amount) 
                        VALUES (:sales_id, :cm_details_id, :uom_id, :pcp_id, :pcp_nos, :qty, :rate, :amount)");
        for ($x = 0; $x < count($_REQUEST['hid_part_drg_no']); $x++) {
            $data1 = array(
                ':sales_id' => trim($_REQUEST["hid_id"]), 
                ':cm_details_id' => trim($_REQUEST['hid_part_drg_no'][$x]),
                ':uom_id' => trim($_REQUEST['hid_uom_id'][$x]),
                ':pcp_id' => trim($_REQUEST['hid_pcp_id'][$x]),
                ':pcp_nos' => trim($_REQUEST['hid_pcp_nos'][$x]),
                ':qty' => trim($_REQUEST['hid_qty'][$x]),
                ':rate' => trim($_REQUEST['hid_rate'][$x]),
                ':amount' => trim($_REQUEST['hid_amount'][$x]),
            );
            $stmt1->execute($data1);
        }
        /* details */

        $_SESSION["msg"] = "Updated Successfully";
    } catch (Exception $e) {
        $str = filter_var($e->getMessage(), FILTER_SANITIZE_STRING);
        echo $_SESSION['msg_err'] = $str;  
    }

    header("location: sales_lst.php");
    die();
}
//---------------------------------update----------------------------------------
//---------------------------------delete----------------------------------------
if (isset($_REQUEST["did"])) {
    $stmt = null;
    $stmt = $con->query("UPDATE tbl_sales SET del_status=1 WHERE id=" . $converter->decode($_REQUEST["did"]));

    $_SESSION["msg"] = "Deleted Successfully";

    header("location: sales_lst.php");
    die();
}
//---------------------------------delete----------------------------------------

$invoice = $dbcon->GetMaxValue('tbl_sales', 'id', 'del_status', 0) + 1;
$invoice_no = 'B' . leadingZeros($invoice, 6);
$sales_date = date('Y-m-d');

if (isset($_REQUEST["id"])) {
    $rs = $con->query("SELECT * FROM tbl_sales WHERE id = ". $converter->decode($_REQUEST["id"]));
    if ($rs->rowCount()) {
        if ($obj = $rs->fetch(PDO::FETCH_OBJ)) {
            $id = $obj->id;
            $invoice_no = $obj->invoice_no;
            $sales_date = $obj->sales_date;
            $customer_id = $obj->customer_id;
        }
    }
}
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
                <?php if (isset($_REQUEST["id"])) {
                    echo '<h3 class="box-title">Edit</h3>';
                } else {
                    echo '<h3 class="box-title">Add</h3>';
                } ?>
                <div class="box-tools pull-right">
                    <a href="sales_lst.php" class="btn btn-block btn-primary"><i class="fa fa-bars" aria-hidden="true"></i></a>
                </div>
            </div>
            <br />
            <form id="thisForm" name="thisForm" action="sales.php" method="post" onsubmit="return fnValid();">
                <div class="box-body">

                    <div class="row" style="padding-bottom:15px;">
                        <div class="col-md-2">
                            <label class="col-form-label">Invoice No.</label>
                            <input type="text" class="form-control" name="invoice_no" id="invoice_no" value="<?php echo $invoice_no; ?>" readonly title="Invoice Number">
                        </div>
                        <div class="col-md-2">
                            <label class="col-form-label">Date</label>
                            <input type="date" class="form-control" name="sales_date" id="sales_date" value="<?php echo $sales_date; ?>" title="Sales Date">
                        </div>
                        <div class="col-md-4">
                            <label class="col-form-label">Customer Name <span class="err">*</span></label>
                            <select class="form-control select2" name="customer_id" id="customer_id" title="Select the Customer Name" required oninvalid="this.setCustomValidity('Please select the customer name...!')" onchange="this.setCustomValidity('')">
                                <option value=""></option>
                                <?php
                                echo $dbcon->fnFillComboFromTable_Where("id", "CONCAT(cust_id, ' - ', cust_name)", "tbl_customer ", "id", "");
                                ?>
                            </select>
                            <script>
                                document.thisForm.customer_id.value = "<?php echo $customer_id; ?>"
                            </script>
                        </div>
                        <div class="col-md-2">
                            <label class="col-form-label">Vendor Code</label>
                            <input type="text" class="form-control" name="vend_code" id="vend_code" value="<?php echo $obj->vend_code; ?>" readonly title="Vendor Code">
                        </div>
                    </div>
                    <div class="col-md-12" style="background-color: #ECF0F5;">
                        <div class="row" style="padding:10px 0;">
                            <div class="col-md-4">
                                <label class="col-form-label">Name of the Particulars <span class="err">*</span></label>
                                <select class="form-control select2" name="particulars" id="particulars" title="Select the Particulars">
                                    <?php
                                    if (isset($_REQUEST["id"])) {
                                        echo '<option value=""></option>';
                                        echo $dbcon->fnFillComboFromTable_Where("id", "particulars", "tbl_cust_material_details", "particulars", " WHERE cm_id = ". $customer_id);
                                    }
                                    ?>
                                </select>
                            </div>
                            
                            <div class="col-md-1">
                                <label class="col-form-label">UOM <span class="err">*</span></label>
                                <select class="form-control select2" name="uom_id" id="uom_id" title="Select the UOM">
                                    <option value=""></option>
                                    <?php
                                    echo $dbcon->fnFillComboFromTable("id", "uom", "tbl_uom", "id");
                                    ?>
                                </select>
                            </div>
                            <div class="col-md-1">
                                <label class="col-form-label">PCP <span class="err">*</span></label>
                                <select class="form-control select2" name="pcp_id" id="pcp_id" title="Select the PCP">
                                    <option value=""></option>
                                    <?php
                                    echo $dbcon->fnFillComboFromTable("id", "pcp", "tbl_pcp", "id");
                                    ?>
                                </select>
                            </div>
                            <div class="col-md-1">
                                <label class="col-form-label">PCP Nos<span class="err">*</span></label>
                                <input type="text" class="form-control" name="pcp_nos" id="pcp_nos" value="" title="Enter the PCP Nos" onkeypress="return isNumberKey(event);">
                            </div>
                            <div class="col-md-1">
                                <label class="col-form-label">Qty<span class="err">*</span></label>
                                <input type="text" class="form-control" name="qty" id="qty" value="" title="Enter the Qty" onkeypress="return isNumberKey(event);">
                            </div>
                            <div class="col-md-1">
                                <label class="col-form-label">Rate</label>
                                <input type="text" class="form-control text-right" name="rate" id="rate" value="" readonly title="Enter the Rate">
                            </div>
                            <div class="col-md-2">
                                <label class="col-form-label">Amount</label>
                                <input type="text" class="form-control text-right" name="amount" id="amount" value="" readonly title="Enter the Amount">
                            </div>
                            <div class="col-lg-1" style="padding-top: 24px;">
                                <button class="btn btn-icon btn-success btn_add" style="width:35px; height:35px; padding:0px 8px; border-radius:0.12rem;" type="button" name="btn_add" id="btn_add"><i class="fa fa-plus-circle"></i></button>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-12">
                            <table class="table table-border table-hover table-sm" id="ParticularsTable">
                                <thead>
                                    <tr>
                                        <th class="text-center" style="padding: 0.5rem;" width="25%">Part / Drg No.</th>
                                        <th class="text-center" style="padding: 0.5rem;">Name of the Particulars</th>
                                        <th class="text-center" style="padding: 0.5rem;" width="6%">HSN / SAC</th>
                                        <th class="text-center" style="padding: 0.5rem;" width="6%">UOM</th>
                                        <th class="text-center" style="padding: 0.5rem;" width="6%">PCP</th>
                                        <th class="text-center" style="padding: 0.5rem;" width="6%">PCP Nos</th>
                                        <th class="text-center" style="padding: 0.5rem;" width="6%">Qty</th>
                                        <th class="text-center" style="padding: 0.5rem;" width="6%">Rate</th>
                                        <th class="text-center" style="padding: 0.5rem;" width="8%">Amount</th>
                                        <th class="text-center" style="padding: 0.5rem;" width="3%">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if (isset($_REQUEST["id"])) {
                                        $i = 1;
                                        $res = $con->query("SELECT * FROM tbl_sales_details WHERE sales_id = ". $id ." ORDER BY id");
                                        if ($res->rowCount()) {
                                            while ($row = $res->fetch(PDO::FETCH_OBJ)) {
                                                echo '<tr id="tr_' . $i .'">
                                                    <td style="padding: 0.3rem;">'. $dbcon->GetOneRecord("tbl_cust_material_details", "part_drg_no", "id", $row->cm_details_id) .'<input type="hidden" name="hid_part_drg_no[]" id="hid_part_drg_no_'. $i .'" value="'. $row->cm_details_id .'"></td>
                                                    <td style="padding: 0.3rem;">'. $dbcon->GetOneRecord("tbl_cust_material_details", "particulars", "id", $row->cm_details_id) .'<input type="hidden" name="hid_particulars[]" id="hid_particulars_'. $i .'" value="'. $row->cm_details_id .'"></td>
                                                    <td class="text-center" style="padding: 0.3rem;">'. $dbcon->GetOneRecord("tbl_cust_material_details", "hsn_sac", "id", $row->cm_details_id) .'<input type="hidden" name="hid_hsn_sac[]" id="hid_hsn_sac_'. $i .'" value="'. $row->cm_details_id .'"></td>
                                                    <td class="text-center" style="padding: 0.3rem;">'. $dbcon->GetOneRecord("tbl_uom", "uom", "id = ". $row->uom_id ." and del_status", 0) .'<input type="hidden" name="hid_uom_id[]" id="hid_uom_id_'. $i .'" value="'. $row->uom_id .'"></td>
                                                    <td class="text-center" style="padding: 0.3rem;">'. $dbcon->GetOneRecord("tbl_pcp", "pcp", "id = ". $row->pcp_id ." and del_status", 0) .'<input type="hidden" name="hid_pcp_id[]" id="hid_pcp_id_'. $i .'" value="'. $row->pcp_id .'"></td>
                                                    <td class="text-right" style="padding: 0.3rem;">'. $row->pcp_nos .'<input type="hidden" name="hid_pcp_nos[]" id="hid_pcp_nos_'. $i .'" value="'. $row->pcp_nos .'"></td>
                                                    <td class="text-right" style="padding: 0.3rem;">'. $row->qty .'<input type="hidden" name="hid_qty[]" id="hid_qty_'. $i .'" value="'. $row->qty .'"></td>
                                                    <td class="text-right" style="padding: 0.3rem;">'. number_format($row->rate, 2, '.', '') .'<input type="hidden" name="hid_rate[]" id="hid_rate_'. $i .'" value="'. $row->rate .'"></td>
                                                    <td class="text-right" style="padding: 0.3rem;">'. number_format($row->amount, 2, '.', '') .'<input type="hidden" name="hid_amount[]" id="hid_amount_'. $i .'" class="hid_amount" value="'. $row->amount .'"></td>
                                                    <td class="text-center" style="padding: 0.3rem;"><a href="javascript:;" class="remove"><i class="fa fa-trash-o" title="Remove"></i></a></td>
                                                </tr>';
                                                $i++;
                                            }
                                        }
                                        else{
                                            echo '<tr class="tr_empty">
                                                <td colspan="10" align="center" style="padding: 0.3rem;"> --No Records Found-- </td>
                                            </tr>';   
                                        }
                                    } else {
                                        echo '<tr class="tr_empty">
                                            <td colspan="10" align="center" style="padding: 0.3rem;"> --No Records Found-- </td>
                                        </tr>';
                                    }
                                    ?>
                                </tbody>
                                <tfoot>
                                    <tr class="text-right">
                                        <td colspan="8" style="padding: 0.3rem; font-weight: 600;">Total</td>
                                        <td style="padding: 0.1rem; font-weight: 600;"><input type="text" name="total" id="total" value="<?php echo iif($obj->total == "", "0.00", $obj->total); ?>" class="text-right input_borderless" size="7"></td>
                                        <td></td>
                                    </tr>
                                    <tr class="text-right">
                                        <td colspan="8" style="padding: 0.3rem; font-weight: 600;">
                                            <input type="hidden" name="hid_sgst_amt" id="hid_sgst_amt" value="<?php echo $obj->sgst_amt; ?>">
                                            <input type="hidden" name="hid_cgst_amt" id="hid_cgst_amt" value="<?php echo $obj->cgst_amt; ?>">
                                            <input type="hidden" name="hid_igst_amt" id="hid_igst_amt" value="<?php echo $obj->igst_amt; ?>">
                                            <a href="" data-toggle="modal" data-target="#modal_add_gst" title="Add GST" class="btn btn-xs bg-purple" style="color: black;">Add GST<span class="err">*</span></a> &nbsp;&nbsp;
                                            Total GST
                                        </td>
                                        <td style="padding: 0.1rem; font-weight: 600;"><input type="text" name="total_gst_amt" id="total_gst_amt" value="<?php echo iif($obj->total_gst_amt == "", "0.00", $obj->total_gst_amt); ?>" class="text-right input_borderless" size="7"></td>
                                        <td></td>
                                    </tr>
                                    <tr class="text-right">
                                        <td colspan="8" style="padding: 0.3rem; font-weight: 600;">Rounded Amount</td>
                                        <td style="padding: 0.1rem; font-weight: 600;"><input type="text" name="round_amount" id="round_amount" value="<?php echo iif($obj->round_amount == "", "0.00", $obj->round_amount); ?>" class="text-right input_borderless" size="7"></td>
                                        <td></td>
                                    </tr>
                                    <tr class="text-right">
                                        <td colspan="8" style="padding: 0.3rem; font-weight: 600;">Total Amount</td>
                                        <td style="padding: 0.1rem; font-weight: 600;"><input type="text" name="total_amount" id="total_amount" value="<?php echo iif($obj->total_amount == "", "0.00", $obj->total_amount); ?>" class="text-right input_borderless" size="7"></td>
                                        <td></td>
                                    </tr>

                                </tfoot>
                            </table>
                        </div>
                    </div>

                </div>
                <div class="box-footer text-right">
                    <?php if (isset($_REQUEST["id"])) { ?>
                        <input type="hidden" value="<?php echo $id; ?>" name="hid_id">
                        <button type="submit" name="update" class="btn btn-primary"><i class="fa fa-check" aria-hidden="true"></i> Update</button>
                    <?php } else { ?>
                        <button type="submit" name="submit" class="btn btn-primary"><i class="fa fa-check" aria-hidden="true"></i> Submit</button>
                    <?php } ?>
                </div>
            </form>
        </div>
    </section>
</div>
<?php include("includes/footer.php"); ?>

<!-- modal -->
<div class="modal fade" id="modal_add_gst" tabindex="-1" role="dialog" aria-hidden="true">
    <!-- modal-dialog -->
    <div class="modal-dialog modal-md modal-dialog-centered modal-dialog-scrollable">
        <!-- modal-content -->
        <div class="modal-content">
            <form id="thisFrm" name="thisFrm" class="form-horizontal" action="" method="post">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                    <h4 class="modal-title" id="myModal_fs_Head"><b>Default Modal</b></h4>
                </div>
                <div class="modal-body" id="m_fs_details">
                    <div class="text-center">
                        <span class="spinner-border spinner-border-sm text-danger"></span> Loading - Add Details Properly...!
                    </div>
                </div>
                <div class="modal-footer" id="m_fs_button">
                    <button type="button" name="submit_gst" id="submit_gst" class="btn btn-primary" disabled>Submit</button>
                </div>
            </form>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->

<script type="text/javascript">
$(document).on('change', '#customer_id', function() {
    var customer_id = $(this).val();

    if (customer_id > 0) {
        $.ajax({
            type: "POST",
            url: "ajax/get_list.php",
            data: {
                mode: 'customerToVendor',
                id: customer_id,
            }
        }).done(function(msg) {
            //alert(msg);
            data_arr = msg.split("~");
            $('#vend_code').val(data_arr[0]);

            $('#particulars').empty(); // -------------- This is to clear select 2 options value
            $('#particulars').append(data_arr[1]).trigger('change');
            $("#particulars").select2("open");
        });
    }
});

$(document).on('change', '#particulars', function() {
    var cm_details_id = $(this).val();

    if (cm_details_id > 0) {
        $.ajax({
            type: "POST",
            url: "ajax/get_list.php",
            data: {
                mode: 'cm_details_particulars',
                id: cm_details_id,
            }
        }).done(function(msg) {
            //alert(msg);
            data_arr = msg.split("~");
            $('#uom_id').val(parseInt(data_arr[0])).trigger('change');
            $('#pcp_id').val(parseInt(data_arr[1])).trigger('change');
            $('#rate').val(data_arr[2]);

            $("#pcp_nos").focus();
        });
    }
});

$(document).on('keyup', '#qty', function() {
    var qty = parseFloat($(this).val());
    if (isNaN(qty)) qty = 0;
    var rate = parseFloat($('#rate').val());
    var amt = qty * rate;

    $('#amount').val(amt.toFixed(2));
});

//--------------btn_add--------------------
<?php if (isset($_REQUEST["id"])) { ?>
    var rowIdx = <?php echo $i; ?>;
<?php } else { ?>
    var rowIdx = 1;
<?php } ?>

$('#btn_add').click(function() {
    var particulars = parseInt($('#particulars').val());
    if (isNaN(particulars)) particulars = 0;
    if (particulars == 0) {
        swal({
            title: "Please select name of the particulars...!",
            //text: "Please select the course name..!",
            closeModal: false,
        }, function() {
            $('#particulars').select2('open');
        });
        return false;
    }

    var uom = parseInt($('#uom_id').val());
    if (isNaN(uom)) uom = 0;
    if (uom == 0) {
        swal({
            title: "Please select the uom...!",
            //text: "Please select the course name..!",
            closeModal: false,
        }, function() {
            $('#uom_id').select2('open');
        });
        return false;
    }

    var pcp = parseInt($('#pcp_id').val());
    if (isNaN(pcp)) pcp = 0;
    if (pcp == 0) {
        swal({
            title: "Please select the uom...!",
            //text: "Please select the course name..!",
            closeModal: false,
        }, function() {
            $('#pcp_id').select2('open');
        });
        return false;
    }

    var pcp_nos = $('#pcp_nos').val();
    if (pcp_nos == "") {
        swal({
            title: "Please enter the pcp nos...!",
            //text: "Please select the course name..!",
            closeModal: false,
        }, function() {
            $('#pcp_nos').focus();
        });
        return false;
    }

    var qty = $('#qty').val();
    if (qty == "") {
        swal({
            title: "Please enter the qty...!",
            //text: "Please select the course name..!",
            closeModal: false,
        }, function() {
            $('#qty').focus();
        });
        return false;
    }

    var rate = $('#rate').val();
    var amount = $('#amount').val();

    $.ajax({
        type: "POST",
        url: "ajax/get_list.php",
        data: {
            mode: 'sales_particulars',
            id: rowIdx + '~' + particulars + '~' + uom + '~' + pcp + '~' + pcp_nos + '~' + qty + '~' + rate + '~' + amount
        }
    }).done(function(msg) {
        //alert(msg);
        $('.tr_empty').remove();
        $('#ParticularsTable tbody').append(msg);

        $('#particulars').val(null).trigger('change');
        $('#uom_id').val(null).trigger('change');
        $('#pcp_id').val(null).trigger('change');
        $('#pcp_nos').val('');
        $('#qty').val('');
        $('#rate').val('');
        $('#amount').val('');
        $('#particulars').select2('open');
        rowIdx++;

        calc();

        $('#total_gst_amt').val('0.00');
        $('#round_amount').val('0.00');
        $('#total_amount').val('0.00');
    });
});
//--------------btn_add--------------------

$('#ParticularsTable tbody').on("click", '.remove', function() {
    $(this).closest("tr").remove();

    calc();
    
    $('#total_gst_amt').val('0.00');
    $('#round_amount').val('0.00');
    $('#total_amount').val('0.00');
});

$('#modal_add_gst').on('show.bs.modal', function (e){
    //var id = $(e.relatedTarget).data('id');
    var id = parseFloat($('#total').val());
    // alert(id);
    if(id != 0)
    {
        $.ajax({
            type : 'post',
            url : 'ajax/get_list.php',
            data: {
                mode: 'modal_add_gst',
                id: id,
            },
            success : function(data){				
                //Show fetched data from database
                data_arr = data.split("~");
                //alert(data_arr[2]);
                $('#myModal_fs_Head').html(data_arr[0]);
                $('#m_fs_details').html(data_arr[1]);
                //$('#m_fs_button').html(data_arr[2]);
                $('#submit_gst').prop('disabled', false);
            }
        });
    }
});

$(document).on('change', '#sgst_id', function() {
    var gst_id = $(this).val();
    var tot_amt = parseFloat($('#total_amt').val());

    if(gst_id != '')
    {
        $.ajax({
            type : 'post',
            url : 'ajax/get_list.php',
            data: {
                mode: 'calculate_sgst',
                id: gst_id +'~'+ tot_amt,
            }
        }).done(function(msg) {
            //alert(msg);
            $('#sgst_amt').val(parseFloat(msg).toFixed(2));
            $('#hid_sgst_amt').val(parseFloat(msg).toFixed(2));
            calc_total_gst();
        });
    }
});

$(document).on('change', '#cgst_id', function() {
    var gst_id = $(this).val();
    var tot_amt = parseFloat($('#total_amt').val());

    if(gst_id != '')
    {
        $.ajax({
            type : 'post',
            url : 'ajax/get_list.php',
            data: {
                mode: 'calculate_cgst',
                id: gst_id +'~'+ tot_amt,
            }
        }).done(function(msg) {
            //alert(msg);
            $('#cgst_amt').val(parseFloat(msg).toFixed(2));
            $('#hid_cgst_amt').val(parseFloat(msg).toFixed(2));
            calc_total_gst();
        });
    }
});

$(document).on('change', '#igst_id', function() {
    var gst_id = $(this).val();
    var tot_amt = parseFloat($('#total_amt').val());

    if(gst_id != '')
    {
        $.ajax({
            type : 'post',
            url : 'ajax/get_list.php',
            data: {
                mode: 'calculate_igst',
                id: gst_id +'~'+ tot_amt,
            }
        }).done(function(msg) {
            //alert(msg);
            $('#igst_amt').val(parseFloat(msg).toFixed(2));
            $('#hid_igst_amt').val(parseFloat(msg).toFixed(2));
            calc_total_gst();
        });
    }
});

function calc_total_gst(){
    var sgst_amt = parseFloat($('#sgst_amt').val());
    if (isNaN(sgst_amt)) sgst_amt = 0;
    var cgst_amt = parseFloat($('#cgst_amt').val());    
    if (isNaN(cgst_amt)) cgst_amt = 0;
    var igst_amt = parseFloat($('#igst_amt').val());
    if (isNaN(igst_amt)) igst_amt = 0;

    var total_gst = sgst_amt + cgst_amt + igst_amt;
    $('#total_gst').val(parseFloat(total_gst).toFixed(2));
}

function calc(){
    var total = 0;
    $(".hid_amount").each(function(){
        var hid_amount = $(this).val();
        if ($.isNumeric(hid_amount)) {
            total += parseFloat(hid_amount);
        }
    });
    if(isNaN(total)) total = 0;

    $("#total").val(total.toFixed(2));
}

$(document).on('click', '#submit_gst', function() {

    if($('#sgst_id').val() == "" || $('#cgst_id').val() == ""){
        if($('#sgst_id').val() == ""){ 
            swal({
                title: "Please select the sgst..!",
                //text: "Please select the course name..!",
                closeModal: false,
            },
            function(){
                $('#sgst_id').select2('open');
            });
            return false; 
        }

        if($('#cgst_id').val() == ""){ 
            swal({
                title: "Please select the cgst..!",
                //text: "Please select the course name..!",
                closeModal: false,
            },
            function(){
                $('#cgst_id').select2('open');
            });
            return false; 
        }
    }

    var total = $('#total').val();
    var total_gst = $('#total_gst').val();

    $('#total_gst_amt').val(total_gst);
    
    var final_amount = parseFloat(total) + parseFloat(total_gst);
    var total_amount = Math.round(final_amount.toFixed(2));
    var round_amount = parseFloat(total_amount) - parseFloat(final_amount);
    
    $('#round_amount').val(round_amount.toFixed(2));
    $('#total_amount').val(total_amount.toFixed(2));

    $('#modal_add_gst').modal('hide');
});

//------------------ This is for Select2 in modal popup --------------------
$('body').on('shown.bs.modal', '.modal', function() {
    $(this).find('select').each(function() {
        var dropdownParent = $(document.body);
        if ($(this).parents('.modal.in:first').length !== 0)
            dropdownParent = $(this).parents('.modal.in:first');
        $(this).select2({
            placeholder: 'Select the Options',
            allowClear: true,
            dropdownParent: dropdownParent
        });
    });
});
//------------------ This is for Select2 in modal popup --------------------

function fnValid(){
    if(document.thisForm.total_amount.value == "0.00"){ 
        swal({
            title: "Please add the particulars properly...!",
            //text: "Please select the course name..!",
            closeModal: false,
        },
        function(){
            $('#particulars').select2('open');
        });
        return false; 
    }
    
}
</script>

<script type="text/javascript">
    $(document).ready(function() {
        $('#transMainNav').addClass('active');
        $('#transSalesSubNav').addClass('active');
    });
</script>