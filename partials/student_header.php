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
$user_id = $_SESSION['user_id'];
$ret = "SELECT * FROM  users WHERE user_id = '$user_id'  ";
$stmt = $mysqli->prepare($ret);
$stmt->execute(); //ok
$res = $stmt->get_result();
while ($student = $res->fetch_object()) {
?>
    <div class="nk-header nk-header-fixed is-light">
        <div class="container-lg wide-xl">
            <div class="nk-header-wrap">
                <div class="nk-header-brand">
                    <a href="home" class="logo-link">
                        <img class="logo-light logo-img" src="../public/backend_assets/images/logo.png" alt="logo">
                        <img class="logo-dark logo-img" src="../public/backend_assets/images/logo.png" alt="logo-dark">
                    </a>
                </div><!-- .nk-header-brand -->
                <div class="nk-header-tools">
                    <ul class="nk-quick-nav">
                        <li class="dropdown user-dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                <div class="user-toggle">
                                    <div class="user-avatar sm">
                                        <em class="icon ni ni-user-alt"></em>
                                    </div>
                                    <div class="user-name dropdown-indicator d-none d-sm-block">Howdy, <?php echo $student->user_name; ?> !</div>
                                </div>
                            </a>
                            <div class="dropdown-menu dropdown-menu-md dropdown-menu-right dropdown-menu-s1">
                                <div class="dropdown-inner user-card-wrap bg-lighter d-none d-md-block">
                                    <div class="user-card">
                                        <div class="user-avatar">
                                            <span><?php echo substr($student->user_name, 0, 3); ?></span>
                                        </div>
                                        <div class="user-info">
                                            <span class="lead-text"><?php echo $student->user_name; ?></span>
                                            <span class="sub-text"><?php echo $student->user_number; ?></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="dropdown-inner">
                                    <ul class="link-list">
                                        <li><a href="my_profile"><em class="icon ni ni-user-alt"></em><span>View Profile</span></a></li>
                                        <li><a href="my_password_settings"><em class="icon ni ni-setting-alt"></em><span>Account Setting</span></a></li>
                                    </ul>
                                </div>
                                <div class="dropdown-inner">
                                    <ul class="link-list">
                                        <li><a href="logout"><em class="icon ni ni-signout"></em><span>Sign out</span></a></li>
                                    </ul>
                                </div>
                            </div>
                        </li><!-- .dropdown -->

                        <li class="d-lg-none">
                            <a href="#" class="toggle nk-quick-nav-icon mr-n1" data-target="sideNav"><em class="icon ni ni-menu"></em></a>
                        </li>
                    </ul><!-- .nk-quick-nav -->
                </div><!-- .nk-header-tools -->
            </div><!-- .nk-header-wrap -->
        </div><!-- .container-fliud -->
    </div>
<?php } ?>