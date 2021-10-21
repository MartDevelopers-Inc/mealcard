<?php
/*
 * Created on Thu Oct 14 2021
 *
 *  Devlan - devlan.co.ke 
 *
 * devlaninc18@gmail.com
 *
 * +254 740 847 563 / +254 799 155 770
 *
 * The Devlan End User License Agreement
 *
 * Copyright (c) 2021 DevLan
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
require_once '../config/config.php';
require_once '../config/checklogin.php';
require_once '../config/codeGen.php';
staff_checklogin();


require_once('../partials/head.php');
?>

<body>
    <div id="preloader">
        <div class="sk-three-bounce">
            <div class="sk-child sk-bounce1"></div>
            <div class="sk-child sk-bounce2"></div>
            <div class="sk-child sk-bounce3"></div>
        </div>
    </div>
    <div id="main-wrapper">
        <?php require_once('../partials/header.php'); ?>
        <?php
        require_once('../partials/sidebar.php');
        /* Load This Page With Logged In User Session */
        $view = $_GET['view'];
        $ret = "SELECT * FROM client WHERE client_id = '$view'  ";
        $stmt = $mysqli->prepare($ret);
        $stmt->execute(); //ok
        $res = $stmt->get_result();
        while ($client = $res->fetch_object()) {

        ?>

            <div class="content-body">
                <div class="container-fluid">
                    <div class="page-titles">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="staff_dashboard">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="staff_clients">Clients</a></li>
                            <li class="breadcrumb-item active"><a href=""><?php echo $client->client_name; ?></a></li>
                        </ol>
                    </div>
                    <!-- row -->
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="profile card card-body px-3 pt-3 pb-0">
                                <div class="profile-head">
                                    <div class="photo-content">
                                        <div class="cover-photo"></div>
                                    </div>
                                    <div class="profile-info">
                                        <div class="profile-photo">
                                            <img src="../public/backend_assets/images/avatar/no-profile.png" class="img-fluid rounded-circle" alt="">
                                        </div>
                                        <div class="profile-details">
                                            <div class="profile-name px-3 pt-2">
                                                <h4 class="text-primary mb-0"><?php echo $client->client_name; ?></h4>
                                                <br>
                                                <p>Phone Number : <?php echo $client->client_phone; ?></p>
                                                <p>Email : <?php echo $client->client_email; ?></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xl-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="profile-tab">
                                        <div class="custom-tab-1">
                                            <ul class="nav nav-tabs">
                                                <li class="nav-item">
                                                    <a href="#booking_history" data-toggle="tab" class="nav-link active">Bookings History</a>
                                                </li>
                                                <li class="nav-item">
                                                    <a href="#payment_history" data-toggle="tab" class="nav-link">Payment History</a>
                                                </li>
                                                <?php
                                                if ($_SESSION['staff_rank'] == 'admin') {
                                                    /* Allow Admins To Update Staffs */
                                                ?>
                                                    <li class="nav-item">
                                                        <a href="#change-password" data-toggle="tab" class="nav-link">Change Password</a>
                                                    </li>
                                                <?php } ?>
                                            </ul>
                                            <div class="tab-content">

                                                <div id="booking_history" class="tab-pane fade tab-pane fade active show">
                                                    <div class="pt-3">
                                                        <div class="settings-form">
                                                            <hr>
                                                            <!-- Load This Client Reservations / Bookings History -->
                                                            <table class="data-table display min-w850">
                                                                <thead>
                                                                    <tr>
                                                                        <th>Barber Details</th>
                                                                        <th>Service Details</th>
                                                                        <th>Bookings Date</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <?php
                                                                    $ret = "SELECT * FROM client_reservations cr
                                                                    INNER JOIN services s ON s.service_id = cr.client_reservation_service_id
                                                                    INNER JOIN staff st ON st.staff_id = cr.client_reservation_service_staff_id
                                                                    WHERE cr.client_reservation_client_id = '$view'";
                                                                    $stmt = $mysqli->prepare($ret);
                                                                    $stmt->execute(); //ok
                                                                    $res = $stmt->get_result();
                                                                    while ($reservaions = $res->fetch_object()) {
                                                                    ?>
                                                                        <tr>
                                                                            <td>
                                                                                <b>Name :</b> <?php echo $reservaions->staff_name; ?> <br>
                                                                                <b>Email :</b> <?php echo $reservaions->staff_email; ?><br>
                                                                                <b>Phone :</b> <?php echo $reservaions->staff_phone; ?>
                                                                            </td>
                                                                            <td>
                                                                                <b>Service :</b> <?php echo $reservaions->service_name; ?> <br>
                                                                                <b>Rate:</b>Ksh <?php echo $reservaions->service_rate; ?><br>
                                                                            </td>
                                                                            <td>
                                                                                <?php echo date('d M Y', strtotime($reservaions->client_reservation_date_reserved)); ?>
                                                                            </td>
                                                                        </tr>
                                                                    <?php
                                                                    }
                                                                    ?>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div id="payment_history" class="tab-pane fade tab-pane fade ">
                                                    <div class="pt-3">
                                                        <div class="settings-form">
                                                            <hr>
                                                            <!-- Load This Client Reservations / Bookings Payments History -->
                                                            <table class="data-table display min-w850">
                                                                <thead>
                                                                    <tr>
                                                                        <th>Bookings Date</th>
                                                                        <th>Payment Details</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <?php
                                                                    $ret = "SELECT * FROM payments p 
                                                                    INNER JOIN client_reservations cr ON cr.client_reservation_id = p.payment_client_reservation_id
                                                                    INNER JOIN services s ON s.service_id  = cr.client_reservation_service_id
                                                                    WHERE p.payment_client_id = '$view' ";
                                                                    $stmt = $mysqli->prepare($ret);
                                                                    $stmt->execute(); //ok
                                                                    $res = $stmt->get_result();
                                                                    while ($payments = $res->fetch_object()) {
                                                                    ?>
                                                                        <tr>
                                                                            <td>
                                                                                <b>Service :</b> <?php echo $payments->service_name; ?> <br>
                                                                                <b>Rate:</b>Ksh <?php echo $payments->service_rate; ?>
                                                                            </td>
                                                                            <td>
                                                                                <b>Confirmation ID :</b> <?php echo $payments->payment_confirmation_code; ?> <br>
                                                                                <b>Amount :</b>Ksh <?php echo $payments->service_rate; ?><br>
                                                                                <b>Date Posted :</b> <?php echo date('d M Y g:ia', strtotime($payments->payment_date_posted)); ?>
                                                                            </td>
                                                                        </tr>
                                                                    <?php
                                                                    }
                                                                    ?>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div id="change-password" class="tab-pane fade tab-pane fade">
                                                    <div class="pt-3">
                                                        <div class="settings-form">
                                                            <hr>
                                                            <form method="post">
                                                                <div class="form-row">
                                                                    <div class="form-group col-md-6">
                                                                        <label>New Password</label>
                                                                        <input type="password" name="new_password" required class="form-control">
                                                                    </div>
                                                                    <div class="form-group col-md-6">
                                                                        <label>Confirm Password</label>
                                                                        <input type="password" name="confirm_password" required class="form-control">
                                                                    </div>
                                                                </div>
                                                                <div class="text-right">
                                                                    <button name="update_password" class="btn btn-primary" type="submit">
                                                                        Update Password
                                                                    </button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        <?php }
        require_once('../partials/footer.php'); ?>
    </div>
    <?php require_once('../partials/scripts.php'); ?>
</body>

</html>