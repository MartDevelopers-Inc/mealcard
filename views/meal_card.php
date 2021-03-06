<?php
/*
 * Created on Sun Oct 24 2021
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
                $ret = "SELECT * FROM meal_cards mc
                INNER JOIN users s ON s.user_id = mc.card_owner_id
                WHERE mc.card_id = '$view'";
                $stmt = $mysqli->prepare($ret);
                $stmt->execute(); //ok
                $res = $stmt->get_result();
                while ($card = $res->fetch_object()) {
                    if ($card->user_profile_pic == '') {
                        $url = "../public/backend_assets/images/no-profile.png";
                    } else {
                        $url = "../public/backend_assets/images/$card->user_profile_pic";
                    }
                ?>
                    <!-- main header @e -->
                    <!-- content @s -->
                    <div class="nk-content ">
                        <div class="container-fluid">
                            <div class="nk-content-inner">
                                <div class="nk-content-body">
                                    <div class="nk-block-head nk-block-head-sm">
                                        <div class="nk-block-between g-3">
                                            <div class="nk-block-head-content">
                                                <h3 class="nk-block-title page-title"><?php echo $card->card_code_number; ?></h3>
                                                <div class="nk-block-des text-soft">
                                                    <nav>
                                                        <ul class="breadcrumb breadcrumb-arrow">
                                                            <li class="breadcrumb-item"><a href="dashboard">Home</a></li>
                                                            <li class="breadcrumb-item"><a href="meal_cards">Meal Card</a></li>
                                                            <li class="breadcrumb-item active"><?php echo $card->card_code_number; ?></li>
                                                        </ul>
                                                    </nav>
                                                </div>
                                            </div>
                                            <div class="nk-block-head-content">
                                                <a href="meal_cards" class="btn btn-outline-light bg-white d-none d-sm-inline-flex"><em class="icon ni ni-arrow-left"></em><span>Back</span></a>
                                                <a href="meal_cards" class="btn btn-icon btn-outline-light bg-white d-inline-flex d-sm-none"><em class="icon ni ni-arrow-left"></em></a>
                                            </div>
                                        </div>
                                    </div><!-- .nk-block-head -->
                                    <div class="nk-block">
                                        <div class="col-lg-12 col-md-6 col-sm-12">
                                            <div class="card card-bordered">
                                                <div class="card-inner">
                                                    <ul class="nav nav-tabs mt-n3">
                                                        <li class="nav-item">
                                                            <a class="nav-link active" data-toggle="tab" href="#tabItem5">
                                                                <em class="icon ni ni-cc-fill"></em><span>Card Details</span></a>
                                                        </li>
                                                        <li class="nav-item">
                                                            <a class="nav-link" data-toggle="tab" href="#tabItem6"><em class="icon ni ni-histroy"></em><span>Card Usage History</span></a>
                                                        </li>
                                                    </ul>
                                                    <div class="tab-content">
                                                        <div class="tab-pane active" id="tabItem5">
                                                            <div class="row">
                                                                <div class="col-lg-6 col-md-6 col-sm-12">
                                                                    <div class="card card-bordered">
                                                                        <img src="<?php echo $url; ?>" class="card-img-top img-thumbnail img-fluid" alt="">
                                                                    </div>
                                                                </div>

                                                                <div class="col-lg-6 col-md-6 col-sm-12">
                                                                    <div class="card card-bordered">
                                                                        <div class="card-inner">
                                                                            <p class="card-title"><b>Card Owner Name : </b> <?php echo $card->user_name; ?></p>
                                                                            <p class="card-title"><b>Card Owner Admission Number : </b> <?php echo $card->user_number; ?></p>
                                                                            <p class="card-title"><b>Card Code Number : </b> <?php echo $card->card_code_number; ?></p>
                                                                            <p class="card-title"><b>Card Loaded Amount Bal : </b> Ksh <?php echo $card->card_loaded_amount; ?></p>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                        </div>
                                                        <div class="tab-pane" id="tabItem6">
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
                                                                    $ret = "SELECT * FROM payments p
                                                                    INNER JOIN orders o ON o.order_id  = p.payment_order_id
                                                                    INNER JOIN meals m ON m.meal_id = o.order_meal_id
                                                                    INNER JOIN meal_categories mc ON mc.category_id = m.meal_category_id
                                                                    WHERE o.order_user_id = '$card->card_owner_id'";
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
                                                </div><!-- .card-aside-wrap -->
                                            </div><!-- .card -->
                                        </div><!-- .nk-block -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php } ?>
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
    <?php require_once('../partials/scripts.php'); ?>
</body>

</html>