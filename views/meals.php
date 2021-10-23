<?php
/*
 * Created on Sat Oct 23 2021
 *
 *  MartDevelopers Inc - martdev.info 
 *
 * mail@martdev.info
 *
 * +254 740 847 563
 *
 * The MartDevelopers Inc End User License Agreement
 *
 * Copyright (c) 2021 MartDevelopers Inc
 *
 * 1. GRANT OF LICENSE
 * MartDevelopers Inc hereby grants to you (an individual) the revocable, personal, non-exclusive, and nontransferable right to
 * install and activate this system on two separated computers solely for your personal and non-commercial use,
 * unless you have purchased a commercial license from MartDevelopers. Sharing this Software with other individuals, 
 * or allowing other individuals to view the contents of this Software, is in violation of this license.
 * You may not make the Software available on a network, or in any way provide the Software to multiple users
 * unless you have first purchased at least a multi-user license from MartDevelopers.
 *
 * 2. COPYRIGHT 
 * The Software is owned by MartDevelopers and protected by copyright law and international copyright treaties. 
 * You may not remove or conceal any proprietary notices, labels or marks from the Software.
 *
 * 3. RESTRICTIONS ON USE
 * You may not, and you may not permit others to
 * (a) reverse engineer, decompile, decode, decrypt, disassemble, or in any way derive source code from, the Software;
 * (b) modify, distribute, or create derivative works of the Software;
 * (c) copy (other than one back-up copy), distribute, publicly display, transmit, sell, rent, lease or 
 * otherwise exploit the Software.  
 *
 * 4. TERM
 * This License is effective until terminated. 
 * You may terminate it at any time by destroying the Software, together with all copies thereof.
 * This License will also terminate if you fail to comply with any term or condition of this Agreement.
 * Upon such termination, you agree to destroy the Software, together with all copies thereof.
 *
 * 5. NO OTHER WARRANTIES. 
 * MartDevelopers Inc  DOES NOT WARRANT THAT THE SOFTWARE IS ERROR FREE. 
 * MartDevelopers Inc SOFTWARE DISCLAIMS ALL OTHER WARRANTIES WITH RESPECT TO THE SOFTWARE, 
 * EITHER EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO IMPLIED WARRANTIES OF MERCHANTABILITY, 
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT OF THIRD PARTY RIGHTS. 
 * SOME JURISDICTIONS DO NOT ALLOW THE EXCLUSION OF IMPLIED WARRANTIES OR LIMITATIONS
 * ON HOW LONG AN IMPLIED WARRANTY MAY LAST, OR THE EXCLUSION OR LIMITATION OF 
 * INCIDENTAL OR CONSEQUENTIAL DAMAGES,
 * SO THE ABOVE LIMITATIONS OR EXCLUSIONS MAY NOT APPLY TO YOU. 
 * THIS WARRANTY GIVES YOU SPECIFIC LEGAL RIGHTS AND YOU MAY ALSO 
 * HAVE OTHER RIGHTS WHICH VARY FROM JURISDICTION TO JURISDICTION.
 *
 * 6. SEVERABILITY
 * In the event of invalidity of any provision of this license, the parties agree that such invalidity shall not
 * affect the validity of the remaining portions of this license.
 *
 * 7. NO LIABILITY FOR CONSEQUENTIAL DAMAGES IN NO EVENT SHALL MartDevelopers Inc  OR ITS SUPPLIERS BE LIABLE TO YOU FOR ANY
 * CONSEQUENTIAL, SPECIAL, INCIDENTAL OR INDIRECT DAMAGES OF ANY KIND ARISING OUT OF THE DELIVERY, PERFORMANCE OR 
 * USE OF THE SOFTWARE, EVEN IF MartDevelopers Inc HAS BEEN ADVISED OF THE POSSIBILITY OF SUCH DAMAGES
 * IN NO EVENT WILL MartDevelopers Inc  LIABILITY FOR ANY CLAIM, WHETHER IN CONTRACT 
 * TORT OR ANY OTHER THEORY OF LIABILITY, EXCEED THE LICENSE FEE PAID BY YOU, IF ANY.
 */

session_start();
require_once('../config/config.php');
require_once('../config/checklogin.php');
require_once('../config/codeGen.php');
checklogin();

/* Handle Add Meal */
if (isset($_POST['add_meal'])) {
    $meal_id = $sys_gen_id;
    $meal_category_id = $_POST['meal_category_id'];
    $meal_name = $_POST['meal_name'];
    $meal_details = $_POST['meal_details'];
    $meal_price = $_POST['meal_price'];

    /* Check If Theres Another Meal With These Records Which Match */
    $sql = "SELECT * FROM  meals  WHERE meal_name ='$meal_name' ";
    $res = mysqli_query($mysqli, $sql);
    if (mysqli_num_rows($res) > 0) {
        $row = mysqli_fetch_assoc($res);
        if ($meal_name == $row['meal_name']) {
            $err = 'A Meal  With This Name Already Exists';
        }
    } else {
        /* Insert This Data */
        $insert = "INSERT INTO meals (meal_id, meal_category_id, meal_name, meal_details, meal_price) VALUES(?,?,?,?,?)";
        $insert_stmt = $mysqli->prepare($insert);
        $insert_rc  = $insert_stmt->bind_param('sssss', $meal_id, $meal_category_id, $meal_name, $meal_details, $meal_price);
        $insert_stmt->execute();
        if ($insert_stmt) {
            $success = "$meal_name, Added";
        } else {
            $err = "Failed!, Please Try Again Later";
        }
    }
}


/* Handle  Meal Update  */
if (isset($_POST['update_meal'])) {
    $meal_id = $_POST['meal_id'];
    $meal_name  = $_POST['meal_name'];
    $meal_details = $_POST['meal_details'];
    $meal_price = $_POST['meal_price'];

    /* Persist Update On Details */
    $update_sql = "UPDATE meals SET meal_name =?, meal_details = ?, meal_price=?  WHERE meal_id = ?";
    $update_sql_stmt = $mysqli->prepare($update_sql);
    $update_rc = $update_sql_stmt->bind_param('ssss', $meal_name, $meal_details,  $meal_price, $meal_id);
    $update_sql_stmt->execute();

    if ($update_sql_stmt) {
        $success = "$meal_name, Updated";
    } else {
        $err = "Failed!, Please Try Again Later";
    }
}

/* Update Meal Image */
if (isset($_POST['update_meal_image'])) {
    $meal_id = $_POST['meal_id'];
    $temp = explode('.', $_FILES['meal_img']['name']);
    $newfilename = 'Meal_IMG_' . (round(microtime(true)) . '.' . end($temp));
    move_uploaded_file(
        $_FILES['meal_img']['tmp_name'],
        '../public/backend_assets/images/' . $newfilename
    );

    /* Persist This Change To Database */
    $sql = "UPDATE meals SET meal_img = ? WHERE meal_id =?";
    $sql_stmt = $mysqli->prepare($sql);
    $sql_rc = $sql_stmt->bind_param('ss', $newfilename, $meal_id);
    $sql_stmt->execute();
    if ($sql_stmt) {
        $success = "Image Uploaded";
    } else {
        $err = "Failed!, Please Try Again Later";
    }
}

/* Handle Delete Meal */
if (isset($_GET['delete'])) {
    $delete = $_GET['delete'];
    /* Wipe This MF */
    $delete_sql = "DELETE FROM meals WHERE meal_id = '$delete'";
    $delete_sql_stmt = $mysqli->prepare($delete_sql);
    $delete_sql_stmt->execute();
    if ($delete_sql_stmt) {
        $success = "Deleted" && header("refresh:1; url=meals");
    } else {
        $err  = "Failed!, Please Try Again Later";
    }
}
require_once('../partials/head.php');

?>

<body class="nk-body bg-lighter npc-general has-sidebar ">
    <div class="nk-app-root">
        <!-- main @s -->
        <div class="nk-main ">
            <!-- sidebar @s -->
            <?php require_once('../partials/sidebar.php'); ?>
            <!-- sidebar @e -->
            <!-- wrap @s -->
            <div class="nk-wrap ">
                <!-- main header @s -->
                <?php require_once('../partials/header.php'); ?>
                <!-- main header @e -->
                <!-- content @s -->
                <div class="nk-content ">
                    <div class="container-fluid">
                        <div class="nk-content-inner">
                            <div class="nk-content-body">
                                <div class="nk-block-head nk-block-head-sm">
                                    <div class="nk-block-between">
                                        <div class="nk-block-head-content">
                                            <h3 class="nk-block-title page-title">Meals</h3>
                                            <div class="nk-block-des text-soft">
                                                <nav>
                                                    <ul class="breadcrumb breadcrumb-arrow">
                                                        <li class="breadcrumb-item"><a href="dashboard">Home</a></li>
                                                        <li class="breadcrumb-item active">Meals</li>
                                                    </ul>
                                                </nav>
                                            </div>
                                        </div><!-- .nk-block-head-content -->

                                        <div class="nk-block-head-content">
                                            <div class="toggle-wrap nk-block-tools-toggle">
                                                <a href="#" class="btn btn-icon btn-trigger toggle-expand mr-n1" data-target="pageMenu"><em class="icon ni ni-menu-alt-r"></em></a>
                                                <div class="toggle-expand-content" data-content="pageMenu">
                                                    <ul class="nk-block-tools g-3">
                                                        <li><a href="#add_modal" data-toggle="modal" class="btn btn-white btn-outline-light"><em class="icon ni ni-plus"></em><span>Add Meal</span></a></li>
                                                    </ul>
                                                </div>
                                            </div><!-- .toggle-wrap -->
                                        </div><!-- .nk-block-head-content -->
                                        <!-- Add Modal -->
                                        <div class="modal fade" id="add_modal">
                                            <div class="modal-dialog  modal-lg">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h4 class="modal-title">Register New Meal</h4>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form method="post" enctype="multipart/form-data" role="form">
                                                            <div class="card-body">
                                                                <div class="row">
                                                                    <div class="form-group col-md-4">
                                                                        <label for="">Meal Name</label>
                                                                        <input type="text" required name="meal_name" class="form-control">
                                                                    </div>
                                                                    <div class="form-group col-md-4">
                                                                        <label for="">Category Name</label>
                                                                        <select name="meal_category_id" class="form-select form-control form-control-lg" data-search="on">
                                                                            <?php
                                                                            $ret = "SELECT * FROM meal_categories ORDER BY category_name ASC";
                                                                            $stmt = $mysqli->prepare($ret);
                                                                            $stmt->execute(); //ok
                                                                            $res = $stmt->get_result();
                                                                            while ($category = $res->fetch_object()) {
                                                                            ?>
                                                                                <option value="<?php echo $category->category_id; ?>"><?php echo $category->category_name; ?></option>
                                                                            <?php } ?>
                                                                        </select>
                                                                    </div>
                                                                    <div class="form-group col-md-4">
                                                                        <label for="">Meal Price(Ksh)</label>
                                                                        <input type="text" required name="meal_price" class="form-control">
                                                                    </div>
                                                                    <div class="form-group col-md-12">
                                                                        <label for="">Meal Details</label>
                                                                        <textarea type="text" rows="2" required name="meal_details" class="form-control"></textarea>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="text-right">
                                                                <button type="submit" name="add_meal" class="btn btn-primary">Submit</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- End Modal -->
                                    </div><!-- .nk-block-between -->
                                </div><!-- .nk-block-head -->
                                <div class="nk-block">
                                    <div class="card card-bordered card-stretch">
                                        <div class="card-inner-group">
                                            <div class="card-preview">
                                                <div class="card-inner">
                                                    <table class="datatable-init nk-tb-list nk-tb-ulist" data-auto-responsive="false">
                                                        <thead>
                                                            <tr class="nk-tb-item nk-tb-head">
                                                                <th class="nk-tb-col"><span class="sub-text">Meal Name</span></th>
                                                                <th class="nk-tb-col tb-col-mb"><span class="sub-text">Meal Category</span></th>
                                                                <th class="nk-tb-col tb-col-md"><span class="sub-text">Meal Price (Ksh)</span></th>
                                                                <th class="nk-tb-col nk-tb-col-tools text-right">
                                                                    <span class="sub-text">Action</span>
                                                                </th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                            /* Pop This Partial With All Meals */
                                                            $ret = "SELECT * FROM meals m
                                                            INNER JOIN meal_categories mc 
                                                            ON mc.category_id = m.meal_category_id";
                                                            $stmt = $mysqli->prepare($ret);
                                                            $stmt->execute(); //ok
                                                            $res = $stmt->get_result();
                                                            while ($meals = $res->fetch_object()) {
                                                            ?>
                                                                <tr class="nk-tb-item">
                                                                    <td class="nk-tb-col">
                                                                        <div class="user-card">
                                                                            <div class="user-avatar bg-dim-primary d-none d-sm-flex">
                                                                                <span><?php echo substr($meals->meal_name, 0, 2); ?></span>
                                                                            </div>
                                                                            <div class="user-info">
                                                                                <span class="tb-lead"><?php echo $meals->meal_name; ?></span>
                                                                            </div>
                                                                        </div>
                                                                    </td>
                                                                    <td class="nk-tb-col tb-col-mb">
                                                                        <span class="tb-amount"><?php echo $meals->category_name; ?></span>
                                                                    </td>
                                                                    <td class="nk-tb-col tb-col-md">
                                                                        <span>Ksh <?php echo $meals->meal_price; ?></span>
                                                                    </td>

                                                                    <td class="nk-tb-col nk-tb-col-tools">
                                                                        <ul class="nk-tb-actions gx-1">
                                                                            <li>
                                                                                <div class="drodown">
                                                                                    <a href="#" class="dropdown-toggle btn btn-icon btn-trigger" data-toggle="dropdown"><em class="icon ni ni-more-h"></em></a>
                                                                                    <div class="dropdown-menu dropdown-menu-right">
                                                                                        <ul class="link-list-opt no-bdr">
                                                                                            <li><a href="meal?view=<?php echo $meals->meal_id; ?>"><em class="icon ni ni-focus"></em><span>Meal Details</span></a></li>
                                                                                            <li><a data-toggle="modal" href="#image-<?php echo $meals->meal_id; ?>"><em class="icon ni ni-file-img"></em><span>Add Image</span></a></li>
                                                                                            <li><a data-toggle="modal" href="#update-<?php echo $meals->meal_id; ?>"><em class="icon ni ni-edit"></em><span>Update Meal</span></a></li>
                                                                                            <li><a data-toggle="modal" href="#delete-<?php echo $meals->meal_id; ?>"><em class="icon ni ni-trash"></em><span>Delete Meal</span></a></li>
                                                                                        </ul>
                                                                                    </div>
                                                                                </div>
                                                                            </li>
                                                                        </ul>
                                                                    </td>
                                                                    <!-- Edit Profile Modal -->
                                                                    <div class="modal fade" id="update-<?php echo $meals->meal_id; ?>">
                                                                        <div class="modal-dialog  modal-lg">
                                                                            <div class="modal-content">
                                                                                <div class="modal-header">
                                                                                    <h4 class="modal-title">Update <?php echo $meals->meal_name; ?> Details</h4>
                                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                        <span aria-hidden="true">&times;</span>
                                                                                    </button>
                                                                                </div>
                                                                                <div class="modal-body">
                                                                                    <form method="post" enctype="multipart/form-data" role="form">
                                                                                        <div class="card-body">
                                                                                            <div class="row">
                                                                                                <div class="form-group col-md-6">
                                                                                                    <label for="">Meal Name</label>
                                                                                                    <input type="text" required name="meal_name" value="<?php echo $meals->meal_name; ?>" class="form-control">
                                                                                                    <input type="hidden" required name="meal_id" value="<?php echo $meals->meal_id; ?>" class="form-control">
                                                                                                </div>

                                                                                                <div class="form-group col-md-6">
                                                                                                    <label for="">Meal Price(Ksh)</label>
                                                                                                    <input type="text" required value="<?php echo $meals->meal_price; ?>" name="meal_price" class="form-control">
                                                                                                </div>
                                                                                                <div class="form-group col-md-12">
                                                                                                    <label for="">Meal Details</label>
                                                                                                    <textarea type="text" rows="2" required name="meal_details" class="form-control"><?php echo $meals->meal_details; ?></textarea>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="text-right">
                                                                                            <button type="submit" name="update_meal" class="btn btn-primary">Submit</button>
                                                                                        </div>
                                                                                    </form>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <!-- End Modal -->

                                                                    <!-- Delete Modal -->
                                                                    <div class="modal fade" id="delete-<?php echo $meals->meal_id; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                                                            <div class="modal-content">
                                                                                <div class="modal-header">
                                                                                    <h5 class="modal-title" id="exampleModalLabel">CONFIRM DELETION</h5>
                                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                        <span aria-hidden="true">&times;</span>
                                                                                    </button>
                                                                                </div>
                                                                                <div class="modal-body text-center text-danger">
                                                                                    <h4>Delete <?php echo $meals->meal_name; ?> Details ?</h4>
                                                                                    <br>
                                                                                    <p>Heads Up, You are about to delete <?php echo $meals->meal_name; ?> Details. This action is irrevisble.</p>
                                                                                    <button type="button" class="text-center btn btn-success" data-dismiss="modal">No</button>
                                                                                    <a href="meals?delete=<?php echo $meals->meal_id; ?>" class="text-center btn btn-danger"> Delete </a>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <!-- End Modal -->

                                                                    <!--Add Image Modal -->
                                                                    <div class="modal fade" id="image-<?php echo $meals->meal_id; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                                                            <div class="modal-content">
                                                                                <div class="modal-header">
                                                                                    <h5 class="modal-title" id="exampleModalLabel">Upload Meal Photo</h5>
                                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                        <span aria-hidden="true">&times;</span>
                                                                                    </button>
                                                                                </div>
                                                                                <div class="modal-body">
                                                                                    <form method="post" enctype="multipart/form-data" role="form">
                                                                                        <div class="card-body">
                                                                                            <div class="row">
                                                                                                <div class="form-group col-md-12">
                                                                                                    <div class="custom-file">
                                                                                                        <input type="file" accept=".png, .jpeg, .jpg" name="meal_img" required multiple class="custom-file-input" id="customFile">
                                                                                                        <label class="custom-file-label" for="customFile">Choose file</label>
                                                                                                    </div>
                                                                                                    <input type="hidden" required name="meal_id" value="<?php echo $meals->meal_id; ?>" class="form-control">
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="text-right">
                                                                                            <button type="submit" name="update_meal_image" class="btn btn-primary">Submit</button>
                                                                                        </div>
                                                                                    </form>

                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <!-- End Modal -->
                                                                </tr>
                                                            <?php } ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div><!-- .card -->
                                    </div><!-- .nk-block -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- content @e -->
                <!-- footer @s -->
                <?php require_once('../partials/footer.php'); ?>
                <!-- footer @e -->
            </div>
            <!-- wrap @e -->
        </div>
        <!-- main @e -->
    </div>
    <!-- Js -->
    <?php require_once('../partials/scripts.php'); ?>
</body>

</html>