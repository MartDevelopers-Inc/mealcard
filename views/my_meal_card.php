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
                                                        <li class="breadcrumb-item active">Meal Card</li>
                                                    </ul>
                                                </nav>
                                                <br>
                                                <h2 class="nk-block-title fw-normal">My Meal Card Details</h2>
                                            </div>
                                        </div>
                                    </div><!-- .nk-block-head -->

                                    <div class="nk-block">
                                        <div class="card card-bordered sp-plan">
                                            <?php
                                            $user_id = $_SESSION['user_id'];
                                            $ret = "SELECT * FROM meal_cards mc
                                            INNER JOIN users s ON s.user_id = mc.card_owner_id
                                            WHERE mc.card_owner_id = '$user_id'";
                                            $stmt = $mysqli->prepare($ret);
                                            $stmt->execute(); //ok
                                            $res = $stmt->get_result();
                                            while ($card = $res->fetch_object()) {
                                            ?>
                                                <div class="row no-gutters">
                                                    <div class="col-md-12">
                                                        <div class="sp-plan-info card-inner">
                                                            <div class="row gx-0 gy-3">
                                                                <div class="col-xl-9 col-sm-8">
                                                                    <div class="sp-plan-name">
                                                                        <h6 class="title">Card Holder :
                                                                            <?php echo $card->user_name; ?>

                                                                        </h6>
                                                                        <p>Card Number: <span class="text-base"><?php echo $card->card_code_number; ?></span></p>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div><!-- .sp-plan-info -->
                                                        <div class="sp-plan-desc card-inner">
                                                            <ul class="row gx-1">
                                                                <li class="col-6 col-lg-3">
                                                                    <p><span class="text-soft">Generated On</span> <?php echo date('M, d Y', strtotime($card->user_date_created)); ?></p>
                                                                </li>
                                                                <li class="col-6 col-lg-3">
                                                                    <p><span class="text-soft">Recuring</span> Yes</p>
                                                                </li>
                                                                <li class="col-6 col-lg-3">
                                                                    <p><span class="text-soft">Current Balance</span>Ksh <?php echo $card->card_loaded_amount; ?> </p>
                                                                </li>
                                                                <li class="col-6 col-lg-3">
                                                                    <p><span class="text-soft">Card Status</span>
                                                                        <?php
                                                                        if ($card->card_status == 'Active') {
                                                                        ?>
                                                                            <span class="text-success">Active</span>
                                                                        <?php } else { ?>
                                                                            <span class="text-danger">InActive</span>
                                                                        <?php } ?> </p>
                                                                </li>
                                                            </ul>
                                                        </div><!-- .sp-plan-desc -->
                                                    </div><!-- .col -->
                                                </div><!-- .row -->

                                            <?php } ?>

                                        </div><!-- .sp-plan -->
                                        <div class="card card-bordered sp-plan">
                                            <div class="text-center">
                                                <br>
                                                <h6 class="title">Card Usage History
                                            </div>
                                            <div class="card-body">
                                                <table class="datatable-init nk-tb-list nk-tb-ulist" data-auto-responsive="false">
                                                    <thead>
                                                        <tr class="nk-tb-item nk-tb-head">
                                                            <th class="nk-tb-col"><span class="sub-text">Meal Details</span></th>
                                                            <th class="nk-tb-col tb-col-mb"><span class="sub-text">Order Details</span></th>
                                                            <th class="nk-tb-col tb-col-md"><span class="sub-text">Payment Details</span></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php
                                                        /* Pop This Partial With All Meals Orders And Payments Done On This Card */
                                                        $ret =
                                                            "SELECT * FROM payments p
                                                    INNER JOIN orders o ON o.order_id  = p.payment_order_id
                                                    INNER JOIN meals m ON m.meal_id = o.order_meal_id
                                                    INNER JOIN meal_categories mc ON mc.category_id = m.meal_category_id
                                                    WHERE o.order_user_id = '$user_id'";
                                                        $stmt = $mysqli->prepare($ret);
                                                        $stmt->execute(); //ok
                                                        $res = $stmt->get_result();
                                                        while ($card_history = $res->fetch_object()) {
                                                        ?>
                                                            <tr class="nk-tb-item">
                                                                <td class="nk-tb-col tb-col-mb">
                                                                    <span class="tb-amount">Name : <?php echo $card_history->meal_name; ?></span>
                                                                    <span class="tb-amount">Category : <?php echo $card_history->category_name; ?></span>
                                                                    <span class="tb-amount">Price : Ksh <?php echo $card_history->meal_price; ?></span>
                                                                </td>
                                                                <td class="nk-tb-col tb-col-mb">
                                                                    <span class="tb-amount">Quantity : <?php echo $card_history->order_quantity; ?></span>
                                                                    <span class="tb-amount">Date Posted : <?php echo date('d M Y g:ia', strtotime($card_history->order_date_posted)); ?></span>
                                                                </td>
                                                                <td class="nk-tb-col tb-col-mb">
                                                                    <span class="tb-amount">Txn ID : <?php echo $card_history->payment_confirmation_code; ?></span>
                                                                    <span class="tb-amount">Amount Paid : Ksh <?php echo $card_history->payment_amount; ?></span>
                                                                    <span class="tb-amount">Date Posted : <?php echo date('d M Y g:ia', strtotime($card_history->payment_date_posted)); ?></span>
                                                                </td>
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