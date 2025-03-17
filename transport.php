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
        $created_by = $_SESSION["user_id"];
        $created_dt = date('Y-m-d H:i:s');

        $stmt = null;
        $stmt = $con->prepare("INSERT INTO tbl_transport (vehicle_name, vehicle_num_plate, driver_name, driver_mobile_num, owner_mobile_num, created_by, created_dt) 
              VALUES (:vehicle_name, :vehicle_num_plate, :driver_name, :driver_mobile_num, :owner_mobile_num, :created_by, :created_dt)");
        $data = array(
            ":vehicle_name" => trim($_REQUEST["vehicle_name"]),
            ":vehicle_num_plate" => trim($_REQUEST["vehicle_num_plate"]),
            ":driver_name" => trim($_REQUEST["driver_name"]),
            ":driver_mobile_num" => trim($_REQUEST["driver_mobile_num"]),
            ":owner_mobile_num" => trim($_REQUEST["owner_mobile_num"]),
            ":created_by" => $created_by,
            ":created_dt" => $created_dt,
        );
        $stmt->execute($data);

        $_SESSION["msg"] = "Saved Successfully";
    } catch (Exception $e) {
        $str = filter_var($e->getMessage(), FILTER_SANITIZE_STRING);
        echo $_SESSION['msg_err'] = $str;  
    }
    
    header("location:transport.php");
    die();
}
//---------------------------------save/submit----------------------------------
//---------------------------------update--------------------------------------
if (isset($_REQUEST["update"])) {
    try{
        $updated_by = $_SESSION["user_id"];
        $updated_dt = date('Y-m-d H:i:s');

        $stmt = null;
        $stmt = $con->prepare("UPDATE tbl_transport SET vehicle_name = :vehicle_name, vehicle_num_plate = :vehicle_num_plate, driver_name = :driver_name, driver_mobile_num = :driver_mobile_num, owner_mobile_num = :owner_mobile_num, updated_by = :updated_by, updated_dt = :updated_dt WHERE id = :id");

        $data = array(
            ":id" => trim($_REQUEST["hid_id"]),
            ":vehicle_name" => trim($_REQUEST["vehicle_name"]),
            ":vehicle_num_plate" => trim($_REQUEST["vehicle_num_plate"]),
            ":driver_name" => trim($_REQUEST["driver_name"]),
            ":driver_mobile_num" => trim($_REQUEST["driver_mobile_num"]),
            ":owner_mobile_num" => trim($_REQUEST["owner_mobile_num"]),
            ":updated_by" => $updated_by,
            ":updated_dt" => $updated_dt
        );
        $stmt->execute($data);

        $_SESSION["msg"] = "Updated Successfully";
    } catch (Exception $e) {
        $str = filter_var($e->getMessage(), FILTER_SANITIZE_STRING);
        echo $_SESSION['msg_err'] = $str;  
    }

    header("location:transport.php");
    die();
}
//---------------------------------update----------------------------------------
//---------------------------------delete----------------------------------------
if (isset($_REQUEST["did"])) {
    $stmt = null;
    $stmt = $con->query("UPDATE tbl_transport SET del_status = 1 WHERE id=" . $converter->decode($_REQUEST["did"]));

    $_SESSION["msg"] = "Deleted Successfully";

    header("location:transport.php");
    die();
}
//---------------------------------delete----------------------------------------
//---------------------------------edit----------------------------------------
if (isset($_REQUEST["id"])) {
    $rs = $con->query("SELECT * FROM tbl_transport where id=" . $converter->decode($_REQUEST["id"]));
    if ($rs->rowCount()) {
        if ($obj = $rs->fetch(PDO::FETCH_OBJ)) {
            $id = $obj->id;
            $vehicle_name = $obj->vehicle_name;
            $vehicle_num_plate = $obj->vehicle_num_plate;
            $driver_name = $obj->driver_name;
            $driver_mobile_num = $obj->driver_mobile_num;
            $owner_mobile_num = $obj->owner_mobile_num;
        }
    }
}
//---------------------------------edit----------------------------------------
?>

<?php include("includes/header.php"); ?>
<?php include("includes/aside.php"); ?>

<div class="content-wrapper">
    <section class="content-header">
        <h1>Transport</h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-edit"></i> Masters</a></li>
            <li class="active">Transport</li>
        </ol>
    </section>
    <section class="content">
        <div class="row">
            <!-------------------------------------------------- Form ------------------------------------------>
            <div class="col-md-4">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">
                        <?php if(isset($_REQUEST["id"])){
                            echo "Edit";
                        }else{
                            echo "Add";
                        }
                        ?>
                        </h3>
                    </div>
					<br />
                    <form id="thisForm" name="thisForm" action="transport.php" method="post">
                        <div class="box-body">
                            <div class="form-group">
                                <label class="control-label">Name of the Vehicle <span class="err">*</span></label>
                                <input type="text" class="form-control" value="<?php echo $vehicle_name; ?>" name="vehicle_name" id="vehicle_name" placeholder="Enter the Name of the Vehicle" title="Enter the Name of the Vehicle" maxlength="150" autocomplete="off" autofocus="autofocus" required oninvalid="this.setCustomValidity('Please enter the Vehicle Name...!')" oninput="this.setCustomValidity('')" />
                            </div>
                            <div class="form-group">
                                <label class="control-label">Vehicle Number Plate<span class="err">*</span></label>
                                <input type="text" class="form-control" value="<?php echo $vehicle_num_plate; ?>" name="vehicle_num_plate" id="vehicle_num_plate" placeholder="Enter the Vehicle Number Plate" title="Enter the Vehicle Number Plate" maxlength="15" autocomplete="off" required oninvalid="this.setCustomValidity('Please enter the Vehicle Number Plate...!')" oninput="this.setCustomValidity('')" />
                            </div>
                            <div class="form-group">
                                <label class="control-label">Driver Name <span class="err">*</span></label>
                                <input type="text" class="form-control" value="<?php echo $driver_name; ?>" name="driver_name" id="driver_name" placeholder="Enter the Driver Name" title="Enter the Driver Name" maxlength="75" autocomplete="off" required oninvalid="this.setCustomValidity('Please enter the Driver Name...!')" oninput="this.setCustomValidity('')" />
                            </div>
                            <div class="form-group">
                                <label class="control-label">Driver Mobile Number <span class="err">*</span></label>
                                <input type="text" class="form-control" value="<?php echo $driver_mobile_num; ?>" name="driver_mobile_num" id="driver_mobile_num" placeholder="Enter the Driver Mobile Number" title="Enter the Driver Mobile Number" maxlength="20" autocomplete="off" required oninvalid="this.setCustomValidity('Please enter the Driver Mobile Number...!')" oninput="this.setCustomValidity('')" />    
                            </div>
                            <div class="form-group">
                                <label class="control-label">Owner Mobile Number <span class="err">*</span></label>
                                <input type="text" class="form-control" value="<?php echo $owner_mobile_num; ?>" name="owner_mobile_num" id="owner_mobile_num" placeholder="Enter the DeOwner Mobile Numberscription" title="Enter the Owner Mobile Number" maxlength="20" autocomplete="off" required oninvalid="this.setCustomValidity('Please enter the Owner Mobile Number...!')" oninput="this.setCustomValidity('')" />    
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
            <div class="col-md-8">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">List</h3>
                    </div>
                    <div class="box-body">
                        <div class="dt-responsive table-responsive">
                            <table id="example1" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th width="8">#</th>
                                        <th width="200" class="text-center">Vehicle Name</th>
                                        <th width="150" class="text-center">Number Plate</th>
                                        <th width="200" class="text-center">Driver Name</th>
                                        <th width="200" class="text-center">Driver Mobile Number</th>
                                        <th width="200" class="text-center">Owner Mobile Number</th>
                                        <th width="60" class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $rs = $con->query("SELECT * FROM tbl_transport where del_status = 0 order by id");
                                    if ($rs->rowCount()) {
                                        $sno=1;
                                        while ($obj = $rs->fetch(PDO::FETCH_OBJ)) {
                                    ?>
                                    <tr>
                                        <td class="text-center"><?php echo $sno; ?></td>
                                        <td><?php echo $obj->vehicle_name; ?></td>
                                        <td><?php echo $obj->vehicle_num_plate; ?></td>
                                        <td><?php echo $obj->driver_name; ?></td>
                                        <td><?php echo $obj->driver_mobile_num; ?></td>
                                        <td><?php echo $obj->owner_mobile_num; ?></td>
                                        <td class="text-center">
                                            <a href="transport.php?id=<?php echo $converter->encode($obj->id); ?>" title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i></a>&nbsp;&nbsp;
                                            <a href="transport.php?did=<?php echo $converter->encode($obj->id); ?>" title="Delete" onclick="return confirm('Are You Sure Want To Delete?');"><i class="fa fa-trash-o" aria-hidden="true"></i></a>
                                        </td>
                                    </tr>
                                    <?php
                                            $sno++;
                                        }
                                    } else {
                                    ?>
                                    <tr>
                                        <td colspan="7" align="center">--No Records Found--</td>
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
            { orderable: true, className: 'reorder', targets: [0,1,2,3,4] },
            { orderable: false, targets: '_all' }
        ]
    })
});
</script>

<script type="text/javascript">
$(document).ready(function() {
    $('#masterMainNav').addClass('active');
    $('#masterGstSubNav').addClass('active');
});
</script>
