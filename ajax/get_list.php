<?php
ob_start();
session_start();
define('BASEPATH', '../');

require_once(BASEPATH ."includes/common/connection.php");
require_once(BASEPATH ."includes/common/dbfunctions.php");
require_once(BASEPATH ."includes/common/functions.php");

// ini_set('display_errors', '1'); ini_set('display_startup_errors', '1'); error_reporting(E_ALL);

$con = new connection();
$dbcon = new dbfunctions();

isset($_POST["mode"]);

if($_POST["mode"]=="cm_details")    //------ Course Material Details ---------
{
    $url_data = explode("~", $_POST["id"]);
    //print_r($url_data);
    $rowIdx = $url_data[0];
    $part_drg_no = $url_data[1];
    $particulars = $url_data[2];
    $hsn_sac = $url_data[3];
    $pcp_id = $url_data[4];
    $uom_id = $url_data[5];
    $rate = $url_data[6];

    $html = '';
    $html = '<tr id="tr_' . $rowIdx .'">
        <td class="text-center" style="padding: 0.3rem;">'. $rowIdx .'<input type="hidden" name="hid_sno[]" id="hid_sno_'. $rowIdx .'" value="'. $rowIdx .'"></td>
        <td style="padding: 0.3rem;">'. $part_drg_no .'<input type="hidden" name="hid_part_drg_no[]" id="hid_part_drg_no_'. $rowIdx .'" value="'. $part_drg_no .'"></td>
        <td style="padding: 0.3rem;">'. $particulars. '<input type="hidden" name="hid_particulars[]" id="hid_particulars_'. $rowIdx .'" value="'. $particulars .'"></td>
        <td style="padding: 0.3rem;">'. $hsn_sac .'<input type="hidden" name="hid_hsn_sac[]" id="hid_hsn_sac_'. $rowIdx .'" value="'. $hsn_sac .'"></td>
        <td style="padding: 0.3rem;">'. $dbcon->GetOneRecord("tbl_pcp", "pcp", "id = ". $pcp_id ." and del_status", 0) .'<input type="hidden" name="hid_pcp_id[]" id="hid_pcp_id_'. $rowIdx .'" value="'. $pcp_id .'"></td>
        <td style="padding: 0.3rem;">'. $dbcon->GetOneRecord("tbl_uom", "uom", "id = ". $uom_id ." and del_status", 0) .'<input type="hidden" name="hid_uom_id[]" id="hid_uom_id_'. $rowIdx .'" value="'. $uom_id .'"></td>
        <td class="text-right" style="padding: 0.3rem;">'. number_format($rate, 2, '.', '') .'<input type="hidden" name="hid_rate[]" id="hid_rate_'. $rowIdx .'" value="'. $rate .'"></td>
        <td class="text-center" style="padding: 0.3rem;"><a href="javascript:;" class="remove"><i class="fa fa-trash-o" title="Remove"></i></a></td>
    </tr>';
    
    echo $html;
}

if($_POST["mode"]=="customerToVendor")    //------ Get Customer Based Vendor Code ---------
{
    $vend_code = $dbcon->GetOneRecord("tbl_customer", "vend_code", "id = ". $_POST["id"] ." AND del_status", 0);

    $html = '<option value=""></option>';
    $rs = $con->query("SELECT id, particulars FROM tbl_cust_material_details WHERE cm_id = ". $_POST['id']);
    while ($obj = $rs->fetch(PDO::FETCH_OBJ)) {
        $html .= '<option value="'. $obj->id .'">'. $obj->particulars .'</option>';
    }

    echo $vend_code ."~". $html;
}

if($_POST["mode"]=="cm_details_particulars")
{
    $rs = $con->query("SELECT * FROM tbl_cust_material_details WHERE id = ". $_POST['id']);
    if ($obj = $rs->fetch(PDO::FETCH_OBJ)) {
        $uom_id = $obj->uom_id;
        $pcp_id = $obj->pcp_id;
        $rate = $obj->rate;
    }

    echo $uom_id ."~". $pcp_id ."~". $rate;
}

if($_POST["mode"]=="sales_particulars")
{
    $url_data = explode("~", $_POST["id"]);
    $rowIdx = $url_data[0];
    $particulars = $url_data[1];
    $uom_id = $url_data[2];
    $pcp_id = $url_data[3];
    $pcp_nos = $url_data[4];
    $qty = $url_data[5];
    $rate = $url_data[6];
    $amount = $url_data[7];

    $html = '';
    $html = '<tr id="tr_' . $rowIdx .'">
        <td style="padding: 0.3rem;">'. $dbcon->GetOneRecord("tbl_cust_material_details", "part_drg_no", "id", $particulars) .'<input type="hidden" name="hid_part_drg_no[]" id="hid_part_drg_no_'. $rowIdx .'" value="'. $particulars .'"></td>
        <td style="padding: 0.3rem;">'. $dbcon->GetOneRecord("tbl_cust_material_details", "particulars", "id", $particulars) .'<input type="hidden" name="hid_particulars[]" id="hid_particulars_'. $rowIdx .'" value="'. $particulars .'"></td>
        <td class="text-center" style="padding: 0.3rem;">'. $dbcon->GetOneRecord("tbl_cust_material_details", "hsn_sac", "id", $particulars) .'<input type="hidden" name="hid_hsn_sac[]" id="hid_hsn_sac_'. $rowIdx .'" value="'. $particulars .'"></td>
        <td class="text-center" style="padding: 0.3rem;">'. $dbcon->GetOneRecord("tbl_uom", "uom", "id = ". $uom_id ." and del_status", 0) .'<input type="hidden" name="hid_uom_id[]" id="hid_uom_id_'. $rowIdx .'" value="'. $uom_id .'"></td>
        <td class="text-center" style="padding: 0.3rem;">'. $dbcon->GetOneRecord("tbl_pcp", "pcp", "id = ". $pcp_id ." and del_status", 0) .'<input type="hidden" name="hid_pcp_id[]" id="hid_pcp_id_'. $rowIdx .'" value="'. $pcp_id .'"></td>
        <td class="text-right" style="padding: 0.3rem;">'. $pcp_nos .'<input type="hidden" name="hid_pcp_nos[]" id="hid_pcp_nos_'. $rowIdx .'" value="'. $pcp_nos .'"></td>
        <td class="text-right" style="padding: 0.3rem;">'. $qty .'<input type="hidden" name="hid_qty[]" id="hid_qty_'. $rowIdx .'" value="'. $qty .'"></td>
        <td class="text-right" style="padding: 0.3rem;">'. number_format($rate, 2, '.', '') .'<input type="hidden" name="hid_rate[]" id="hid_rate_'. $rowIdx .'" value="'. $rate .'"></td>
        <td class="text-right" style="padding: 0.3rem;">'. number_format($amount, 2, '.', '') .'<input type="hidden" name="hid_amount[]" id="hid_amount_'. $rowIdx .'" class="hid_amount" value="'. $amount .'"></td>
        <td class="text-center" style="padding: 0.3rem;"><a href="javascript:;" class="remove"><i class="fa fa-trash-o" title="Remove"></i></a></td>
    </tr>';
    
    echo $html;
}

if($_POST["mode"]=="modal_add_gst")
{
    $total_amt = $_POST["id"];

    $html_output ="";
    $html_output ='<div class="row">
        <div class="col-lg-12">
            <div class="row txt-dets">
                <div class="col-md-12" style="line-height:2rem;">
                    <div class="card-body" style="padding: 0px 15px;">
                        <div class="form-group">
                            <label class="col-sm-7 control-label">Total Amount</label>
                            <div class="col-sm-3">
                                <input type="text" class="form-control text-right" name="total_amt" id="total_amt" value="'. number_format($total_amt, 2, '.', '') .'" readonly title="Total Amount">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-4 control-label">SGST <span class="err">*</span></label>
                            <div class="col-sm-3">
                                <select class="form-control select2" name="sgst_id" id="sgst_id" title="Select the SGST">
                                    <option value=""></option>'.
                                    $dbcon->fnFillComboFromTable_Where("id", "sgst", "tbl_gst", "id", " WHERE del_status = 0") .'
                                </select>
                            </div>
                            <div class="col-sm-3">
                                <input type="text" class="form-control text-right" name="sgst_amt" id="sgst_amt" value="" readonly title="SGST Amount">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-4 control-label">CGST <span class="err">*</span></label>
                            <div class="col-sm-3">
                                <select class="form-control select2" name="cgst_id" id="cgst_id" title="Select the CGST">
                                    <option value=""></option>'.
                                    $dbcon->fnFillComboFromTable_Where("id", "cgst", "tbl_gst", "id", " WHERE del_status = 0") .'
                                </select>
                            </div>
                            <div class="col-sm-3">
                                <input type="text" class="form-control text-right" name="cgst_amt" id="cgst_amt" value="" readonly title="CGST Amount">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-4 control-label">IGST <span class="err">*</span></label>
                            <div class="col-sm-3">
                                <select class="form-control select2" name="igst_id" id="igst_id" title="Select the IGST">
                                    <option value=""></option>'.
                                    $dbcon->fnFillComboFromTable_Where("id", "igst", "tbl_gst", "id", " WHERE del_status = 0") .'
                                </select>
                            </div>
                            <div class="col-sm-3">
                                <input type="text" class="form-control text-right" name="igst_amt" id="igst_amt" value="" readonly title="IGST Amount">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-7 control-label">Total GST</label>
                            <div class="col-sm-3">
                                <input type="text" class="form-control text-right" name="total_gst" id="total_gst" value="" readonly title="Total GST">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>';

    echo "<b>Sales - Add GST</b>" ."~". $html_output;
}

if($_POST["mode"]=="calculate_sgst")
{
    $url_data = explode("~", $_POST["id"]);
    $id = $url_data[0];
    $tot_amt = $url_data[1];

    $sgst = $dbcon->GetOneRecord("tbl_gst", "sgst", "id = ". $id ." and del_status", 0);

    echo ($tot_amt * $sgst)/100;
}

if($_POST["mode"]=="calculate_cgst")
{
    $url_data = explode("~", $_POST["id"]);
    $id = $url_data[0];
    $tot_amt = $url_data[1];

    $cgst = $dbcon->GetOneRecord("tbl_gst", "cgst", "id = ". $id ." and del_status", 0);

    echo ($tot_amt * $cgst)/100;
}

if($_POST["mode"]=="calculate_igst")
{
    $url_data = explode("~", $_POST["id"]);
    $id = $url_data[0];
    $tot_amt = $url_data[1];

    $igst = $dbcon->GetOneRecord("tbl_gst", "igst", "id = ". $id ." and del_status", 0);

    echo ($tot_amt * $igst)/100;
}
?>