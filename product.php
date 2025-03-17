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

        $opening_qty_date = date('Y-m-d',strtotime($_REQUEST["opening_qty_date"]));



        $path = "assets/pro_image/";
        $filename = $_FILES["p_img"]["name"];
        $extension  = pathinfo( $_FILES["p_img"]["name"], PATHINFO_EXTENSION ); // jpg
        $_REQUEST['p_img'] = trim($_REQUEST["p_code"])."_product_".trim($_REQUEST["p_name"]).'.'.$extension;
        move_uploaded_file($_FILES["p_img"]["tmp_name"], $path . $p_img);

        $stmt = null;
        $stmt = $con->prepare("INSERT INTO tbl_product (p_code, p_name, p_hsn, category_name, uom, sales_price, sales_gstfix, purchase_price, purchase_gstfix, gst, opening_qty, opening_qty_date, min_stock_maintain, location, p_img,created_by, created_dt) 
                        VALUES (:p_code, :p_name, :p_hsn, :category_name, :uom, :sales_price, :sales_gstfix, :purchase_price, :purchase_gstfix, :gst, :opening_qty, :opening_qty_date, :min_stock_maintain, :location, :p_img, :created_by, :created_dt)");
        $data = array(
            ":p_code" => trim($_REQUEST["p_code"]),
            ":p_name" => trim($_REQUEST["p_name"]),
            ":p_hsn" => trim($_REQUEST["p_hsn"]),
            ":category_name" => trim($_REQUEST["category_name"]),
            ":uom" => trim($_REQUEST["uom"]),
            ":sales_price" => trim($_REQUEST["sales_price"]),
            ":sales_gstfix" => trim($_REQUEST["sales_gstfix"]),
            ":purchase_price" => trim($_REQUEST["purchase_price"]),
            ":purchase_gstfix" => trim($_REQUEST["purchase_gstfix"]),
            ":gst" => trim($_REQUEST["gst"]),
            ":opening_qty" => trim($_REQUEST["opening_qty"]),
            ":opening_qty_date" => $opening_qty_date,
            ":min_stock_maintain" => trim($_REQUEST["min_stock_maintain"]),
            ":location" => trim ($_REQUEST["location"]),
            ":p_img" => $_REQUEST['p_img'],
            ":created_by" => $created_by,
            ":created_dt" => $created_dt,
        );
        // print_r($data);die;
        $stmt->execute($data);

        $_SESSION["msg"] = "Saved Successfully";
    } catch (Exception $e) {
        $str = filter_var($e->getMessage(), FILTER_SANITIZE_STRING);
        echo $_SESSION['msg_err'] = $str;
    }
    
    header("location: product_lst.php");
    die();
}
// //---------------------------------save/submit----------------------------------
// //---------------------------------update--------------------------------------
if (isset($_REQUEST["update"])) {
    try{
        

        $updated_by = $_SESSION["user_id"];
        $updated_dt = date('Y-m-d H:i:s');

        if($_FILES["p_img"]["tmp_name"] !=''){
            if($_REQUEST["hid_P_img"] !=''){
                unlink("assets/pro_image/".$_REQUEST["hid_P_img"]);
            }
            $path = "assets/pro_image/";
            $filename = $_FILES["p_img"]["name"];
            $extension  = pathinfo( $_FILES["p_img"]["name"], PATHINFO_EXTENSION );
            $_REQUEST['p_img'] = trim($_REQUEST["p_code"])."_product_".trim($_REQUEST["p_name"]).'.'.$extension;
            move_uploaded_file($_FILES["p_img"]["tmp_name"], $path . $_REQUEST['p_img']);

        }else{
            if($_REQUEST["hid_P_img_del"] !=''){
                unlink("assets/pro_image/".$_REQUEST["hid_P_img_del"]);
                $_REQUEST['p_img'] = null;
            }else{
                $_REQUEST['p_img'] = $_REQUEST["hid_P_img"];
            }
        }

        $opening_qty_date = date('Y-m-d',strtotime($_REQUEST["opening_qty_date"]));
        $stmt = null;
        $stmt = $con->prepare("UPDATE tbl_product SET p_code=:p_code,p_name=:p_name, p_hsn=:p_hsn, category_name=:category_name, uom=:uom, sales_price=:sales_price, sales_gstfix=:sales_gstfix, purchase_price=:purchase_price, purchase_gstfix=:purchase_gstfix, gst=:gst, 
        opening_qty=:opening_qty, opening_qty_date=:opening_qty_date, min_stock_maintain=:min_stock_maintain, location=:location,p_img=:p_img, updated_by=:updated_by, updated_dt=:updated_dt where id=:id");
        $data = array(
            ":p_code" => trim($_REQUEST["p_code"]),
            ":id" => trim($_REQUEST["hid_id"]),
            ":p_name" => trim($_REQUEST["p_name"]),
            ":p_hsn" => trim($_REQUEST["p_hsn"]),
            ":category_name" => trim($_REQUEST["category_name"]),
            ":uom" => trim($_REQUEST["uom"]),
            ":sales_price" => trim($_REQUEST["sales_price"]),
            ":sales_gstfix" => trim($_REQUEST["sales_gstfix"]),
            ":purchase_price" => trim($_REQUEST["purchase_price"]),
            ":purchase_gstfix" => trim($_REQUEST["purchase_gstfix"]),
            ":gst" => trim($_REQUEST["gst"]),
            ":opening_qty" => trim($_REQUEST["opening_qty"]),
            ":opening_qty_date" => $opening_qty_date,
            ":min_stock_maintain" => trim($_REQUEST["min_stock_maintain"]),
            ":location" => trim ($_REQUEST["location"]),
            ":p_img" => trim($_REQUEST['p_img']),
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

    header("location:product_lst.php");
    die();
}
//---------------------------------update----------------------------------------

// //---------------------------------edit----------------------------------------
if (isset($_REQUEST["id"])) {
    $rs = $con->query("SELECT * FROM tbl_product where id=" . $converter->decode($_REQUEST["id"]));
    if ($rs->rowCount()) {
        if ($obj = $rs->fetch(PDO::FETCH_OBJ)) {
            $id = $obj->id;
            $p_code = $obj->p_code;
            $p_name = $obj->p_name;
            $p_hsn = $obj->p_hsn;
            $category_name = $obj->category_name;
            $uom = $obj->uom;
            $sales_price = $obj->sales_price;
            $sales_gstfix = $obj->sales_gstfix;
            $purchase_price = $obj->purchase_price;
            $purchase_gstfix = $obj->purchase_gstfix;
            $gst = $obj->gst;
            $opening_qty = $obj->opening_qty;
            $opening_qty_date = $obj->opening_qty_date;
            $min_stock_maintain = $obj->min_stock_maintain;
            $location = $obj->location;
            $pro_imge =$obj->p_img;
        }
    }
}

if($pro_imge != '' || $pro_imge != null){
    $p_img = "assets/pro_image/".$obj->p_img;
}
else{
    $p_img = "assets/default_img/default_img.jpg";
}
//---------------------------------edit----------------------------------------
?>

<?php include("includes/header.php"); ?>
<?php include("includes/aside.php"); ?>
<style>
       
hr {
    border: 0;
    height: 1px;
    background: #e5e5dd;
}
    .input-group {
  display: flex;
  flex-wrap: wrap;
  align-items: stretch;
  width: 100%;
  position: relative;
}

.input-group-append {
  flex: 0 0 auto;
}

.input-group .form-control {
  flex: 1 1 auto;
  width: 1%;
  min-width: 0;
  position: relative;
  height: auto;
}

.input-group select {
  border-top-left-radius: 0;
  border-bottom-left-radius: 0;
  position: relative;
  width: 100%;
}
.marginborder{
    margin-bottom: 15px;
}
h3,hr {
    margin-top: 15px;
    margin-bottom: 10px;
    margin-left: 15px;
}


.image-preview-container {
    position: relative;
    width: 385px; /* Adjust as needed */
    height: 385px; /* Adjust as needed */
    border: 2px dashed #ccc;
    display: flex;
    justify-content: center;
    align-items: center;
    text-align: center;
    overflow: hidden; /* Ensure overflow is hidden */
    background-color: #f0f0f0; /* Optional: background color for container */
}

.image-preview {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    padding: 6px;
}

.image-preview img {
    width: 100%;
    height: 100%;
    
}

.remove-icon {
    position: absolute;
    top: 0px;
    right: 0px;
    width: 18px; /* Adjust size as needed */
    height: 18px;
    border-radius: 50%; /* Round shape */
    background-color: rgba(0, 0, 0, 0.5); /* Semi-transparent background */
    color: white; /* Text color */
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    font-size: 18px;
    font-weight: bold;
    text-align: center;
    line-height: 1;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
    z-index: 10; /* Ensure it's above other content */
}

.remove-icon:hover {
    background-color: rgba(255, 0, 0, 0.7); /* Change background color on hover */
}

.placeholder {
    position: absolute; /* Make sure it is positioned within the container */
    display: flex;
    align-items: center;
    justify-content: center;
    width: 100%;
    height: 100%;
    color: #aaa;
    font-size: 14px;
    text-align: center; 
}

</style>

<div class="content-wrapper">
    <section class="content-header">
        <h1>Product</h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-edit"></i> Masters</a></li>
            <li class="active">Product</li>
        </ol>
    </section>
    <section class="content">
        <div class="row">
            <!-------------------------------------------------- Form ------------------------------------------>
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <?php if (isset($_REQUEST["id"])) {
                            echo '<h3 class="box-title">Edit</h3>';
                        } else {
                            echo '<h3 class="box-title">Add</h3>';
                        } ?>
                        <div class="box-tools pull-right">
                            <a href="product_lst.php" class="btn btn-block btn-primary"><i class="fa fa-bars" aria-hidden="true"></i></a>
                        </div>
                    </div>
                    
                    <form id="thisForm" name="thisForm" action="product.php" method="post" enctype="multipart/form-data">
                        
                        <div class="box-body">
                        <h3>Details </h3>
                        <hr>
                            <div class="form-group col-md-4">
                                <label class="col-form-label">Product Code <span class="err">*</span></label>
                                <input type="text" class="form-control UPPERCASE" name="p_code" id="p_code" placeholder="Enter the Product Code" title="Enter the Product Code" required oninvalid="this.setCustomValidity('Please Enter the Product Code...!')" onchange="this.setCustomValidity('')" autocomplete="off" maxlength="20" value="<?php echo $p_code; ?>">
                            </div>
                            <div class="form-group col-md-4">
                                <label class="col-form-label">Name of the Product <span class="err">*</span></label>
                                <input type="text" class="form-control" name="p_name" id="p_name" placeholder="Enter the Product Name" title="Enter the Product Name" required oninvalid="this.setCustomValidity('Please Enter the Product Name...!')" onchange="this.setCustomValidity('')" autocomplete="off" maxlength="100" value="<?php echo $p_name; ?>">
                            </div>
                            <div class="form-group col-md-4">
                                <label class="col-form-label">HSN Code <span class="err">*</span></label>
                                <input type="text" class="form-control" name="p_hsn" id="p_hsn" placeholder="Enter the Product HSN" title="Enter the Product HSN" required oninvalid="this.setCustomValidity('Please Enter the Product HSN...!')" onchange="this.setCustomValidity('')" autocomplete="off" maxlength="20" value="<?php echo $p_hsn; ?>">
                            </div>
                            
                            <div class="form-group col-md-4">
                                <label>UOM <span class="err">*</span></label>
                                <select class="form-control select2" name="uom" id="uom" title="Select the UOM Name" required oninvalid="this.setCustomValidity('Please select the UOM name...!')" onchange="this.setCustomValidity('')">
                                    <?php
                                        echo '<option value=""></option>';
                                        echo $dbcon->fnFillComboFromTable_Where("id", "uom_name", "tbl_uom", "uom_name", " WHERE del_status = 0");
                                    ?>
                                </select>
                                <script>
                                    document.thisForm.uom.value = "<?php echo $uom; ?>"
                                </script>
                            </div>
                            <div class="form-group col-md-4">
                                <label>Category <span class="err">*</span></label>
                                <select class="form-control select2" name="category_name" id="category_name" title="Select the Category Name" required oninvalid="this.setCustomValidity('Please select the Category name...!')" onchange="this.setCustomValidity('')">
                                    <?php
                                        echo '<option value=""></option>';
                                        echo $dbcon->fnFillComboFromTable_Where("id", "category_name", "tbl_category", "category_name", " WHERE del_status = 0");
                                    ?>
                                </select>
                                <script>
                                    document.thisForm.category_name.value = "<?php echo $category_name; ?>"
                                </script>
                            </div>
                            <div class="clearfix"></div>
                            <h3 >Pricing</h3>
                                    <hr>
                            <div class="form-group">
                                <!-- <ul class="nav nav-pills pl-3">
                                    <li class="active"><a data-toggle="pill" href="#Pricing">Pricing</a></li>
                                    <li><a data-toggle="pill" href="#Stock">Stock</a></li>
                                </ul> -->
                                <!-- <div class="tab-content"> -->
                                    <div id="" class="form-group ">
                                        <div class="col-md-4">
                                            <label >Sale Price <span class="err">*</span></label>
                                            <div class="input-group">
                                                <input type="text" class="form-control" id="sales_price" name="sales_price" placeholder="Enter the Sale Price" title="Enter the Sale Price" required oninvalid="this.setCustomValidity('Please Enter the Sale Price...!')" onchange="this.setCustomValidity('')" value="<?php echo $sales_price ?>">
                                                <div class="input-group-append">
                                                    <select class="form-control select2" placeholder="Select the GST type" id="sales_gstfix" name="sales_gstfix" title="Select the Sale Price"  required oninvalid="this.setCustomValidity('Please select the Category name...!')" onchange="this.setCustomValidity('')">
                                                        <option value="">--- Select the option ---</option>
                                                        <option value="1">Including GST</option>
                                                        <option value="3">Excluding GST</option>
                                                    </select>
                                                    <script>
                                                        document.thisForm.sales_gstfix.value = "<?php echo $sales_gstfix; ?>"
                                                    </script>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4 marginborder">
                                            <label >Purchase Price <span class="err">*</span></label>
                                            <div class="input-group">
                                                <input type="text" class="form-control" id="purchase_price" name="purchase_price" placeholder="Enter the Purchase Price" title="Enter the Purchase Price" required oninvalid="this.setCustomValidity('Please Enter the Purchase Price...!')" onchange="this.setCustomValidity('')" value="<?php echo $purchase_price ?>">
                                                <div class="input-group-append">
                                                    <select class="form-control select2" placeholder="Select the GST type" id="purchase_gstfix" name="purchase_gstfix" title="Select the GST type" required oninvalid="this.setCustomValidity('Please Select the GST type...!')" onchange="this.setCustomValidity('')">
                                                        <option value="">--- Select the option ---</option>
                                                        <option value="1">Including GST</option>
                                                        <option value="3">Excluding GST</option>
                                                    </select>
                                                    <script>
                                                        document.thisForm.purchase_gstfix.value = "<?php echo $purchase_gstfix; ?>"
                                                    </script>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label>Tax Rate <span class="err">*</span></label>
                                            <select class="form-control select2" name="gst" id="gst" title="Select the Tax Rate" required oninvalid="this.setCustomValidity('Please select the Tax Rate...!')" onchange="this.setCustomValidity('')">
                                                <?php
                                                    echo '<option value=""></option>';
                                                    echo $dbcon->fnFillComboFromTable_Where("id", "descriptions", "tbl_gst", "descriptions", " WHERE del_status = 0");
                                                ?>
                                            </select>
                                            <script>
                                            document.thisForm.gst.value = "<?php echo $gst; ?>"
                                            </script>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                        <h3>Stock </h3>
                                    <hr>
                                    <div id="Stock" class="">
                                        <div class="form-group col-md-4">
                                            <label class="col-form-label">Opening Quality <span class="err">*</span></label>
                                            <input type="text" class="form-control" name="opening_qty" id="opening_qty" placeholder="Enter the Opening Quality" title="Enter the Opening Quality" autocomplete="off" maxlength="20" required oninvalid="this.setCustomValidity('Please Enter the Opening Quality...!')" onchange="this.setCustomValidity('')" value="<?php echo $opening_qty; ?>">
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label class="col-form-label">As of Date <span class="err">*</span></label>
                                            <input type="text" class="form-control date_picker" name="opening_qty_date" id="opening_qty_date" placeholder="Select the As of Date" title="Select the As of Date" autocomplete="off" required oninvalid="this.setCustomValidity('Please Select the As of Date...!')" onchange="this.setCustomValidity('')" value="<?php if(isset($_REQUEST["id"])){ echo date('d-m-Y',strtotime($opening_qty_date)); }?>">
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label class="col-form-label">Min. Stock To Maintain <span class="err">*</span></label>
                                            <input type="text" class="form-control" name="min_stock_maintain" id="min_stock_maintain" placeholder="Enter the Min. Stock To Maintain" title="Enter the Min. Stock To Maintain" autocomplete="off" maxlength="20" required oninvalid="this.setCustomValidity('Please Enter the Min. Stock To Maintain...!')" onchange="this.setCustomValidity('')" value="<?php echo $min_stock_maintain; ?>">
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label class="col-form-label">Location </label>
                                            <input type="text" class="form-control" name="location" id="location" placeholder="Enter the location" title="Enter the location" autocomplete="off" maxlength="100" value="<?php echo $location; ?>">
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                        <h3>Image </h3>
                                    <hr>
                                    <div class="form-group col-md-4">
                                        <div class="image-preview-container" onclick="$('#p_img').click()">
                                            <div id="imagePreview" class="image-preview">
                                                <img id="initialImage" src="<?php echo $p_img; ?>" class="initial-image">
                                                <div id="placeholder" class="placeholder" >Drop an image or click to upload</div>
                                            </div>
                                            <div id="removeIcon" class="remove-icon">×</div>
                                        </div>
                                        <input type="file" id="p_img" name="p_img" accept="image/*" style="display:none">

                                        <input type="hidden" value="<?php echo $pro_imge; ?>"  name="hid_P_img" id="hid_P_img">
                                        <input type="hidden" value="" name="hid_P_img_del" id="hid_P_img_del">

                                        
                                        <?php 
                                            if((isset($_REQUEST["id"])) && ($pro_imge != '' || $pro_imge != null)){
                                                echo'<div id="imageDetails" class="image-details">Name : '.$pro_imge.'</div>';
                                            }
                                            else{
                                                echo'<div id="imageDetails" class="image-details"></div>';
                                            }
                                        ?>
                                    <div id="imageDetails" class="image-details"></div>
                                </div>
                                <!-- </div> -->
                            </div>
                        </div>
                        <div class="box-footer">
                            <div class="pull-right">
                                <?php if (isset($_REQUEST["id"])) { ?>
                                <input type="hidden" value="<?php echo $id; ?>" name="hid_id" name="hid_id">
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
        
        </div>
    </section>
</div>
<?php include("includes/footer.php"); ?>
<script>

$(document).on('change','#p_code',function(){
    
    var p_code = $('#p_code').val(); 
    var id = $('#hid_id').val(); 
    $.ajax({
    method: "POST",
    url: "ajax/get_pro_code.php",
    data: { 'p_code': p_code,'id': id, mode: "product_code" }
    })
    .done(function( msg ) {
        // alert(msg);
        if(msg > 0){
            alert( p_code+" This Product Code Already Exist");
            $('#p_code').val('').focus();
        }
        
    });
});
$(document).ready(function() {

    if($("#initialImage").attr("src")){
        $("#removeIcon").show();
        $("#placeholder").hide();
    }else{
        $("#removeIcon").hide();
        $("#placeholder").show();
    }

    function showImagePreview(file) {
        let reader = new FileReader();
        reader.onload = function(e) {
            let image = new Image();
            image.src = e.target.result;
            image.onload = function() {
                $("#imagePreview").html('');
                $("#imagePreview").append(image);
                $("#removeIcon").show();
                $("#placeholder").hide();
            }
        }
        reader.readAsDataURL(file);
    }
    function resetImagePreview() {
        $("#imagePreview").html('<div id="placeholder" class="placeholder">Drop an image or click to upload</div>');
        $("#removeIcon").hide();
        $("#imageDetails").text("");
        $("#p_img").val("");
    }
    function validateFile(file) {
        var allowedExtensions = [".jpeg", ".png", ".webp", ".jpg"];
        var fileExtension = '.' + file.type.split('/')[1]; // Extract the file extension
        if (jQuery.inArray(fileExtension, allowedExtensions) === -1) {
            alert("Please upload a file type of jpeg, png, webp, or jpg.");
            return false;
        }
        if ((file.size / 1024).toFixed(2) > 500) {
            alert("Please upload a file size less than 500 KB.");
            return false;
        }
        return true;
    }
    $(document).on('change','#p_img',function(event){
        let file = this.files[0];
        if (file && validateFile(file)) {
            showImagePreview(file);
            $("#imageDetails").text(`File name: ${file.name}`);
        } 
        else {
            // resetImagePreview();
        }
    });

    $("#removeIcon").click(function(event) {
        event.stopPropagation();
        resetImagePreview();
        var hid_P_img = $('#hid_P_img').val();
        $('#hid_P_img_del').val(hid_P_img);
    });

    $("#imagePreview").on("dragover", function(event) {
        event.preventDefault();
        event.stopPropagation();
        $(this).addClass("drag-over");
    });

    $("#imagePreview").on("dragleave", function(event) {
        event.preventDefault();
        event.stopPropagation();
        $(this).removeClass("drag-over");
    });

    $("#imagePreview").on("drop", function(event) {
        event.preventDefault();
        event.stopPropagation();
        $(this).removeClass("drag-over");
        let files = event.originalEvent.dataTransfer.files;
        if (files.length > 0) {
            let file = files[0];
            if (validateFile(file)) {
                showImagePreview(file);
                $("#imageDetails").text(`File name: ${file.name}`);
                let dataTransfer = new DataTransfer();
                dataTransfer.items.add(file);
                $("#p_img")[0].files = dataTransfer.files;
                $("#p_img").trigger("change");
            } 
            else {
            }
        }
    });
});


</script>

<script type="text/javascript">
$(document).ready(function() {
    $('#masterMainNav').addClass('active');
    $('#masterCustomerSubNav').addClass('active');
});
</script>
