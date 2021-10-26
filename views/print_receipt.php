<?php
/*
 * Created on Mon Oct 25 2021
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
                <?php require_once('../partials/header.php');
                $view = $_GET['view'];
                $ret = "SELECT * FROM payments p
                INNER JOIN orders o ON o.order_id = p.payment_order_id
                INNER JOIN meals m ON m.meal_id = o.order_meal_id
                INNER JOIN meal_categories mc ON mc.category_id = m.meal_category_id
                INNER JOIN users s ON s.user_id  = o.order_user_id
                INNER JOIN system_settings
                WHERE p.payment_id = '$view'";
                $stmt = $mysqli->prepare($ret);
                $stmt->execute(); //ok
                $res = $stmt->get_result();
                while ($payments = $res->fetch_object()) {
                ?>
                    <div class="nk-content ">
                        <div class="container-fluid">
                            <div class="nk-content-inner">
                                <div class="nk-content-body">
                                    <div class="nk-block-head nk-block-head-sm">
                                        <div class="nk-block-between">
                                            <div class="nk-block-head-content">
                                                <h3 class="nk-block-title page-title">Meal Orders Payment Receipt</h3>
                                                <div class="nk-block-des text-soft">
                                                    <nav>
                                                        <ul class="breadcrumb breadcrumb-arrow">
                                                            <li class="breadcrumb-item"><a href="dashboard">Home</a></li>
                                                            <li class="breadcrumb-item"><a href="payments">Orders Payments</a></li>
                                                            <li class="breadcrumb-item active"><?php echo $payments->payment_confirmation_code; ?></li>
                                                        </ul>
                                                    </nav>
                                                </div>
                                            </div><!-- .nk-block-head-content -->
                                        </div><!-- .nk-block-between -->
                                    </div><!-- .nk-block-head -->
                                    <div class="nk-block">
                                        <div class="nk-block">
                                            <div class="invoice">
                                                <div class="invoice-action">
                                                    <button class="btn btn-icon btn-lg btn-white btn-dim btn-outline-primary" id="print" onclick="printContent('Print_Receipt');">
                                                        <em class="icon ni ni-printer-fill"></em>
                                                    </button>
                                                </div><!-- .invoice-actions -->
                                                <div class="invoice-wrap" id="Print_Receipt">
                                                    <div class="invoice-brand text-center">
                                                        <img src="../public/backend_assets/images/logo.png" alt="">
                                                        <br>
                                                        <br>
                                                        <h5>
                                                            <?php echo $payments->sys_name; ?><br>
                                                            <?php echo $payments->sys_paypal_email; ?> <br>
                                                            <?php echo $payments->sys_contacts; ?> <br>
                                                        </h5>
                                                        <hr>
                                                        <h5 class="title">Meal Order Payment Receipt</h5>
                                                    </div>
                                                    <div class="invoice-head">
                                                        <div class="invoice-contact">
                                                            <div class="invoice-contact-info">
                                                                <h4 class="title"><?php echo $payments->user_name; ?></h4>
                                                                <ul class="list-plain">
                                                                    <li><em class="icon ni ni-user-list"></em><span><?php echo $payments->user_number; ?><br><?php echo $payments->user_email; ?></span></li>
                                                                    <li><em class="icon ni ni-call-fill"></em><span><?php echo $payments->user_phone_no; ?></span></li>
                                                                </ul>
                                                            </div>
                                                        </div>
                                                        <div class="invoice-desc">
                                                            <ul class="list-plain">
                                                                <li class="invoice-id"><span>Receipt #</span>:<span><?php echo $a . $b; ?></span></li>
                                                                <li class="invoice-date"><span>Date</span>:<span><?php echo date('d, M Y'); ?></span></li>
                                                                <li class="invoice-id"><span>TXN # </span>:<span><?php echo $payments->payment_confirmation_code; ?></span></li>
                                                            </ul>
                                                        </div>
                                                    </div><!-- .invoice-head -->
                                                    <div class="invoice-bills">
                                                        <div class="table-responsive">
                                                            <table class="table table-striped">
                                                                <thead>
                                                                    <tr>
                                                                        <th>Meal Name</th>
                                                                        <th>Meal Category</th>
                                                                        <th>Price</th>
                                                                        <th>Qty</th>
                                                                        <th>Amount</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <tr>
                                                                        <td><?php echo $payments->meal_name; ?></td>
                                                                        <td><?php echo $payments->category_name; ?></td>
                                                                        <td>Ksh <?php echo $payments->meal_price; ?></td>
                                                                        <td><?php echo $payments->order_quantity; ?></td>
                                                                        <td>Ksh <?php echo $payments->payment_amount; ?></td>
                                                                    </tr>
                                                                </tbody>
                                                                <tfoot>
                                                                    <tr>
                                                                        <td colspan="2"></td>
                                                                        <td colspan="2">Subtotal</td>
                                                                        <td>Ksh <?php echo $payments->payment_amount; ?></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td colspan="2"></td>
                                                                        <td colspan="2">Grand Total</td>
                                                                        <td>Ksh <?php echo $payments->payment_amount; ?></td>
                                                                    </tr>
                                                                </tfoot>
                                                            </table>
                                                            <div class="nk-notes ff-italic fs-12px text-soft"> This Is A System Generated Receipt & Its Valid Without The Signature And Seal. </div>
                                                        </div>
                                                    </div><!-- .invoice-bills -->
                                                </div><!-- .invoice-wrap -->
                                            </div><!-- .invoice -->
                                        </div><!-- .nk-block -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php } ?>
                <!-- conten @e -->
                <!-- footer @s -->
                <?php require_once('../partials/footer.php'); ?>
                <!-- footer @e -->
            </div>
            <!-- wrap @e -->
        </div>
        <!-- main @e -->
    </div>
    <!-- JavaScript -->
    <?php require_once('../partials/scripts.php'); ?>
</body>

</html>