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
/* Add Bookings */
if (isset($_POST['add_reservation'])) {

    $client_reservation_id = $sys_gen_id;
    $client_reservation_client_id = $_POST['client_reservation_client_id'];
    $client_reservation_service_id = $_POST['client_reservation_service_id'];
    $client_reservation_service_staff_id = $_POST['client_reservation_service_staff_id'];
    $client_reservation_date_reserved = $_POST['client_reservation_date_reserved'];
    $client_reservation_payment_status = $_POST['client_reservation_payment_status'];
    /* Log A Notification */
    $notification_details = "Your Reservation On Date $client_reservation_date_reserved, has been submitted successfully, we will notify you on approval";

    $query = 'INSERT INTO client_reservations (client_reservation_id,client_reservation_client_id,client_reservation_service_id,client_reservation_service_staff_id,client_reservation_date_reserved,client_reservation_payment_status) VALUES (?,?,?,?,?,?)';
    $log = "INSERT INTO notifications (notification_client_id, notification_details) VALUES(?,?)";

    $stmt = $mysqli->prepare($query);
    $log_stmt = $mysqli->prepare($log);

    $rc = $stmt->bind_param(
        'ssssss',
        $client_reservation_id,
        $client_reservation_client_id,
        $client_reservation_service_id,
        $client_reservation_service_staff_id,
        $client_reservation_date_reserved,
        $client_reservation_payment_status
    );
    $rc = $log_stmt->bind_param('ss', $client_reservation_client_id, $notification_details);

    $stmt->execute();
    $log_stmt->execute();

    if ($stmt && $log_stmt) {
        $success = 'Booking Confirmed, Proceed To Pay';
    } else {
        //inject alert that task failed
        $err = 'Please Try Again Or Try Later';
    }
}


/*Update Booking */
if (isset($_POST['update_reservation'])) {
    $client_reservation_id = $_POST['client_reservation_id'];
    $client_reservation_date_reserved = $_POST['client_reservation_date_reserved'];

    $sql = "UPDATE client_reservations SET client_reservation_date_reserved =? WHERE client_reservation_id =?";
    $stmt = $mysqli->prepare($sql);
    $rc = $stmt->bind_param(
        'ss',
        $client_reservation_date_reserved,
        $client_reservation_id

    );
    $stmt->execute();
    if ($stmt) {
        $success = 'Booking Updated';
    } else {
        //inject alert that task failed
        $err = 'Please Try Again Or Try Later';
    }
}

/* Delete Bookings */
if (isset($_GET['delete'])) {
    $client_reservation_id = $_GET['delete'];

    $sql = "DELETE  FROM client_reservations WHERE client_reservation_id ='$client_reservation_id'";
    $stmt = $mysqli->prepare($sql);
    $stmt->execute();
    if ($stmt) {
        $success = "Booking Deleted " && header("refresh:1; url=staff_bookings");
    } else {
        $err = "Please Try Again Or Try Later";
    }
}

/*Pay*/
if (isset($_POST['add_payment'])) {
    $client_reservation_id = $_POST['payment_client_reservation_id'];
    $client_reservation_payment_status = "Paid";
    $payment_id = $sys_gen_id;
    $payment_client_id = $_POST['payment_client_id'];
    $payment_amount = $_POST['payment_amount'];
    $payment_confirmation_code = $_POST['payment_confirmation_code'];
    $payment_client_reservation_id = $_POST['payment_client_reservation_id'];
    /* Log Payment */
    $notification_details = "Your reservation payment was successfull, hope you enjoy our services";

    $sql1 = "UPDATE client_reservations SET client_reservation_payment_status =? WHERE client_reservation_id =?";
    $sql2 = "INSERT INTO payments (payment_id,payment_client_id,payment_amount,payment_confirmation_code,payment_client_reservation_id) VALUES (?,?,?,?,?)";
    $log = "INSERT INTO notifications (notification_client_id, notification_details) VALUES(?,?)";

    $stmt1 = $mysqli->prepare($sql1);
    $stmt2 = $mysqli->prepare($sql2);
    $log_stmt = $mysqli->prepare($log);

    $rc1 = $stmt1->bind_param(
        'ss',
        $client_reservation_payment_status,
        $client_reservation_id

    );
    $rc2 = $stmt2->bind_param(
        'sssss',
        $payment_id,
        $payment_client_id,
        $payment_amount,
        $payment_confirmation_code,
        $payment_client_reservation_id
    );
    $rc = $log_stmt->bind_param('ss', $payment_client_id, $notification_details);

    $stmt1->execute();
    $stmt2->execute();
    $log_stmt->execute();

    if ($stmt1 && $stmt2 && $log_stmt) {
        $success = 'Booking Payment Posted';
    } else {
        $err = 'Please Try Again Or Try Later';
    }
}

/* Approve And DisApprove Reservations */
if (isset($_GET['reservation'])) {
    $client_reservation_id = $_GET['reservation'];
    $client_reservation_status = 'Approved';
    $client = $_GET['client'];

    /* Log A Notification */
    $notification_details = "Your Reservation has been  approved, hope you will enjoy your booked service.";

    $sql = "UPDATE  client_reservations SET  client_reservation_status ='$client_reservation_status'
    WHERE client_reservation_id = '$client_reservation_id' ";

    $log = "INSERT INTO notifications (notification_client_id, notification_details) VALUES(?,?)";

    $stmt = $mysqli->prepare($sql);
    $log_stmt = $mysqli->prepare($log);

    $rc = $log_stmt->bind_param('ss', $client, $notification_details);

    $stmt->execute();
    $log_stmt->execute();

    if ($stmt && $log_stmt) {
        $success = "Booking Updated " && header("refresh:1; url=staff_bookings");
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
                        <li class="breadcrumb-item active"><a href="">Clients Bookings</a></li>
                    </ol>
                </div>
                <!-- row -->
                <div class="text-right">
                    <button type="button" data-toggle="modal" data-target="#add_modal" class="btn btn-primary">Add Booking</button>
                </div>
                <hr>

                <!-- Add Staff  -->
                <div class="modal fade" id="add_modal">
                    <div class="modal-dialog  modal-xl">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title">Fill All Required Values</h4>
                                <button type="button" class="close" data-dismiss="modal">
                                    <span>&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form method="post" enctype="multipart/form-data">
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label>Client Name</label>
                                            <select class="form-control default-select" name="client_reservation_client_id" id="inputState">
                                                <?php
                                                $ret = "SELECT * FROM client";
                                                $stmt = $mysqli->prepare($ret);
                                                $stmt->execute(); //ok
                                                $res = $stmt->get_result();
                                                while ($client = $res->fetch_object()) {
                                                ?>
                                                    <option value="<?php echo $client->client_id; ?>"><?php echo $client->client_name; ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label>Service Name</label>
                                            <select class="form-control default-select" name="client_reservation_service_id" id="inputState">
                                                <?php
                                                $ret = "SELECT * FROM services";
                                                $stmt = $mysqli->prepare($ret);
                                                $stmt->execute(); //ok
                                                $res = $stmt->get_result();
                                                while ($service = $res->fetch_object()) {
                                                ?>
                                                    <option value="<?php echo $service->service_id; ?>"><?php echo $service->service_name; ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label>Barber Name</label>
                                            <select class="form-control default-select" name="client_reservation_service_staff_id" id="inputState">
                                                <?php
                                                $ret = "SELECT * FROM staff";
                                                $stmt = $mysqli->prepare($ret);
                                                $stmt->execute(); //ok
                                                $res = $stmt->get_result();
                                                while ($staffs = $res->fetch_object()) {
                                                ?>
                                                    <option value="<?php echo $staffs->staff_id; ?>"><?php echo $staffs->staff_name; ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>

                                        <div class="form-group col-md-6">
                                            <label>Booking Date</label>
                                            <input type="date" name="client_reservation_date_reserved" required class="form-control">
                                            <!-- By Default Payment Is Null -->
                                            <input type="hidden" name="client_reservation_payment_status" value="Unpaid" required class="form-control">
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <button name="add_reservation" class="btn btn-primary" type="submit">
                                            Save
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- End Add Staff Modal -->

                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Babershop Clients Reservations</h4>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="data-table display min-w850">
                                        <thead>
                                            <tr>
                                                <th>Client Details</th>
                                                <th>Barber Details</th>
                                                <th>Service Details</th>
                                                <th>Reservation Status</th>
                                                <th>Date Reserved</th>
                                                <th>Manage</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $ret = "SELECT * FROM client_reservations cr
                                            INNER JOIN client c ON c.client_id = cr.client_reservation_client_id
                                            INNER JOIN services s ON s.service_id = cr.client_reservation_service_id
                                            INNER JOIN staff st ON st.staff_id = cr.client_reservation_service_staff_id 
                                            JOIN  system_settings ";
                                            $stmt = $mysqli->prepare($ret);
                                            $stmt->execute(); //ok
                                            $res = $stmt->get_result();
                                            while ($reservations = $res->fetch_object()) {
                                                /* Convert Service Payment Amount  To Dollars Because Paypal Accepts Dollar */
                                                $converted_rate = 0.0090 * $reservations->service_rate;
                                            ?>
                                                <tr>
                                                    <td>
                                                        <b>Name :</b> <?php echo $reservations->client_name; ?> <br>
                                                        <b>Email :</b> <?php echo $reservations->client_email; ?><br>
                                                        <b>Phone :</b> <?php echo $reservations->client_phone; ?>
                                                    </td>
                                                    <td>
                                                        <b>Name :</b> <?php echo $reservations->staff_name; ?> <br>
                                                        <b>Email :</b> <?php echo $reservations->staff_email; ?><br>
                                                        <b>Phone :</b> <?php echo $reservations->staff_phone; ?>
                                                    </td>
                                                    <td>
                                                        <b>Service :</b> <?php echo $reservations->service_name; ?> <br>
                                                        <b>Rate:</b>Ksh <?php echo $reservations->service_rate; ?><br>
                                                    </td>
                                                    <td>
                                                        <?php
                                                        if ($reservations->client_reservation_payment_status == 'Paid') { ?>
                                                            Payment: <span class="text-success">Paid</span> <br>
                                                        <?php } else { ?>
                                                            Payment: <span class="text-danger">Unpaid</span> <br>
                                                        <?php }
                                                        if ($reservations->client_reservation_status == 'Approved') { ?>
                                                            Approval : <span class="text-success">Approved</span> <br>
                                                        <?php } else { ?>
                                                            Approval : <span class="text-danger">Pending</span> <br>
                                                        <?php } ?>
                                                    </td>
                                                    <td>
                                                        <?php echo date('d M Y', strtotime($reservations->client_reservation_date_reserved)); ?>
                                                    </td>
                                                    <td>
                                                        <div class="d-flex">
                                                            <!-- Pay Reservation -->
                                                            <?php
                                                            if ($reservations->client_reservation_payment_status == 'Unpaid') {
                                                            ?>
                                                                <a data-toggle="modal" href="#pay_<?php echo $reservations->client_reservation_id; ?>" class="btn btn-primary shadow btn-xs sharp mr-1"><i class="fas fa-hand-holding-usd"></i></a>
                                                            <?php
                                                            }
                                                            /* Approve And Disaprove Booking */
                                                            if ($reservations->client_reservation_status == 'Pending') {
                                                            ?>
                                                                <a data-toggle="modal" href="#approve_<?php echo $reservations->client_reservation_id; ?>" class="btn btn-primary shadow btn-xs sharp mr-1"><i class="fas fa-check"></i></a>

                                                            <?php }

                                                            if ($_SESSION['staff_rank'] == 'admin') {
                                                            ?>
                                                                <a data-toggle="modal" href="#update_<?php echo $reservations->client_reservation_id; ?>" class="btn btn-primary shadow btn-xs sharp mr-1"><i class="fa fa-pencil"></i></a>
                                                                <a data-toggle="modal" href="#delete_<?php echo $reservations->client_reservation_id; ?>" class="btn btn-danger shadow btn-xs sharp"><i class="fa fa-trash"></i></a>
                                                            <?php } ?>
                                                        </div>
                                                        <!-- Pay Reservation Modal -->
                                                        <div class="modal fade" id="pay_<?php echo $reservations->client_reservation_id; ?>">
                                                            <div class="modal-dialog  modal-lg">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <h4 class="modal-title">Pay Via MPESA</h4>
                                                                        <button type="button" class="close" data-dismiss="modal">
                                                                            <span>&times;</span>
                                                                        </button>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        <form method="post" enctype="multipart/form-data">
                                                                            <div class="form-row">
                                                                                <div class="form-group col-md-6">
                                                                                    <label>Mpesa Payment Confirmation Code</label>
                                                                                    <input type="text" value="<?php echo $sys_gen_paycode; ?>" name="payment_confirmation_code" required class="form-control">
                                                                                    <!-- Hide This -->
                                                                                    <input type="hidden" value="<?php echo $reservations->client_reservation_client_id; ?>" name="payment_client_id" required class="form-control">
                                                                                    <input type="hidden" value="<?php echo $reservations->client_reservation_id; ?>" name="payment_client_reservation_id" required class="form-control">

                                                                                </div>
                                                                                <div class="form-group col-md-6">
                                                                                    <label>Payment Amount (Ksh)</label>
                                                                                    <input type="text" readonly value="<?php echo $reservations->service_rate; ?>" name="payment_amount" required class="form-control">
                                                                                </div>
                                                                            </div>

                                                                            <div class="text-right">
                                                                                <button name="add_payment" class="btn btn-primary" type="submit">
                                                                                    Save
                                                                                </button>
                                                                            </div>
                                                                            <hr>
                                                                        </form>
                                                                        <br>
                                                                        <!-- Pay Via PayPal -->
                                                                        <h4 class="modal-title text-center">Pay Via Paypal</h4>
                                                                        <br>
                                                                        <form action="<?php echo $reservations->sandbox_url; ?>" method="post">
                                                                            <input type='hidden' name='business' value='<?php echo $reservations->sys_email; ?>'>
                                                                            <input type='hidden' name='item_name' value='<?php echo $reservations->service_name; ?>'>
                                                                            <input type='hidden' name='item_number' value='<?php echo $reservations->client_reservation_id; ?>'>
                                                                            <input type='hidden' name='amount' value='<?php echo $converted_rate; ?>'>
                                                                            <input type='hidden' name='no_shipping' value='1'>
                                                                            <input type='hidden' name='currency_code' value='USD'>
                                                                            <input type='hidden' name='cancel_return' value='http://127.0.0.1/barbershop/views/staff_bookings'>
                                                                            <input type='hidden' name='return' value='http://127.0.0.1/barbershop/views/staff_paypal_payment_confirmation?client=<?php echo $reservations->client_reservation_client_id; ?>&reservation=<?php echo $reservations->client_reservation_id; ?>&booking=<?php echo $reservations->client_reservation_client_id; ?>&amount=<?php echo $reservations->service_rate; ?>'>
                                                                            <input type="hidden" name="cmd" value="_xclick">
                                                                            <div class="text-center">
                                                                                <button type="submit" class="btn btn-primary">
                                                                                    <i class="fab fa-paypal"></i>
                                                                                    Pay With Paypal
                                                                                </button>
                                                                            </div>
                                                                        </form>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <!-- End Pay -->


                                                        <!-- Edit Modal -->
                                                        <div class="modal fade" id="update_<?php echo $reservations->client_reservation_id; ?>">
                                                            <div class="modal-dialog  modal-lg">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <h4 class="modal-title">Fill All Required Values</h4>
                                                                        <button type="button" class="close" data-dismiss="modal">
                                                                            <span>&times;</span>
                                                                        </button>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        <form method="post" enctype="multipart/form-data">
                                                                            <div class="form-row">
                                                                                <div class="form-group col-md-12">
                                                                                    <label>Booking Date</label>
                                                                                    <!-- Hide This -->
                                                                                    <input type="hidden" value="<?php echo $reservations->client_reservation_id; ?>" name="client_reservation_id" required class="form-control">
                                                                                    <input type="date" value="<?php echo $reservations->client_reservation_date_reserved; ?>" name="client_reservation_date_reserved" required class="form-control">
                                                                                </div>
                                                                            </div>
                                                                            <div class="text-right">
                                                                                <button name="update_reservation" class="btn btn-primary" type="submit">
                                                                                    Save
                                                                                </button>
                                                                            </div>
                                                                        </form>
                                                                    </div>

                                                                </div>
                                                            </div>
                                                        </div>
                                                        <!-- End Modal -->

                                                        <!-- Approve Reservation -->
                                                        <div class="modal fade" id="approve_<?php echo $reservations->client_reservation_id; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                            <div class="modal-dialog modal-dialog-centered" role="document">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <h5 class="modal-title" id="exampleModalLabel">CONFIRM APPROVAL</h5>
                                                                        <button type="button" class="close" data-dismiss="modal">
                                                                            <span>&times;</span>
                                                                        </button>
                                                                    </div>
                                                                    <div class="modal-body text-center text-danger">
                                                                        <h4>Approve This Reservation Record</h4>
                                                                        <br>
                                                                        <button type="button" class="text-center btn btn-success" data-dismiss="modal">No</button>
                                                                        <a href="staff_bookings?reservation=<?php echo $reservations->client_reservation_id; ?>&client=<?php echo $reservations->client_reservation_client_id; ?>" class="text-center btn btn-danger"> Approve </a>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <!-- Delete Modal -->
                                                        <div class="modal fade" id="delete_<?php echo $reservations->client_reservation_id; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                            <div class="modal-dialog modal-dialog-centered" role="document">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <h5 class="modal-title" id="exampleModalLabel">CONFIRM DELETE</h5>
                                                                        <button type="button" class="close" data-dismiss="modal">
                                                                            <span>&times;</span>
                                                                        </button>
                                                                    </div>
                                                                    <div class="modal-body text-center text-danger">
                                                                        <h4>Delete This Client Reservation Record</h4>
                                                                        <br>
                                                                        <button type="button" class="text-center btn btn-success" data-dismiss="modal">No</button>
                                                                        <a href="staff_bookings?delete=<?php echo $reservations->client_reservation_id; ?>" class="text-center btn btn-danger"> Delete </a>
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