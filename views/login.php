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

/* Handle User Login  */
session_start();
require_once('../config/config.php');
require_once('../config/checklogin.php');
require_once('../config/codeGen.php');

if (isset($_POST['sign_in'])) {
    $user_email = $_POST['user_email'];
    $user_password = sha1(md5($_POST['user_password']));

    $stmt = $mysqli->prepare("SELECT user_email, user_password, user_access_level, user_id FROM users
     WHERE user_email=? AND user_password=? ");
    $stmt->bind_param('ss', $user_email, $user_password);
    $stmt->execute();
    $stmt->bind_result($user_email, $user_password, $user_access_level, $user_id);
    $rs = $stmt->fetch();

    /* Persist Sessions */
    $_SESSION['user_id'] = $user_id;
    $_SESSION['user_access_level'] = $user_access_level;

    if ($rs && $_SESSION['user_access_level'] == 'admin') {
        header("location:dashboard");
    } else if ($rs && $_SESSION['user_access_level'] == 'student') {
        header("location:home");
    } else {
        $err = "Access Denied Please Check Your Email Or Password";
    }
}
require_once('../partials/head.php');
/* Pop System Settings Here */
$ret = "SELECT * FROM  system_settings  ";
$stmt = $mysqli->prepare($ret);
$stmt->execute(); //ok
$res = $stmt->get_result();
while ($sys = $res->fetch_object()) {
?>

    <body class="nk-body npc-crypto ui-clean pg-auth">
        <!-- app body @s -->
        <div class="nk-app-root">
            <div class="nk-split nk-split-page nk-split-md">
                <div class="nk-split-content nk-block-area nk-block-area-column nk-auth-container">
                    <div class="absolute-top-right d-lg-none p-3 p-sm-5">
                        <a href="#" class="toggle btn-white btn btn-icon btn-light" data-target="athPromo"><em class="icon ni ni-info"></em></a>
                    </div>
                    <div class="nk-block nk-block-middle nk-auth-body">
                        <div class="nk-block-head">
                            <div class="nk-block-head-content">
                                <h5 class="nk-block-title">Sign In</h5>
                                <div class="nk-block-des">
                                    <p>Enter Your Email And Password, To Access Your <?php echo $sys->sys_name; ?> Dashboard.</p>
                                </div>
                            </div>
                        </div><!-- .nk-block-head -->

                        <form method="POST">
                            <div class="form-group">
                                <div class="form-label-group">
                                    <label class="form-label" for="default-01">Email </label>
                                </div>
                                <input type="email" name="user_email" required class="form-control form-control-lg">
                            </div><!-- .foem-group -->
                            <div class="form-group">
                                <div class="form-label-group">
                                    <label class="form-label" for="password">Password</label>
                                    <a class="link link-primary link-sm" tabindex="-1" href="forgot_password">Forgot Password?</a>
                                </div>
                                <div class="form-control-wrap">
                                    <a tabindex="-1" href="#" class="form-icon form-icon-right passcode-switch" data-target="password">
                                        <em class="passcode-icon icon-show icon ni ni-eye"></em>
                                        <em class="passcode-icon icon-hide icon ni ni-eye-off"></em>
                                    </a>
                                    <input type="password" name="user_password" required class="form-control form-control-lg" id="password">
                                </div>
                            </div><!-- .foem-group -->
                            <div class="form-group">
                                <button name="sign_in" type="submit" class="btn btn-lg btn-primary btn-block">Sign in</button>
                            </div>
                        </form><!-- form -->

                    </div><!-- .nk-block -->
                    <div class="nk-block nk-auth-footer">
                        <div class="mt-3">
                            <p>&copy; 2019 <?php echo $sys->sys_name; ?>. All Rights Reserved. A <a href="https://martdev.info" target="_blank">MartDevelopers Inc</a> Production</p>
                        </div>
                    </div><!-- .nk-block -->
                </div><!-- .nk-split-content -->

                <div class="nk-split-content nk-split-stretch bg-lighter d-flex toggle-break-lg toggle-slide toggle-slide-right" data-content="athPromo" data-toggle-screen="lg" data-toggle-overlay="true">
                    <div class="slider-wrap w-100 w-max-550px p-3 p-sm-5 m-auto">
                        <div class="slider-init">
                            <div class="slider-item">
                                <div class="nk-feature nk-feature-center">
                                    <div class="nk-feature-img">
                                        <img class="round" src="../public/backend_assets/images/slides/KCA_Auth_bg.png" alt="">
                                    </div>
                                    <div class="nk-feature-content py-4 p-sm-5">
                                        <h4><?php echo $sys->sys_name; ?></h4>
                                        <p><?php echo $sys->sys_tagline; ?></p>
                                    </div>
                                </div>
                            </div><!-- .slider-item -->
                        </div><!-- .slider-init -->
                    </div><!-- .slider-wrap -->
                </div><!-- .nk-split-content -->
            </div><!-- .nk-split -->
        </div><!-- app body @e -->
        <!-- JavaScript -->
        <?php require_once('../partials/scripts.php'); ?>
    </body>
<?php } ?>

</html>