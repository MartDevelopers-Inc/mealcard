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
/* Load Composer Vendor */
require_once('../vendor/autoload.php');
$qrcode  = new \Com\Tecnick\Barcode\Barcode();
/* Add Order */
if (isset($_POST['add_order'])) {
    $order_id = $sys_gen_id;
    $order_user_id = $_POST['order_user_id'];
    $order_meal_id = $_POST['order_meal_id'];
    $order_quantity = $_POST['order_quantity'];

    /* Persist The Changes */
    $insert = "INSERT INTO orders(order_id, order_user_id, order_meal_id, order_quantity) VALUES(?,?,?,?)";
    $stmt = $mysqli->prepare($insert);
    $rc = $stmt->bind_param('ssss', $order_id, $order_user_id, $order_meal_id, $order_quantity);
    $stmt->execute();
    if ($stmt) {
        $success = "Order Posted, Proceed To Pay";
    } else {
        $err = "Failed!, Please Try Again Later";
    }
}

/* Update Order */
if (isset($_POST['update_order'])) {
    $order_id = $_POST['order_id'];
    $order_quantity = $_POST['order_quantity'];

    /* Persist Update */
    $update = "UPDATE orders SET order_quantity =? WHERE order_id = ?";
    $stmt = $mysqli->prepare($update);
    $rc = $stmt->bind_param('ss', $order_quantity, $order_id);
    $stmt->execute();
    if ($stmt) {
        $success = "Order Updated";
    } else {
        $err = "Failed!, Please Try Again Later";
    }
}

/* Delete Order */
if (isset($_GET['delete'])) {
    $delete = $_GET['delete'];
    $deletesql  = "DELETE FROM orders WHERE order_id = ?";
    $stmt = $mysqli->prepare($deletesql);
    $rc = $stmt->bind_param('s', $delete);
    $stmt->execute();

    if ($stmt) {
        $success = "Deleted" && header("refresh:1, orders");
    } else {
        $err = "Failed!, Please Try Again Later";
    }
}

/* Pay Order */
if (isset($_POST['pay_order'])) {
    $payment_id = $sys_gen_id;
    $payment_order_id  = $_POST['payment_order_id'];
    $payment_confirmation_code = $_POST['payment_confirmation_code'];
    $payment_amount = $_POST['payment_amount'];
    $payment_means = $_POST['payment_means'];
    $new_balance = $_POST['new_balance'];
    $card_id = $_POST['card_id'];
    /* Order Status */
    $order_status  = 'Paid';

    /* Deduct The Amount If They Have Used Meal Card */
    if ($payment_means == 'Meal Card Swipe') {
        $pay = "INSERT INTO payments (payment_id, payment_order_id, payment_confirmation_code, payment_amount, payment_means) VALUES(?,?,?,?,?)";
        $order  = "UPDATE orders SET order_payment_status = ? WHERE order_id = ?";
        $deduct = "UPDATE meal_cards SET card_loaded_amount =? WHERE card_id = ?";

        $payprep = $mysqli->prepare($pay);
        $orderprep = $mysqli->prepare($order);
        $deductprep = $mysqli->prepare($deduct);

        $rc = $payprep->bind_param('sssss', $payment_id, $payment_order_id, $payment_confirmation_code, $payment_amount, $payment_means);
        $rc = $orderprep->bind_param('ss', $order_status, $payment_order_id);
        $rc = $deductprep->bind_param('ss', $new_balance, $card_id);

        $payprep->execute();
        $orderprep->execute();
        $deductprep->execute();

        if ($payprep && $orderprep && $deductprep) {
            $success = "Order Payment Posted";
        } else {
            $err = "Failed!, Please Try Again Later";
        }
    } else {
        $pay = "INSERT INTO payments (payment_id, payment_order_id, payment_confirmation_code, payment_amount, payment_means) VALUES(?,?,?,?,?)";
        $order  = "UPDATE orders SET order_payment_status = ? WHERE order_id = ?";

        $payprep = $mysqli->prepare($pay);
        $orderprep = $mysqli->prepare($order);

        $rc = $payprep->bind_param('sssss', $payment_id, $payment_order_id, $payment_confirmation_code, $payment_amount, $payment_means);
        $rc = $orderprep->bind_param('ss', $order_status, $payment_order_id);

        $payprep->execute();
        $orderprep->execute();

        if ($payprep && $orderprep) {
            $success = "Order Payment Posted";
        } else {
            $err = "Failed!, Please Try Again Later";
        }
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
                                            <h3 class="nk-block-title page-title">Student Meal Orders</h3>
                                            <div class="nk-block-des text-soft">
                                                <nav>
                                                    <ul class="breadcrumb breadcrumb-arrow">
                                                        <li class="breadcrumb-item"><a href="dashboard">Home</a></li>
                                                        <li class="breadcrumb-item active">Orders</li>
                                                    </ul>
                                                </nav>
                                            </div>
                                        </div><!-- .nk-block-head-content -->

                                        <div class="nk-block-head-content">
                                            <div class="toggle-wrap nk-block-tools-toggle">
                                                <a href="#" class="btn btn-icon btn-trigger toggle-expand mr-n1" data-target="pageMenu"><em class="icon ni ni-menu-alt-r"></em></a>
                                                <div class="toggle-expand-content" data-content="pageMenu">
                                                    <ul class="nk-block-tools g-3">
                                                        <li><a href="#add_modal" data-toggle="modal" class="btn btn-white btn-outline-light"><em class="icon ni ni-plus"></em><span>Add Meal Order</span></a></li>
                                                    </ul>
                                                </div>
                                            </div><!-- .toggle-wrap -->
                                        </div><!-- .nk-block-head-content -->
                                        <!-- Add Modal -->
                                        <div class="modal fade" id="add_modal">
                                            <div class="modal-dialog  modal-lg">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h4 class="modal-title">Register New Meal Order</h4>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form method="post" enctype="multipart/form-data" role="form">
                                                            <div class="card-body">
                                                                <div class="row">
                                                                    <div class="form-group col-md-12">
                                                                        <label for="">Order Owner (Student)</label>
                                                                        <select name="order_user_id" class="form-select form-control form-control-lg" data-search="on">
                                                                            <?php
                                                                            $ret = "SELECT * FROM users 
                                                                            WHERE user_access_level = 'student' 
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
                                                                        <label for="">Meal Name</label>
                                                                        <select name="order_meal_id" class="form-select form-control form-control-lg" data-search="on">
                                                                            <?php
                                                                            $ret = "SELECT * FROM meals
                                                                            ORDER BY meal_name ASC";
                                                                            $stmt = $mysqli->prepare($ret);
                                                                            $stmt->execute(); //ok
                                                                            $res = $stmt->get_result();
                                                                            while ($meal = $res->fetch_object()) {
                                                                            ?>
                                                                                <option value="<?php echo $meal->meal_id; ?>"><?php echo $meal->meal_name; ?></option>
                                                                            <?php } ?>
                                                                        </select>
                                                                    </div>
                                                                    <div class="form-group col-md-6">
                                                                        <label for="">Order Quantity</label>
                                                                        <input type="text" required value="1" name="order_quantity" class="form-control">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="text-right">
                                                                <button type="submit" name="add_order" class="btn btn-primary">Submit</button>
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
                                                                <th class="nk-tb-col"><span class="sub-text">Order Owner</span></th>
                                                                <th class="nk-tb-col tb-col-mb"><span class="sub-text">Meal Ordered</span></th>
                                                                <th class="nk-tb-col tb-col-md"><span class="sub-text">Quantity</span></th>
                                                                <th class="nk-tb-col tb-col-md"><span class="sub-text">Amount</span></th>
                                                                <th class="nk-tb-col tb-col-md"><span class="sub-text">Date Ordered</span></th>
                                                                <th class="nk-tb-col tb-col-md"><span class="sub-text">Payment Status</span></th>
                                                                <th class="nk-tb-col nk-tb-col-tools text-right">
                                                                    <span class="sub-text">Action</span>
                                                                </th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                            /* Pop This Partial With All Meal Cards */
                                                            $ret = "SELECT * FROM  orders o
                                                            INNER JOIN users s ON s.user_id = o.order_user_id
                                                            INNER JOIN meals m ON m.meal_id = o.order_meal_id 
                                                            INNER JOIN meal_categories mc ON mc.category_id = m.meal_category_id
                                                            INNER JOIN meal_cards mcr ON  mcr.card_owner_id = o.order_user_id
                                                            INNER JOIN system_settings 
                                                            ORDER BY o.order_date_posted DESC
                                                            ";
                                                            $stmt = $mysqli->prepare($ret);
                                                            $stmt->execute(); //ok
                                                            $res = $stmt->get_result();
                                                            while ($orders = $res->fetch_object()) {
                                                                /* Total Payment */
                                                                $total_pay = ($orders->order_quantity) * ($orders->meal_price);
                                                            ?>
                                                                <tr class="nk-tb-item">
                                                                    <td class="nk-tb-col">
                                                                        <div class="user-card">
                                                                            <div class="user-avatar bg-dim-primary d-none d-sm-flex">
                                                                                <span><?php echo substr($orders->user_name, 0, 2); ?></span>
                                                                            </div>
                                                                            <div class="user-info">
                                                                                <span class="tb-lead"><?php echo $orders->user_name; ?></span>
                                                                                <span class=""><?php echo $orders->user_number; ?></span>
                                                                            </div>
                                                                        </div>
                                                                    </td>
                                                                    <td class="nk-tb-col tb-col-md">
                                                                        <span><?php echo $orders->meal_name; ?></span><br>
                                                                        <span>Category: <?php echo $orders->category_name; ?></span>
                                                                    </td>
                                                                    <td class="nk-tb-col tb-col-md">
                                                                        <span><?php echo $orders->order_quantity; ?></span>
                                                                    </td>
                                                                    <td class="nk-tb-col tb-col-md">
                                                                        <span>Ksh <?php echo $total_pay; ?></span>
                                                                    </td>
                                                                    <td class="nk-tb-col tb-col-md">
                                                                        <span><?php echo date('d M Y g:ia', strtotime($orders->order_date_posted)); ?></span>
                                                                    </td>
                                                                    <td class="nk-tb-col tb-col-md">
                                                                        <span>
                                                                            <?php
                                                                            if ($orders->order_payment_status == 'Paid') {
                                                                                echo '<span class="text-success">' . $orders->order_payment_status . '</span>';
                                                                            } else {
                                                                                echo '<span class="text-danger">' . $orders->order_payment_status . '</span>';
                                                                            }
                                                                            ?>
                                                                        </span>
                                                                    </td>
                                                                    <td class="nk-tb-col nk-tb-col-tools">
                                                                        <ul class="nk-tb-actions gx-1">
                                                                            <li>
                                                                                <div class="drodown">
                                                                                    <a href="#" class="dropdown-toggle btn btn-icon btn-trigger" data-toggle="dropdown"><em class="icon ni ni-more-h"></em></a>
                                                                                    <div class="dropdown-menu dropdown-menu-right">
                                                                                        <ul class="link-list-opt no-bdr">
                                                                                            <li><a data-toggle="modal" href="#update-<?php echo $orders->order_id; ?>"><em class="icon ni ni-edit"></em><span>Update Order</span></a></li>
                                                                                            <?php
                                                                                            if ($orders->order_payment_status != 'Paid') { ?>
                                                                                                <li><a data-toggle="modal" href="#pay-<?php echo $orders->order_id; ?>"><em class="icon ni ni-money"></em><span>Pay Order</span></a></li>
                                                                                            <?php } ?>
                                                                                            <li><a data-toggle="modal" href="#delete-<?php echo $orders->order_id; ?>"><em class="icon ni ni-trash"></em><span>Delete Order</span></a></li>
                                                                                        </ul>
                                                                                    </div>
                                                                                </div>
                                                                            </li>
                                                                        </ul>
                                                                    </td>
                                                                    <!-- Edit Profile Modal -->
                                                                    <div class="modal fade" id="update-<?php echo $orders->order_id; ?>">
                                                                        <div class="modal-dialog  modal-lg">
                                                                            <div class="modal-content">
                                                                                <div class="modal-header">
                                                                                    <h4 class="modal-title">Update <?php echo $orders->user_name; ?> Meal Order Details</h4>
                                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                        <span aria-hidden="true">&times;</span>
                                                                                    </button>
                                                                                </div>
                                                                                <div class="modal-body">
                                                                                    <form method="post" enctype="multipart/form-data" role="form">
                                                                                        <div class="card-body">
                                                                                            <div class="row">
                                                                                                <div class="form-group col-md-12">
                                                                                                    <label for="">Order Quantity</label>
                                                                                                    <input type="text" required name="order_quantity" value="<?php echo $orders->order_quantity; ?>" class="form-control">
                                                                                                    <input type="hidden" required name="order_id" value="<?php echo $orders->order_id; ?>" class="form-control">
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="text-right">
                                                                                            <button type="submit" name="update_order" class="btn btn-primary">Submit</button>
                                                                                        </div>
                                                                                    </form>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <!-- End Modal -->

                                                                    <!-- Delete Modal -->
                                                                    <div class="modal fade" id="delete-<?php echo $orders->order_id; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                                                            <div class="modal-content">
                                                                                <div class="modal-header">
                                                                                    <h5 class="modal-title" id="exampleModalLabel">CONFIRM DELETION</h5>
                                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                        <span aria-hidden="true">&times;</span>
                                                                                    </button>
                                                                                </div>
                                                                                <div class="modal-body text-center text-danger">
                                                                                    <h4>Delete <?php echo $orders->user_name; ?> Meal Order?</h4>
                                                                                    <br>
                                                                                    <p>Heads Up, You are about to delete this order, This action is irrevisble.</p>
                                                                                    <button type="button" class="text-center btn btn-success" data-dismiss="modal">No</button>
                                                                                    <a href="orders?delete=<?php echo $orders->order_id; ?>" class="text-center btn btn-danger"> Delete </a>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <!-- End Modal -->

                                                                    <!-- Pay Order Modal -->
                                                                    <div class="modal fade" id="pay-<?php echo $orders->order_id; ?>">
                                                                        <div class="modal-dialog  modal-xl">
                                                                            <div class="modal-content">
                                                                                <div class="modal-header">
                                                                                    <h4 class="modal-title">Pay <?php echo $orders->user_name; ?> Meal Order</h4>
                                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                        <span aria-hidden="true">&times;</span>
                                                                                    </button>
                                                                                </div>
                                                                                <div class="modal-body">
                                                                                    <div class="row">
                                                                                        <div class="card col-md-6 col-sm-12 col-xl-6">
                                                                                            <div class="text-center">Scan QR Code To Pay</div>
                                                                                            <?php
                                                                                            /*
                                                                                            * This Library is used here under demo purposes
                                                                                            * This Client Has Not PURCHASED THE FULL SYSTEM LICENSE
                                                                                            * 
                                                                                            */
                                                                                            $payment_details = "Mercant:" . $orders->sys_name . " Payment Amount: Ksh" . $total_pay;
                                                                                            $targetPath = "../public/backend_assets/qr_codes/";
                                                                                            if (!is_dir($targetPath)) {
                                                                                                mkdir($targetPath, 0777, true);
                                                                                            }
                                                                                            $bobj = $qrcode->getBarcodeObj('QRCODE,H', $payment_details, -16, -16, 'black', array(
                                                                                                -2,
                                                                                                -2,
                                                                                                -2,
                                                                                                -2
                                                                                            ))->setBackgroundColor('#f0f0f0');

                                                                                            $imageData = $bobj->getPngData();
                                                                                            $timestamp = time();

                                                                                            file_put_contents($targetPath . $timestamp . '.png', $imageData);
                                                                                            ?>
                                                                                            <br>
                                                                                            <img class="img-fluid img-thumbnail" src="<?php echo $targetPath . $timestamp; ?>.png">
                                                                                        </div>
                                                                                        <div class="card col-md-6 col-sm-12 col-xl-6">
                                                                                            <form method="post" enctype="multipart/form-data" role="form">
                                                                                                <div class="card-body">
                                                                                                    <div class="row">
                                                                                                        <div class="form-group col-md-12">
                                                                                                            <label for="">Payment Confirmation Code</label>
                                                                                                            <input type="text" required name="payment_confirmation_code" value="<?php echo $sys_gen_paycode; ?>" class="form-control">
                                                                                                            <input type="hidden" required name="payment_order_id" value="<?php echo $orders->order_id; ?>" class="form-control">
                                                                                                            <?php
                                                                                                            /* Compute Existing Balance In Meal Card */
                                                                                                            $initialbal = $orders->card_loaded_amount;
                                                                                                            $new_bal  = $initialbal - $total_pay;
                                                                                                            ?>
                                                                                                            <input type="hidden" required name="new_balance" value="<?php echo $new_bal; ?>" class="form-control">
                                                                                                            <input type="hidden" required name="card_id" value="<?php echo $orders->card_id; ?>" class="form-control">
                                                                                                        </div>
                                                                                                        <div class="form-group col-md-6">
                                                                                                            <label for="">Payment Amount (Ksh)</label>
                                                                                                            <input type="text" required name="payment_amount" value="<?php echo $total_pay; ?>" readonly class="form-control">
                                                                                                        </div>
                                                                                                        <div class="form-group col-md-6">
                                                                                                            <label for="">Payment Means</label>
                                                                                                            <select name="payment_means" class="form-select form-control form-control-lg" data-search="on">
                                                                                                                <option>Meal Card Swipe</option>
                                                                                                                <option>Mpesa</option>
                                                                                                                <option>Cash</option>
                                                                                                            </select>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                </div>
                                                                                                <div class="text-right">
                                                                                                    <button type="submit" name="pay_order" class="btn btn-primary">Submit</button>
                                                                                                </div>
                                                                                            </form>
                                                                                        </div>
                                                                                    </div>
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