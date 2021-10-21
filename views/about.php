<?php
/*
 * Created on Wed Oct 13 2021
 *
 * The Devlan End User License Agreement
 * Copyright (c) 2021 DevLan
 *
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
require_once '../config/config.php';
require_once '../config/checklogin.php';
require_once('../partials/landing_head.php');
?>

<body>

    <?php
    require_once('../partials/landing_navbar.php');
    $ret = "SELECT * FROM system_settings ";
    $stmt = $mysqli->prepare($ret);
    $stmt->execute(); //ok
    $res = $stmt->get_result();
    while ($system_settings = $res->fetch_object()) {
    ?>
        <!-- END header -->

        <section class="site-hero overlay" data-stellar-background-ratio="0.5" style="background-image: url(../public/landing_assets/images/big_image_1.jpg);">
            <div class="container">
                <div class="row align-items-center site-hero-inner justify-content-center">
                    <div class="col-md-8 text-center">

                        <div class="mb-5 element-animate">
                            <h1 class="mb-4">About Us</h1>
                            <p class="lead">
                                <?php echo $system_settings->sys_about; ?>
                            </p>
                        </div>

                    </div>
                </div>
            </div>
        </section>
        <!-- END section -->

        <section class="quick-info element-animate" data-animate-effect="fadeInLeft">
            <div class="container">
                <div class="row">
                    <div class="col-lg-8 bgcolor">
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <div class="media">
                                    <div class="mr-3 icon-wrap"><span class="icon ion-ios-telephone"></span></div>
                                    <div class="media-body">
                                        <h5><?php echo $system_settings->sys_contacts; ?></h5>
                                        <p>Call us 24/7 we will get back to you ASAP</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="media">
                                    <div class="mr-3 icon-wrap"><span class="icon ion-location"></span></div>
                                    <div class="media-body">
                                        <h5><?php echo $system_settings->sys_address; ?></h5>
                                        <p>Visit our barbershop in the above address</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="media">
                                    <div class="mr-3 icon-wrap"><span class="icon ion-android-time"></span></div>
                                    <div class="media-body">
                                        <h5>Daily: 8 am - 10 pm</h5>
                                        <p>Mon-Fri, Sunday <br> Saturday: Closed</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- END section -->


        <?php require_once('../partials/landing_footer.php'); ?>
        <!-- END footer -->

        <!-- loader -->
        <div id="loader" class="show fullscreen">
            <svg class="circular" width="48px" height="48px">
                <circle class="path-bg" cx="24" cy="24" r="22" fill="none" stroke-width="4" stroke="#eeeeee" />
                <circle class="path" cx="24" cy="24" r="22" fill="none" stroke-width="4" stroke-miterlimit="10" stroke="#f4b214" />
            </svg>
        </div>

        <?php require_once('../partials/landing_scripts.php'); ?>
</body>

</html>
<?php } ?>