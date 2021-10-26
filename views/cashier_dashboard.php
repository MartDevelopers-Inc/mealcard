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
checklogin();
require_once('../partials/analytics.php');
require_once('../partials/head.php');
?>

<body class="nk-body npc-invest bg-lighter ">
    <div class="nk-app-root">
        <!-- wrap @s -->
        <div class="nk-wrap ">
            <!-- main header @s -->
            <?php
            require_once('../partials/cashier_header.php');
            ?>
            <!-- main header @e -->
            <!-- content @s -->
            <div class="nk-content nk-content-lg nk-content-fluid">
                <div class="container-xl wide-lg">
                    <div class="nk-content-inner">
                        <div class="nk-content-body">
                            <div class="nk-block-head">
                                <div class="nk-block-between-md g-3">
                                    <div class="nk-block-head-content">
                                        <nav>
                                            <ul class="breadcrumb breadcrumb-arrow">
                                                <li class="breadcrumb-item"><a href="cashier_dashboard">Home</a></li>
                                                <li class="breadcrumb-item active">Dashboard</li>
                                            </ul>
                                        </nav>
                                        <br>
                                        <div class="align-center flex-wrap pb-2 gx-4 gy-3">
                                            <div>
                                                <h2 class="nk-block-title fw-normal">Cashier Dashboard</h2>
                                            </div>
                                        </div><!-- .nk-block-head-content -->
                                    </div><!-- .nk-block-between -->
                                </div><!-- .nk-block-head -->
                                <div class="nk-block">
                                    <div class="row gy-gs">
                                        <div class="col-md-6 col-lg-4">
                                            <div class="nk-wg-card is-dark card card-bordered">
                                                <div class="card-inner">
                                                    <div class="nk-iv-wg2">
                                                        <div class="nk-iv-wg2-title">
                                                            <h6 class="title">Total Income</h6>
                                                        </div>
                                                        <div class="nk-iv-wg2-text">
                                                            <div class="nk-iv-wg2-amount"> Ksh <?php echo $overall_income; ?></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div><!-- .card -->
                                        </div><!-- .col -->
                                        <div class="col-md-6 col-lg-4">
                                            <div class="nk-wg-card is-s1 card card-bordered">
                                                <div class="card-inner">
                                                    <div class="nk-iv-wg2">
                                                        <div class="nk-iv-wg2-title">
                                                            <h6 class="title">Pending Payment Orders </h6>
                                                        </div>
                                                        <div class="nk-iv-wg2-text">
                                                            <div class="nk-iv-wg2-amount"> <?php echo $pending_orders; ?> </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div><!-- .card -->
                                        </div><!-- .col -->
                                        <div class="col-md-12 col-lg-4">
                                            <div class="nk-wg-card is-s3 card card-bordered">
                                                <div class="card-inner">
                                                    <div class="nk-iv-wg2">
                                                        <div class="nk-iv-wg2-title">
                                                            <h6 class="title">Paid Orders </h6>
                                                        </div>
                                                        <div class="nk-iv-wg2-text">
                                                            <div class="nk-iv-wg2-amount"> <?php echo $paid_orders; ?></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div><!-- .card -->
                                        </div><!-- .col -->
                                    </div><!-- .row -->
                                </div><!-- .nk-block -->
                                <div class="nk-block">
                                    <div class="row gy-gs">
                                        <div class="col-md-6 col-lg-6">
                                            <div class="nk-wg-card card card-bordered h-100">
                                                <div class="card-inner h-100">
                                                    <div class="nk-iv-wg2">
                                                        <div class="nk-iv-wg2-title">
                                                            <h6 class="title">Recent Orders </h6>
                                                        </div>
                                                        <div class="nk-iv-wg2-text">
                                                            <div class="nk-iv-wg2-amount ui-v2"><?php echo $orders; ?></div>

                                                            <ul class="nk-activity">
                                                                <?php
                                                                /* Load Orders Logs */
                                                                $ret = "SELECT * FROM  orders o
                                                                    INNER JOIN users s ON s.user_id = o.order_user_id
                                                                    INNER JOIN meals m ON m.meal_id = o.order_meal_id 
                                                                    ORDER BY o.order_date_posted DESC
                                                                    LIMIT 15
                                                                    ";
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
                                                                                <?php echo $orders->user_name; ?> Has Ordered <?php echo $orders->meal_name . '. Quantity: ' . $orders->order_quantity; ?>
                                                                            </div>
                                                                            <span class="time"><?php echo date('d M Y, g:ia', strtotime($orders->order_date_posted)); ?></span>
                                                                        </div>
                                                                    </li>
                                                                <?php } ?>
                                                            </ul>
                                                        </div>
                                                        <div class="nk-iv-wg2-cta">
                                                            <a href="cashier_orders" class="btn btn-primary">View All</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div><!-- .card -->
                                        </div><!-- .col -->
                                        <div class="col-md-6 col-lg-6">
                                            <div class="nk-wg-card card card-bordered h-100">
                                                <div class="card-inner h-100">
                                                    <div class="nk-iv-wg2">
                                                        <div class="nk-iv-wg2-title">
                                                            <h6 class="title">Orders Payments</h6>
                                                        </div>
                                                        <div class="nk-iv-wg2-text">
                                                            <div class="nk-iv-wg2-amount ui-v2">Ksh <?php echo $overall_income; ?></div>
                                                            <div class="timeline">
                                                                <ul class="timeline-list">
                                                                    <?php
                                                                    $ret = "SELECT * FROM  payments p
                                                                        INNER JOIN orders o ON o.order_id  = p.payment_order_id
                                                                        INNER JOIN users s ON s.user_id = o.order_user_id
                                                                        INNER JOIN meals m ON m.meal_id = o.order_meal_id 
                                                                        ORDER BY p.payment_date_posted DESC
                                                                        LIMIT 15
                                                                        ";
                                                                    $stmt = $mysqli->prepare($ret);
                                                                    $stmt->execute(); //ok
                                                                    $res = $stmt->get_result();
                                                                    while ($payments = $res->fetch_object()) {

                                                                    ?>
                                                                        <li class="timeline-item">
                                                                            <div class="timeline-status bg-primary is-outline"></div>
                                                                            <div class="timeline-date"><?php echo date('d M Y g:ia', strtotime($payments->payment_date_posted)); ?></div>
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
                                                        <div class="nk-iv-wg2-cta">
                                                            <a href="cashier_payments" class="btn btn-primary">View All</a>
                                                        </div>
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
                <?php require_once('../partials/cashier_footer.php'); ?>
                <!-- footer @e -->
            </div>
            <!-- wrap @e -->
        </div>
        <!-- app-root @e -->
        <!-- JavaScript -->
        <?php require_once('../partials/scripts.php'); ?>
</body>

</html>