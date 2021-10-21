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
/* Update Profile */
if (isset($_POST['update_profile'])) {

    $user_id = $_SESSION['user_id'];
    $user_name = $_POST['user_name'];
    $user_email = $_POST['user_email'];
    $user_phone_no = $_POST['user_phone_no'];

    $query = 'UPDATE users SET user_name =?, user_email =?, user_phone_no =? WHERE user_id = ?';
    $stmt = $mysqli->prepare($query);
    $rc = $stmt->bind_param(
        'ssss',
        $user_name,
        $user_email,
        $user_phone_no,
        $user_id
    );

    $stmt->execute();
    if ($stmt) {
        $success = 'Account Updated';
    } else {
        $err = 'Please Try Again Or Try Later';
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
                /* Pop Logged In User Session */
                $user_id = $_SESSION['user_id'];
                $ret = "SELECT * FROM  users  ";
                $stmt = $mysqli->prepare($ret);
                $stmt->execute(); //ok
                $res = $stmt->get_result();
                while ($user = $res->fetch_object()) {
                ?>
                    <!-- main header @e -->
                    <!-- content @s -->
                    <div class="nk-content ">
                        <div class="container-fluid">
                            <div class="nk-content-inner">
                                <div class="nk-content-body">
                                    <div class="nk-block">
                                        <div class="card card-bordered">
                                            <div class="card-aside-wrap">
                                                <div class="card-inner card-inner-lg">
                                                    <div class="nk-block-head nk-block-head-lg">
                                                        <div class="nk-block-between">
                                                            <div class="nk-block-head-content">
                                                                <h4 class="nk-block-title">Update Personal Information</h4>
                                                                <div class="nk-block-des">
                                                                    <p>Basic Information</p>
                                                                </div>
                                                            </div>
                                                            <div class="nk-block-head-content align-self-start d-lg-none">
                                                                <a href="#" class="toggle btn btn-icon btn-trigger mt-n1" data-target="userAside"><em class="icon ni ni-menu-alt-r"></em></a>
                                                            </div>
                                                        </div>
                                                    </div><!-- .nk-block-head -->
                                                    <form method="POST">
                                                        <div class="nk-block">
                                                            <div class="row gy-4">
                                                                <div class="col-md-12">
                                                                    <div class="form-group">
                                                                        <label class="form-label" for="full-name">Full Name</label>
                                                                        <input type="text" name="user_name" required value="<?php echo $user->user_name; ?>" class="form-control form-control-lg">
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-12">
                                                                    <div class="form-group">
                                                                        <label class="form-label" for="display-name">Email</label>
                                                                        <input type="email" value="<?php echo $user->user_email; ?>" name="user_email" required class="form-control form-control-lg" id="display-name">
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-12">
                                                                    <div class="form-group">
                                                                        <label class="form-label" for="phone-no">Phone Number</label>
                                                                        <input type="text" value="<?php echo $user->user_phone_no; ?>" name="user_phone_no" class="form-control form-control-lg" id="phone-no">
                                                                    </div>
                                                                </div>
                                                                <div class="col-12 text-right">
                                                                    <input type="submit" name="update_profile" class="btn btn-lg btn-primary" value="Update Profile" />
                                                                </div>
                                                            </div>
                                                        </div><!-- .nk-block -->
                                                    </form>

                                                </div>
                                                <div class="card-aside card-aside-left user-aside toggle-slide toggle-slide-left toggle-break-lg" data-content="userAside" data-toggle-screen="lg" data-toggle-overlay="true">
                                                    <div class="card-inner-group" data-simplebar>
                                                        <div class="card-inner">
                                                            <div class="user-card">
                                                                <div class="user-avatar bg-primary">
                                                                    <span>
                                                                        <?php echo substr($user->user_name, 0, 2); ?>
                                                                    </span>
                                                                </div>
                                                                <div class="user-info">
                                                                    <span class="lead-text"><?php echo $user->user_name; ?></span>
                                                                    <span class="sub-text"><?php echo $user->user_email; ?></span>
                                                                </div>
                                                            </div><!-- .user-card -->
                                                        </div><!-- .card-inner -->

                                                        <div class="card-inner p-0">
                                                            <ul class="link-list-menu">
                                                                <li><a href="profile"><em class="icon ni ni-user-fill-c"></em><span>Personal Infomation</span></a></li>
                                                                <li><a class="active" href="profile_settings"><em class="icon ni ni-setting"></em><span>Settings</span></a></li>
                                                                <li><a href="profile_change_password"><em class="icon ni ni-lock"></em><span>Change Password</span></a></li>
                                                            </ul>
                                                        </div><!-- .card-inner -->
                                                    </div><!-- .card-inner-group -->
                                                </div><!-- card-aside -->
                                            </div><!-- .card-aside-wrap -->
                                        </div><!-- .card -->
                                    </div><!-- .nk-block -->
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
    <?php require_once('../partials/scripts.php'); ?>
</body>

</html>