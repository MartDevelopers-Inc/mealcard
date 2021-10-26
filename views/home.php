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
require_once('../partials/analytics.php');
checklogin();
require_once('../partials/head.php');
?>

<body class="nk-body npc-subscription has-aside ui-clean ">
    <div class="nk-app-root">
        <!-- main @s -->
        <div class="nk-main ">
            <!-- wrap @s -->
            <div class="nk-wrap ">
                <?php require_once('../partials/student_header.php');
                $user_id = $_SESSION['user_id'];
                $ret = "SELECT * FROM  users JOIN system_settings
                 WHERE user_id = '$user_id'  ";
                $stmt = $mysqli->prepare($ret);
                $stmt->execute(); //ok
                $res = $stmt->get_result();
                while ($student = $res->fetch_object()) {
                ?>
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
                                                    <h2 class="nk-block-title fw-normal">Welcome, <?php echo $student->user_name; ?></h2>
                                                    <div class="nk-block-des">
                                                        <p>Welcome To <?php echo $student->sys_name; ?>, Student Dashboard.</p>
                                                    </div>
                                                </div>
                                            </div>
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
                                                                                    <h6 class="title">My Orders</h6>
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
                                                                                    <h6 class="title">Meal Card Current Balance</h6>
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
                                                                                    <h6 class="title">Overall Expenditure</h6>
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
                                            </div><!-- .row -->
                                        </div><!-- .nk-block -->

                                    </div>
                                    <!-- footer @s -->
                                    <?php require_once('../partials/footer.php'); ?>
                                    <!-- footer @e -->
                                </div>
                            </div>
                        </div>
                    </div>
                <?php } ?>
                <!-- content @e -->
            </div>
            <!-- wrap @e -->
        </div>
        <!-- main @e -->
    </div>
    <!-- app-root @e -->
    <!-- JavaScript -->
    <?php require_once('../partials/scripts.php'); ?>

</body>

</html>