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
checklogin();
/* Update Sys Details */
if (isset($_POST['update_sys_details'])) {
    $sys_name = $_POST['sys_name'];
    $sys_tagline = $_POST['sys_tagline'];
    $sys_contacts = $_POST['sys_contacts'];
    $sys_paypal_email = $_POST['sys_paypal_email'];

    /* Persist This */
    $sql = "UPDATE system_settings SET sys_name = ?, sys_tagline =?, sys_contacts =?, sys_paypal_email =?";
    $stmt = $mysqli->prepare($sql);
    $rc = $stmt->bind_param('ssss', $sys_name, $sys_tagline, $sys_contacts, $sys_paypal_email);
    $stmt->execute();
    if ($stmt) {
        $success = "System Details Updated";
    } else {
        $err = "Failed!, Please Try Again Later";
    }
}

/* Update Sys Payment Gateway Details */
if (isset($_POST['update_payment'])) {
    $sys_paybill_no = $_POST['sys_paybill_no'];
    $sys_standard_amount_loaded = $_POST['sys_standard_amount_loaded'];

    /* Persist This */
    $sql = "UPDATE system_settings SET sys_paybill_no =? WHERE  sys_standard_amount_loaded =?";
    $stmt = $mysqli->prepare($sql);
    $rc = $stmt->bind_param('ss', $sys_paybill_no, $sys_standard_amount_loaded);
    $stmt->execute();
    if ($stmt) {
        $success = "System Details Updated";
    } else {
        $err = "Failed!, Please Try Again Later";
    }
}

/* Update Mailer Settings */
if (isset($_POST['update_mailer'])) {
    $mailer_host = $_POST['mailer_host'];
    $mailer_username = $_POST['mailer_username'];
    $mailer_from_email = $_POST['mailer_from_email'];
    $mailer_password = $_POST['mailer_password'];

    /* Persist This */
    $sql = "UPDATE mailer_setttings SET mailer_host =?, mailer_username =?, mailer_from_email =?, mailer_password =? ";
    $stmt = $mysqli->prepare($sql);
    $rc = $stmt->bind_param('ssss', $mailer_host, $mailer_username, $mailer_from_email, $mailer_password);
    $stmt->execute();
    if ($stmt) {
        $success = "System Details Updated";
    } else {
        $err = "Failed!, Please Try Again Later";
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
                <?php require_once('../partials/header.php');
                $view = $_GET['view'];
                $ret = "SELECT * FROM system_settings JOIN mailer_setttings";
                $stmt = $mysqli->prepare($ret);
                $stmt->execute(); //ok
                $res = $stmt->get_result();
                while ($sys = $res->fetch_object()) {

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
                                                <h3 class="nk-block-title page-title">System Settings </h3>
                                                <div class="nk-block-des text-soft">
                                                    <nav>
                                                        <ul class="breadcrumb breadcrumb-arrow">
                                                            <li class="breadcrumb-item"><a href="dashboard">Home</a></li>
                                                            <li class="breadcrumb-item active">System Configurations</li>
                                                        </ul>
                                                    </nav>
                                                </div>
                                            </div>
                                        </div>
                                    </div><!-- .nk-block-head -->
                                    <div class="nk-block">
                                        <div class="card card-bordered">
                                            <div class="card-inner">
                                                <ul class="nav nav-tabs mt-n3">
                                                    <li class="nav-item">
                                                        <a class="nav-link active" data-toggle="tab" href="#tabItem5"><em class="icon ni ni-sign-steem"></em><span>System Core Settings</span></a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a class="nav-link" data-toggle="tab" href="#tabItem6"><em class="icon ni ni-money"></em><span>System Payment Gateway Settings</span></a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a class="nav-link" data-toggle="tab" href="#tabItem7"><em class="icon ni ni-emails"></em><span>System Mailer Settings</span></a>
                                                    </li>
                                                </ul>
                                                <div class="tab-content">
                                                    <div class="tab-pane active" id="tabItem5">
                                                        <form method="post" enctype="multipart/form-data" role="form">
                                                            <div class="card-body">
                                                                <div class="row">
                                                                    <div class="form-group col-md-6">
                                                                        <label for="">System Name</label>
                                                                        <input type="text" required name="sys_name" value="<?php echo $sys->sys_name; ?>" class="form-control">
                                                                    </div>
                                                                    <div class="form-group col-md-6">
                                                                        <label for="">Email Address</label>
                                                                        <input type="text" required value="<?php echo $sys->sys_paypal_email; ?>" name="sys_paypal_email" class="form-control">
                                                                    </div>
                                                                    <div class="form-group col-md-12">
                                                                        <label for="">System Contacts</label>
                                                                        <input type="text" required name="sys_contacts" value="<?php echo $sys->sys_contacts; ?>" class="form-control">
                                                                    </div>
                                                                    <div class="form-group col-md-12">
                                                                        <label for="">System Tagline</label>
                                                                        <textarea type="text" required name="sys_tagline" value="" class="form-control"><?php echo $sys->sys_tagline; ?></textarea>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="text-right">
                                                                <button type="submit" name="update_sys_details" class="btn btn-primary">Submit</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                    <div class="tab-pane" id="tabItem6">
                                                        <form method="post" enctype="multipart/form-data" role="form">
                                                            <div class="card-body">
                                                                <div class="row">
                                                                    <div class="form-group col-md-6">
                                                                        <label for="">System Paybill Number</label>
                                                                        <input type="text" required name="sys_paybill_no" value="<?php echo $sys->sys_paybill_no; ?>" class="form-control">
                                                                    </div>
                                                                    <div class="form-group col-md-6">
                                                                        <label for="">Default Amount Loaded To Each Meal Card (KSH)</label>
                                                                        <input type="text" required value="<?php echo $sys->sys_standard_amount_loaded; ?>" name="sys_standard_amount_loaded" class="form-control">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="text-right">
                                                                <button type="submit" name="update_payment" class="btn btn-primary">Submit</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                    <div class="tab-pane" id="tabItem7">
                                                        <form method="post" enctype="multipart/form-data" role="form">
                                                            <div class="card-body">
                                                                <div class="row">
                                                                    <div class="form-group col-md-6">
                                                                        <label for="">STMP Host</label>
                                                                        <input type="text" required name="mailer_host" value="<?php echo $sys->mailer_host; ?>" class="form-control">
                                                                    </div>
                                                                    <div class="form-group col-md-6">
                                                                        <label for="">STMP Username</label>
                                                                        <input type="text" required value="<?php echo $sys->mailer_username; ?>" name="mailer_username" class="form-control">
                                                                    </div>
                                                                    <div class="form-group col-md-6">
                                                                        <label for="">STMP Mail From</label>
                                                                        <input type="text" required value="<?php echo $sys->mailer_from_email; ?>" name="mailer_from_email" class="form-control">
                                                                    </div>
                                                                    <div class="form-group col-md-6">
                                                                        <label for="">STMP Password</label>
                                                                        <input type="password" required value="<?php echo $sys->mailer_password; ?>" name="mailer_password" class="form-control">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="text-right">
                                                                <button type="submit" name="update_mailer" class="btn btn-primary">Submit</button>
                                                            </div>
                                                        </form>
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
        </div>
    <?php } ?>
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