<?php
ob_start();
session_start();

require_once("includes/common/connection.php");
require_once("includes/common/dbfunctions.php");
require_once("includes/common/functions.php");

$con = new connection();
$dbcon = new dbfunctions();
$converter = new Encryption;

ini_set('display_errors', '1'); ini_set('display_startup_errors', '1'); error_reporting(E_ALL);

isAdmin();

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

<link rel="stylesheet" href="assets/dist/my_js_css/invoice.css">

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
                <h3 class="box-title">Invoice</h3>
                <div class="box-tools pull-right">
                    <a href="sales_lst.php" class="btn btn-block btn-primary"><i class="fa fa-bars" aria-hidden="true"></i></a>
                </div>
            </div>
            <br />
            <form id="thisForm" name="thisForm" action="sales.php" method="post" onsubmit="return fnValid();">
                <div class="box-body">
                    <div class="pull-right">
                        <!-- <button type="button" class="btn btn-icon btn-primary btn-sm btn_excel"><i class="fa fa-file-excel-o"></i>&nbsp; Excel</button>&nbsp;&nbsp; -->
                        <button type="button" class="btn btn-icon btn-primary btn-sm" onclick="createPDF()"><i class="fa fa-file-pdf-o"></i>&nbsp;Generate PDF</button>
                        <!--&nbsp;&nbsp;
                        <button type="button" class="btn btn-icon btn-primary btn-sm btn_print"><i class="fa fa-print"></i>&nbsp; Print</button>-->
                    </div>
                    <br><br>
                    <?php
                        $rcnt = $dbcon->GetCount("tbl_sales_details", "sales_id", $id);
                        if($rcnt <= 9){  //------------------------- Less Then 9 records means -------------------------
                    ?>
                    <div class="main-page" id="print_content">  <!-- ---------------------------------------------- Page 1 Original ----------------------------------------------- -->
                        <div class="sub-page">
                            <div class="row">
                                <div class="col-sm-12 text-center">
                                    <table class="table table-sm table-bordered" style="margin-bottom: 0px;">
                                        <tbody>
                                            <tr>
                                                <td colspan="7">
                                                    <div class="row">
                                                        <div class="col-sm-3" style="padding-right: 0px;">
                                                            <b>
                                                                <div style="font-size: 12px;">
                                                                GSTIN.: 33ARGPJ1151Q1ZP<br>
                                                                Email: srividyaaplastics@gmail.com
                                                                </div>
                                                            </b>
                                                        </div>
                                                        <div class="col-sm-6 text-center" style="padding: 0px;">
                                                            <center><div style="border-radius: 5px; border: 2px solid #000000; padding: 1px; width: 190px;"><b>TAX INVOICE</b></div></center>
                                                            <div style="font-size: 28px; color: #c43318;"><b>SRI VIDYAA PLASTICS</b></div>
                                                            <div style="font-size: 12px;">AN ISO 9001 - 2008 CERTIFIED COMPANY<br>
                                                            <i>Mfrs: All Kinds of Precision Moulded Plastic Components</i><br></div>
                                                            <div style="font-size: 14px;">603, Thoppu thottam, Jothipuram, Coimbatore - 641 047.</div>
                                                        </div>
                                                        <div class="col-sm-3 text-right" style="padding-left: 0px;">
                                                            <div style="font-size: 12px;">Cell: 99942 63220</div>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="4">
                                                    <div style="line-height: 2;" >
                                                        <b>To M/s. :</b> <br>
                                                        <div style="text-transform: uppercase;">
                                                        <?php echo $dbcon->GetOneRecord("tbl_customer", "CONCAT(cust_id, ' - ', cust_name)", "id", $customer_id); ?> <br>
                                                        <?php echo $dbcon->GetOneRecord("tbl_customer", "CONCAT(addr_line1, ', ', addr_line2)", "id", $customer_id); ?> <br>
                                                        <?php echo $dbcon->GetOneRecord("tbl_customer", "CONCAT(city_dist, ', ', cstate, ' - ', pincode)", "id", $customer_id); ?> <br>
                                                        </div>
                                                        <b>Customer GSTIN. :</b> <?php echo $dbcon->GetOneRecord("tbl_customer", "gst_no", "id", $customer_id); ?>
                                                    </div>
                                                </td>
                                                <td colspan="3" width="34%">
                                                    <div style="line-height: 2;">
                                                        <b>Invoice No :</b> <?php echo $invoice_no; ?><input type="hidden" name="hid_invoice" id="hid_invoice" value="<?php echo $invoice_no; ?>"><br>
                                                        <b>Date :</b> <?php echo date('d-m-Y', strtotime($sales_date)); ?> <br>
                                                        <b>Vendor Code :</b> <?php echo $obj->vend_code; ?> <br>
                                                        <br>
                                                        <div class="pull-right"><b>Original Copy</b>&nbsp;&nbsp;</div>
                                                    </div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <table class="table table-sm table-borderless" style="margin-bottom: 0px;">
                                        <tbody style="text-transform: uppercase;">
                                            <tr>
                                                <td class="text-center" width="6%" style="border-top: none; border-right: 1px solid black; border-bottom: 1px solid black; border-left: 1px solid black;"><b>S.No</b></td>
                                                <td class="text-center" style="border-top: none; border-right: 1px solid black; border-bottom: 1px solid black; border-left: 1px solid black;"><b>Particulars</b></td>
                                                <td class="text-center" width="10%" style="border-top: none; border-right: 1px solid black; border-bottom: 1px solid black; border-left: 1px solid black;"><b>HSN/SAC</b></td>
                                                <td class="text-center" width="6%" style="border-top: none; border-right: 1px solid black; border-bottom: 1px solid black; border-left: 1px solid black;"><b>UOM</b></td>
                                                <td class="text-center" width="9%" style="border-top: none; border-right: 1px solid black; border-bottom: 1px solid black; border-left: 1px solid black;"><b>T.Qty</b></td>
                                                <td class="text-center" width="10%" style="border-top: none; border-right: 1px solid black; border-bottom: 1px solid black; border-left: 1px solid black;"><b>Rate<br>Rs. &nbsp;&nbsp;P.</b></td>
                                                <td class="text-center" width="15%" style="border-top: none; border-right: 1px solid black; border-bottom: 1px solid black; border-left: 1px solid black;"><b>Total Amount<br>Rs. &nbsp;&nbsp;P.</b></td>
                                            </tr>
                                            <?php
                                            if (isset($_REQUEST["id"])) {
                                                $i = 1;
                                                $res = $con->query("SELECT * FROM tbl_sales_details WHERE sales_id = ". $id ." ORDER BY id");
                                                if ($res->rowCount()) {
                                                    while ($row = $res->fetch(PDO::FETCH_OBJ)) {
                                                        echo '<tr>
                                                            <td class="text-center" style="border-top: none; border-right: 1px solid black; border-bottom: none; border-left: 1px solid black;">'. $i .'</td>
                                                            <td style="border-top: none; border-right: 1px solid black; border-bottom: none; border-left: 1px solid black;">'. $dbcon->GetOneRecord("tbl_cust_material_details", "particulars", "id", $row->cm_details_id) .'<br>'. $row->pcp_nos .' '. $dbcon->GetOneRecord("tbl_pcp", "pcp", "id = ". $row->pcp_id ." and del_status", 0) .'</td>
                                                            <td style="border-top: none; border-right: 1px solid black; border-bottom: none; border-left: 1px solid black;">'. $dbcon->GetOneRecord("tbl_cust_material_details", "hsn_sac", "id", $row->cm_details_id) .'</td>
                                                            <td style="border-top: none; border-right: 1px solid black; border-bottom: none; border-left: 1px solid black;">'. $dbcon->GetOneRecord("tbl_uom", "uom", "id = ". $row->uom_id ." and del_status", 0) .'</td>
                                                            <td class="text-right" style="border-top: none; border-right: 1px solid black; border-bottom: none; border-left: 1px solid black;">'. $row->qty .'</td>
                                                            <td class="text-right" style="border-top: none; border-right: 1px solid black; border-bottom: none; border-left: 1px solid black;">'. number_format($row->rate, 2, '.', '') .'</td>
                                                            <td class="text-right" style="border-top: none; border-right: 1px solid black; border-bottom: none; border-left: 1px solid black;">'. number_format($row->amount, 2, '.', '') .'</td>
                                                        </tr>';
                                                        $i++;
                                                    }
                                                }
                                            }
                                            //echo 18 - (($i-1) * 2);
                                            $n = 18 - (($i-1) * 2);     // 9 Rows Only. i.e., 9 x 2 = 18; 9 Particulars
                                            for($j = 1; $j <= $n; $j++){
                                                echo '<tr>
                                                <td class="text-center" style="border-top: none; border-right: 1px solid black; border-bottom: none; border-left: 1px solid black;">&nbsp;</td>
                                                <td style="border-top: none; border-right: 1px solid black; border-bottom: none; border-left: 1px solid black;">&nbsp;</td>
                                                <td style="border-top: none; border-right: 1px solid black; border-bottom: none; border-left: 1px solid black;">&nbsp;</td>
                                                <td style="border-top: none; border-right: 1px solid black; border-bottom: none; border-left: 1px solid black;">&nbsp;</td>
                                                <td class="text-right" style="border-top: none; border-right: 1px solid black; border-bottom: none; border-left: 1px solid black;">&nbsp;</td>
                                                <td class="text-right" style="border-top: none; border-right: 1px solid black; border-bottom: none; border-left: 1px solid black;">&nbsp;</td>
                                                <td class="text-right" style="border-top: none; border-right: 1px solid black; border-bottom: none; border-left: 1px solid black;">&nbsp;</td>
                                            </tr>';
                                            }
                                            ?>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td colspan="4" style="border-top: 1px solid black; border-right: 1px solid black; border-bottom: none; border-left: 1px solid black;"><b>Total Amount in Words Rs.: </b></td>
                                                <td colspan="2" style="border-top: 1px solid black; border-right: 1px solid black; border-bottom: 1px solid black; border-left: 1px solid black;"><b>TOTAL</b></td>
                                                <td align="right" style="border-top: 1px solid black; border-right: 1px solid black; border-bottom: 1px solid black; border-left: 1px solid black;"><b><?php echo $obj->total; ?></b></td>
                                            </tr>
                                            <tr>
                                                <td style="border-top: none; border-right: none; border-bottom: none; border-left: 1px solid black;">&nbsp;</td>
                                                <td rowspan="6" colspan="3" style="border-top: none; border-right: 1px solid black; border-bottom: 1px solid black; border-left: none;"><?php echo number_to_words($obj->total_amount); ?></td>
                                                <td colspan="2" style="border-top: 1px solid black; border-right: 1px solid black; border-bottom: 1px solid black; border-left: 1px solid black;"><b>SGST : 9 %</b></td>
                                                <td align="right" style="border-top: 1px solid black; border-right: 1px solid black; border-bottom: 1px solid black; border-left: 1px solid black;"><?php echo iif($obj->sgst_amt == "", "0.00", $obj->sgst_amt); ?></td>
                                            </tr>
                                            <tr>
                                                <td style="border-top: none; border-right: none; border-bottom: none; border-left: 1px solid black;">&nbsp;</td>
                                                <td colspan="2" style="border-top: 1px solid black; border-right: 1px solid black; border-bottom: 1px solid black; border-left: 1px solid black;"><b>CGST : 9 %</b></td>
                                                <td align="right" style="border-top: 1px solid black; border-right: 1px solid black; border-bottom: 1px solid black; border-left: 1px solid black;"><?php echo iif($obj->cgst_amt == "", "0.00", $obj->cgst_amt); ?></td>
                                            </tr>
                                            <tr>
                                                <td style="border-top: none; border-right: none; border-bottom: none; border-left: 1px solid black;">&nbsp;</td>
                                                <td colspan="2" style="border-top: 1px solid black; border-right: 1px solid black; border-bottom: 1px solid black; border-left: 1px solid black;"><b>IGST : 0 %</b></td>
                                                <td align="right" style="border-top: 1px solid black; border-right: 1px solid black; border-bottom: 1px solid black; border-left: 1px solid black;"><?php echo iif($obj->igst_amt == "", "0.00", $obj->igst_amt); ?></td>
                                            </tr>
                                            <tr>
                                                <td style="border-top: none; border-right: none; border-bottom: none; border-left: 1px solid black;">&nbsp;</td>
                                                <td colspan="2" style="border-top: 1px solid black; border-right: 1px solid black; border-bottom: 1px solid black; border-left: 1px solid black;"><b>TOTAL GST</b></td>
                                                <td align="right" style="border-top: 1px solid black; border-right: 1px solid black; border-bottom: 1px solid black; border-left: 1px solid black;"><b><?php echo iif($obj->total_gst_amt == "", "0.00", $obj->total_gst_amt); ?></b></td>
                                            </tr>
                                            <tr>
                                                <td style="border-top: none; border-right: none; border-bottom: none; border-left: 1px solid black;">&nbsp;</td>
                                                <td colspan="2" style="border-top: 1px solid black; border-right: 1px solid black; border-bottom: 1px solid black; border-left: 1px solid black;"><b>ROUNDED AMOUNT</b></td>
                                                <td align="right" style="border-top: 1px solid black; border-right: 1px solid black; border-bottom: 1px solid black; border-left: 1px solid black;"><b><?php echo iif($obj->round_amount == "", "0.00", $obj->round_amount); ?></b></td>
                                            </tr>
                                            <tr>
                                                <td style="border-top: none; border-right: none; border-bottom: 1px solid black; border-left: 1px solid black;">&nbsp;</td>
                                                <td colspan="2" style="border-top: 1px solid black; border-right: 1px solid black; border-bottom: 1px solid black; border-left: 1px solid black;"><b>TOTAL AMOUNT</b></td>
                                                <td align="right" style="border-top: 1px solid black; border-right: 1px solid black; border-bottom: 1px solid black; border-left: 1px solid black;"><b><?php echo iif($obj->total_amount == "", "0.00", $obj->total_amount); ?></b></td>
                                            </tr>

                                            <tr>
                                                <td colspan="4" style="border-top: 1px solid black; border-right: 1px solid black; border-bottom: none; border-left: 1px solid black;"><b>Declaration: </b></td>
                                                <td align="right" colspan="3" style="border-top: 1px solid black; border-right: 1px solid black; border-bottom: none; border-left: 1px solid black;">For <b>SRI VIDYAA PLASTICS</b></td>
                                            </tr>
                                            <tr>
                                                <td colspan="4" style="border-top: none; border-right: 1px solid black; border-bottom: none; border-left: 1px solid black;"><b>We declare that this Invoice shows the actual price of the goods described and that all <br>particulars are true and correct.</b></td>
                                                <td align="right" colspan="3" style="border-top: none; border-right: 1px solid black; border-bottom: none; border-left: 1px solid black;">&nbsp;</td>
                                            </tr>
                                            <tr>
                                                <td colspan="4" style="border-top: none; border-right: 1px solid black; border-bottom: none; border-left: 1px solid black;">&nbsp;</td>
                                                <td align="right" colspan="3" style="border-top: none; border-right: 1px solid black; border-bottom: none; border-left: 1px solid black;">&nbsp;</td>
                                            </tr>
                                            <tr>
                                                <td colspan="4" style="border-top: none; border-right: 1px solid black; border-bottom: 1px solid black; border-left: 1px solid black;"><small>Note: Subject to Coimbatore Judiction</small></td>
                                                <td align="right" colspan="3" style="border-top: none; border-right: 1px solid black; border-bottom: 1px solid black; border-left: 1px solid black;">Authorised Signatory</td>
                                            </tr>                                            
                                        </tfoot>
                                    </table>
                                    <!-- <table>
                                        <tr>
                                            <td style="border-top: none; border-right: 1px solid black; border-bottom: 1px solid black; border-left: 1px solid black;">as</td>
                                            <td style="border-top: none; border-right: 1px solid black; border-bottom: 1px solid black; border-left: 1px solid black;">as</td>
                                            <td style="border-top: none; border-right: 1px solid black; border-bottom: 1px solid black; border-left: 1px solid black;">as</td>
                                        </tr>
                                    </table> -->
                                </div>
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="main-page" id="print_content1">  <!-- ---------------------------------------------- Page 1 Duplicate Copy ----------------------------------------------- -->
                        <div class="sub-page">
                            <div class="row">
                                <div class="col-sm-12 text-center">
                                    <table class="table table-sm table-bordered" style="margin-bottom: 0px;">
                                        <tbody>
                                            <tr>
                                                <td colspan="7">
                                                    <div class="row">
                                                        <div class="col-sm-3" style="padding-right: 0px;">
                                                            <b>
                                                                <div style="font-size: 12px;">
                                                                GSTIN.: 33ARGPJ1151Q1ZP<br>
                                                                Email: srividyaaplastics@gmail.com
                                                                </div>
                                                            </b>
                                                        </div>
                                                        <div class="col-sm-6 text-center" style="padding: 0px;">
                                                            <center><div style="border-radius: 5px; border: 2px solid #000000; padding: 1px; width: 190px;"><b>TAX INVOICE</b></div></center>
                                                            <div style="font-size: 28px; color: #c43318;"><b>SRI VIDYAA PLASTICS</b></div>
                                                            <div style="font-size: 12px;">AN ISO 9001 - 2008 CERTIFIED COMPANY<br>
                                                            <i>Mfrs: All Kinds of Precision Moulded Plastic Components</i><br></div>
                                                            <div style="font-size: 14px;">603, Thoppu thottam, Jothipuram, Coimbatore - 641 047.</div>
                                                        </div>
                                                        <div class="col-sm-3 text-right" style="padding-left: 0px;">
                                                            <div style="font-size: 12px;">Cell: 99942 63220</div>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="4">
                                                    <div style="line-height: 2;" >
                                                        <b>To M/s. :</b> <br>
                                                        <div style="text-transform: uppercase;">
                                                        <?php echo $dbcon->GetOneRecord("tbl_customer", "CONCAT(cust_id, ' - ', cust_name)", "id", $customer_id); ?> <br>
                                                        <?php echo $dbcon->GetOneRecord("tbl_customer", "CONCAT(addr_line1, ', ', addr_line2)", "id", $customer_id); ?> <br>
                                                        <?php echo $dbcon->GetOneRecord("tbl_customer", "CONCAT(city_dist, ', ', cstate, ' - ', pincode)", "id", $customer_id); ?> <br>
                                                        </div>
                                                        <b>Customer GSTIN. :</b> <?php echo $dbcon->GetOneRecord("tbl_customer", "gst_no", "id", $customer_id); ?>
                                                    </div>
                                                </td>
                                                <td colspan="3" width="34%">
                                                    <div style="line-height: 2;">
                                                        <b>Invoice No :</b> <?php echo $invoice_no; ?><br>
                                                        <b>Date :</b> <?php echo date('d-m-Y', strtotime($sales_date)); ?> <br>
                                                        <b>Vendor Code :</b> <?php echo $obj->vend_code; ?> <br>
                                                        <br>
                                                        <div class="pull-right"><b>Duplicate Copy</b>&nbsp;&nbsp;</div>
                                                    </div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <table class="table table-sm table-borderless" style="margin-bottom: 0px;">
                                        <tbody style="text-transform: uppercase;">
                                            <tr>
                                                <td class="text-center" width="6%" style="border-top: none; border-right: 1px solid black; border-bottom: 1px solid black; border-left: 1px solid black;"><b>S.No</b></td>
                                                <td class="text-center" style="border-top: none; border-right: 1px solid black; border-bottom: 1px solid black; border-left: 1px solid black;"><b>Particulars</b></td>
                                                <td class="text-center" width="10%" style="border-top: none; border-right: 1px solid black; border-bottom: 1px solid black; border-left: 1px solid black;"><b>HSN/SAC</b></td>
                                                <td class="text-center" width="6%" style="border-top: none; border-right: 1px solid black; border-bottom: 1px solid black; border-left: 1px solid black;"><b>UOM</b></td>
                                                <td class="text-center" width="9%" style="border-top: none; border-right: 1px solid black; border-bottom: 1px solid black; border-left: 1px solid black;"><b>T.Qty</b></td>
                                                <td class="text-center" width="10%" style="border-top: none; border-right: 1px solid black; border-bottom: 1px solid black; border-left: 1px solid black;"><b>Rate<br>Rs. &nbsp;&nbsp;P.</b></td>
                                                <td class="text-center" width="15%" style="border-top: none; border-right: 1px solid black; border-bottom: 1px solid black; border-left: 1px solid black;"><b>Total Amount<br>Rs. &nbsp;&nbsp;P.</b></td>
                                            </tr>
                                            <?php
                                            if (isset($_REQUEST["id"])) {
                                                $i = 1;
                                                $res = $con->query("SELECT * FROM tbl_sales_details WHERE sales_id = ". $id ." ORDER BY id");
                                                if ($res->rowCount()) {
                                                    while ($row = $res->fetch(PDO::FETCH_OBJ)) {
                                                        echo '<tr>
                                                            <td class="text-center" style="border-top: none; border-right: 1px solid black; border-bottom: none; border-left: 1px solid black;">'. $i .'</td>
                                                            <td style="border-top: none; border-right: 1px solid black; border-bottom: none; border-left: 1px solid black;">'. $dbcon->GetOneRecord("tbl_cust_material_details", "particulars", "id", $row->cm_details_id) .'<br>'. $row->pcp_nos .' '. $dbcon->GetOneRecord("tbl_pcp", "pcp", "id = ". $row->pcp_id ." and del_status", 0) .'</td>
                                                            <td style="border-top: none; border-right: 1px solid black; border-bottom: none; border-left: 1px solid black;">'. $dbcon->GetOneRecord("tbl_cust_material_details", "hsn_sac", "id", $row->cm_details_id) .'</td>
                                                            <td style="border-top: none; border-right: 1px solid black; border-bottom: none; border-left: 1px solid black;">'. $dbcon->GetOneRecord("tbl_uom", "uom", "id = ". $row->uom_id ." and del_status", 0) .'</td>
                                                            <td class="text-right" style="border-top: none; border-right: 1px solid black; border-bottom: none; border-left: 1px solid black;">'. $row->qty .'</td>
                                                            <td class="text-right" style="border-top: none; border-right: 1px solid black; border-bottom: none; border-left: 1px solid black;">'. number_format($row->rate, 2, '.', '') .'</td>
                                                            <td class="text-right" style="border-top: none; border-right: 1px solid black; border-bottom: none; border-left: 1px solid black;">'. number_format($row->amount, 2, '.', '') .'</td>
                                                        </tr>';
                                                        $i++;
                                                    }
                                                }
                                            }
                                            //echo 18 - (($i-1) * 2);
                                            $n = 18 - (($i-1) * 2);     // 9 Rows Only. i.e., 9 x 2 = 18; 9 Particulars
                                            for($j = 1; $j <= $n; $j++){
                                                echo '<tr>
                                                <td class="text-center" style="border-top: none; border-right: 1px solid black; border-bottom: none; border-left: 1px solid black;">&nbsp;</td>
                                                <td style="border-top: none; border-right: 1px solid black; border-bottom: none; border-left: 1px solid black;">&nbsp;</td>
                                                <td style="border-top: none; border-right: 1px solid black; border-bottom: none; border-left: 1px solid black;">&nbsp;</td>
                                                <td style="border-top: none; border-right: 1px solid black; border-bottom: none; border-left: 1px solid black;">&nbsp;</td>
                                                <td class="text-right" style="border-top: none; border-right: 1px solid black; border-bottom: none; border-left: 1px solid black;">&nbsp;</td>
                                                <td class="text-right" style="border-top: none; border-right: 1px solid black; border-bottom: none; border-left: 1px solid black;">&nbsp;</td>
                                                <td class="text-right" style="border-top: none; border-right: 1px solid black; border-bottom: none; border-left: 1px solid black;">&nbsp;</td>
                                            </tr>';
                                            }
                                            ?>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td colspan="4" style="border-top: 1px solid black; border-right: 1px solid black; border-bottom: none; border-left: 1px solid black;"><b>Total Amount in Words Rs.: </b></td>
                                                <td colspan="2" style="border-top: 1px solid black; border-right: 1px solid black; border-bottom: 1px solid black; border-left: 1px solid black;"><b>TOTAL</b></td>
                                                <td align="right" style="border-top: 1px solid black; border-right: 1px solid black; border-bottom: 1px solid black; border-left: 1px solid black;"><b><?php echo $obj->total; ?></b></td>
                                            </tr>
                                            <tr>
                                                <td style="border-top: none; border-right: none; border-bottom: none; border-left: 1px solid black;">&nbsp;</td>
                                                <td rowspan="6" colspan="3" style="border-top: none; border-right: 1px solid black; border-bottom: 1px solid black; border-left: none;"><?php echo number_to_words($obj->total_amount); ?></td>
                                                <td colspan="2" style="border-top: 1px solid black; border-right: 1px solid black; border-bottom: 1px solid black; border-left: 1px solid black;"><b>SGST : 9 %</b></td>
                                                <td align="right" style="border-top: 1px solid black; border-right: 1px solid black; border-bottom: 1px solid black; border-left: 1px solid black;"><?php echo iif($obj->sgst_amt == "", "0.00", $obj->sgst_amt); ?></td>
                                            </tr>
                                            <tr>
                                                <td style="border-top: none; border-right: none; border-bottom: none; border-left: 1px solid black;">&nbsp;</td>
                                                <td colspan="2" style="border-top: 1px solid black; border-right: 1px solid black; border-bottom: 1px solid black; border-left: 1px solid black;"><b>CGST : 9 %</b></td>
                                                <td align="right" style="border-top: 1px solid black; border-right: 1px solid black; border-bottom: 1px solid black; border-left: 1px solid black;"><?php echo iif($obj->cgst_amt == "", "0.00", $obj->cgst_amt); ?></td>
                                            </tr>
                                            <tr>
                                                <td style="border-top: none; border-right: none; border-bottom: none; border-left: 1px solid black;">&nbsp;</td>
                                                <td colspan="2" style="border-top: 1px solid black; border-right: 1px solid black; border-bottom: 1px solid black; border-left: 1px solid black;"><b>IGST : 0 %</b></td>
                                                <td align="right" style="border-top: 1px solid black; border-right: 1px solid black; border-bottom: 1px solid black; border-left: 1px solid black;"><?php echo iif($obj->igst_amt == "", "0.00", $obj->igst_amt); ?></td>
                                            </tr>
                                            <tr>
                                                <td style="border-top: none; border-right: none; border-bottom: none; border-left: 1px solid black;">&nbsp;</td>
                                                <td colspan="2" style="border-top: 1px solid black; border-right: 1px solid black; border-bottom: 1px solid black; border-left: 1px solid black;"><b>TOTAL GST</b></td>
                                                <td align="right" style="border-top: 1px solid black; border-right: 1px solid black; border-bottom: 1px solid black; border-left: 1px solid black;"><b><?php echo iif($obj->total_gst_amt == "", "0.00", $obj->total_gst_amt); ?></b></td>
                                            </tr>
                                            <tr>
                                                <td style="border-top: none; border-right: none; border-bottom: none; border-left: 1px solid black;">&nbsp;</td>
                                                <td colspan="2" style="border-top: 1px solid black; border-right: 1px solid black; border-bottom: 1px solid black; border-left: 1px solid black;"><b>ROUNDED AMOUNT</b></td>
                                                <td align="right" style="border-top: 1px solid black; border-right: 1px solid black; border-bottom: 1px solid black; border-left: 1px solid black;"><b><?php echo iif($obj->round_amount == "", "0.00", $obj->round_amount); ?></b></td>
                                            </tr>
                                            <tr>
                                                <td style="border-top: none; border-right: none; border-bottom: 1px solid black; border-left: 1px solid black;">&nbsp;</td>
                                                <td colspan="2" style="border-top: 1px solid black; border-right: 1px solid black; border-bottom: 1px solid black; border-left: 1px solid black;"><b>TOTAL AMOUNT</b></td>
                                                <td align="right" style="border-top: 1px solid black; border-right: 1px solid black; border-bottom: 1px solid black; border-left: 1px solid black;"><b><?php echo iif($obj->total_amount == "", "0.00", $obj->total_amount); ?></b></td>
                                            </tr>

                                            <tr>
                                                <td colspan="4" style="border-top: 1px solid black; border-right: 1px solid black; border-bottom: none; border-left: 1px solid black;"><b>Declaration: </b></td>
                                                <td align="right" colspan="3" style="border-top: 1px solid black; border-right: 1px solid black; border-bottom: none; border-left: 1px solid black;">For <b>SRI VIDYAA PLASTICS</b></td>
                                            </tr>
                                            <tr>
                                                <td colspan="4" style="border-top: none; border-right: 1px solid black; border-bottom: none; border-left: 1px solid black;"><b>We declare that this Invoice shows the actual price of the goods described and that all <br>particulars are true and correct.</b></td>
                                                <td align="right" colspan="3" style="border-top: none; border-right: 1px solid black; border-bottom: none; border-left: 1px solid black;">&nbsp;</td>
                                            </tr>
                                            <tr>
                                                <td colspan="4" style="border-top: none; border-right: 1px solid black; border-bottom: none; border-left: 1px solid black;">&nbsp;</td>
                                                <td align="right" colspan="3" style="border-top: none; border-right: 1px solid black; border-bottom: none; border-left: 1px solid black;">&nbsp;</td>
                                            </tr>
                                            <tr>
                                                <td colspan="4" style="border-top: none; border-right: 1px solid black; border-bottom: 1px solid black; border-left: 1px solid black;"><small>Note: Subject to Coimbatore Judiction</small></td>
                                                <td align="right" colspan="3" style="border-top: none; border-right: 1px solid black; border-bottom: 1px solid black; border-left: 1px solid black;">Authorised Signatory</td>
                                            </tr>                                            
                                        </tfoot>
                                    </table>
                                    <!-- <table>
                                        <tr>
                                            <td style="border-top: none; border-right: 1px solid black; border-bottom: 1px solid black; border-left: 1px solid black;">as</td>
                                            <td style="border-top: none; border-right: 1px solid black; border-bottom: 1px solid black; border-left: 1px solid black;">as</td>
                                            <td style="border-top: none; border-right: 1px solid black; border-bottom: 1px solid black; border-left: 1px solid black;">as</td>
                                        </tr>
                                    </table> -->
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php
                        }
                        elseif($rcnt > 9 && $rcnt <= 15){   //--------------- More then 9 and less then 15 particular details means ----------------------------------------
                    ?>
                    <div class="main-page" id="print_content2">  <!-- ---------------------------------------------- Page 1 Original Copy ----------------------------------------------- -->
                        <div class="sub-page">
                            <div class="row">
                                <div class="col-sm-12 text-center">
                                    <table class="table table-sm table-bordered" style="margin-bottom: 0px;">
                                        <tbody>
                                            <tr>
                                                <td colspan="7">
                                                    <div class="row">
                                                        <div class="col-sm-3" style="padding-right: 0px;">
                                                            <b>
                                                                <div style="font-size: 12px;">
                                                                GSTIN.: 33ARGPJ1151Q1ZP<br>
                                                                Email: srividyaaplastics@gmail.com
                                                                </div>
                                                            </b>
                                                        </div>
                                                        <div class="col-sm-6 text-center" style="padding: 0px;">
                                                            <center><div style="border-radius: 5px; border: 2px solid #000000; padding: 1px; width: 190px;"><b>TAX INVOICE</b></div></center>
                                                            <div style="font-size: 28px; color: #c43318;"><b>SRI VIDYAA PLASTICS</b></div>
                                                            <div style="font-size: 12px;">AN ISO 9001 - 2008 CERTIFIED COMPANY<br>
                                                            <i>Mfrs: All Kinds of Precision Moulded Plastic Components</i><br></div>
                                                            <div style="font-size: 14px;">603, Thoppu thottam, Jothipuram, Coimbatore - 641 047.</div>
                                                        </div>
                                                        <div class="col-sm-3 text-right" style="padding-left: 0px;">
                                                            <div style="font-size: 12px;">Cell: 99942 63220</div>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="4">
                                                    <div style="line-height: 2;" >
                                                        <b>To M/s. :</b> <br>
                                                        <div style="text-transform: uppercase;">
                                                        <?php echo $dbcon->GetOneRecord("tbl_customer", "CONCAT(cust_id, ' - ', cust_name)", "id", $customer_id); ?> <br>
                                                        <?php echo $dbcon->GetOneRecord("tbl_customer", "CONCAT(addr_line1, ', ', addr_line2)", "id", $customer_id); ?> <br>
                                                        <?php echo $dbcon->GetOneRecord("tbl_customer", "CONCAT(city_dist, ', ', cstate, ' - ', pincode)", "id", $customer_id); ?> <br>
                                                        </div>
                                                        <b>Customer GSTIN. :</b> <?php echo $dbcon->GetOneRecord("tbl_customer", "gst_no", "id", $customer_id); ?>
                                                    </div>
                                                </td>
                                                <td colspan="3" width="34%">
                                                    <div style="line-height: 2;">
                                                        <b>Invoice No :</b> <?php echo $invoice_no; ?><input type="hidden" name="hid_invoice" id="hid_invoice" value="<?php echo $invoice_no; ?>"><br>
                                                        <b>Date :</b> <?php echo date('d-m-Y', strtotime($sales_date)); ?> <br>
                                                        <b>Vendor Code :</b> <?php echo $obj->vend_code; ?> <br>
                                                        <br>
                                                        <div class="pull-right"><b>Original Copy</b>&nbsp;&nbsp;</div>
                                                    </div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <table class="table table-sm table-borderless" style="margin-bottom: 0px;">
                                        <tbody style="text-transform: uppercase;">
                                            <tr>
                                                <td class="text-center" width="6%" style="border-top: none; border-right: 1px solid black; border-bottom: 1px solid black; border-left: 1px solid black;"><b>S.No</b></td>
                                                <td class="text-center" style="border-top: none; border-right: 1px solid black; border-bottom: 1px solid black; border-left: 1px solid black;"><b>Particulars</b></td>
                                                <td class="text-center" width="10%" style="border-top: none; border-right: 1px solid black; border-bottom: 1px solid black; border-left: 1px solid black;"><b>HSN/SAC</b></td>
                                                <td class="text-center" width="6%" style="border-top: none; border-right: 1px solid black; border-bottom: 1px solid black; border-left: 1px solid black;"><b>UOM</b></td>
                                                <td class="text-center" width="9%" style="border-top: none; border-right: 1px solid black; border-bottom: 1px solid black; border-left: 1px solid black;"><b>T.Qty</b></td>
                                                <td class="text-center" width="10%" style="border-top: none; border-right: 1px solid black; border-bottom: 1px solid black; border-left: 1px solid black;"><b>Rate<br>Rs. &nbsp;&nbsp;P.</b></td>
                                                <td class="text-center" width="15%" style="border-top: none; border-right: 1px solid black; border-bottom: 1px solid black; border-left: 1px solid black;"><b>Total Amount<br>Rs. &nbsp;&nbsp;P.</b></td>
                                            </tr>
                                            <?php
                                            if (isset($_REQUEST["id"])) {
                                                $i = 1;
                                                $res = $con->query("SELECT * FROM tbl_sales_details WHERE sales_id = ". $id ." ORDER BY id LIMIT 15");
                                                if ($res->rowCount()) {
                                                    while ($row = $res->fetch(PDO::FETCH_OBJ)) {
                                                        echo '<tr>
                                                            <td class="text-center" style="border-top: none; border-right: 1px solid black; border-bottom: none; border-left: 1px solid black;">'. $i .'</td>
                                                            <td style="border-top: none; border-right: 1px solid black; border-bottom: none; border-left: 1px solid black;">'. $dbcon->GetOneRecord("tbl_cust_material_details", "particulars", "id", $row->cm_details_id) .'<br>'. $row->pcp_nos .' '. $dbcon->GetOneRecord("tbl_pcp", "pcp", "id = ". $row->pcp_id ." and del_status", 0) .'</td>
                                                            <td style="border-top: none; border-right: 1px solid black; border-bottom: none; border-left: 1px solid black;">'. $dbcon->GetOneRecord("tbl_cust_material_details", "hsn_sac", "id", $row->cm_details_id) .'</td>
                                                            <td style="border-top: none; border-right: 1px solid black; border-bottom: none; border-left: 1px solid black;">'. $dbcon->GetOneRecord("tbl_uom", "uom", "id = ". $row->uom_id ." and del_status", 0) .'</td>
                                                            <td class="text-right" style="border-top: none; border-right: 1px solid black; border-bottom: none; border-left: 1px solid black;">'. $row->qty .'</td>
                                                            <td class="text-right" style="border-top: none; border-right: 1px solid black; border-bottom: none; border-left: 1px solid black;">'. number_format($row->rate, 2, '.', '') .'</td>
                                                            <td class="text-right" style="border-top: none; border-right: 1px solid black; border-bottom: none; border-left: 1px solid black;">'. number_format($row->amount, 2, '.', '') .'</td>
                                                        </tr>';
                                                        $i++;
                                                    }
                                                }
                                            }
                                            //echo 30 - (($i-1) * 2);
                                            $n = 30 - (($i-1) * 2);     // 15 Rows Only. i.e., 15 x 2 = 30; 15 Particulars
                                            for($j = 1; $j <= $n; $j++){
                                                echo '<tr>
                                                <td class="text-center" style="border-top: none; border-right: 1px solid black; border-bottom: none; border-left: 1px solid black;">&nbsp;</td>
                                                <td style="border-top: none; border-right: 1px solid black; border-bottom: none; border-left: 1px solid black;">&nbsp;</td>
                                                <td style="border-top: none; border-right: 1px solid black; border-bottom: none; border-left: 1px solid black;">&nbsp;</td>
                                                <td style="border-top: none; border-right: 1px solid black; border-bottom: none; border-left: 1px solid black;">&nbsp;</td>
                                                <td class="text-right" style="border-top: none; border-right: 1px solid black; border-bottom: none; border-left: 1px solid black;">&nbsp;</td>
                                                <td class="text-right" style="border-top: none; border-right: 1px solid black; border-bottom: none; border-left: 1px solid black;">&nbsp;</td>
                                                <td class="text-right" style="border-top: none; border-right: 1px solid black; border-bottom: none; border-left: 1px solid black;">&nbsp;</td>
                                            </tr>';
                                            }
                                            ?>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td colspan="7" style="border-top: 1px solid black; border-right: none; border-bottom: none; border-left: none;" class="text-right"><b>P.T.O...</b></td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="main-page" id="print_content3">  <!-- ---------------------------------------------- Page 2 Original Copy----------------------------------------------- -->
                        <div class="sub-page">
                            <div class="row">
                                <div class="col-sm-12 text-center">
                                    <table class="table table-sm table-borderless" style="margin-bottom: 0px;">
                                        <tbody style="text-transform: uppercase;">
                                            <tr>
                                                <td class="text-right" colspan="7" style="border-top: none; border-right: none; border-bottom: none; border-left: none;"><b>Page 2</b></td>
                                            </tr>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td colspan="4" style="border-top: 1px solid black; border-right: 1px solid black; border-bottom: none; border-left: 1px solid black;"><b>Total Amount in Words Rs.: </b></td>
                                                <td colspan="2" style="border-top: 1px solid black; border-right: 1px solid black; border-bottom: 1px solid black; border-left: 1px solid black;"><b>TOTAL</b></td>
                                                <td align="right" style="border-top: 1px solid black; border-right: 1px solid black; border-bottom: 1px solid black; border-left: 1px solid black;"><b><?php echo $obj->total; ?></b></td>
                                            </tr>
                                            <tr>
                                                <td style="border-top: none; border-right: none; border-bottom: none; border-left: 1px solid black;">&nbsp;</td>
                                                <td rowspan="6" colspan="3" style="border-top: none; border-right: 1px solid black; border-bottom: 1px solid black; border-left: none;"><?php echo number_to_words($obj->total_amount); ?></td>
                                                <td colspan="2" style="border-top: 1px solid black; border-right: 1px solid black; border-bottom: 1px solid black; border-left: 1px solid black;"><b>SGST : 9 %</b></td>
                                                <td align="right" style="border-top: 1px solid black; border-right: 1px solid black; border-bottom: 1px solid black; border-left: 1px solid black;"><?php echo iif($obj->sgst_amt == "", "0.00", $obj->sgst_amt); ?></td>
                                            </tr>
                                            <tr>
                                                <td style="border-top: none; border-right: none; border-bottom: none; border-left: 1px solid black;">&nbsp;</td>
                                                <td colspan="2" style="border-top: 1px solid black; border-right: 1px solid black; border-bottom: 1px solid black; border-left: 1px solid black;"><b>CGST : 9 %</b></td>
                                                <td align="right" style="border-top: 1px solid black; border-right: 1px solid black; border-bottom: 1px solid black; border-left: 1px solid black;"><?php echo iif($obj->cgst_amt == "", "0.00", $obj->cgst_amt); ?></td>
                                            </tr>
                                            <tr>
                                                <td style="border-top: none; border-right: none; border-bottom: none; border-left: 1px solid black;">&nbsp;</td>
                                                <td colspan="2" style="border-top: 1px solid black; border-right: 1px solid black; border-bottom: 1px solid black; border-left: 1px solid black;"><b>IGST : 0 %</b></td>
                                                <td align="right" style="border-top: 1px solid black; border-right: 1px solid black; border-bottom: 1px solid black; border-left: 1px solid black;"><?php echo iif($obj->igst_amt == "", "0.00", $obj->igst_amt); ?></td>
                                            </tr>
                                            <tr>
                                                <td style="border-top: none; border-right: none; border-bottom: none; border-left: 1px solid black;">&nbsp;</td>
                                                <td colspan="2" style="border-top: 1px solid black; border-right: 1px solid black; border-bottom: 1px solid black; border-left: 1px solid black;"><b>TOTAL GST</b></td>
                                                <td align="right" style="border-top: 1px solid black; border-right: 1px solid black; border-bottom: 1px solid black; border-left: 1px solid black;"><b><?php echo iif($obj->total_gst_amt == "", "0.00", $obj->total_gst_amt); ?></b></td>
                                            </tr>
                                            <tr>
                                                <td style="border-top: none; border-right: none; border-bottom: none; border-left: 1px solid black;">&nbsp;</td>
                                                <td colspan="2" style="border-top: 1px solid black; border-right: 1px solid black; border-bottom: 1px solid black; border-left: 1px solid black;"><b>ROUNDED AMOUNT</b></td>
                                                <td align="right" style="border-top: 1px solid black; border-right: 1px solid black; border-bottom: 1px solid black; border-left: 1px solid black;"><b><?php echo iif($obj->round_amount == "", "0.00", $obj->round_amount); ?></b></td>
                                            </tr>
                                            <tr>
                                                <td style="border-top: none; border-right: none; border-bottom: 1px solid black; border-left: 1px solid black;">&nbsp;</td>
                                                <td colspan="2" style="border-top: 1px solid black; border-right: 1px solid black; border-bottom: 1px solid black; border-left: 1px solid black;"><b>TOTAL AMOUNT</b></td>
                                                <td align="right" style="border-top: 1px solid black; border-right: 1px solid black; border-bottom: 1px solid black; border-left: 1px solid black;"><b><?php echo iif($obj->total_amount == "", "0.00", $obj->total_amount); ?></b></td>
                                            </tr>

                                            <tr>
                                                <td colspan="4" style="border-top: 1px solid black; border-right: 1px solid black; border-bottom: none; border-left: 1px solid black;"><b>Declaration: </b></td>
                                                <td align="right" colspan="3" style="border-top: 1px solid black; border-right: 1px solid black; border-bottom: none; border-left: 1px solid black;">For <b>SRI VIDYAA PLASTICS</b></td>
                                            </tr>
                                            <tr>
                                                <td colspan="4" style="border-top: none; border-right: 1px solid black; border-bottom: none; border-left: 1px solid black;"><b>We declare that this Invoice shows the actual price of the goods described and that all <br>particulars are true and correct.</b></td>
                                                <td align="right" colspan="3" style="border-top: none; border-right: 1px solid black; border-bottom: none; border-left: 1px solid black;">&nbsp;</td>
                                            </tr>
                                            <tr>
                                                <td colspan="4" style="border-top: none; border-right: 1px solid black; border-bottom: none; border-left: 1px solid black;">&nbsp;</td>
                                                <td align="right" colspan="3" style="border-top: none; border-right: 1px solid black; border-bottom: none; border-left: 1px solid black;">&nbsp;</td>
                                            </tr>
                                            <tr>
                                                <td colspan="4" style="border-top: none; border-right: 1px solid black; border-bottom: 1px solid black; border-left: 1px solid black;"><small>Note: Subject to Coimbatore Judiction</small></td>
                                                <td align="right" colspan="3" style="border-top: none; border-right: 1px solid black; border-bottom: 1px solid black; border-left: 1px solid black;">Authorised Signatory</td>
                                            </tr>                                            
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="main-page" id="print_content4">  <!-- ---------------------------------------------- Page 1 Duplicate Copy ----------------------------------------------- -->
                        <div class="sub-page">
                            <div class="row">
                                <div class="col-sm-12 text-center">
                                    <table class="table table-sm table-bordered" style="margin-bottom: 0px;">
                                        <tbody>
                                            <tr>
                                                <td colspan="7">
                                                    <div class="row">
                                                        <div class="col-sm-3" style="padding-right: 0px;">
                                                            <b>
                                                                <div style="font-size: 12px;">
                                                                GSTIN.: 33ARGPJ1151Q1ZP<br>
                                                                Email: srividyaaplastics@gmail.com
                                                                </div>
                                                            </b>
                                                        </div>
                                                        <div class="col-sm-6 text-center" style="padding: 0px;">
                                                            <center><div style="border-radius: 5px; border: 2px solid #000000; padding: 1px; width: 190px;"><b>TAX INVOICE</b></div></center>
                                                            <div style="font-size: 28px; color: #c43318;"><b>SRI VIDYAA PLASTICS</b></div>
                                                            <div style="font-size: 12px;">AN ISO 9001 - 2008 CERTIFIED COMPANY<br>
                                                            <i>Mfrs: All Kinds of Precision Moulded Plastic Components</i><br></div>
                                                            <div style="font-size: 14px;">603, Thoppu thottam, Jothipuram, Coimbatore - 641 047.</div>
                                                        </div>
                                                        <div class="col-sm-3 text-right" style="padding-left: 0px;">
                                                            <div style="font-size: 12px;">Cell: 99942 63220</div>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="4">
                                                    <div style="line-height: 2;" >
                                                        <b>To M/s. :</b> <br>
                                                        <div style="text-transform: uppercase;">
                                                        <?php echo $dbcon->GetOneRecord("tbl_customer", "CONCAT(cust_id, ' - ', cust_name)", "id", $customer_id); ?> <br>
                                                        <?php echo $dbcon->GetOneRecord("tbl_customer", "CONCAT(addr_line1, ', ', addr_line2)", "id", $customer_id); ?> <br>
                                                        <?php echo $dbcon->GetOneRecord("tbl_customer", "CONCAT(city_dist, ', ', cstate, ' - ', pincode)", "id", $customer_id); ?> <br>
                                                        </div>
                                                        <b>Customer GSTIN. :</b> <?php echo $dbcon->GetOneRecord("tbl_customer", "gst_no", "id", $customer_id); ?>
                                                    </div>
                                                </td>
                                                <td colspan="3" width="34%">
                                                    <div style="line-height: 2;">
                                                        <b>Invoice No :</b> <?php echo $invoice_no; ?><br>
                                                        <b>Date :</b> <?php echo date('d-m-Y', strtotime($sales_date)); ?> <br>
                                                        <b>Vendor Code :</b> <?php echo $obj->vend_code; ?> <br>
                                                        <br>
                                                        <div class="pull-right"><b>Duplicate Copy</b>&nbsp;&nbsp;</div>
                                                    </div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <table class="table table-sm table-borderless" style="margin-bottom: 0px;">
                                        <tbody style="text-transform: uppercase;">
                                            <tr>
                                                <td class="text-center" width="6%" style="border-top: none; border-right: 1px solid black; border-bottom: 1px solid black; border-left: 1px solid black;"><b>S.No</b></td>
                                                <td class="text-center" style="border-top: none; border-right: 1px solid black; border-bottom: 1px solid black; border-left: 1px solid black;"><b>Particulars</b></td>
                                                <td class="text-center" width="10%" style="border-top: none; border-right: 1px solid black; border-bottom: 1px solid black; border-left: 1px solid black;"><b>HSN/SAC</b></td>
                                                <td class="text-center" width="6%" style="border-top: none; border-right: 1px solid black; border-bottom: 1px solid black; border-left: 1px solid black;"><b>UOM</b></td>
                                                <td class="text-center" width="9%" style="border-top: none; border-right: 1px solid black; border-bottom: 1px solid black; border-left: 1px solid black;"><b>T.Qty</b></td>
                                                <td class="text-center" width="10%" style="border-top: none; border-right: 1px solid black; border-bottom: 1px solid black; border-left: 1px solid black;"><b>Rate<br>Rs. &nbsp;&nbsp;P.</b></td>
                                                <td class="text-center" width="15%" style="border-top: none; border-right: 1px solid black; border-bottom: 1px solid black; border-left: 1px solid black;"><b>Total Amount<br>Rs. &nbsp;&nbsp;P.</b></td>
                                            </tr>
                                            <?php
                                            if (isset($_REQUEST["id"])) {
                                                $i = 1;
                                                $res = $con->query("SELECT * FROM tbl_sales_details WHERE sales_id = ". $id ." ORDER BY id LIMIT 15");
                                                if ($res->rowCount()) {
                                                    while ($row = $res->fetch(PDO::FETCH_OBJ)) {
                                                        echo '<tr>
                                                            <td class="text-center" style="border-top: none; border-right: 1px solid black; border-bottom: none; border-left: 1px solid black;">'. $i .'</td>
                                                            <td style="border-top: none; border-right: 1px solid black; border-bottom: none; border-left: 1px solid black;">'. $dbcon->GetOneRecord("tbl_cust_material_details", "particulars", "id", $row->cm_details_id) .'<br>'. $row->pcp_nos .' '. $dbcon->GetOneRecord("tbl_pcp", "pcp", "id = ". $row->pcp_id ." and del_status", 0) .'</td>
                                                            <td style="border-top: none; border-right: 1px solid black; border-bottom: none; border-left: 1px solid black;">'. $dbcon->GetOneRecord("tbl_cust_material_details", "hsn_sac", "id", $row->cm_details_id) .'</td>
                                                            <td style="border-top: none; border-right: 1px solid black; border-bottom: none; border-left: 1px solid black;">'. $dbcon->GetOneRecord("tbl_uom", "uom", "id = ". $row->uom_id ." and del_status", 0) .'</td>
                                                            <td class="text-right" style="border-top: none; border-right: 1px solid black; border-bottom: none; border-left: 1px solid black;">'. $row->qty .'</td>
                                                            <td class="text-right" style="border-top: none; border-right: 1px solid black; border-bottom: none; border-left: 1px solid black;">'. number_format($row->rate, 2, '.', '') .'</td>
                                                            <td class="text-right" style="border-top: none; border-right: 1px solid black; border-bottom: none; border-left: 1px solid black;">'. number_format($row->amount, 2, '.', '') .'</td>
                                                        </tr>';
                                                        $i++;
                                                    }
                                                }
                                            }
                                            //echo 30 - (($i-1) * 2);
                                            $n = 30 - (($i-1) * 2);     // 15 Rows Only. i.e., 15 x 2 = 30; 15 Particulars
                                            for($j = 1; $j <= $n; $j++){
                                                echo '<tr>
                                                <td class="text-center" style="border-top: none; border-right: 1px solid black; border-bottom: none; border-left: 1px solid black;">&nbsp;</td>
                                                <td style="border-top: none; border-right: 1px solid black; border-bottom: none; border-left: 1px solid black;">&nbsp;</td>
                                                <td style="border-top: none; border-right: 1px solid black; border-bottom: none; border-left: 1px solid black;">&nbsp;</td>
                                                <td style="border-top: none; border-right: 1px solid black; border-bottom: none; border-left: 1px solid black;">&nbsp;</td>
                                                <td class="text-right" style="border-top: none; border-right: 1px solid black; border-bottom: none; border-left: 1px solid black;">&nbsp;</td>
                                                <td class="text-right" style="border-top: none; border-right: 1px solid black; border-bottom: none; border-left: 1px solid black;">&nbsp;</td>
                                                <td class="text-right" style="border-top: none; border-right: 1px solid black; border-bottom: none; border-left: 1px solid black;">&nbsp;</td>
                                            </tr>';
                                            }
                                            ?>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td colspan="7" style="border-top: 1px solid black; border-right: none; border-bottom: none; border-left: none;" class="text-right"><b>P.T.O...</b></td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="main-page" id="print_content5">  <!-- ---------------------------------------------- Page 2 Duplicate Copy----------------------------------------------- -->
                        <div class="sub-page">
                            <div class="row">
                                <div class="col-sm-12 text-center">
                                    <table class="table table-sm table-borderless" style="margin-bottom: 0px;">
                                        <tbody style="text-transform: uppercase;">
                                            <tr>
                                                <td class="text-right" colspan="7" style="border-top: none; border-right: none; border-bottom: none; border-left: none;"><b>Page 2</b></td>
                                            </tr>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td colspan="4" style="border-top: 1px solid black; border-right: 1px solid black; border-bottom: none; border-left: 1px solid black;"><b>Total Amount in Words Rs.: </b></td>
                                                <td colspan="2" style="border-top: 1px solid black; border-right: 1px solid black; border-bottom: 1px solid black; border-left: 1px solid black;"><b>TOTAL</b></td>
                                                <td align="right" style="border-top: 1px solid black; border-right: 1px solid black; border-bottom: 1px solid black; border-left: 1px solid black;"><b><?php echo $obj->total; ?></b></td>
                                            </tr>
                                            <tr>
                                                <td style="border-top: none; border-right: none; border-bottom: none; border-left: 1px solid black;">&nbsp;</td>
                                                <td rowspan="6" colspan="3" style="border-top: none; border-right: 1px solid black; border-bottom: 1px solid black; border-left: none;"><?php echo number_to_words($obj->total_amount); ?></td>
                                                <td colspan="2" style="border-top: 1px solid black; border-right: 1px solid black; border-bottom: 1px solid black; border-left: 1px solid black;"><b>SGST : 9 %</b></td>
                                                <td align="right" style="border-top: 1px solid black; border-right: 1px solid black; border-bottom: 1px solid black; border-left: 1px solid black;"><?php echo iif($obj->sgst_amt == "", "0.00", $obj->sgst_amt); ?></td>
                                            </tr>
                                            <tr>
                                                <td style="border-top: none; border-right: none; border-bottom: none; border-left: 1px solid black;">&nbsp;</td>
                                                <td colspan="2" style="border-top: 1px solid black; border-right: 1px solid black; border-bottom: 1px solid black; border-left: 1px solid black;"><b>CGST : 9 %</b></td>
                                                <td align="right" style="border-top: 1px solid black; border-right: 1px solid black; border-bottom: 1px solid black; border-left: 1px solid black;"><?php echo iif($obj->cgst_amt == "", "0.00", $obj->cgst_amt); ?></td>
                                            </tr>
                                            <tr>
                                                <td style="border-top: none; border-right: none; border-bottom: none; border-left: 1px solid black;">&nbsp;</td>
                                                <td colspan="2" style="border-top: 1px solid black; border-right: 1px solid black; border-bottom: 1px solid black; border-left: 1px solid black;"><b>IGST : 0 %</b></td>
                                                <td align="right" style="border-top: 1px solid black; border-right: 1px solid black; border-bottom: 1px solid black; border-left: 1px solid black;"><?php echo iif($obj->igst_amt == "", "0.00", $obj->igst_amt); ?></td>
                                            </tr>
                                            <tr>
                                                <td style="border-top: none; border-right: none; border-bottom: none; border-left: 1px solid black;">&nbsp;</td>
                                                <td colspan="2" style="border-top: 1px solid black; border-right: 1px solid black; border-bottom: 1px solid black; border-left: 1px solid black;"><b>TOTAL GST</b></td>
                                                <td align="right" style="border-top: 1px solid black; border-right: 1px solid black; border-bottom: 1px solid black; border-left: 1px solid black;"><b><?php echo iif($obj->total_gst_amt == "", "0.00", $obj->total_gst_amt); ?></b></td>
                                            </tr>
                                            <tr>
                                                <td style="border-top: none; border-right: none; border-bottom: none; border-left: 1px solid black;">&nbsp;</td>
                                                <td colspan="2" style="border-top: 1px solid black; border-right: 1px solid black; border-bottom: 1px solid black; border-left: 1px solid black;"><b>ROUNDED AMOUNT</b></td>
                                                <td align="right" style="border-top: 1px solid black; border-right: 1px solid black; border-bottom: 1px solid black; border-left: 1px solid black;"><b><?php echo iif($obj->round_amount == "", "0.00", $obj->round_amount); ?></b></td>
                                            </tr>
                                            <tr>
                                                <td style="border-top: none; border-right: none; border-bottom: 1px solid black; border-left: 1px solid black;">&nbsp;</td>
                                                <td colspan="2" style="border-top: 1px solid black; border-right: 1px solid black; border-bottom: 1px solid black; border-left: 1px solid black;"><b>TOTAL AMOUNT</b></td>
                                                <td align="right" style="border-top: 1px solid black; border-right: 1px solid black; border-bottom: 1px solid black; border-left: 1px solid black;"><b><?php echo iif($obj->total_amount == "", "0.00", $obj->total_amount); ?></b></td>
                                            </tr>

                                            <tr>
                                                <td colspan="4" style="border-top: 1px solid black; border-right: 1px solid black; border-bottom: none; border-left: 1px solid black;"><b>Declaration: </b></td>
                                                <td align="right" colspan="3" style="border-top: 1px solid black; border-right: 1px solid black; border-bottom: none; border-left: 1px solid black;">For <b>SRI VIDYAA PLASTICS</b></td>
                                            </tr>
                                            <tr>
                                                <td colspan="4" style="border-top: none; border-right: 1px solid black; border-bottom: none; border-left: 1px solid black;"><b>We declare that this Invoice shows the actual price of the goods described and that all <br>particulars are true and correct.</b></td>
                                                <td align="right" colspan="3" style="border-top: none; border-right: 1px solid black; border-bottom: none; border-left: 1px solid black;">&nbsp;</td>
                                            </tr>
                                            <tr>
                                                <td colspan="4" style="border-top: none; border-right: 1px solid black; border-bottom: none; border-left: 1px solid black;">&nbsp;</td>
                                                <td align="right" colspan="3" style="border-top: none; border-right: 1px solid black; border-bottom: none; border-left: 1px solid black;">&nbsp;</td>
                                            </tr>
                                            <tr>
                                                <td colspan="4" style="border-top: none; border-right: 1px solid black; border-bottom: 1px solid black; border-left: 1px solid black;"><small>Note: Subject to Coimbatore Judiction</small></td>
                                                <td align="right" colspan="3" style="border-top: none; border-right: 1px solid black; border-bottom: 1px solid black; border-left: 1px solid black;">Authorised Signatory</td>
                                            </tr>                                            
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php
                        }
                        else{   //--------------- More then 15 particular details means ----------------------------------------
                    ?>
                    <div class="main-page" id="print_content6">  <!-- ---------------------------------------------- Page 1 Original Copy----------------------------------------------- -->
                        <div class="sub-page">
                            <div class="row">
                                <div class="col-sm-12 text-center">
                                    <table class="table table-sm table-bordered" style="margin-bottom: 0px;">
                                        <tbody>
                                            <tr>
                                                <td colspan="7">
                                                    <div class="row">
                                                        <div class="col-sm-3" style="padding-right: 0px;">
                                                            <b>
                                                                <div style="font-size: 12px;">
                                                                GSTIN.: 33ARGPJ1151Q1ZP<br>
                                                                Email: srividyaaplastics@gmail.com
                                                                </div>
                                                            </b>
                                                        </div>
                                                        <div class="col-sm-6 text-center" style="padding: 0px;">
                                                            <center><div style="border-radius: 5px; border: 2px solid #000000; padding: 1px; width: 190px;"><b>TAX INVOICE</b></div></center>
                                                            <div style="font-size: 28px; color: #c43318;"><b>SRI VIDYAA PLASTICS</b></div>
                                                            <div style="font-size: 12px;">AN ISO 9001 - 2008 CERTIFIED COMPANY<br>
                                                            <i>Mfrs: All Kinds of Precision Moulded Plastic Components</i><br></div>
                                                            <div style="font-size: 14px;">603, Thoppu thottam, Jothipuram, Coimbatore - 641 047.</div>
                                                        </div>
                                                        <div class="col-sm-3 text-right" style="padding-left: 0px;">
                                                            <div style="font-size: 12px;">Cell: 99942 63220</div>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="4">
                                                    <div style="line-height: 2;" >
                                                        <b>To M/s. :</b> <br>
                                                        <div style="text-transform: uppercase;">
                                                        <?php echo $dbcon->GetOneRecord("tbl_customer", "CONCAT(cust_id, ' - ', cust_name)", "id", $customer_id); ?> <br>
                                                        <?php echo $dbcon->GetOneRecord("tbl_customer", "CONCAT(addr_line1, ', ', addr_line2)", "id", $customer_id); ?> <br>
                                                        <?php echo $dbcon->GetOneRecord("tbl_customer", "CONCAT(city_dist, ', ', cstate, ' - ', pincode)", "id", $customer_id); ?> <br>
                                                        </div>
                                                        <b>Customer GSTIN. :</b> <?php echo $dbcon->GetOneRecord("tbl_customer", "gst_no", "id", $customer_id); ?>
                                                    </div>
                                                </td>
                                                <td colspan="3" width="34%">
                                                    <div style="line-height: 2;">
                                                        <b>Invoice No :</b> <?php echo $invoice_no; ?><input type="hidden" name="hid_invoice" id="hid_invoice" value="<?php echo $invoice_no; ?>"><br>
                                                        <b>Date :</b> <?php echo date('d-m-Y', strtotime($sales_date)); ?> <br>
                                                        <b>Vendor Code :</b> <?php echo $obj->vend_code; ?> <br>
                                                        <br>
                                                        <div class="pull-right"><b>Original Copy</b>&nbsp;&nbsp;</div>
                                                    </div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <table class="table table-sm table-borderless" style="margin-bottom: 0px;">
                                        <tbody style="text-transform: uppercase;">
                                            <tr>
                                                <td class="text-center" width="6%" style="border-top: none; border-right: 1px solid black; border-bottom: 1px solid black; border-left: 1px solid black;"><b>S.No</b></td>
                                                <td class="text-center" style="border-top: none; border-right: 1px solid black; border-bottom: 1px solid black; border-left: 1px solid black;"><b>Particulars</b></td>
                                                <td class="text-center" width="10%" style="border-top: none; border-right: 1px solid black; border-bottom: 1px solid black; border-left: 1px solid black;"><b>HSN/SAC</b></td>
                                                <td class="text-center" width="6%" style="border-top: none; border-right: 1px solid black; border-bottom: 1px solid black; border-left: 1px solid black;"><b>UOM</b></td>
                                                <td class="text-center" width="9%" style="border-top: none; border-right: 1px solid black; border-bottom: 1px solid black; border-left: 1px solid black;"><b>T.Qty</b></td>
                                                <td class="text-center" width="10%" style="border-top: none; border-right: 1px solid black; border-bottom: 1px solid black; border-left: 1px solid black;"><b>Rate<br>Rs. &nbsp;&nbsp;P.</b></td>
                                                <td class="text-center" width="15%" style="border-top: none; border-right: 1px solid black; border-bottom: 1px solid black; border-left: 1px solid black;"><b>Total Amount<br>Rs. &nbsp;&nbsp;P.</b></td>
                                            </tr>
                                            <?php
                                            if (isset($_REQUEST["id"])) {
                                                $i = 1;
                                                $res = $con->query("SELECT * FROM tbl_sales_details WHERE sales_id = ". $id ." ORDER BY id LIMIT 15");
                                                if ($res->rowCount()) {
                                                    while ($row = $res->fetch(PDO::FETCH_OBJ)) {
                                                        echo '<tr>
                                                            <td class="text-center" style="border-top: none; border-right: 1px solid black; border-bottom: none; border-left: 1px solid black;">'. $i .'</td>
                                                            <td style="border-top: none; border-right: 1px solid black; border-bottom: none; border-left: 1px solid black;">'. $dbcon->GetOneRecord("tbl_cust_material_details", "particulars", "id", $row->cm_details_id) .'<br>'. $row->pcp_nos .' '. $dbcon->GetOneRecord("tbl_pcp", "pcp", "id = ". $row->pcp_id ." and del_status", 0) .'</td>
                                                            <td style="border-top: none; border-right: 1px solid black; border-bottom: none; border-left: 1px solid black;">'. $dbcon->GetOneRecord("tbl_cust_material_details", "hsn_sac", "id", $row->cm_details_id) .'</td>
                                                            <td style="border-top: none; border-right: 1px solid black; border-bottom: none; border-left: 1px solid black;">'. $dbcon->GetOneRecord("tbl_uom", "uom", "id = ". $row->uom_id ." and del_status", 0) .'</td>
                                                            <td class="text-right" style="border-top: none; border-right: 1px solid black; border-bottom: none; border-left: 1px solid black;">'. $row->qty .'</td>
                                                            <td class="text-right" style="border-top: none; border-right: 1px solid black; border-bottom: none; border-left: 1px solid black;">'. number_format($row->rate, 2, '.', '') .'</td>
                                                            <td class="text-right" style="border-top: none; border-right: 1px solid black; border-bottom: none; border-left: 1px solid black;">'. number_format($row->amount, 2, '.', '') .'</td>
                                                        </tr>';
                                                        $i++;
                                                    }
                                                }
                                            }
                                            //echo 30 - (($i-1) * 2);
                                            $n = 30 - (($i-1) * 2);     // 15 Rows Only. i.e., 15 x 2 = 30; 15 Particulars
                                            for($j = 1; $j <= $n; $j++){
                                                echo '<tr>
                                                <td class="text-center" style="border-top: none; border-right: 1px solid black; border-bottom: none; border-left: 1px solid black;">&nbsp;</td>
                                                <td style="border-top: none; border-right: 1px solid black; border-bottom: none; border-left: 1px solid black;">&nbsp;</td>
                                                <td style="border-top: none; border-right: 1px solid black; border-bottom: none; border-left: 1px solid black;">&nbsp;</td>
                                                <td style="border-top: none; border-right: 1px solid black; border-bottom: none; border-left: 1px solid black;">&nbsp;</td>
                                                <td class="text-right" style="border-top: none; border-right: 1px solid black; border-bottom: none; border-left: 1px solid black;">&nbsp;</td>
                                                <td class="text-right" style="border-top: none; border-right: 1px solid black; border-bottom: none; border-left: 1px solid black;">&nbsp;</td>
                                                <td class="text-right" style="border-top: none; border-right: 1px solid black; border-bottom: none; border-left: 1px solid black;">&nbsp;</td>
                                            </tr>';
                                            }
                                            ?>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td colspan="7" style="border-top: 1px solid black; border-right: none; border-bottom: none; border-left: none;" class="text-right"><b>P.T.O...</b></td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="main-page" id="print_content7">  <!-- ---------------------------------------------- Page 2 Original Copy----------------------------------------------- -->
                        <div class="sub-page">
                            <div class="row">
                                <div class="col-sm-12 text-center">
                                    <table class="table table-sm table-borderless" style="margin-bottom: 0px;">
                                        <tbody style="text-transform: uppercase;">
                                            <tr>
                                                <td class="text-right" colspan="7" style="border-top: none; border-right: none; border-bottom: none; border-left: none;"><b>Page 2</b></td>
                                            </tr>
                                            <tr>
                                                <td class="text-center" width="6%" style="border-top: 1px solid black; border-right: 1px solid black; border-bottom: 1px solid black; border-left: 1px solid black;"><b>S.No</b></td>
                                                <td class="text-center" style="border-top: 1px solid black; border-right: 1px solid black; border-bottom: 1px solid black; border-left: 1px solid black;"><b>Particulars</b></td>
                                                <td class="text-center" width="10%" style="border-top: 1px solid black; border-right: 1px solid black; border-bottom: 1px solid black; border-left: 1px solid black;"><b>HSN/SAC</b></td>
                                                <td class="text-center" width="6%" style="border-top: 1px solid black; border-right: 1px solid black; border-bottom: 1px solid black; border-left: 1px solid black;"><b>UOM</b></td>
                                                <td class="text-center" width="9%" style="border-top: 1px solid black; border-right: 1px solid black; border-bottom: 1px solid black; border-left: 1px solid black;"><b>T.Qty</b></td>
                                                <td class="text-center" width="10%" style="border-top: 1px solid black; border-right: 1px solid black; border-bottom: 1px solid black; border-left: 1px solid black;"><b>Rate<br>Rs. &nbsp;&nbsp;P.</b></td>
                                                <td class="text-center" width="15%" style="border-top: 1px solid black; border-right: 1px solid black; border-bottom: 1px solid black; border-left: 1px solid black;"><b>Total Amount<br>Rs. &nbsp;&nbsp;P.</b></td>
                                            </tr>
                                            <?php
                                            if (isset($_REQUEST["id"])) {
                                                $i = 1;
                                                $p = 16;
                                                $res = $con->query("SELECT * FROM tbl_sales_details WHERE sales_id = ". $id ." ORDER BY id LIMIT 15, 29");
                                                if ($res->rowCount()) {
                                                    while ($row = $res->fetch(PDO::FETCH_OBJ)) {
                                                        echo '<tr>
                                                            <td class="text-center" style="border-top: none; border-right: 1px solid black; border-bottom: none; border-left: 1px solid black;">'. $p .'</td>
                                                            <td style="border-top: none; border-right: 1px solid black; border-bottom: none; border-left: 1px solid black;">'. $dbcon->GetOneRecord("tbl_cust_material_details", "particulars", "id", $row->cm_details_id) .'<br>'. $row->pcp_nos .' '. $dbcon->GetOneRecord("tbl_pcp", "pcp", "id = ". $row->pcp_id ." and del_status", 0) .'</td>
                                                            <td style="border-top: none; border-right: 1px solid black; border-bottom: none; border-left: 1px solid black;">'. $dbcon->GetOneRecord("tbl_cust_material_details", "hsn_sac", "id", $row->cm_details_id) .'</td>
                                                            <td style="border-top: none; border-right: 1px solid black; border-bottom: none; border-left: 1px solid black;">'. $dbcon->GetOneRecord("tbl_uom", "uom", "id = ". $row->uom_id ." and del_status", 0) .'</td>
                                                            <td class="text-right" style="border-top: none; border-right: 1px solid black; border-bottom: none; border-left: 1px solid black;">'. $row->qty .'</td>
                                                            <td class="text-right" style="border-top: none; border-right: 1px solid black; border-bottom: none; border-left: 1px solid black;">'. number_format($row->rate, 2, '.', '') .'</td>
                                                            <td class="text-right" style="border-top: none; border-right: 1px solid black; border-bottom: none; border-left: 1px solid black;">'. number_format($row->amount, 2, '.', '') .'</td>
                                                        </tr>';
                                                        $i++;
                                                        $p++;
                                                    }
                                                }
                                            }
                                            //echo 28 - (($i-1) * 2);
                                            $n = 28 - (($i-1) * 2);     // 14 Rows Only. i.e., 14 x 2 = 28; 14 Particulars
                                            for($j = 1; $j <= $n; $j++){
                                                echo '<tr>
                                                <td class="text-center" style="border-top: none; border-right: 1px solid black; border-bottom: none; border-left: 1px solid black;">&nbsp;</td>
                                                <td style="border-top: none; border-right: 1px solid black; border-bottom: none; border-left: 1px solid black;">&nbsp;</td>
                                                <td style="border-top: none; border-right: 1px solid black; border-bottom: none; border-left: 1px solid black;">&nbsp;</td>
                                                <td style="border-top: none; border-right: 1px solid black; border-bottom: none; border-left: 1px solid black;">&nbsp;</td>
                                                <td class="text-right" style="border-top: none; border-right: 1px solid black; border-bottom: none; border-left: 1px solid black;">&nbsp;</td>
                                                <td class="text-right" style="border-top: none; border-right: 1px solid black; border-bottom: none; border-left: 1px solid black;">&nbsp;</td>
                                                <td class="text-right" style="border-top: none; border-right: 1px solid black; border-bottom: none; border-left: 1px solid black;">&nbsp;</td>
                                            </tr>';
                                            }
                                            ?>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td colspan="4" style="border-top: 1px solid black; border-right: 1px solid black; border-bottom: none; border-left: 1px solid black;"><b>Total Amount in Words Rs.: </b></td>
                                                <td colspan="2" style="border-top: 1px solid black; border-right: 1px solid black; border-bottom: 1px solid black; border-left: 1px solid black;"><b>TOTAL</b></td>
                                                <td align="right" style="border-top: 1px solid black; border-right: 1px solid black; border-bottom: 1px solid black; border-left: 1px solid black;"><b><?php echo $obj->total; ?></b></td>
                                            </tr>
                                            <tr>
                                                <td style="border-top: none; border-right: none; border-bottom: none; border-left: 1px solid black;">&nbsp;</td>
                                                <td rowspan="6" colspan="3" style="border-top: none; border-right: 1px solid black; border-bottom: 1px solid black; border-left: none;"><?php echo number_to_words($obj->total_amount); ?></td>
                                                <td colspan="2" style="border-top: 1px solid black; border-right: 1px solid black; border-bottom: 1px solid black; border-left: 1px solid black;"><b>SGST : 9 %</b></td>
                                                <td align="right" style="border-top: 1px solid black; border-right: 1px solid black; border-bottom: 1px solid black; border-left: 1px solid black;"><?php echo iif($obj->sgst_amt == "", "0.00", $obj->sgst_amt); ?></td>
                                            </tr>
                                            <tr>
                                                <td style="border-top: none; border-right: none; border-bottom: none; border-left: 1px solid black;">&nbsp;</td>
                                                <td colspan="2" style="border-top: 1px solid black; border-right: 1px solid black; border-bottom: 1px solid black; border-left: 1px solid black;"><b>CGST : 9 %</b></td>
                                                <td align="right" style="border-top: 1px solid black; border-right: 1px solid black; border-bottom: 1px solid black; border-left: 1px solid black;"><?php echo iif($obj->cgst_amt == "", "0.00", $obj->cgst_amt); ?></td>
                                            </tr>
                                            <tr>
                                                <td style="border-top: none; border-right: none; border-bottom: none; border-left: 1px solid black;">&nbsp;</td>
                                                <td colspan="2" style="border-top: 1px solid black; border-right: 1px solid black; border-bottom: 1px solid black; border-left: 1px solid black;"><b>IGST : 0 %</b></td>
                                                <td align="right" style="border-top: 1px solid black; border-right: 1px solid black; border-bottom: 1px solid black; border-left: 1px solid black;"><?php echo iif($obj->igst_amt == "", "0.00", $obj->igst_amt); ?></td>
                                            </tr>
                                            <tr>
                                                <td style="border-top: none; border-right: none; border-bottom: none; border-left: 1px solid black;">&nbsp;</td>
                                                <td colspan="2" style="border-top: 1px solid black; border-right: 1px solid black; border-bottom: 1px solid black; border-left: 1px solid black;"><b>TOTAL GST</b></td>
                                                <td align="right" style="border-top: 1px solid black; border-right: 1px solid black; border-bottom: 1px solid black; border-left: 1px solid black;"><b><?php echo iif($obj->total_gst_amt == "", "0.00", $obj->total_gst_amt); ?></b></td>
                                            </tr>
                                            <tr>
                                                <td style="border-top: none; border-right: none; border-bottom: none; border-left: 1px solid black;">&nbsp;</td>
                                                <td colspan="2" style="border-top: 1px solid black; border-right: 1px solid black; border-bottom: 1px solid black; border-left: 1px solid black;"><b>ROUNDED AMOUNT</b></td>
                                                <td align="right" style="border-top: 1px solid black; border-right: 1px solid black; border-bottom: 1px solid black; border-left: 1px solid black;"><b><?php echo iif($obj->round_amount == "", "0.00", $obj->round_amount); ?></b></td>
                                            </tr>
                                            <tr>
                                                <td style="border-top: none; border-right: none; border-bottom: 1px solid black; border-left: 1px solid black;">&nbsp;</td>
                                                <td colspan="2" style="border-top: 1px solid black; border-right: 1px solid black; border-bottom: 1px solid black; border-left: 1px solid black;"><b>TOTAL AMOUNT</b></td>
                                                <td align="right" style="border-top: 1px solid black; border-right: 1px solid black; border-bottom: 1px solid black; border-left: 1px solid black;"><b><?php echo iif($obj->total_amount == "", "0.00", $obj->total_amount); ?></b></td>
                                            </tr>

                                            <tr>
                                                <td colspan="4" style="border-top: 1px solid black; border-right: 1px solid black; border-bottom: none; border-left: 1px solid black;"><b>Declaration: </b></td>
                                                <td align="right" colspan="3" style="border-top: 1px solid black; border-right: 1px solid black; border-bottom: none; border-left: 1px solid black;">For <b>SRI VIDYAA PLASTICS</b></td>
                                            </tr>
                                            <tr>
                                                <td colspan="4" style="border-top: none; border-right: 1px solid black; border-bottom: none; border-left: 1px solid black;"><b>We declare that this Invoice shows the actual price of the goods described and that all <br>particulars are true and correct.</b></td>
                                                <td align="right" colspan="3" style="border-top: none; border-right: 1px solid black; border-bottom: none; border-left: 1px solid black;">&nbsp;</td>
                                            </tr>
                                            <tr>
                                                <td colspan="4" style="border-top: none; border-right: 1px solid black; border-bottom: none; border-left: 1px solid black;">&nbsp;</td>
                                                <td align="right" colspan="3" style="border-top: none; border-right: 1px solid black; border-bottom: none; border-left: 1px solid black;">&nbsp;</td>
                                            </tr>
                                            <tr>
                                                <td colspan="4" style="border-top: none; border-right: 1px solid black; border-bottom: 1px solid black; border-left: 1px solid black;"><small>Note: Subject to Coimbatore Judiction</small></td>
                                                <td align="right" colspan="3" style="border-top: none; border-right: 1px solid black; border-bottom: 1px solid black; border-left: 1px solid black;">Authorised Signatory</td>
                                            </tr>                                            
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="main-page" id="print_content8">  <!-- ---------------------------------------------- Page 1 Duplicate Copy----------------------------------------------- -->
                        <div class="sub-page">
                            <div class="row">
                                <div class="col-sm-12 text-center">
                                    <table class="table table-sm table-bordered" style="margin-bottom: 0px;">
                                        <tbody>
                                            <tr>
                                                <td colspan="7">
                                                    <div class="row">
                                                        <div class="col-sm-3" style="padding-right: 0px;">
                                                            <b>
                                                                <div style="font-size: 12px;">
                                                                GSTIN.: 33ARGPJ1151Q1ZP<br>
                                                                Email: srividyaaplastics@gmail.com
                                                                </div>
                                                            </b>
                                                        </div>
                                                        <div class="col-sm-6 text-center" style="padding: 0px;">
                                                            <center><div style="border-radius: 5px; border: 2px solid #000000; padding: 1px; width: 190px;"><b>TAX INVOICE</b></div></center>
                                                            <div style="font-size: 28px; color: #c43318;"><b>SRI VIDYAA PLASTICS</b></div>
                                                            <div style="font-size: 12px;">AN ISO 9001 - 2008 CERTIFIED COMPANY<br>
                                                            <i>Mfrs: All Kinds of Precision Moulded Plastic Components</i><br></div>
                                                            <div style="font-size: 14px;">603, Thoppu thottam, Jothipuram, Coimbatore - 641 047.</div>
                                                        </div>
                                                        <div class="col-sm-3 text-right" style="padding-left: 0px;">
                                                            <div style="font-size: 12px;">Cell: 99942 63220</div>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="4">
                                                    <div style="line-height: 2;" >
                                                        <b>To M/s. :</b> <br>
                                                        <div style="text-transform: uppercase;">
                                                        <?php echo $dbcon->GetOneRecord("tbl_customer", "CONCAT(cust_id, ' - ', cust_name)", "id", $customer_id); ?> <br>
                                                        <?php echo $dbcon->GetOneRecord("tbl_customer", "CONCAT(addr_line1, ', ', addr_line2)", "id", $customer_id); ?> <br>
                                                        <?php echo $dbcon->GetOneRecord("tbl_customer", "CONCAT(city_dist, ', ', cstate, ' - ', pincode)", "id", $customer_id); ?> <br>
                                                        </div>
                                                        <b>Customer GSTIN. :</b> <?php echo $dbcon->GetOneRecord("tbl_customer", "gst_no", "id", $customer_id); ?>
                                                    </div>
                                                </td>
                                                <td colspan="3" width="34%">
                                                    <div style="line-height: 2;">
                                                        <b>Invoice No :</b> <?php echo $invoice_no; ?><br>
                                                        <b>Date :</b> <?php echo date('d-m-Y', strtotime($sales_date)); ?> <br>
                                                        <b>Vendor Code :</b> <?php echo $obj->vend_code; ?> <br>
                                                        <br>
                                                        <div class="pull-right"><b>Duplicate Copy</b>&nbsp;&nbsp;</div>
                                                    </div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <table class="table table-sm table-borderless" style="margin-bottom: 0px;">
                                        <tbody style="text-transform: uppercase;">
                                            <tr>
                                                <td class="text-center" width="6%" style="border-top: none; border-right: 1px solid black; border-bottom: 1px solid black; border-left: 1px solid black;"><b>S.No</b></td>
                                                <td class="text-center" style="border-top: none; border-right: 1px solid black; border-bottom: 1px solid black; border-left: 1px solid black;"><b>Particulars</b></td>
                                                <td class="text-center" width="10%" style="border-top: none; border-right: 1px solid black; border-bottom: 1px solid black; border-left: 1px solid black;"><b>HSN/SAC</b></td>
                                                <td class="text-center" width="6%" style="border-top: none; border-right: 1px solid black; border-bottom: 1px solid black; border-left: 1px solid black;"><b>UOM</b></td>
                                                <td class="text-center" width="9%" style="border-top: none; border-right: 1px solid black; border-bottom: 1px solid black; border-left: 1px solid black;"><b>T.Qty</b></td>
                                                <td class="text-center" width="10%" style="border-top: none; border-right: 1px solid black; border-bottom: 1px solid black; border-left: 1px solid black;"><b>Rate<br>Rs. &nbsp;&nbsp;P.</b></td>
                                                <td class="text-center" width="15%" style="border-top: none; border-right: 1px solid black; border-bottom: 1px solid black; border-left: 1px solid black;"><b>Total Amount<br>Rs. &nbsp;&nbsp;P.</b></td>
                                            </tr>
                                            <?php
                                            if (isset($_REQUEST["id"])) {
                                                $i = 1;
                                                $res = $con->query("SELECT * FROM tbl_sales_details WHERE sales_id = ". $id ." ORDER BY id LIMIT 15");
                                                if ($res->rowCount()) {
                                                    while ($row = $res->fetch(PDO::FETCH_OBJ)) {
                                                        echo '<tr>
                                                            <td class="text-center" style="border-top: none; border-right: 1px solid black; border-bottom: none; border-left: 1px solid black;">'. $i .'</td>
                                                            <td style="border-top: none; border-right: 1px solid black; border-bottom: none; border-left: 1px solid black;">'. $dbcon->GetOneRecord("tbl_cust_material_details", "particulars", "id", $row->cm_details_id) .'<br>'. $row->pcp_nos .' '. $dbcon->GetOneRecord("tbl_pcp", "pcp", "id = ". $row->pcp_id ." and del_status", 0) .'</td>
                                                            <td style="border-top: none; border-right: 1px solid black; border-bottom: none; border-left: 1px solid black;">'. $dbcon->GetOneRecord("tbl_cust_material_details", "hsn_sac", "id", $row->cm_details_id) .'</td>
                                                            <td style="border-top: none; border-right: 1px solid black; border-bottom: none; border-left: 1px solid black;">'. $dbcon->GetOneRecord("tbl_uom", "uom", "id = ". $row->uom_id ." and del_status", 0) .'</td>
                                                            <td class="text-right" style="border-top: none; border-right: 1px solid black; border-bottom: none; border-left: 1px solid black;">'. $row->qty .'</td>
                                                            <td class="text-right" style="border-top: none; border-right: 1px solid black; border-bottom: none; border-left: 1px solid black;">'. number_format($row->rate, 2, '.', '') .'</td>
                                                            <td class="text-right" style="border-top: none; border-right: 1px solid black; border-bottom: none; border-left: 1px solid black;">'. number_format($row->amount, 2, '.', '') .'</td>
                                                        </tr>';
                                                        $i++;
                                                    }
                                                }
                                            }
                                            //echo 30 - (($i-1) * 2);
                                            $n = 30 - (($i-1) * 2);     // 15 Rows Only. i.e., 15 x 2 = 30; 15 Particulars
                                            for($j = 1; $j <= $n; $j++){
                                                echo '<tr>
                                                <td class="text-center" style="border-top: none; border-right: 1px solid black; border-bottom: none; border-left: 1px solid black;">&nbsp;</td>
                                                <td style="border-top: none; border-right: 1px solid black; border-bottom: none; border-left: 1px solid black;">&nbsp;</td>
                                                <td style="border-top: none; border-right: 1px solid black; border-bottom: none; border-left: 1px solid black;">&nbsp;</td>
                                                <td style="border-top: none; border-right: 1px solid black; border-bottom: none; border-left: 1px solid black;">&nbsp;</td>
                                                <td class="text-right" style="border-top: none; border-right: 1px solid black; border-bottom: none; border-left: 1px solid black;">&nbsp;</td>
                                                <td class="text-right" style="border-top: none; border-right: 1px solid black; border-bottom: none; border-left: 1px solid black;">&nbsp;</td>
                                                <td class="text-right" style="border-top: none; border-right: 1px solid black; border-bottom: none; border-left: 1px solid black;">&nbsp;</td>
                                            </tr>';
                                            }
                                            ?>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td colspan="7" style="border-top: 1px solid black; border-right: none; border-bottom: none; border-left: none;" class="text-right"><b>P.T.O...</b></td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="main-page" id="print_content9">  <!-- ---------------------------------------------- Page 2 Duplicate Copy----------------------------------------------- -->
                        <div class="sub-page">
                            <div class="row">
                                <div class="col-sm-12 text-center">
                                    <table class="table table-sm table-borderless" style="margin-bottom: 0px;">
                                        <tbody style="text-transform: uppercase;">
                                            <tr>
                                                <td class="text-right" colspan="7" style="border-top: none; border-right: none; border-bottom: none; border-left: none;"><b>Page 2</b></td>
                                            </tr>
                                            <tr>
                                                <td class="text-center" width="6%" style="border-top: 1px solid black; border-right: 1px solid black; border-bottom: 1px solid black; border-left: 1px solid black;"><b>S.No</b></td>
                                                <td class="text-center" style="border-top: 1px solid black; border-right: 1px solid black; border-bottom: 1px solid black; border-left: 1px solid black;"><b>Particulars</b></td>
                                                <td class="text-center" width="10%" style="border-top: 1px solid black; border-right: 1px solid black; border-bottom: 1px solid black; border-left: 1px solid black;"><b>HSN/SAC</b></td>
                                                <td class="text-center" width="6%" style="border-top: 1px solid black; border-right: 1px solid black; border-bottom: 1px solid black; border-left: 1px solid black;"><b>UOM</b></td>
                                                <td class="text-center" width="9%" style="border-top: 1px solid black; border-right: 1px solid black; border-bottom: 1px solid black; border-left: 1px solid black;"><b>T.Qty</b></td>
                                                <td class="text-center" width="10%" style="border-top: 1px solid black; border-right: 1px solid black; border-bottom: 1px solid black; border-left: 1px solid black;"><b>Rate<br>Rs. &nbsp;&nbsp;P.</b></td>
                                                <td class="text-center" width="15%" style="border-top: 1px solid black; border-right: 1px solid black; border-bottom: 1px solid black; border-left: 1px solid black;"><b>Total Amount<br>Rs. &nbsp;&nbsp;P.</b></td>
                                            </tr>
                                            <?php
                                            if (isset($_REQUEST["id"])) {
                                                $i = 1;
                                                $p = 16;
                                                $res = $con->query("SELECT * FROM tbl_sales_details WHERE sales_id = ". $id ." ORDER BY id LIMIT 15, 29");
                                                if ($res->rowCount()) {
                                                    while ($row = $res->fetch(PDO::FETCH_OBJ)) {
                                                        echo '<tr>
                                                            <td class="text-center" style="border-top: none; border-right: 1px solid black; border-bottom: none; border-left: 1px solid black;">'. $p .'</td>
                                                            <td style="border-top: none; border-right: 1px solid black; border-bottom: none; border-left: 1px solid black;">'. $dbcon->GetOneRecord("tbl_cust_material_details", "particulars", "id", $row->cm_details_id) .'<br>'. $row->pcp_nos .' '. $dbcon->GetOneRecord("tbl_pcp", "pcp", "id = ". $row->pcp_id ." and del_status", 0) .'</td>
                                                            <td style="border-top: none; border-right: 1px solid black; border-bottom: none; border-left: 1px solid black;">'. $dbcon->GetOneRecord("tbl_cust_material_details", "hsn_sac", "id", $row->cm_details_id) .'</td>
                                                            <td style="border-top: none; border-right: 1px solid black; border-bottom: none; border-left: 1px solid black;">'. $dbcon->GetOneRecord("tbl_uom", "uom", "id = ". $row->uom_id ." and del_status", 0) .'</td>
                                                            <td class="text-right" style="border-top: none; border-right: 1px solid black; border-bottom: none; border-left: 1px solid black;">'. $row->qty .'</td>
                                                            <td class="text-right" style="border-top: none; border-right: 1px solid black; border-bottom: none; border-left: 1px solid black;">'. number_format($row->rate, 2, '.', '') .'</td>
                                                            <td class="text-right" style="border-top: none; border-right: 1px solid black; border-bottom: none; border-left: 1px solid black;">'. number_format($row->amount, 2, '.', '') .'</td>
                                                        </tr>';
                                                        $i++;
                                                        $p++;
                                                    }
                                                }
                                            }
                                            //echo 28 - (($i-1) * 2);
                                            $n = 28 - (($i-1) * 2);     // 14 Rows Only. i.e., 14 x 2 = 28; 14 Particulars
                                            for($j = 1; $j <= $n; $j++){
                                                echo '<tr>
                                                <td class="text-center" style="border-top: none; border-right: 1px solid black; border-bottom: none; border-left: 1px solid black;">&nbsp;</td>
                                                <td style="border-top: none; border-right: 1px solid black; border-bottom: none; border-left: 1px solid black;">&nbsp;</td>
                                                <td style="border-top: none; border-right: 1px solid black; border-bottom: none; border-left: 1px solid black;">&nbsp;</td>
                                                <td style="border-top: none; border-right: 1px solid black; border-bottom: none; border-left: 1px solid black;">&nbsp;</td>
                                                <td class="text-right" style="border-top: none; border-right: 1px solid black; border-bottom: none; border-left: 1px solid black;">&nbsp;</td>
                                                <td class="text-right" style="border-top: none; border-right: 1px solid black; border-bottom: none; border-left: 1px solid black;">&nbsp;</td>
                                                <td class="text-right" style="border-top: none; border-right: 1px solid black; border-bottom: none; border-left: 1px solid black;">&nbsp;</td>
                                            </tr>';
                                            }
                                            ?>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td colspan="4" style="border-top: 1px solid black; border-right: 1px solid black; border-bottom: none; border-left: 1px solid black;"><b>Total Amount in Words Rs.: </b></td>
                                                <td colspan="2" style="border-top: 1px solid black; border-right: 1px solid black; border-bottom: 1px solid black; border-left: 1px solid black;"><b>TOTAL</b></td>
                                                <td align="right" style="border-top: 1px solid black; border-right: 1px solid black; border-bottom: 1px solid black; border-left: 1px solid black;"><b><?php echo $obj->total; ?></b></td>
                                            </tr>
                                            <tr>
                                                <td style="border-top: none; border-right: none; border-bottom: none; border-left: 1px solid black;">&nbsp;</td>
                                                <td rowspan="6" colspan="3" style="border-top: none; border-right: 1px solid black; border-bottom: 1px solid black; border-left: none;"><?php echo number_to_words($obj->total_amount); ?></td>
                                                <td colspan="2" style="border-top: 1px solid black; border-right: 1px solid black; border-bottom: 1px solid black; border-left: 1px solid black;"><b>SGST : 9 %</b></td>
                                                <td align="right" style="border-top: 1px solid black; border-right: 1px solid black; border-bottom: 1px solid black; border-left: 1px solid black;"><?php echo iif($obj->sgst_amt == "", "0.00", $obj->sgst_amt); ?></td>
                                            </tr>
                                            <tr>
                                                <td style="border-top: none; border-right: none; border-bottom: none; border-left: 1px solid black;">&nbsp;</td>
                                                <td colspan="2" style="border-top: 1px solid black; border-right: 1px solid black; border-bottom: 1px solid black; border-left: 1px solid black;"><b>CGST : 9 %</b></td>
                                                <td align="right" style="border-top: 1px solid black; border-right: 1px solid black; border-bottom: 1px solid black; border-left: 1px solid black;"><?php echo iif($obj->cgst_amt == "", "0.00", $obj->cgst_amt); ?></td>
                                            </tr>
                                            <tr>
                                                <td style="border-top: none; border-right: none; border-bottom: none; border-left: 1px solid black;">&nbsp;</td>
                                                <td colspan="2" style="border-top: 1px solid black; border-right: 1px solid black; border-bottom: 1px solid black; border-left: 1px solid black;"><b>IGST : 0 %</b></td>
                                                <td align="right" style="border-top: 1px solid black; border-right: 1px solid black; border-bottom: 1px solid black; border-left: 1px solid black;"><?php echo iif($obj->igst_amt == "", "0.00", $obj->igst_amt); ?></td>
                                            </tr>
                                            <tr>
                                                <td style="border-top: none; border-right: none; border-bottom: none; border-left: 1px solid black;">&nbsp;</td>
                                                <td colspan="2" style="border-top: 1px solid black; border-right: 1px solid black; border-bottom: 1px solid black; border-left: 1px solid black;"><b>TOTAL GST</b></td>
                                                <td align="right" style="border-top: 1px solid black; border-right: 1px solid black; border-bottom: 1px solid black; border-left: 1px solid black;"><b><?php echo iif($obj->total_gst_amt == "", "0.00", $obj->total_gst_amt); ?></b></td>
                                            </tr>
                                            <tr>
                                                <td style="border-top: none; border-right: none; border-bottom: none; border-left: 1px solid black;">&nbsp;</td>
                                                <td colspan="2" style="border-top: 1px solid black; border-right: 1px solid black; border-bottom: 1px solid black; border-left: 1px solid black;"><b>ROUNDED AMOUNT</b></td>
                                                <td align="right" style="border-top: 1px solid black; border-right: 1px solid black; border-bottom: 1px solid black; border-left: 1px solid black;"><b><?php echo iif($obj->round_amount == "", "0.00", $obj->round_amount); ?></b></td>
                                            </tr>
                                            <tr>
                                                <td style="border-top: none; border-right: none; border-bottom: 1px solid black; border-left: 1px solid black;">&nbsp;</td>
                                                <td colspan="2" style="border-top: 1px solid black; border-right: 1px solid black; border-bottom: 1px solid black; border-left: 1px solid black;"><b>TOTAL AMOUNT</b></td>
                                                <td align="right" style="border-top: 1px solid black; border-right: 1px solid black; border-bottom: 1px solid black; border-left: 1px solid black;"><b><?php echo iif($obj->total_amount == "", "0.00", $obj->total_amount); ?></b></td>
                                            </tr>

                                            <tr>
                                                <td colspan="4" style="border-top: 1px solid black; border-right: 1px solid black; border-bottom: none; border-left: 1px solid black;"><b>Declaration: </b></td>
                                                <td align="right" colspan="3" style="border-top: 1px solid black; border-right: 1px solid black; border-bottom: none; border-left: 1px solid black;">For <b>SRI VIDYAA PLASTICS</b></td>
                                            </tr>
                                            <tr>
                                                <td colspan="4" style="border-top: none; border-right: 1px solid black; border-bottom: none; border-left: 1px solid black;"><b>We declare that this Invoice shows the actual price of the goods described and that all <br>particulars are true and correct.</b></td>
                                                <td align="right" colspan="3" style="border-top: none; border-right: 1px solid black; border-bottom: none; border-left: 1px solid black;">&nbsp;</td>
                                            </tr>
                                            <tr>
                                                <td colspan="4" style="border-top: none; border-right: 1px solid black; border-bottom: none; border-left: 1px solid black;">&nbsp;</td>
                                                <td align="right" colspan="3" style="border-top: none; border-right: 1px solid black; border-bottom: none; border-left: 1px solid black;">&nbsp;</td>
                                            </tr>
                                            <tr>
                                                <td colspan="4" style="border-top: none; border-right: 1px solid black; border-bottom: 1px solid black; border-left: 1px solid black;"><small>Note: Subject to Coimbatore Judiction</small></td>
                                                <td align="right" colspan="3" style="border-top: none; border-right: 1px solid black; border-bottom: 1px solid black; border-left: 1px solid black;">Authorised Signatory</td>
                                            </tr>                                            
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php
                        }
                    ?>
                    <br>
                    
                </div>
                <!-- <div class="box-footer text-right">
                    <?php //if (isset($_REQUEST["id"])) { ?>
                        <input type="hidden" value="<?php //echo $id; ?>" name="hid_id">
                        <button type="submit" name="update" class="btn btn-primary"><i class="fa fa-check" aria-hidden="true"></i> Update</button>
                    <?php //} else { ?>
                        <button type="submit" name="submit" class="btn btn-primary"><i class="fa fa-check" aria-hidden="true"></i> Submit</button>
                    <?php //} ?>
                </div> -->
            </form>
        </div>
    </section>
</div>
<?php include("includes/footer.php"); ?>

<script src="assets/dist/my_js_css/html2pdf.bundle.min.js"></script>

<script type="text/javascript">
function createPDF() {
    var element = document.getElementById('print_content');
    var hid_invoice = $("#hid_invoice").val();
    
    <?php $update = $con->query("UPDATE tbl_sales SET bill = 1 WHERE id=" . $id); ?>
    
    html2pdf(element, {
        margin: 0,
        padding: 0,
        filename: hid_invoice + '<?php echo "_1_" . date("dMY"); ?>' + '.pdf',
        image: { type: 'jpeg', quality: 1 },
        html2canvas: { scale: 2,  logging: true },
        jsPDF: { unit: 'cm', format: 'A4', orientation: 'P' },
        class: createPDF
    });

    var element1 = document.getElementById('print_content1');
    html2pdf(element1, {
        margin: 0,
        padding: 0,
        filename: hid_invoice + '<?php echo "_2_" . date("dMY"); ?>' + '.pdf',
        image: { type: 'jpeg', quality: 1 },
        html2canvas: { scale: 2,  logging: true },
        jsPDF: { unit: 'cm', format: 'A4', orientation: 'P' },
        class: createPDF
    });

    var element2 = document.getElementById('print_content2');
    if (element2 != ""){
        html2pdf(element2, {
            margin: 0,
            padding: 0,
            filename: hid_invoice + '<?php echo "_3_" . date("dMY"); ?>' + '.pdf',
            image: { type: 'jpeg', quality: 1 },
            html2canvas: { scale: 2,  logging: true },
            jsPDF: { unit: 'cm', format: 'A4', orientation: 'P' },
            class: createPDF
        });
    }

    var element3 = document.getElementById('print_content3');
    if (element3 != ""){
        html2pdf(element3, {
            margin: 0,
            padding: 0,
            filename: hid_invoice + '<?php echo "_4_" . date("dMY"); ?>' + '.pdf',
            image: { type: 'jpeg', quality: 1 },
            html2canvas: { scale: 2,  logging: true },
            jsPDF: { unit: 'cm', format: 'A4', orientation: 'P' },
            class: createPDF
        });
    }

    var element4 = document.getElementById('print_content4');
    if (element4 != ""){
        html2pdf(element4, {
            margin: 0,
            padding: 0,
            filename: hid_invoice + '<?php echo "_5_" . date("dMY"); ?>' + '.pdf',
            image: { type: 'jpeg', quality: 1 },
            html2canvas: { scale: 2,  logging: true },
            jsPDF: { unit: 'cm', format: 'A4', orientation: 'P' },
            class: createPDF
        });
    }

    var element5 = document.getElementById('print_content5');
    if (element5 != ""){
        html2pdf(element5, {
            margin: 0,
            padding: 0,
            filename: hid_invoice + '<?php echo "_6_" . date("dMY"); ?>' + '.pdf',
            image: { type: 'jpeg', quality: 1 },
            html2canvas: { scale: 2,  logging: true },
            jsPDF: { unit: 'cm', format: 'A4', orientation: 'P' },
            class: createPDF
        });
    }

    var element6 = document.getElementById('print_content6');
    if (element6 != ""){
        html2pdf(element6, {
            margin: 0,
            padding: 0,
            filename: hid_invoice + '<?php echo "_7_" . date("dMY"); ?>' + '.pdf',
            image: { type: 'jpeg', quality: 1 },
            html2canvas: { scale: 2,  logging: true },
            jsPDF: { unit: 'cm', format: 'A4', orientation: 'P' },
            class: createPDF
        });
    }

    var element7 = document.getElementById('print_content7');
    if (element7 != ""){
        html2pdf(element7, {
            margin: 0,
            padding: 0,
            filename: hid_invoice + '<?php echo "_8_" . date("dMY"); ?>' + '.pdf',
            image: { type: 'jpeg', quality: 1 },
            html2canvas: { scale: 2,  logging: true },
            jsPDF: { unit: 'cm', format: 'A4', orientation: 'P' },
            class: createPDF
        });
    }

    var element8 = document.getElementById('print_content8');
    if (element8 != ""){
        html2pdf(element8, {
            margin: 0,
            padding: 0,
            filename: hid_invoice + '<?php echo "_9_" . date("dMY"); ?>' + '.pdf',
            image: { type: 'jpeg', quality: 1 },
            html2canvas: { scale: 2,  logging: true },
            jsPDF: { unit: 'cm', format: 'A4', orientation: 'P' },
            class: createPDF
        });
    }

    var element9 = document.getElementById('print_content9');
    if (element9 != ""){
        html2pdf(element9, {
            margin: 0,
            padding: 0,
            filename: hid_invoice + '<?php echo "_10_" . date("dMY"); ?>' + '.pdf',
            image: { type: 'jpeg', quality: 1 },
            html2canvas: { scale: 2,  logging: true },
            jsPDF: { unit: 'cm', format: 'A4', orientation: 'P' },
            class: createPDF
        });
    }
};
</script>

<script type="text/javascript">
    $(document).ready(function() {
        $('#transMainNav').addClass('active');
        $('#transSalesSubNav').addClass('active');
    });
</script>