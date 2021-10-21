<?php
/*
 * Created on Thu Oct 21 2021
 *
 *  MartDevelopers - martdev.info 
 *
 * mail@martdev.info
 *
 * +254 740 847 563
 *
 * The Devlan End User License Agreement
 *
 * Copyright (c) 2021 MartDevelopers Inc
 *
 * 1. GRANT OF LICENSE
 * Devlan hereby grants to you (an individual) the revocable, personal, non-exclusive, and nontransferable right to
 * install and activate this system on two separated computers solely for your personal and non-commercial use,
 * unless you have purchased a commercial license from Devlan. Sharing this Software with other individuals, 
 * or allowing other individuals to view the contents of this Software, is in violation of this license.
 * You may not make the Software available on a network, or in any way provide the Software to multiple users
 * unless you have first purchased at least a multi-user license from Devlan.
 *
 * 2. COPYRIGHT 
 * The Software is owned by Devlan and protected by copyright law and international copyright treaties. 
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
 * DEVLAN  DOES NOT WARRANT THAT THE SOFTWARE IS ERROR FREE. 
 * DEVLAN SOFTWARE DISCLAIMS ALL OTHER WARRANTIES WITH RESPECT TO THE SOFTWARE, 
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
 * 7. NO LIABILITY FOR CONSEQUENTIAL DAMAGES IN NO EVENT SHALL DEVLAN  OR ITS SUPPLIERS BE LIABLE TO YOU FOR ANY
 * CONSEQUENTIAL, SPECIAL, INCIDENTAL OR INDIRECT DAMAGES OF ANY KIND ARISING OUT OF THE DELIVERY, PERFORMANCE OR 
 * USE OF THE SOFTWARE, EVEN IF DEVLAN HAS BEEN ADVISED OF THE POSSIBILITY OF SUCH DAMAGES
 * IN NO EVENT WILL DEVLAN  LIABILITY FOR ANY CLAIM, WHETHER IN CONTRACT 
 * TORT OR ANY OTHER THEORY OF LIABILITY, EXCEED THE LICENSE FEE PAID BY YOU, IF ANY.
 */
session_start();
require_once('../config/config.php');
require_once('../config/checklogin.php');
checklogin();
require_once('../partials/analytics.php');
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
                                            <h3 class="nk-block-title page-title">Dashboard</h3>
                                        </div><!-- .nk-block-head-content -->
                                        <div class="nk-block-head-content">
                                            <div class="toggle-wrap nk-block-tools-toggle">
                                                <a href="#" class="btn btn-icon btn-trigger toggle-expand mr-n1" data-target="pageMenu"><em class="icon ni ni-more-v"></em></a>
                                                <div class="toggle-expand-content" data-content="pageMenu">
                                                    <ul class="nk-block-tools g-3">
                                                        <li class="nk-block-tools-opt"><a href="reports" class="btn btn-primary"><em class="icon ni ni-reports"></em><span>Reports</span></a></li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div><!-- .nk-block-head-content -->
                                    </div><!-- .nk-block-between -->
                                </div><!-- .nk-block-head -->
                                <div class="nk-block">
                                    <div class="row g-gs">
                                        <div class="col-xxl-12">
                                            <div class="row g-gs">
                                                <div class="col-lg-12 col-xxl-12">
                                                    <div class="row g-gs">
                                                        <div class="col-sm-4 col-lg-4 col-xxl-6">
                                                            <div class="card card-bordered">
                                                                <div class="card-inner">
                                                                    <div class="card-title-group align-start mb-2">
                                                                        <div class="card-title">
                                                                            <h6 class="title">Students</h6>
                                                                        </div>
                                                                    </div>
                                                                    <div class="align-end flex-sm-wrap g-4 flex-md-nowrap">
                                                                        <div class="nk-sale-data">
                                                                            <span class="amount"><?php echo $students; ?></span>
                                                                        </div>
                                                                        <div class="nk-sales-ck text-right">
                                                                            <i class="fas fa-users fa-4x"></i>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div><!-- .card -->
                                                        </div><!-- .col -->
                                                        <div class="col-sm-4 col-lg-4 col-xxl-6">
                                                            <div class="card card-bordered">
                                                                <div class="card-inner">
                                                                    <div class="card-title-group align-start mb-2">
                                                                        <div class="card-title">
                                                                            <h6 class="title">Cashiers</h6>
                                                                        </div>
                                                                    </div>
                                                                    <div class="align-end flex-sm-wrap g-4 flex-md-nowrap">
                                                                        <div class="nk-sale-data">
                                                                            <span class="amount"><?php echo $cashiers; ?></span>
                                                                        </div>
                                                                        <div class="nk-sales-ck text-right">
                                                                            <i class="fas fa-user-tie fa-4x"></i>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div><!-- .card -->
                                                        </div><!-- .col -->
                                                        <div class="col-sm-4 col-lg-4 col-xxl-6">
                                                            <div class="card card-bordered">
                                                                <div class="card-inner">
                                                                    <div class="card-title-group align-start mb-2">
                                                                        <div class="card-title">
                                                                            <h6 class="title">Available Meals</h6>
                                                                        </div>
                                                                    </div>
                                                                    <div class="align-end flex-sm-wrap g-4 flex-md-nowrap">
                                                                        <div class="nk-sale-data">
                                                                            <span class="amount"><?php echo $meals; ?></span>
                                                                        </div>
                                                                        <div class="nk-sales-ck text-right">
                                                                            <i class="fas fa-pie fa-4x"></i>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div><!-- .card -->
                                                        </div><!-- .col -->
                                                        <div class="col-sm-4 col-lg-4 col-xxl-6">
                                                            <div class="card card-bordered">
                                                                <div class="card-inner">
                                                                    <div class="card-title-group align-start mb-2">
                                                                        <div class="card-title">
                                                                            <h6 class="title">Orders</h6>
                                                                        </div>
                                                                    </div>
                                                                    <div class="align-end flex-sm-wrap g-4 flex-md-nowrap">
                                                                        <div class="nk-sale-data">
                                                                            <span class="amount"><?php echo $orders; ?></span>
                                                                        </div>
                                                                        <div class="nk-sales-ck text-right">
                                                                            <i class="fas fa-file-signature fa-4x"></i>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div><!-- .card -->
                                                        </div><!-- .col -->

                                                        <div class="col-sm-4 col-lg-4 col-xxl-6">
                                                            <div class="card card-bordered">
                                                                <div class="card-inner">
                                                                    <div class="card-title-group align-start mb-2">
                                                                        <div class="card-title">
                                                                            <h6 class="title">MealCards Allocated Funds</h6>
                                                                        </div>
                                                                    </div>
                                                                    <div class="align-end flex-sm-wrap g-4 flex-md-nowrap">
                                                                        <div class="nk-sale-data">
                                                                            <span class="amount">Ksh <?php echo $allocated_funds; ?></span>
                                                                        </div>
                                                                        <div class="nk-sales-ck text-right">
                                                                            <i class="fas fa-id-card fa-4x"></i>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div><!-- .card -->
                                                        </div><!-- .col -->
                                                        <div class="col-sm-4 col-lg-4 col-xxl-6">
                                                            <div class="card card-bordered">
                                                                <div class="card-inner">
                                                                    <div class="card-title-group align-start mb-2">
                                                                        <div class="card-title">
                                                                            <h6 class="title">Overall Cafe Income</h6>
                                                                        </div>
                                                                    </div>
                                                                    <div class="align-end flex-sm-wrap g-4 flex-md-nowrap">
                                                                        <div class="nk-sale-data">
                                                                            <span class="amount">Ksh <?php echo $overall_income; ?></span>
                                                                        </div>
                                                                        <div class="nk-sales-ck text-right">
                                                                            <i class="fas fa-money-bill-alt fa-4x"></i>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div><!-- .card -->
                                                        </div><!-- .col -->

                                                    </div><!-- .row -->
                                                </div><!-- .col -->
                                            </div><!-- .row -->
                                        </div><!-- .col -->

                                        <div class="col-md-6 col-xxl-6">
                                            <div class="card card-bordered card-full">
                                                <div class="card-inner border-bottom">
                                                    <div class="card-title-group">
                                                        <div class="card-title">
                                                            <h6 class="title"><?Php echo date('d M Y'); ?> Meal Orders</h6>
                                                        </div>
                                                        <div class="card-tools">
                                                            <a href="orders" class="link">View All</a>
                                                        </div>
                                                    </div>
                                                </div>
                                                <ul class="nk-activity">
                                                    <?php
                                                    /* Load Orders Logs */
                                                    $ret = "SELECT * FROM  orders o
                                                    INNER JOIN users s ON s.user_id = o.order_user_id
                                                    INNER JOIN meals m ON m.meal_id = o.order_meal_id 
                                                    WHERE  o.order_date_posted = CURDATE()";
                                                    $stmt = $mysqli->prepare($ret);
                                                    $stmt->execute(); //ok
                                                    $res = $stmt->get_result();
                                                    while ($orders = $res->fetch_object()) {

                                                    ?>
                                                        <li class="nk-activity-item">
                                                            <div class="nk-activity-media user-avatar bg-success">
                                                                <?php echo substr($orders->user_name, 0, 2); ?>
                                                            </div>
                                                            <div class="nk-activity-data">
                                                                <div class="label">
                                                                    <?php echo $orders->user_name; ?> Has Ordered <?php echo $orders->meal_name . '. Quantity: ' . $orders->order_quanty; ?>
                                                                </div>
                                                                <span class="time"><?php echo date('d M Y, g:ia', strtotime($orders->order_date_posted)); ?></span>
                                                            </div>
                                                        </li>
                                                    <?php } ?>

                                                </ul>
                                            </div><!-- .card -->
                                        </div><!-- .col -->


                                        <div class="col-lg-6 col-xxl-6">
                                            <div class="card card-bordered h-100">
                                                <div class="card-inner border-bottom">
                                                    <div class="card-title-group">
                                                        <div class="card-title">
                                                            <h6 class="title"><?Php echo date('d M Y'); ?> Orders Payment Logs</h6>
                                                        </div>
                                                        <div class="card-tools">
                                                            <a href="html/subscription/tickets.html" class="link">View All</a>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="card-inner">
                                                    <div class="timeline">
                                                        <ul class="timeline-list">
                                                            <?php
                                                            $ret = "SELECT * FROM  payments p
                                                            INNER JOIN orders o ON o.order_id  = p.payment_order_id
                                                            INNER JOIN users s ON s.user_id = o.order_user_id
                                                            INNER JOIN meals m ON m.meal_id = o.order_meal_id 
                                                            WHERE  p.payment_date_posted  = CURDATE()";
                                                            $stmt = $mysqli->prepare($ret);
                                                            $stmt->execute(); //ok
                                                            $res = $stmt->get_result();
                                                            while ($payments = $res->fetch_object()) {

                                                            ?>
                                                                <li class="timeline-item">
                                                                    <div class="timeline-status bg-primary is-outline"></div>
                                                                    <div class="timeline-date"><?php echo date('d M Y', strtotime($payments->payment_date_posted)); ?>
                                                                        <em class="icon ni ni-check"></em></div>
                                                                    <div class="timeline-data">
                                                                        <h6 class="timeline-title"><?php echo $payments->payment_confirmation_code; ?> Confirmed</h6>
                                                                        <div class="timeline-des">
                                                                            <p>
                                                                                <?php echo $payments->user_name . '' . $user->user_number; ?> Paid Ksh <?php echo $payments->payment_amount; ?><br>
                                                                                Using <?php echo $payments->payment_means; ?> For <?php echo $payments->meal_name; ?>
                                                                            </p>
                                                                            <span class="time"><?php echo date('g:ia', strtotime($payments->payment_date_posted)); ?></span>
                                                                        </div>
                                                                    </div>
                                                                </li>
                                                            <?php } ?>

                                                        </ul>
                                                    </div>
                                                </div>
                                            </div><!-- .card -->
                                        </div><!-- .col -->
                                    </div><!-- .row -->
                                </div><!-- .nk-block -->
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
    <!-- app-root @e -->
    <!-- JavaScript -->
    <?php require_once('../partials/scripts.php');    ?>
</body>

</html>