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
                                                <li class="breadcrumb-item"><a href="cashier_dashboard">Dashboard</a></li>
                                                <li class="breadcrumb-item active">Payments</li>
                                            </ul>
                                        </nav>
                                        <br>
                                        <div class="align-center flex-wrap pb-2 gx-4 gy-3">
                                            <div>
                                                <h2 class="nk-block-title fw-normal">Cafe Meals Orders Payments</h2>
                                            </div>
                                        </div><!-- .nk-block-head-content -->
                                    </div><!-- .nk-block-between -->
                                </div><!-- .nk-block-head -->
                                <div class="nk-block">
                                    <div class="row gy-gs">
                                        <div class="col-md-12 col-lg-12">
                                            <div class="nk-wg-card card card-bordered h-100">
                                                <div class="card  card-stretch">
                                                    <div class="card-inner-group">
                                                        <div class="card-preview">
                                                            <div class="card-inner">
                                                                <table class="datatable-init nk-tb-list nk-tb-ulist" data-auto-responsive="false">
                                                                    <thead>
                                                                        <tr class="nk-tb-item nk-tb-head">
                                                                            <th class="nk-tb-col"><span class="sub-text">Student Details</span></th>
                                                                            <th class="nk-tb-col tb-col-mb"><span class="sub-text">Order Details</span></th>
                                                                            <th class="nk-tb-col tb-col-md"><span class="sub-text">Payment Details</span></th>
                                                                            <th class="nk-tb-col nk-tb-col-tools text-right">
                                                                                <span class="sub-text">Action</span>
                                                                            </th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        <?php
                                                                        /* Pop This Partial With All Meal Cards */
                                                                        $ret = "SELECT * FROM payments p
                                                                        INNER JOIN orders o ON o.order_id = p.payment_order_id
                                                                        INNER JOIN meals m ON m.meal_id = o.order_meal_id
                                                                        INNER JOIN users s ON s.user_id  = o.order_user_id
                                                                        ";
                                                                        $stmt = $mysqli->prepare($ret);
                                                                        $stmt->execute(); //ok
                                                                        $res = $stmt->get_result();
                                                                        while ($payments = $res->fetch_object()) {
                                                                        ?>
                                                                            <tr class="nk-tb-item">
                                                                                <td class="nk-tb-col">
                                                                                    <div class="user-card">
                                                                                        <div class="user-avatar bg-dim-primary d-none d-sm-flex">
                                                                                            <span><?php echo substr($payments->user_name, 0, 2); ?></span>
                                                                                        </div>
                                                                                        <div class="user-info">
                                                                                            <span class="tb-lead"><?php echo $payments->user_name; ?></span>
                                                                                            <span class=""><?php echo $payments->user_number; ?></span>
                                                                                        </div>
                                                                                    </div>
                                                                                </td>
                                                                                <td class="nk-tb-col tb-col-md">
                                                                                    <span>Meal: <?php echo $payments->meal_name; ?></span><br>
                                                                                    <span>Qty: <?php echo $payments->order_quantity; ?></span><br>
                                                                                    <span>Date: <?php echo date('d M Y g:ia', strtotime($payments->order_date_posted)); ?></span>
                                                                                </td>
                                                                                <td class="nk-tb-col tb-col-md">
                                                                                    <span>Trxn ID: <?php echo $payments->payment_confirmation_code; ?></span><br>
                                                                                    <span>Amount: Ksh <?php echo $payments->payment_amount; ?></span><br>
                                                                                    <span>Date Paid: <?php echo date('d M Y g:ia', strtotime($payments->payment_date_posted)); ?></span>
                                                                                </td>

                                                                                <td class="nk-tb-col nk-tb-col-tools">
                                                                                    <ul class="nk-tb-actions gx-1">
                                                                                        <li>
                                                                                            <div class="drodown">
                                                                                                <a href="#" class="dropdown-toggle btn btn-icon btn-trigger" data-toggle="dropdown"><em class="icon ni ni-more-h"></em></a>
                                                                                                <div class="dropdown-menu dropdown-menu-right">
                                                                                                    <ul class="link-list-opt no-bdr">
                                                                                                        <li><a href="cashier_print_receipt?view=<?php echo $payments->payment_id; ?>"><em class="icon ni ni-printer"></em><span>Print Receipt</span></a></li>
                                                                                                    </ul>
                                                                                                </div>
                                                                                            </div>
                                                                                        </li>
                                                                                    </ul>
                                                                                </td>
                                                                            </tr>
                                                                        <?php } ?>
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div><!-- .card -->
                                                </div><!-- .nk-block -->
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