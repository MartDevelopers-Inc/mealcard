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

/* Add Meal Card */
if (isset($_POST['new_card'])) {
    $card_id  = $sys_gen_id;
    $card_owner_id = $_POST['card_owner_id'];
    $card_code_number = $a . $b;
    $card_loaded_amount = $_POST['card_loaded_amount'];

    /* Prevent Posting Null Value */
    if (empty($_POST['card_owner_id'])) {
        $err = "Please Select Student Admission Number";
    } else {

        /* Check If This MF Has Already Meal Card - Prevent Double Entry */
        $sql = "SELECT * FROM  meal_cards  WHERE card_owner_id ='$card_owner_id' ";
        $res = mysqli_query($mysqli, $sql);
        if (mysqli_num_rows($res) > 0) {
            $row = mysqli_fetch_assoc($res);
            if ($card_owner_id == $row['card_owner_id']) {
                $err = 'Student Already Has  A Meal Card';
            }
        } else {
            /* Persist This  */
            $sql = "INSERT INTO meal_cards (card_id, card_owner_id, card_code_number, card_loaded_amount) VALUES(?,?,?,?)";
            $sql_1 = "UPDATE users SET user_has_card = 'Yes' WHERE user_id = ?";

            $sql_stmt = $mysqli->prepare($sql);
            $sql_1_stmt = $mysqli->prepare($sql_1);

            $rc = $sql_stmt->bind_param('ssss', $card_id, $card_owner_id, $card_code_number, $card_loaded_amount);
            $rc = $sql_1_stmt->bind_param('s', $card_owner_id);

            $sql_stmt->execute();
            $sql_1_stmt->execute();

            if ($sql_stmt && $sql_1_stmt) {
                $success = "Student Meal Card Posted";
            } else {
                $err = "Failed!, Please Try Again Later";
            }
        }
    }
}

/* Update Meal Card */
if (isset($_POST['update_card'])) {
    $card_id = $_POST['card_id'];
    $card_loaded_amount = $_POST['card_loaded_amount'];

    /* Persist This */
    $update = "UPDATE meal_cards SET card_loaded_amount =? WHERE card_id =?";
    $update_stmt = $mysqli->prepare($update);
    $rc = $update_stmt->bind_param('ss', $card_loaded_amount, $card_id);
    $update_stmt->execute();

    if ($update_stmt) {
        $success = "Student Meal Allocated Amount Updated";
    } else {
        $err = "Failed!, Please Try Again Later";
    }
}


/* Delete Meal Card */
if (isset($_GET['delete'])) {
    $delete = $_GET['delete'];
    $card_holder = $_GET['owner'];
    /* Wipe This MF */
    $delete_sql = "DELETE FROM meal_cards WHERE card_id = '$delete'";
    $user_sql = "UPDATE users SET user_has_card = 'No' WHERE user_id = '$card_holder'";

    $delete_sql_stmt = $mysqli->prepare($delete_sql);
    $user_sql_stmt = $mysqli->prepare($user_sql);

    $delete_sql_stmt->execute();
    $user_sql_stmt->execute();

    if ($delete_sql_stmt &&  $user_sql_stmt) {
        $success = "Deleted" && header("refresh:1; url=meal_cards");
    } else {
        $err  = "Failed!, Please Try Again Later";
    }
}

/* Revoke Meal Card */
if (isset($_GET['revoke_card'])) {
    $card_id = $_GET['revoke_card'];
    $card_status  = $_GET['card_status'];

    /* Revoke / Unrevoke Card */
    $sql = "UPDATE meal_cards SET card_status = ? WHERE card_id = ?";
    $stmt = $mysqli->prepare($sql);
    $rc = $stmt->bind_param('ss', $card_status, $card_id);
    $stmt->execute();
    if ($stmt) {
        $success = "Updated" && header("refresh:1; url=meal_cards");
    } else {
        $err = "Failed!, Please Try Again Later";
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
                                            <h3 class="nk-block-title page-title">Meal Cards</h3>
                                            <div class="nk-block-des text-soft">
                                                <nav>
                                                    <ul class="breadcrumb breadcrumb-arrow">
                                                        <li class="breadcrumb-item"><a href="dashboard">Home</a></li>
                                                        <li class="breadcrumb-item active">Meal Cards</li>
                                                    </ul>
                                                </nav>
                                            </div>
                                        </div><!-- .nk-block-head-content -->

                                        <div class="nk-block-head-content">
                                            <div class="toggle-wrap nk-block-tools-toggle">
                                                <a href="#" class="btn btn-icon btn-trigger toggle-expand mr-n1" data-target="pageMenu"><em class="icon ni ni-menu-alt-r"></em></a>
                                                <div class="toggle-expand-content" data-content="pageMenu">
                                                    <ul class="nk-block-tools g-3">
                                                        <li><a href="#add_modal" data-toggle="modal" class="btn btn-white btn-outline-light"><em class="icon ni ni-plus"></em><span>Add Meal Card</span></a></li>
                                                    </ul>
                                                </div>
                                            </div><!-- .toggle-wrap -->
                                        </div><!-- .nk-block-head-content -->
                                        <!-- Add Modal -->
                                        <div class="modal fade" id="add_modal">
                                            <div class="modal-dialog  modal-lg">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h4 class="modal-title">Register New Meal Card</h4>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form method="post" enctype="multipart/form-data" role="form">
                                                            <div class="card-body">
                                                                <div class="row">
                                                                    <div class="form-group col-md-6">
                                                                        <label for="">Card Owner (Student)</label>
                                                                        <select name="card_owner_id" class="form-select form-control form-control-lg" data-search="on">
                                                                            <?php
                                                                            $ret = "SELECT * FROM users 
                                                                            WHERE user_access_level = 'student' 
                                                                            AND user_has_card = 'NO' 
                                                                            ORDER BY user_number ASC";
                                                                            $stmt = $mysqli->prepare($ret);
                                                                            $stmt->execute(); //ok
                                                                            $res = $stmt->get_result();
                                                                            while ($card_owner = $res->fetch_object()) {
                                                                            ?>
                                                                                <option value="<?php echo $card_owner->user_id; ?>"><?php echo $card_owner->user_number; ?></option>
                                                                            <?php } ?>
                                                                        </select>
                                                                    </div>
                                                                    <div class="form-group col-md-6">
                                                                        <label for="">Meal Card Standard Loaded Amount (Ksh)</label>
                                                                        <?php
                                                                        /* Pop System Settings */
                                                                        $ret = "SELECT * FROM system_settings";
                                                                        $stmt = $mysqli->prepare($ret);
                                                                        $stmt->execute(); //ok
                                                                        $res = $stmt->get_result();
                                                                        while ($sys = $res->fetch_object()) {
                                                                        ?>
                                                                            <input type="text" required name="card_loaded_amount" value="<?php echo $sys->sys_standard_amount_loaded; ?>" class="form-control">
                                                                        <?php } ?>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="text-right">
                                                                <button type="submit" name="new_card" class="btn btn-primary">Submit</button>
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
                                                                <th class="nk-tb-col"><span class="sub-text">Card Owner</span></th>
                                                                <th class="nk-tb-col tb-col-mb"><span class="sub-text">Card Number</span></th>
                                                                <th class="nk-tb-col tb-col-md"><span class="sub-text">Card Status</span></th>
                                                                <th class="nk-tb-col tb-col-md"><span class="sub-text">Card Amount (Ksh)</span></th>
                                                                <th class="nk-tb-col nk-tb-col-tools text-right">
                                                                    <span class="sub-text">Action</span>
                                                                </th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                            /* Pop This Partial With All Meal Cards */
                                                            $ret = "SELECT * FROM meal_cards mc
                                                            INNER JOIN users s ON s.user_id = mc.card_owner_id";
                                                            $stmt = $mysqli->prepare($ret);
                                                            $stmt->execute(); //ok
                                                            $res = $stmt->get_result();
                                                            while ($cards = $res->fetch_object()) {
                                                            ?>
                                                                <tr class="nk-tb-item">
                                                                    <td class="nk-tb-col">
                                                                        <div class="user-card">
                                                                            <div class="user-avatar bg-dim-primary d-none d-sm-flex">
                                                                                <span><?php echo substr($cards->user_name, 0, 2); ?></span>
                                                                            </div>
                                                                            <div class="user-info">
                                                                                <span class="tb-lead"><?php echo $cards->user_name; ?></span>
                                                                                <span class=""><?php echo $cards->user_number; ?></span>
                                                                            </div>
                                                                        </div>
                                                                    </td>
                                                                    <td class="nk-tb-col tb-col-md">
                                                                        <span><?php echo $cards->card_code_number; ?></span>
                                                                    </td>
                                                                    <td class="nk-tb-col tb-col-md">
                                                                        <span>
                                                                            <?php
                                                                            if ($cards->card_status == 'Active') {
                                                                                echo '<span class="text-success">' . $cards->card_status . '</span>';
                                                                            } else {
                                                                                echo '<span class="text-danger">' . $cards->card_status . '</span>';
                                                                            }
                                                                            ?>
                                                                        </span>
                                                                    </td>
                                                                    <td class="nk-tb-col tb-col-md">
                                                                        <span>Ksh <?php echo $cards->card_loaded_amount; ?></span>
                                                                    </td>
                                                                    <td class="nk-tb-col nk-tb-col-tools">
                                                                        <ul class="nk-tb-actions gx-1">
                                                                            <li>
                                                                                <div class="drodown">
                                                                                    <a href="#" class="dropdown-toggle btn btn-icon btn-trigger" data-toggle="dropdown"><em class="icon ni ni-more-h"></em></a>
                                                                                    <div class="dropdown-menu dropdown-menu-right">
                                                                                        <ul class="link-list-opt no-bdr">
                                                                                            <li><a href="meal_card?view=<?php echo $cards->card_id; ?>"><em class="icon ni ni-focus"></em><span>Card Details</span></a></li>
                                                                                            <li><a data-toggle="modal" href="#update-<?php echo $cards->card_id; ?>"><em class="icon ni ni-edit"></em><span>Update Card</span></a></li>
                                                                                            <li><a data-toggle="modal" href="#status-<?php echo $cards->card_id; ?>"><em class="icon ni ni-shield-alert-fill"></em><span>Update Card Status</span></a></li>
                                                                                            <li><a data-toggle="modal" href="#delete-<?php echo $cards->card_id; ?>"><em class="icon ni ni-trash"></em><span>Delete Card</span></a></li>
                                                                                        </ul>
                                                                                    </div>
                                                                                </div>
                                                                            </li>
                                                                        </ul>
                                                                    </td>
                                                                    <!-- Edit Profile Modal -->
                                                                    <div class="modal fade" id="update-<?php echo $cards->card_id; ?>">
                                                                        <div class="modal-dialog  modal-lg">
                                                                            <div class="modal-content">
                                                                                <div class="modal-header">
                                                                                    <h4 class="modal-title">Update <?php echo $cards->user_name; ?> Meal Card Details</h4>
                                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                        <span aria-hidden="true">&times;</span>
                                                                                    </button>
                                                                                </div>
                                                                                <div class="modal-body">
                                                                                    <form method="post" enctype="multipart/form-data" role="form">
                                                                                        <div class="card-body">
                                                                                            <div class="row">
                                                                                                <div class="form-group col-md-12">
                                                                                                    <label for="">Meal Card Allocated Amount (Ksh)</label>
                                                                                                    <input type="text" required name="card_loaded_amount" value="<?php echo $cards->card_loaded_amount; ?>" class="form-control">
                                                                                                    <input type="hidden" required name="card_id" value="<?php echo $cards->card_id; ?>" class="form-control">
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="text-right">
                                                                                            <button type="submit" name="update_card" class="btn btn-primary">Submit</button>
                                                                                        </div>
                                                                                    </form>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <!-- End Modal -->

                                                                    <!-- Delete Modal -->
                                                                    <div class="modal fade" id="delete-<?php echo $cards->card_id; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                                                            <div class="modal-content">
                                                                                <div class="modal-header">
                                                                                    <h5 class="modal-title" id="exampleModalLabel">CONFIRM DELETION</h5>
                                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                        <span aria-hidden="true">&times;</span>
                                                                                    </button>
                                                                                </div>
                                                                                <div class="modal-body text-center text-danger">
                                                                                    <h4>Delete <?php echo $cards->card_code_number; ?>?</h4>
                                                                                    <br>
                                                                                    <p>Heads Up, You are about to delete <?php echo $cards->user_name; ?> meal card. This action is irrevisble.</p>
                                                                                    <button type="button" class="text-center btn btn-success" data-dismiss="modal">No</button>
                                                                                    <a href="meal_cards?delete=<?php echo $cards->card_id; ?>&owner=<?php echo $cards->card_owner_id; ?>" class="text-center btn btn-danger"> Delete </a>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <!-- End Modal -->

                                                                    <!-- Update Card Status -->
                                                                    <div class="modal fade" id="status-<?php echo $cards->card_id; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                                                            <div class="modal-content">
                                                                                <div class="modal-header">
                                                                                    <h5 class="modal-title" id="exampleModalLabel">CONFIRM CARD STATUS UPDATE</h5>
                                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                        <span aria-hidden="true">&times;</span>
                                                                                    </button>
                                                                                </div>
                                                                                <div class="modal-body text-center text-danger">
                                                                                    <h4>Change <?php echo $cards->card_code_number; ?> Status?</h4>
                                                                                    <?php
                                                                                    /* Card Status */
                                                                                    if ($cards->card_status == 'Active') {
                                                                                        $new_status = 'Revoked';
                                                                                    } else {
                                                                                        $new_status = 'Active';
                                                                                    }
                                                                                    ?>
                                                                                    <br>
                                                                                    <p>Heads Up, You are about to change <?php echo $cards->user_name; ?> meal card status to <?php echo $new_status; ?>. </p>
                                                                                    <button type="button" class="text-center btn btn-success" data-dismiss="modal">No</button>
                                                                                    <a href="meal_cards?revoke_card=<?php echo $cards->card_id; ?>&card_status=<?php echo $new_status; ?>" class="text-center btn btn-danger"> Yes </a>
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