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

/* Delete Payments */
if (isset($_GET['delete'])) {
    $payment_id = $_GET['delete'];
    $sql = "DELETE  FROM payments WHERE payment_id ='$payment_id'";
    $stmt = $mysqli->prepare($sql);
    $stmt->execute();
    if ($stmt) {
        $success = "Payment Removed " && header("refresh:1; url=staff_payments");
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

        <?php require_once('../partials/header.php'); ?>

        <?php require_once('../partials/sidebar.php'); ?>

        <div class="content-body">
            <div class="container-fluid">
                <div class="page-titles">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="staff_dashboard">Dashboard</a></li>
                        <li class="breadcrumb-item active"><a href="">Clients Bookings Payments</a></li>
                    </ol>
                </div>
                <hr>

                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Babershop Clients Reservations Payments</h4>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="data-table display min-w850">
                                        <thead>
                                            <tr>
                                                <th>Client Details</th>
                                                <th>Barber Details</th>
                                                <th>Service Details</th>
                                                <th>Payment Details</th>
                                                <th>Manage</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $ret = "SELECT * FROM payments p 
                                            INNER JOIN client c ON c.client_id = p.payment_client_id
                                            INNER JOIN client_reservations cr ON cr.client_reservation_id = p.payment_client_reservation_id
                                            INNER JOIN services s ON s.service_id  = cr.client_reservation_service_id
                                            INNER JOIN staff st ON st.staff_id = cr.client_reservation_service_staff_id
                                            ";
                                            $stmt = $mysqli->prepare($ret);
                                            $stmt->execute(); //ok
                                            $res = $stmt->get_result();
                                            while ($payments = $res->fetch_object()) {
                                            ?>
                                                <tr>
                                                    <td>
                                                        <b>Name :</b> <?php echo $payments->client_name; ?> <br>
                                                        <b>Email :</b> <?php echo $payments->client_email; ?><br>
                                                        <b>Phone :</b> <?php echo $payments->client_phone; ?>
                                                    </td>
                                                    <td>
                                                        <b>Name :</b> <?php echo $payments->staff_name; ?> <br>
                                                        <b>Email :</b> <?php echo $payments->staff_email; ?><br>
                                                        <b>Phone :</b> <?php echo $payments->staff_phone; ?>
                                                    </td>
                                                    <td>
                                                        <b>Service :</b> <?php echo $payments->service_name; ?> <br>
                                                        <b>Serice Rate :</b> Ksh <?php echo $payments->service_rate; ?><br>
                                                        <b>Date Booked :</b> <?php echo date('d M Y', strtotime($payments->client_reservation_date_reserved)); ?>
                                                    </td>
                                                    <td>
                                                        <b>Confirmation ID :</b> <?php echo $payments->payment_confirmation_code; ?> <br>
                                                        <b>Amount :</b>Ksh <?php echo $payments->payment_amount; ?><br>
                                                        <b>Date Posted :</b> <?php echo date('d M Y g:ia', strtotime($payments->payment_date_posted)); ?>
                                                    </td>
                                                    <td>
                                                        <div class="d-flex">
                                                            <?php
                                                            if ($_SESSION['staff_rank'] == 'admin') {
                                                            ?>
                                                                <a data-toggle="modal" href="#delete_<?php echo $payments->payment_id; ?>" class="btn btn-danger shadow btn-xs sharp"><i class="fa fa-trash"></i></a>
                                                            <?php } ?>
                                                        </div>
                                                        <!-- Delete Modal -->
                                                        <div class="modal fade" id="delete_<?php echo $payments->payment_id; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                            <div class="modal-dialog modal-dialog-centered" role="document">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <h5 class="modal-title" id="exampleModalLabel">CONFIRM DELETE</h5>
                                                                        <button type="button" class="close" data-dismiss="modal">
                                                                            <span>&times;</span>
                                                                        </button>
                                                                    </div>
                                                                    <div class="modal-body text-center text-danger">
                                                                        <h4>Delete This Client Reservation Payment Record</h4>
                                                                        <br>
                                                                        <button type="button" class="text-center btn btn-success" data-dismiss="modal">No</button>
                                                                        <a href="staff_payments?delete=<?php echo $payments->payment_id; ?>" class="text-center btn btn-danger"> Delete </a>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <!-- End Modal -->
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
        <?php require_once('../partials/footer.php'); ?>

    </div>
    <!-- Load Paypal Partial -->
    <?php require_once('../partials/scripts.php'); ?>
</body>

</html>