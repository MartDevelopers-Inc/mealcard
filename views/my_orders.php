<?php
/*
 * Created on Tue Oct 26 2021
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
/* Add Order */
if (isset($_POST['add_order'])) {
    $order_id = $sys_gen_id;
    $order_user_id = $_SESSION['user_id'];
    $order_meal_id = $_POST['order_meal_id'];
    $order_quantity = $_POST['order_quantity'];

    /* Persist The Changes */
    $insert = "INSERT INTO orders(order_id, order_user_id, order_meal_id, order_quantity) VALUES(?,?,?,?)";
    $stmt = $mysqli->prepare($insert);
    $rc = $stmt->bind_param('ssss', $order_id, $order_user_id, $order_meal_id, $order_quantity);
    $stmt->execute();
    if ($stmt) {
        $success = "Order Posted, Pay At Cafeteria Checkout Terminal";
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

/* Delete My order */
if (isset($_GET['delete'])) {
    $order_id = $_GET['delete'];
    /* Persist this delete */
    $del = "DELETE FROM orders WHERE order_id = ?";
    $stmt = $mysqli->prepare($del);
    $rc = $stmt->bind_param('s', $order_id);
    if ($stmt) {
        $success = "Order Deleted" && header('refresh:1; my_orders');
    } else {
        $err = "Failed!, Please Try Again Later";
    }
}

require_once('../partials/head.php');
?>

<body class="nk-body npc-subscription has-aside ui-clean ">
    <div class="nk-app-root">
        <!-- main @s -->
        <div class="nk-main ">
            <!-- wrap @s -->
            <div class="nk-wrap ">
                <?php require_once('../partials/student_header.php'); ?>
                <!-- main header @e -->
                <!-- content @s -->
                <div class="nk-content ">
                    <div class="container wide-xl">
                        <div class="nk-content-inner">
                            <?php require_once('../partials/student_aside.php'); ?>
                            <div class="nk-content-body">
                                <div class="nk-content-wrap">
                                    <div class="nk-block-head nk-block-head-lg">
                                        <div class="nk-block-between-md g-4">
                                            <div class="nk-block-head-content">
                                                <nav>
                                                    <ul class="breadcrumb breadcrumb-arrow">
                                                        <li class="breadcrumb-item"><a href="home">Home</a></li>
                                                        <li class="breadcrumb-item active">Orders</li>
                                                    </ul>
                                                </nav>
                                                <br>
                                                <h2 class="nk-block-title fw-normal">My Orders</h2>
                                            </div>
                                        </div>
                                    </div><!-- .nk-block-head -->
                                    <div class="text-right">
                                        <a href="#add_modal" data-toggle="modal" class="btn btn-white btn-outline-light"><em class="icon ni ni-plus"></em><span>Add Meal Order</span></a>
                                    </div>
                                    <br>
                                    <div class="modal fade" id="add_modal">
                                        <div class="modal-dialog  modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h4 class="modal-title">Select A Meal And Quantity To Order</h4>
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
                                    <div class="nk-block">
                                        <div class="card card-bordered sp-plan">
                                            <div class="card-body">
                                                <table class="datatable-init nk-tb-list nk-tb-ulist" data-auto-responsive="false">
                                                    <thead>
                                                        <tr class="nk-tb-item nk-tb-head">
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
                                                        $user_id = $_SESSION['user_id'];
                                                        /* Pop This Partial With All Meal Cards */
                                                        $ret = "SELECT * FROM  orders o
                                                        INNER JOIN users s ON s.user_id = o.order_user_id
                                                        INNER JOIN meals m ON m.meal_id = o.order_meal_id 
                                                        INNER JOIN meal_categories mc ON mc.category_id = m.meal_category_id
                                                        INNER JOIN meal_cards mcr ON  mcr.card_owner_id = o.order_user_id
                                                        WHERE s.user_id = '$user_id' 
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
                                                                    <?php
                                                                    /* Only Manage Unpaid Orders  */
                                                                    if ($orders->order_payment_status != 'Paid') {
                                                                    ?>
                                                                        <ul class="nk-tb-actions gx-1">
                                                                            <li>
                                                                                <div class="drodown">
                                                                                    <a href="#" class="dropdown-toggle btn btn-icon btn-trigger" data-toggle="dropdown"><em class="icon ni ni-more-h"></em></a>
                                                                                    <div class="dropdown-menu dropdown-menu-right">
                                                                                        <ul class="link-list-opt no-bdr">
                                                                                            <li><a data-toggle="modal" href="#update-<?php echo $orders->order_id; ?>"><em class="icon ni ni-edit"></em><span>Update Order</span></a></li>
                                                                                        </ul>
                                                                                    </div>
                                                                                </div>
                                                                            </li>
                                                                        </ul>
                                                                    <?php } ?>
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
                                                                                <a href="my_orders?delete=<?php echo $orders->order_id; ?>" class="text-center btn btn-danger"> Delete </a>
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
                                    </div><!-- .nk-block -->
                                </div>
                                <!-- footer @s -->
                                <?php require_once('../partials/footer.php'); ?>
                                <!-- footer @e -->
                            </div>
                        </div>
                    </div>
                </div>
                <!-- content @e -->
            </div>
            <!-- wrap @e -->
        </div>
        <!-- main @e -->
    </div>
    <!-- app-root @e -->
    <?php require_once('../partials/scripts.php'); ?>
</body>

</html>