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

/* Handle Add Cashier */
if (isset($_POST['add_cashier'])) {
    $user_id = $sys_gen_id;
    $user_number = $_POST['user_number'];
    $user_name = $_POST['user_name'];
    $user_email = $_POST['user_email'];
    $user_phone_no = $_POST['user_phone_no'];
    $user_password = sha1(md5($_POST['user_password']));
    $confirm_password = sha1(md5($_POST['confirm_password']));
    $user_access_level = 'cashier';
    $user_date_created = date('d M Y');
    /* Check If These MFs Match */
    if ($user_password != $confirm_password) {
        $err = "Passwords Does Not Match";
    } else {
        /* Check If Theres Another Cashier With These Records Which Match */
        $sql = "SELECT * FROM  users  WHERE user_phone_no ='$user_phone_no' || user_email = '$user_email' ";
        $res = mysqli_query($mysqli, $sql);
        if (mysqli_num_rows($res) > 0) {
            $row = mysqli_fetch_assoc($res);
            if ($user_phone_no == $row['user_phone_no'] || $user_email == $row['user_email']) {
                $err = 'A Cashier Account With That Phone Number Or Email  Already Exists';
            }
        } else {
            /* Insert This Data */
            $insert = "INSERT INTO users (user_id, user_number, user_name, user_email, user_phone_no, user_password, user_access_level, user_date_created)
            VALUES(?,?,?,?,?,?,?,?)";
            $insert_stmt = $mysqli->prepare($insert);
            $insert_rc  = $insert_stmt->bind_param('ssssssss', $user_id, $user_number, $user_name, $user_email, $user_phone_no, $user_password, $user_access_level, $user_date_created);
            $insert_stmt->execute();
            if ($insert_stmt) {
                $success = "$user_name, Account Created";
            } else {
                $err = "Failed!, Please Try Again Later";
            }
        }
    }
}

/* Handle Update Cashier */
if (isset($_POST['update_cashier'])) {
    $user_id = $_POST['user_id'];
    $user_name  = $_POST['user_name'];
    $user_phone_no = $_POST['user_phone_no'];
    $user_email = $_POST['user_email'];

    /* Persist Update On Details */
    $update_sql = "UPDATE users SET user_name =?, user_email =?, user_phone_no =? WHERE user_id = ?";
    $update_sql_stmt = $mysqli->prepare($update_sql);
    $update_rc = $update_sql_stmt->bind_param('ssss', $user_name, $user_email, $user_phone_no, $user_id);
    $update_sql_stmt->execute();

    if ($update_sql_stmt) {
        $success = "$user_name, Account Updated";
    } else {
        $err = "Failed!, Please Try Again Later";
    }
}

/* Update Password */
if (isset($_POST['update_password'])) {
    $user_id = $_POST['user_id'];
    $new_password = sha1(md5($_POST['new_password']));
    $confirm_password = sha1(md5($_POST['confirm_password']));
    /* Check If They Match */
    if ($confirm_password != $new_password) {
        $err = "Passwords Does Not Match";
    } else {
        /* Persist Password Update */
        $update = "UPDATE users SET user_password = ? WHERE user_id =?";
        $update_stmt = $mysqli->prepare($update);
        $update_rc = $update_stmt->bind_param('ss', $confirm_password, $user_id);
        $update_stmt->execute();
        if ($update_stmt) {
            $success = "Password Updated";
        } else {
            $err = "Failed!, Please Try Again Later";
        }
    }
}

/* Handle Delete Cashier */
if (isset($_POST['delete'])) {
    $user_id = $_POST['user_id'];
    /* Wipe This MF */
    $delete_sql = "DELETE FROM users WHERE user_id = '$user_id'";
    $delete_sql_stmt = $mysqli->prepare($delete_sql);
    $delete_sql_stmt->execute();
    if ($delete_sql_stmt) {
        $success = "Cashier Details Deleted";
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
                                            <h3 class="nk-block-title page-title">Cashiers</h3>
                                            <div class="nk-block-des text-soft">
                                                <nav>
                                                    <ul class="breadcrumb breadcrumb-arrow">
                                                        <li class="breadcrumb-item"><a href="dashboard">Home</a></li>
                                                        <li class="breadcrumb-item active">Cashiers</li>
                                                    </ul>
                                                </nav>
                                            </div>
                                        </div><!-- .nk-block-head-content -->

                                        <div class="nk-block-head-content">
                                            <div class="toggle-wrap nk-block-tools-toggle">
                                                <a href="#" class="btn btn-icon btn-trigger toggle-expand mr-n1" data-target="pageMenu"><em class="icon ni ni-menu-alt-r"></em></a>
                                                <div class="toggle-expand-content" data-content="pageMenu">
                                                    <ul class="nk-block-tools g-3">
                                                        <li><a href="#add_modal" data-toggle="modal" class="btn btn-white btn-outline-light"><em class="icon ni ni-user-add"></em><span>Add New Cashier</span></a></li>
                                                    </ul>
                                                </div>
                                            </div><!-- .toggle-wrap -->
                                        </div><!-- .nk-block-head-content -->
                                        <!-- Add Modal -->
                                        <div class="modal fade" id="add_modal">
                                            <div class="modal-dialog  modal-lg">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h4 class="modal-title">Register New Cashier</h4>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form method="post" enctype="multipart/form-data" role="form">
                                                            <div class="card-body">
                                                                <div class="row">
                                                                    <div class="form-group col-md-8">
                                                                        <label for="">Full Name</label>
                                                                        <input type="text" required name="user_name" class="form-control">
                                                                    </div>
                                                                    <div class="form-group col-md-4">
                                                                        <label for="">User Number</label>
                                                                        <input type="text" readonly value="<?php echo $a . $b; ?>" required name="user_number" class="form-control">
                                                                    </div>
                                                                    <div class="form-group col-md-6">
                                                                        <label for="">Email Address</label>
                                                                        <input type="text" required name="user_email" class="form-control">
                                                                    </div>
                                                                    <div class="form-group col-md-6">
                                                                        <label for="">Phone Number</label>
                                                                        <input type="text" required name="user_phone_no" class="form-control">
                                                                    </div>
                                                                    <div class="form-group col-md-6">
                                                                        <label for="">Login Password</label>
                                                                        <input type="password" required name="user_password" class="form-control">
                                                                    </div>
                                                                    <div class="form-group col-md-6">
                                                                        <label for="">Confirm Login Password</label>
                                                                        <input type="password" required name="confirm_password" class="form-control">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="text-right">
                                                                <button type="submit" name="add_cashier" class="btn btn-primary">Submit</button>
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
                                            <div class="card card-bordered card-preview">
                                                <div class="card-inner">
                                                    <table class="datatable-init nk-tb-list nk-tb-ulist" data-auto-responsive="false">
                                                        <thead>
                                                            <tr class="nk-tb-item nk-tb-head">
                                                                <th class="nk-tb-col"><span class="sub-text">Full Name</span></th>
                                                                <th class="nk-tb-col tb-col-mb"><span class="sub-text">Cashier Number</span></th>
                                                                <th class="nk-tb-col tb-col-md"><span class="sub-text">Contacts</span></th>
                                                                <th class="nk-tb-col tb-col-lg"><span class="sub-text">Email Address</span></th>
                                                                <th class="nk-tb-col tb-col-lg"><span class="sub-text">Date Created</span></th>
                                                                <th class="nk-tb-col nk-tb-col-tools text-right">
                                                                    <span class="sub-text">Action</span>
                                                                </th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                            /* Pop All Cashiers */
                                                            $ret = "SELECT * FROM  users WHERE user_access_level  = 'cashier'  ";
                                                            $stmt = $mysqli->prepare($ret);
                                                            $stmt->execute(); //ok
                                                            $res = $stmt->get_result();
                                                            while ($cashiers = $res->fetch_object()) {
                                                            ?>
                                                                <tr class="nk-tb-item">
                                                                    <td class="nk-tb-col">
                                                                        <div class="user-card">
                                                                            <div class="user-avatar bg-dim-primary d-none d-sm-flex">
                                                                                <span><?php echo substr($cashiers->user_name, 0, 2); ?></span>
                                                                            </div>
                                                                            <div class="user-info">
                                                                                <span class="tb-lead"><?php echo $cashiers->user_name; ?></span>
                                                                            </div>
                                                                        </div>
                                                                    </td>
                                                                    <td class="nk-tb-col tb-col-mb">
                                                                        <span class="tb-amount"><?php echo $cashiers->user_number; ?></span>
                                                                    </td>
                                                                    <td class="nk-tb-col tb-col-md">
                                                                        <span><?php echo $cashiers->user_phone_no; ?></span>
                                                                    </td>
                                                                    <td class="nk-tb-col tb-col-lg">
                                                                        <?php echo $cashiers->user_email; ?>
                                                                    </td>
                                                                    <td class="nk-tb-col tb-col-lg">
                                                                        <span><?php echo $cashiers->user_date_created; ?></span>
                                                                    </td>

                                                                    <td class="nk-tb-col nk-tb-col-tools">
                                                                        <ul class="nk-tb-actions gx-1">
                                                                            <li>
                                                                                <div class="drodown">
                                                                                    <a href="#" class="dropdown-toggle btn btn-icon btn-trigger" data-toggle="dropdown"><em class="icon ni ni-more-h"></em></a>
                                                                                    <div class="dropdown-menu dropdown-menu-right">
                                                                                        <ul class="link-list-opt no-bdr">
                                                                                            <li><a data-toggle="modal" href="#change-password-<?php echo $cashiers->user_id; ?>"><em class="icon ni ni-lock"></em><span>Change Password</span></a></li>
                                                                                            <li><a data-toggle="modal" href="#update-<?php echo $cashiers->user_id; ?>"><em class="icon ni ni-edit"></em><span>Update Profile</span></a></li>
                                                                                            <li><a data-toggle="modal" href="#delete-<?php echo $cashiers->user_id; ?>"><em class="icon ni ni-trash"></em><span>Delete Account</span></a></li>
                                                                                        </ul>
                                                                                    </div>
                                                                                </div>
                                                                            </li>
                                                                        </ul>
                                                                    </td>
                                                                    <!-- Edit Profile Modal -->
                                                                    <div class="modal fade" id="update-<?php echo $cashiers->user_id; ?>">
                                                                        <div class="modal-dialog  modal-lg">
                                                                            <div class="modal-content">
                                                                                <div class="modal-header">
                                                                                    <h4 class="modal-title">Update <?php echo $cashiers->user_name; ?> Details</h4>
                                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                        <span aria-hidden="true">&times;</span>
                                                                                    </button>
                                                                                </div>
                                                                                <div class="modal-body">
                                                                                    <form method="post" enctype="multipart/form-data" role="form">
                                                                                        <div class="card-body">
                                                                                            <div class="row">
                                                                                                <div class="form-group col-md-8">
                                                                                                    <label for="">Full Name</label>
                                                                                                    <input type="text" required name="user_name" value="<?php echo $cashiers->user_name; ?>" class="form-control">
                                                                                                    <input type="hidden" required name="user_id" value="<?php echo $cashiers->user_id; ?>" class="form-control">
                                                                                                </div>
                                                                                                <div class="form-group col-md-4">
                                                                                                    <label for="">User Number</label>
                                                                                                    <input type="text" readonly value="<?php echo $cashiers->user_number; ?>" required name="user_number" class="form-control">
                                                                                                </div>
                                                                                                <div class="form-group col-md-6">
                                                                                                    <label for="">Email Address</label>
                                                                                                    <input type="text" required value="<?php echo $cashiers->user_email; ?>" name="user_email" class="form-control">
                                                                                                </div>
                                                                                                <div class="form-group col-md-6">
                                                                                                    <label for="">Phone Number</label>
                                                                                                    <input type="text" required value="<?php echo $cashiers->user_phone_no; ?>" name="user_phone_no" class="form-control">
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="text-right">
                                                                                            <button type="submit" name="update_cashier" class="btn btn-primary">Submit</button>
                                                                                        </div>
                                                                                    </form>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <!-- End Modal -->

                                                                    <!-- Delete Modal -->
                                                                    <div class="modal fade" id="delete-<?php echo $cashiers->user_id; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                                                            <div class="modal-content">
                                                                                <div class="modal-header">
                                                                                    <h5 class="modal-title" id="exampleModalLabel">CONFIRM DELETION</h5>
                                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                        <span aria-hidden="true">&times;</span>
                                                                                    </button>
                                                                                </div>
                                                                                <div class="modal-body text-center text-danger">
                                                                                    <form method="POST">
                                                                                        <h4>Delete <?php echo $cashiers->user_name; ?> Details ?</h4>
                                                                                        <br>
                                                                                        <p>Heads Up, You are about to delete <?php echo $cashiers->user_name; ?> Details. This action is irrevisble.</p>
                                                                                        <button type="button" class="text-center btn btn-success" data-dismiss="modal">No</button>
                                                                                        <input type="hidden" name="user_id" value="<?php echo $cashiers->user_id; ?>">
                                                                                        <input type="submit" name="delete" value="Delete" class="text-center btn btn-danger">
                                                                                    </form>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <!-- End Modal -->
                                                                    <!-- Change Password Modal -->
                                                                    <div class="modal fade" id="change-password-<?php echo $cashiers->user_id; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                                                            <div class="modal-content">
                                                                                <div class="modal-header">
                                                                                    <h5 class="modal-title" id="exampleModalLabel">Change <?php echo $cashiers->user_name; ?> Password</h5>
                                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                        <span aria-hidden="true">&times;</span>
                                                                                    </button>
                                                                                </div>
                                                                                <div class="modal-body">
                                                                                    <form method="post" enctype="multipart/form-data" role="form">
                                                                                        <div class="card-body">
                                                                                            <div class="row">
                                                                                                <div class="form-group col-md-12">
                                                                                                    <label for="">New Password</label>
                                                                                                    <input type="password" required name="new_password" class="form-control">
                                                                                                    <input type="hidden" required name="user_id" value="<?php echo $cashiers->user_id; ?>" class="form-control">
                                                                                                </div>
                                                                                                <div class="form-group col-md-12">
                                                                                                    <label for="">Confirm New Password</label>
                                                                                                    <input type="password" name="confirm_password" required class="form-control">
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="text-right">
                                                                                            <button type="submit" name="update_password" class="btn btn-primary">Submit</button>
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