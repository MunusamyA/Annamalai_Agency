<?php
ob_start();
session_start();

require_once("includes/common/connection.php");
require_once("includes/common/dbfunctions.php");
require_once("includes/common/functions.php");

$con = new connection();
$dbcon = new dbfunctions();
$converter = new Encryption;

// ini_set('display_errors', '1'); ini_set('display_startup_errors', '1'); error_reporting(E_ALL);

isAdmin();

//---------------------------------save/submit----------------------------------
if (isset($_REQUEST["submit"])) {
    try{
        $created_by = $_SESSION["user_id"]; ;
        $created_dt = date('Y-m-d H:i:s');

        $stmt = null;
        $stmt = $con->prepare("INSERT INTO tbl_customer (cust_id, cust_name, gst_no, pan_no, door_no, addr_line1, addr_line2, city_dist, cstate, pincode, mobile_no1, mobile_no2, email_id, created_by, created_dt) 
                        VALUES (:cust_id, :cust_name, :gst_no, :pan_no, :door_no, :addr_line1, :addr_line2, :city_dist, :cstate, :pincode, :mobile_no1, :mobile_no2, :email_id, :created_by, :created_dt)");
        $data = array(
            ":cust_id" => trim($_REQUEST["cust_id"]),
            ":cust_name" => trim($_REQUEST["cust_name"]),
            ":gst_no" => trim($_REQUEST["gst_no"]),
            ":pan_no" => trim($_REQUEST["pan_no"]),
            ":door_no" => trim($_REQUEST["door_no"]),
            ":addr_line1" => trim($_REQUEST["addr_line1"]),
            ":addr_line2" => trim($_REQUEST["addr_line2"]),
            ":city_dist" => trim($_REQUEST["city_dist"]),
            ":cstate" => trim($_REQUEST["cstate"]),
            ":pincode" => trim($_REQUEST["pincode"]),
            ":mobile_no1" => trim($_REQUEST["mobile_no1"]),
            ":mobile_no2" => trim($_REQUEST["mobile_no2"]),
            ":email_id" => trim($_REQUEST["email_id"]),
            ":created_by" => $created_by,
            ":created_dt" => $created_dt,
        );
        $stmt->execute($data);

        $_SESSION["msg"] = "Saved Successfully";
    } catch (Exception $e) {
        $str = filter_var($e->getMessage(), FILTER_SANITIZE_STRING);
        echo $_SESSION['msg_err'] = $str;  
    }
    
    header("location: customer.php");
    die();
}
//---------------------------------save/submit----------------------------------
//---------------------------------update--------------------------------------
if (isset($_REQUEST["update"])) {
    try{
        //echo "Update"; die();
        $updated_by = $_SESSION["user_id"];
        $updated_dt = date('Y-m-d H:i:s');

        $stmt = null;
        $stmt = $con->prepare("UPDATE tbl_customer SET cust_name=:cust_name, gst_no=:gst_no, pan_no=:pan_no, door_no=:door_no, addr_line1=:addr_line1, addr_line2=:addr_line2, city_dist=:city_dist, cstate=:cstate, pincode=:pincode, 
        mobile_no1=:mobile_no1, mobile_no2=:mobile_no2, email_id=:email_id, updated_by=:updated_by, updated_dt=:updated_dt where id=:id");
        $data = array(
            ":id" => trim($_REQUEST["hid_id"]),
            ":cust_name" => trim($_REQUEST["cust_name"]),
            ":gst_no" => trim($_REQUEST["gst_no"]),
            ":pan_no" => trim($_REQUEST["pan_no"]),
            ":door_no" => trim($_REQUEST["door_no"]),
            ":addr_line1" => trim($_REQUEST["addr_line1"]),
            ":addr_line2" => trim($_REQUEST["addr_line2"]),
            ":city_dist" => trim($_REQUEST["city_dist"]),
            ":cstate" => trim($_REQUEST["cstate"]),
            ":pincode" => trim($_REQUEST["pincode"]),
            ":mobile_no1" => trim($_REQUEST["mobile_no1"]),
            ":mobile_no2" => trim($_REQUEST["mobile_no2"]),
            ":email_id" => trim($_REQUEST["email_id"]),
            ":updated_by" => $updated_by,
            ":updated_dt" => $updated_dt
        );
        //print_r($data); die();
        $stmt->execute($data);

        $_SESSION["msg"] = "Updated Successfully";
    } catch (Exception $e) {
        $str = filter_var($e->getMessage(), FILTER_SANITIZE_STRING);
        echo $_SESSION['msg_err'] = $str;  
    }

    header("location:customer.php");
    die();
}
//---------------------------------update----------------------------------------
//---------------------------------delete----------------------------------------
if (isset($_REQUEST["did"])) {
    $stmt = null;
    $stmt = $con->query("UPDATE tbl_customer SET del_status=1 WHERE id=" . $converter->decode($_REQUEST["did"]));

    $_SESSION["msg"] = "Deleted Successfully";

    header("location:customer.php");
    die();
}
//---------------------------------delete----------------------------------------

$cust_no = $dbcon->GetMaxValue('tbl_customer', 'id', 'del_status', 0) + 1;
$cust_id = 'C'.leadingZeros($cust_no,5);

//---------------------------------edit----------------------------------------
if (isset($_REQUEST["id"])) {
    $rs = $con->query("SELECT * FROM tbl_customer where id=" . $converter->decode($_REQUEST["id"]));
    if ($rs->rowCount()) {
        if ($obj = $rs->fetch(PDO::FETCH_OBJ)) {
            $id = $obj->id;
            $cust_id = $obj->cust_id;
            $cust_name = $obj->cust_name;
            $gst_no = $obj->gst_no;
            $pan_no = $obj->pan_no;
            $door_no = $obj->door_no;
            $addr_line1 = $obj->addr_line1;
            $addr_line2 = $obj->addr_line2;
            $city_dist = $obj->city_dist;
            $cstate = $obj->cstate;
            $pincode = $obj->pincode;
            $mobile_no1 = $obj->mobile_no1;
            $mobile_no2 = $obj->mobile_no2;
            $email_id = $obj->email_id;
        }
    }
}
//---------------------------------edit----------------------------------------
?>

<?php include("includes/header.php"); ?>
<?php include("includes/aside.php"); ?>

<div class="content-wrapper">
    <section class="content-header">
        <h1>Customer</h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-edit"></i> Masters</a></li>
            <li class="active">Customer</li>
        </ol>
    </section>
    <section class="content">
        <div class="row">
            <!-------------------------------------------------- Form ------------------------------------------>
            <div class="col-md-7">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">
                        <?php if(isset($_REQUEST["id"])){
                            echo 'Edit - <span style="color: blue;">'. $cust_id .'</span>';
                        }else{
                            echo 'Add - <span style="color: blue;">'. $cust_id .'</span>';
                        }
                        ?>
                        </h3>
                    </div>
					<br />
                    <form id="thisForm" name="thisForm" action="customer.php" method="post">
                        <div class="box-body">
                            <div class="form-group col-md-4">
                                <!-- <label class="col-sm-4 control-label">Register No. *</label> -->
                                <!-- <label class="col-sm-5 col-form-label">Customer Name (IN CAPS) <span class="err">*</span></label> -->
                                <label class="col-form-label">Customer Name (IN CAPS) <span class="err">*</span></label>
                                <input type="hidden" name="cust_id" id="cust_id" value="<?php echo $cust_id; ?>">
                                <input type="text" class="form-control" name="cust_name" id="cust_name" placeholder="Enter the Customer Name" title="Enter the Customer Name" autocomplete="off" autofocus="autofocus" onKeyPress="return isCapitalWithSpace(event);" required oninvalid="this.setCustomValidity('Please enter the customer name...!')" oninput="this.setCustomValidity('')" maxlength="50" value="<?php echo $cust_name; ?>">
                            </div>
                            <div class="form-group col-md-4">
                                <label class="col-form-label">GST Number </label>
                                <input type="text" class="form-control" name="gst_no" id="gst_no" placeholder="Enter the GST Number" title="Enter the GST Number" autocomplete="off" maxlength="20" value="<?php echo $gst_no; ?>">
                            </div>
                            <div class="form-group col-md-4">
                                <label class="col-form-label">PAN Number </label>
                                <input type="text" class="form-control" name="pan_no" id="pan_no" placeholder="Enter the PAN Number" title="Enter the PAN Number" autocomplete="off" onKeyPress="return isCapitalNumericNoSpace(event);" maxlength="10" value="<?php echo $pan_no; ?>">
                            </div>
                            <div class="form-group col-md-4">
                                <label class="col-form-label">Door No <span class="err">*</span></label>
                                <input type="text" class="form-control" name="door_no" id="door_no" placeholder="Enter the Door No" title="Enter the Door No" autocomplete="off" required oninvalid="this.setCustomValidity('Please enter the door no...!')" oninput="this.setCustomValidity('')" maxlength="20" value="<?php echo $door_no; ?>">
                            </div>
                            <div class="form-group col-md-4">
                                <label class="col-form-label">Address Line 1 <span class="err">*</span></label>
                                <input type="text" class="form-control" name="addr_line1" id="addr_line1" placeholder="Enter the Address Line 1" title="Enter the Address Line 1" autocomplete="off" required oninvalid="this.setCustomValidity('Please enter the address line 1...!')" oninput="this.setCustomValidity('')" maxlength="60" value="<?php echo $addr_line1; ?>">
                            </div>
                            <div class="form-group col-md-4">
                                <label class="col-form-label">Address Line 2 </label>
                                <input type="text" class="form-control" name="addr_line2" id="addr_line2" placeholder="Enter the Address Line 2" title="Enter the Address Line 2" autocomplete="off" maxlength="60" value="<?php echo $addr_line2; ?>">
                            </div>
                            <div class="form-group col-md-4">
                                <label class="col-form-label">City / District </label>
                                <input type="text" class="form-control" name="city_dist" id="city_dist" placeholder="Enter the City / District" title="Enter the City / District" autocomplete="off" maxlength="40" value="<?php echo $city_dist; ?>">
                            </div>
                            <div class="form-group col-md-4">
                                <label class="col-form-label">State </label>
                                <input type="text" class="form-control" name="cstate" id="cstate" placeholder="Enter the State" title="Enter the State" autocomplete="off" maxlength="40" value="<?php echo $cstate; ?>">
                            </div>
                            <div class="form-group col-md-4">
                                <label class="col-form-label">Pincode </label>
                                <input type="text" class="form-control" name="pincode" id="pincode" placeholder="Enter the Pincode" title="Enter the Pincode" autocomplete="off" onKeyPress="return isNumberKey(event);" maxlength="6" value="<?php echo $pincode; ?>">
                            </div>
                            <div class="form-group col-md-4">
                                <label class="col-form-label">Mobile No 1 <span class="err">*</span></label>
                                <input type="text" class="form-control" name="mobile_no1" id="mobile_no1" placeholder="Enter the Mobile No 1" title="Enter the Mobile No 1" required oninvalid="this.setCustomValidity('Please enter the mobile no 1...!')" oninput="this.setCustomValidity('')" maxlength="10" onKeyPress="return isNumberKey(event);"  value="<?php echo $mobile_no1; ?>">
                            </div>
                            <div class="form-group col-md-4">
                                <label class="col-form-label">Mobile No 2</label>
                                <input type="text" class="form-control" name="mobile_no2" id="mobile_no2" placeholder="Enter the Mobile No 2" title="Enter the Mobile No 2" maxlength="10" onKeyPress="return isNumberKey(event);"  value="<?php echo $mobile_no2; ?>">
                            </div>
                            <div class="form-group col-md-4">
                                <label class="col-form-label">E-Mail ID</label>
                                <input type="text" class="form-control" name="email_id" id="email_id" placeholder="Enter the E-Mail ID" title="Enter the E-Mail ID" maxlength="70" onKeyPress="return isEmailKey(event);"  value="<?php echo $email_id; ?>">
                            </div>
                        </div>
                        <div class="box-footer">
							<div class="pull-right">
								<?php if (isset($_REQUEST["id"])) { ?>
								<input type="hidden" value="<?php echo $id; ?>" name="hid_id">
								<button type="submit" name="update" class="btn btn-primary"><i class="fa fa-check" aria-hidden="true"></i> Update</button>
								<?php
								} else { ?>
								<button type="submit" name="submit" class="btn btn-primary"><i class="fa fa-check" aria-hidden="true"></i> Submit</button>
								<?php } ?>
							</div>
                        </div>
                    </form>
                </div>
            </div>
            <!-- ----------------------------------------------- Form ------------------------------------------ -->
            <!-- --------------------------------------------- View -------------------------------------------- -->
            <div class="col-md-5">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">List</h3>
                    </div>
                    <div class="box-body">
                        <div class="dt-responsive table-responsive">
                            <table id="example1" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th width="12">#</th>
                                        <th width="60" class="text-center">Cust. ID</th>
                                        <th class="text-center">Customer Name</th>
                                        <th width="60" class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $rs = $con->query("SELECT * FROM tbl_customer where del_status = 0 order by id");
                                    if ($rs->rowCount()) {
                                        $sno=1;
                                        while ($obj = $rs->fetch(PDO::FETCH_OBJ)) {
                                    ?>
                                    <tr>
                                        <td class="text-center"><?php echo $sno; ?></td>
                                        <td><?php echo $obj->cust_id; ?></td>
                                        <td><?php echo $obj->cust_name; ?></td>
                                        <td class="text-center">
                                            <a href="customer.php?id=<?php echo $converter->encode($obj->id); ?>" title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i></a>&nbsp;&nbsp;
                                            <a href="customer.php?did=<?php echo $converter->encode($obj->id); ?>" title="Delete" onclick="return confirm('Are You Sure Want To Delete?');"><i class="fa fa-trash-o" aria-hidden="true"></i></a>
                                        </td>
                                    </tr>
                                    <?php
                                            $sno++;
                                        }
                                    } else {
                                    ?>
                                    <tr>
                                        <td colspan="5" align="center">--No Records Found--</td>
                                    </tr>
                                    <?php
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
            <!-- ------------------------ View ---------------------------------- -->
        </div>
    </section>
</div>
<?php include("includes/footer.php"); ?>
<script>
$(function () {
    $('#example1').DataTable({
        'responsive'    : true,
        'pageLength'    : 10,
        'searching'     : true,
        'autoWidth'     : false,
        //'dom'           : 'Bfrtip',
        // 'dom'           : "<'row'<'col-sm-12 d-flex col-md-5'lf><'col-sm-12 col-md-7 text-right'B>>" +
        //                     "<'row'<'col-sm-12'tr>>" +
        //                     "<'row m-2'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
                            
        // buttons         : ['copyHtml5','excelHtml5','csvHtml5','pdfHtml5','print'],

        rowReorder      : true,
        columnDefs      : [
            { orderable: true, className: 'reorder', targets: [0,1,2] },
            { orderable: false, targets: '_all' }
        ]
    })
});
</script>

<script type="text/javascript">
$(document).ready(function() {
    $('#masterMainNav').addClass('active');
    $('#masterCustomerSubNav').addClass('active');
});
</script>
