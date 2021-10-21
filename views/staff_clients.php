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
/* Add CLient */
if (isset($_POST['add_client'])) {
    $client_phone = $_POST['client_phone'];
    $client_email = $_POST['client_email'];
    $sql = "SELECT * FROM  client  WHERE client_phone ='$client_phone' || client_email = '$client_email' ";
    $res = mysqli_query($mysqli, $sql);
    if (mysqli_num_rows($res) > 0) {
        $row = mysqli_fetch_assoc($res);
        if (
            $client_phone == $row['client_phone'] ||
            $client_email == $row['client_email']
        ) {
            $err = 'Account With That Phone Number Or Email  Already Exists';
        }
    } else {
        $client_id = $sys_gen_id;
        $client_name = $_POST['client_name'];
        $client_email = $_POST['client_email'];
        $client_phone = $_POST['client_phone'];
        $client_password = sha1(md5($_POST['client_password']));


        $query = 'INSERT INTO client (client_id,client_name,client_email,client_phone,client_password) VALUES (?,?,?,?,?)';
        $stmt = $mysqli->prepare($query);
        $rc = $stmt->bind_param(
            'sssss',
            $client_id,
            $client_name,
            $client_email,
            $client_phone,
            $client_password

        );
        $stmt->execute();

        if ($stmt) {
            $success = ' ' . $client_name . ',is added as Client';
        } else {
            //inject alert that task failed
            $err = 'Please Try Again Or Try Later';
        }
    }
}
/* Update Client */
if(isset($_POST['update_client']))
{
    $client_id = $_POST['client_id'];
    $client_name = $_POST['client_name'];
    $client_email = $_POST['client_email'];
    $client_phone = $_POST['client_phone'];
    $sql ="UPDATE client SET client_name =? ,client_email =? ,client_phone =? WHERE client_id =?";
    $stmt = $mysqli->prepare($sql);
    $rc=$stmt->bind_param(
        'ssss',
        $client_name,
        $client_email,
        $client_phone,
        $client_id

    ) ;
    $stmt->execute();
    if($stmt)
    {
        $success = '' . $client_name . ' details updated';
    } else {
        //inject alert that task failed
        $err = 'Please Try Again Or Try Later';
    }
}

/* Delete Client */
if (isset($_GET['delete'])) {
    $client_id = $_GET['delete'];
    $sql = "SELECT * FROM client WHERE client_id ='$client_id'";
    $stmt = $mysqli->prepare($sql);
    $stmt->execute(); //ok
    $res = $stmt->get_result();
    while ($client = $res->fetch_object()) {
        /* Delete client Profile From UPloaded Directory */
        unlink('../public/backend_assets/images/' . $client->client_profile . '');
        $sql = "DELETE  FROM client WHERE client_id ='$client_id'";
        $stmt = $mysqli->prepare($sql);
        $stmt->execute();
        if ($stmt) {
            $success = "Removed '.$client->client_name" && header("refresh:1; url=staff_clients");
        } else {
            $err = "Please Try Again Or Try Later";
        }
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
                        <li class="breadcrumb-item active"><a href="">Clients</a></li>
                    </ol>
                </div>
                <!-- row -->
                <div class="text-right">
                    <button type="button" data-toggle="modal" data-target="#add_modal" class="btn btn-primary">Add Client</button>
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
                                            <label>Full Name</label>
                                            <input type="text" name="client_name" required class="form-control">
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label>Phone Number</label>
                                            <input type="text" name="client_phone" required class="form-control">
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label>Email</label>
                                            <input type="text" name="client_email" required class="form-control">
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label>Password</label>
                                            <input type="text" name="client_password" required class="form-control">
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <button name="add_client" class="btn btn-primary" type="submit">
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
                                <h4 class="card-title">Babershop Loyal Clients</h4>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="data-table display min-w850">
                                        <thead>
                                            <tr>
                                                <th>Name</th>
                                                <th>Contacts</th>
                                                <th>Email</th>
                                                <th>Manage</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $ret = "SELECT * FROM client  ";
                                            $stmt = $mysqli->prepare($ret);
                                            $stmt->execute(); //ok
                                            $res = $stmt->get_result();
                                            while ($client = $res->fetch_object()) {
                                            ?>
                                                <tr>
                                                    <td><?php echo $client->client_name; ?></td>
                                                    <td><?php echo $client->client_phone; ?></td>
                                                    <td><?php echo $client->client_email; ?></td>
                                                    <td>
                                                        <div class="d-flex">
                                                            <a href="staff_client?view=<?php echo $client->client_id; ?>" class="btn btn-primary shadow btn-xs sharp mr-1"><i class="fa fa-eye"></i></a>
                                                            <?php
                                                            if ($_SESSION['staff_rank'] == 'admin') {
                                                            ?>
                                                                <a data-toggle="modal" href="#update_<?php echo $client->client_id; ?>" class="btn btn-primary shadow btn-xs sharp mr-1"><i class="fa fa-pencil"></i></a>
                                                                <a data-toggle="modal" href="#delete_<?php echo $client->client_id; ?>" class="btn btn-danger shadow btn-xs sharp"><i class="fa fa-trash"></i></a>
                                                            <?php } ?>
                                                        </div>
                                                        <!-- Edit Modal -->
                                                        <div class="modal fade" id="update_<?php echo $client->client_id; ?>">
                                                            <div class="modal-dialog  modal-lg">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <h4 class="modal-title">Fill All Required Values</h4>
                                                                        <button type="button" class="close" data-dismiss="modal">
                                                                            <span>&times;</span>
                                                                        </button>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        <div class="settings-form">
                                                                            <form method="post" enctype="multipart/form-data">
                                                                                <div class="form-row">
                                                                                    <div class="form-group col-md-6">
                                                                                        <label>Full Name</label>
                                                                                        <input type="text" name="client_name" value="<?php echo $client->client_name; ?>" required class="form-control">
                                                                                        <!-- Hide This -->
                                                                                        <input type="hidden" name="client_id" value="<?php echo $client->client_id; ?>" required class="form-control">
                                                                                    </div>
                                                                                    <div class="form-group col-md-6">
                                                                                        <label>Phone Number</label>
                                                                                        <input type="text" name="client_phone" value="<?php echo $client->client_phone; ?>" required class="form-control">
                                                                                    </div>
                                                                                    <div class="form-group col-md-12">
                                                                                        <label>Email</label>
                                                                                        <input type="text" name="client_email" value="<?php echo $client->client_email; ?>" required class="form-control">
                                                                                    </div>

                                                                                </div>
                                                                                <div class="text-right">
                                                                                    <button name="update_client" class="btn btn-primary" type="submit">
                                                                                        Save
                                                                                    </button>
                                                                                </div>
                                                                            </form>
                                                                        </div>
                                                                    </div>

                                                                </div>
                                                            </div>
                                                        </div>
                                                        <!-- End Modal -->

                                                        <!-- Delete Modal -->
                                                        <div class="modal fade" id="delete_<?php echo $client->client_id; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                            <div class="modal-dialog modal-dialog-centered" role="document">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <h5 class="modal-title" id="exampleModalLabel">CONFIRM DELETE</h5>
                                                                        <button type="button" class="close" data-dismiss="modal">
                                                                            <span>&times;</span>
                                                                        </button>
                                                                    </div>
                                                                    <div class="modal-body text-center text-danger">
                                                                        <h4>Delete This Client Record</h4>
                                                                        <br>
                                                                        <button type="button" class="text-center btn btn-success" data-dismiss="modal">No</button>
                                                                        <a href="staff_clients?delete=<?php echo $client->client_id; ?>" class="text-center btn btn-danger"> Delete </a>
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
    <?php require_once('../partials/scripts.php'); ?>
</body>

</html>