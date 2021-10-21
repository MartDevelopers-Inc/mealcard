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

session_start();
require_once '../config/config.php';
require_once '../config/checklogin.php';
require_once '../config/codeGen.php';
staff_checklogin();
/* Update Staff Profile */
if (isset($_POST['update_profile'])) {

    $staff_id = $_POST['staff_id'];
    $staff_name = $_POST['staff_name'];
    $staff_email = $_POST['staff_email'];
    $staff_phone = $_POST['staff_phone'];
    $staff_rank = $_POST['staff_rank'];
    $temp = explode('.', $_FILES['staff_profile']['name']);
    $newfilename = 'Staff' . (round(microtime(true)) . '.' . end($temp));
    move_uploaded_file(
        $_FILES['staff_profile']['tmp_name'],
        '../public/backend_assets/images/avatar/' . $newfilename
    );
    $query = "UPDATE  staff SET
    staff_name=?,
    staff_email=?,
    staff_phone=?,
    staff_rank=?,
    staff_profile =?
    WHERE
    staff_id=?";
    $stmt = $mysqli->prepare($query);
    $rc = $stmt->bind_param(
        'ssssss',

        $staff_name,
        $staff_email,
        $staff_phone,
        $staff_rank,
        $newfilename,
        $staff_id

    );
    $stmt->execute();

    if ($stmt) {
        $success = 'Profile is update';
    } else {
        //inject alert that task failed
        $err = 'Please Try Again Or Try Later';
    }
}
/* Update Staff Password */
if (isset($_POST['update_password'])) {
    $staff_id = $_SESSION['staff_id'];
    $old_password = sha1(md5($_POST['old_password']));
    $new_password = sha1(md5($_POST['new_password']));
    $confirm_password = sha1(md5($_POST['confirm_password']));

    $sql = "SELECT * FROM staff WHERE staff_id = '$staff_id'";
    $res = mysqli_query($mysqli, $sql);
    if (mysqli_num_rows($res) > 0) {
        $row = mysqli_fetch_assoc($res);
        if ($old_password != $row['staff_password']) {
            $err =  "Please Enter Correct Old Password";
        } else if ($new_password != $confirm_password) {
            $err = "Confirmation Password Does Not Match";
        } else {
            $query = "UPDATE staff SET staff_password =? WHERE staff_id =?";
            $stmt = $mysqli->prepare($query);
            $rc = $stmt->bind_param('ss', $new_password, $staff_id);
            $stmt->execute();
            if ($stmt) {
                $success = "Password Updated";
            } else {
                $err = "Please Try Again Or Try Later";
            }
        }
    } else {
        $err = "Kindly log out and login again";
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
        <?php
        require_once('../partials/sidebar.php');
        /* Load This Page With Logged In User Session */
        $staff_id = $_SESSION['staff_id'];
        $ret = "SELECT * FROM staff WHERE staff_id = '$staff_id'  ";
        $stmt = $mysqli->prepare($ret);
        $stmt->execute(); //ok
        $res = $stmt->get_result();
        while ($staff = $res->fetch_object()) {
            if ($staff->staff_profile != '') {
                $staff_img_url = "../public/backend_assets/images/avatar/$staff->staff_profile";
            } else {
                $staff_img_url = "../public/backend_assets/images/avatar/no-profile.png";
            }
        ?>

            <div class="content-body">
                <div class="container-fluid">
                    <div class="page-titles">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="staff_dashboard">Dashboard</a></li>
                            <li class="breadcrumb-item active"><a href="staff_profile">Profile</a></li>
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
                                            <img src="<?php echo $staff_img_url; ?>" class="img-fluid rounded-circle" alt="">
                                        </div>
                                        <div class="profile-details">
                                            <div class="profile-name px-3 pt-2">
                                                <h4 class="text-primary mb-0"><?php echo $staff->staff_name; ?></h4>
                                                <p><?php echo $staff->staff_rank; ?></p>
                                            </div>
                                            <div class="profile-email px-2 pt-2">
                                                <h4 class="text-muted mb-0"><?php echo $staff->staff_email; ?></h4>
                                                <p>Email</p>
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
                                                    <a href="#profile-settings" data-toggle="tab" class="nav-link active">Profile Settings</a>
                                                </li>
                                                <li class="nav-item">
                                                    <a href="#change-password" data-toggle="tab" class="nav-link">Change Password</a>
                                                </li>
                                            </ul>
                                            <div class="tab-content">
                                                <div id="profile-settings" class="tab-pane fade active show">
                                                    <div class="pt-3">
                                                        <div class="settings-form">
                                                            <form method="post" enctype="multipart/form-data">
                                                                <div class="form-row">
                                                                    <div class="form-group col-md-12">
                                                                        <label>Full Name</label>
                                                                        <input type="text" value="<?php echo $staff->staff_name; ?>" name="staff_name" required class="form-control">
                                                                        <input type="hidden" value="<?php echo $staff_id; ?>" name="staff_id" required class="form-control">
                                                                    </div>
                                                                    <div class="form-group col-md-12">
                                                                        <label>Phone Number</label>
                                                                        <input type="text" value="<?php echo $staff->staff_phone; ?>" name="staff_phone" required class="form-control">
                                                                    </div>
                                                                    <div class="form-group col-md-12">
                                                                        <label>Email</label>
                                                                        <input type="text" value="<?php echo $staff->staff_email; ?>" name="staff_email" required class="form-control">
                                                                    </div>
                                                                    <!-- Staff Rank -->
                                                                    <div class="form-group col-md-12">
                                                                        <label>Access Level</label>
                                                                        <select class="form-control default-select" name="staff_rank" id="inputState">
                                                                            <?php
                                                                            /* Limit Staff On Updating Their Access Level */
                                                                            if ($_SESSION['staff_rank'] == 'admin') {
                                                                            ?>
                                                                                <option value="staff">Staff</option>
                                                                                <option value="admin">Administrator</option>
                                                                            <?php } else { ?>
                                                                                <option value="staff">Staff</option>
                                                                            <?php } ?>
                                                                        </select>
                                                                    </div>
                                                                    <div class="form-group col-md-12">
                                                                        <label>Staff Profile</label>
                                                                        <div class="input-group">
                                                                            <div class="custom-file">
                                                                                <input type="file" required accept=".png, .jpeg, .jpg" name="staff_profile" class="custom-file-input">
                                                                                <label class="custom-file-label">Choose file</label>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="text-right">
                                                                    <button name="update_profile" class="btn btn-primary" type="submit">
                                                                        Update Profile
                                                                    </button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div id="change-password" class="tab-pane fade">
                                                    <div class="pt-3">
                                                        <div class="settings-form">
                                                            <h4 class="text-primary">Change Password</h4>
                                                            <br>
                                                            <form method="post">
                                                                <div class="form-row">
                                                                    <div class="form-group col-md-6">
                                                                        <label>Old Password</label>
                                                                        <input type="password" name="old_password" required class="form-control">
                                                                    </div>
                                                                    <div class="form-group col-md-6">
                                                                        <label>New Password</label>
                                                                        <input type="password" name="new_password" required class="form-control">
                                                                    </div>
                                                                    <div class="form-group col-md-12">
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