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
        $success = "Deleted" && header("refresh:1, my_orders");
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
                                    <div class="nk-block">
                                        <div class="card card-bordered sp-plan">
                                            <div class="card-body">

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