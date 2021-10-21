<?php
/*
 * Created on Fri Oct 15 2021
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
require_once '../partials/client_analytics.php';
client_checklogin();

/* Clear All Notifications */
if (isset($_GET['clear'])) {
    $client_id = $_GET['clear'];
    $sql = "DELETE  FROM notifications WHERE notification_client_id ='$client_id'";
    $stmt = $mysqli->prepare($sql);
    $stmt->execute();
    if ($stmt) {
        $success = "Cleared" && header("refresh:1; url=client_home");
    } else {
        $err = "Please Try Again Or Try Later";
    }
}
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


        <?php require_once('../partials/client_header.php'); ?>

        <?php require_once('../partials/client_sidebar.php'); ?>

        <div class="content-body">
            <!-- row -->
            <div class="container-fluid">
                <div class="row">
                    <div class="col-xl-3 col-xxl-4">
                        <div class="row">
                            <div class="col-xl-12 col-md-6">
                                <div class="card">
                                    <div class="card-header border-0 pb-0">
                                        <h4 class="fs-20">My Reservation Payment Logs</h4>
                                    </div>
                                    <div class="card-body pb-0 dz-scroll height630 loadmore-content" id="latestSalesContent">
                                        <?php
                                        $client_id = $_SESSION['client_id'];
                                        $ret = "SELECT * FROM payments p 
                                        INNER JOIN client c ON c.client_id = p.payment_client_id
                                        INNER JOIN client_reservations cr ON cr.client_reservation_id = p.payment_client_reservation_id
                                        INNER JOIN services s ON s.service_id  = cr.client_reservation_service_id
                                        INNER JOIN staff st ON st.staff_id = cr.client_reservation_service_staff_id
                                        WHERE p.payment_client_id  = '$client_id'
                                        ORDER BY p.payment_date_posted DESC

                                        ";
                                        $stmt = $mysqli->prepare($ret);
                                        $stmt->execute(); //ok
                                        $res = $stmt->get_result();
                                        while ($payments = $res->fetch_object()) {
                                        ?>
                                            <!-- Load Recent Payment Logs -->
                                            <div class="pb-3 mb-3 border-bottom">
                                                <p class="font-w600">
                                                    <a href="client_payments" class="text-success">
                                                        <?php echo $payments->payment_confirmation_code; ?> Confirmed
                                                    </a>
                                                </p>
                                                <div class="d-flex align-items-end">
                                                    <img src="../public/backend_assets/images/avatar/no-profile.png" alt="" width="42" class="rounded-circle mr-2">
                                                    <div class="mr-auto">
                                                        <h4 class="fs-14 mb-0">
                                                            <a href="client_payments" class="text-black">
                                                                You Paid Ksh <?php echo $payments->service_rate; ?>
                                                                For <?php echo $payments->service_name; ?> Booking On
                                                                <?php echo date('d M Y g:ia', strtotime($payments->payment_date_posted)); ?>
                                                            </a>
                                                            <br>
                                                        </h4>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php } ?>
                                    </div>
                                    <div class="card-footer text-center border-0">
                                        <a class="btn btn-primary btn-sm" href="client_payments">View More</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-9 col-xxl-8">
                        <div class="row">

                            <div class="col-xl-6 col-xxl-6 col-lg-6 col-sm-6">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <p class="fs-14 mb-1">My Bookings</p>
                                                <span class="fs-35 text-black font-w600">
                                                    <?php echo $bookings; ?>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-6 col-xxl-6 col-lg-6 col-sm-6">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <p class="fs-14 mb-1">My Overall Expenditure</p>
                                                <span class="fs-35 text-black font-w600">
                                                    Ksh <?php echo $overall_revenue; ?>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-12">
                                <div class="card">
                                    <div class="card-header border-0 pb-sm-0 pb-5">
                                        <h4 class="fs-20">My Recent Reservations</h4>
                                        <div class="dropdown custom-dropdown mb-0">
                                            <div data-toggle="dropdown">
                                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M12 12.9999C12.5523 12.9999 13 12.5522 13 11.9999C13 11.4477 12.5523 10.9999 12 10.9999C11.4477 10.9999 11 11.4477 11 11.9999C11 12.5522 11.4477 12.9999 12 12.9999Z" stroke="#7E7E7E" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                                    <path d="M12 5.99994C12.5523 5.99994 13 5.55222 13 4.99994C13 4.44765 12.5523 3.99994 12 3.99994C11.4477 3.99994 11 4.44765 11 4.99994C11 5.55222 11.4477 5.99994 12 5.99994Z" stroke="#7E7E7E" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                                    <path d="M12 19.9999C12.5523 19.9999 13 19.5522 13 18.9999C13 18.4477 12.5523 17.9999 12 17.9999C11.4477 17.9999 11 18.4477 11 18.9999C11 19.5522 11.4477 19.9999 12 19.9999Z" stroke="#7E7E7E" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                                </svg>
                                            </div>
                                            <div class="dropdown-menu dropdown-menu-right">
                                                <a class="dropdown-item" href="staff_bookings">Details</a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="data-table   display min-w850">
                                                <thead>
                                                    <tr>
                                                        <th>Barber Details</th>
                                                        <th>Service Details</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    $ret =
                                                        "SELECT * FROM client_reservations cr
                                                INNER JOIN client c ON c.client_id = cr.client_reservation_client_id
                                                INNER JOIN services s ON s.service_id = cr.client_reservation_service_id
                                                INNER JOIN staff st ON st.staff_id = cr.client_reservation_service_staff_id 
                                                WHERE c.client_id ='$client_id' ";
                                                    $stmt = $mysqli->prepare($ret);
                                                    $stmt->execute(); //ok
                                                    $res = $stmt->get_result();
                                                    while ($reservations = $res->fetch_object()) {
                                                    ?>
                                                        <tr>
                                                            <td>
                                                                <b>Name :</b> <?php echo $reservations->staff_name; ?> <br>
                                                                <b>Email :</b> <?php echo $reservations->staff_email; ?><br>
                                                                <b>Phone :</b> <?php echo $reservations->staff_phone; ?><br>
                                                                <b>Reservation Date : </b> <?php echo date('d M Y', strtotime($reservations->client_reservation_date_reserved)); ?>

                                                            </td>
                                                            <td>
                                                                <b>Service :</b> <?php echo $reservations->service_name; ?> <br>
                                                                <b>Rate:</b>Ksh <?php echo $reservations->service_rate; ?><br>
                                                                <?php
                                                                if ($reservations->client_reservation_payment_status == 'Paid') { ?>
                                                                    <b>Payment Status:</b><span class="text-success"> Paid</span>
                                                                <?php } else { ?>
                                                                    <b>Payment Status:</b> <span class="text-danger"> Unpaid</span>
                                                                <?php } ?>
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
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php require_once('../partials/footer.php'); ?>
    </div>
    <!-- Required vendors -->
    <?php require_once('../partials/scripts.php'); ?>
</body>

</html>