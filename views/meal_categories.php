<?php
/*
 * Created on Sat Oct 23 2021
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

session_start();
require_once('../config/config.php');
require_once('../config/checklogin.php');
require_once('../config/codeGen.php');
checklogin();

/* Handle Meal Category Add */
if (isset($_POST['add_meal_category'])) {
    $category_id = $sys_gen_id;
    $category_name = $_POST['category_name'];
    $category_details = $_POST['category_details'];

    /* Check If Theres Another Another Category With These Records Which Match */
    $sql = "SELECT * FROM  meal_categories  WHERE category_name ='$category_name' ";
    $res = mysqli_query($mysqli, $sql);
    if (mysqli_num_rows($res) > 0) {
        $row = mysqli_fetch_assoc($res);
        if ($category_name == $row['category_name']) {
            $err = 'A Meal Categiry With This Name Already Exists';
        }
    } else {
        /* Insert This Data */
        $insert = "INSERT INTO meal_categories(category_id, category_name, category_details) VALUES(?,?,?)";
        $insert_stmt = $mysqli->prepare($insert);
        $insert_rc  = $insert_stmt->bind_param('sss', $category_id, $category_name, $category_details);
        $insert_stmt->execute();
        if ($insert_stmt) {
            $success = "$category_name, Added";
        } else {
            $err = "Failed!, Please Try Again Later";
        }
    }
}


/* Handle Update Meal Category */
if (isset($_POST['update_meal_category'])) {
    $category_id = $_POST['category_id'];
    $category_name  = $_POST['category_name'];
    $category_details = $_POST['category_details'];

    /* Persist Update On Details */
    $update_sql = "UPDATE meal_categories SET category_name =?, category_details = ? WHERE category_id = ?";
    $update_sql_stmt = $mysqli->prepare($update_sql);
    $update_rc = $update_sql_stmt->bind_param('sss', $category_name, $category_details,  $category_id);
    $update_sql_stmt->execute();

    if ($update_sql_stmt) {
        $success = "$category_name, Category Updated";
    } else {
        $err = "Failed!, Please Try Again Later";
    }
}

/* Handle Delete Meal Category */
if (isset($_GET['delete'])) {
    $delete = $_GET['delete'];
    /* Wipe This MF */
    $delete_sql = "DELETE FROM meal_categories WHERE category_id = '$delete'";
    $delete_sql_stmt = $mysqli->prepare($delete_sql);
    $delete_sql_stmt->execute();
    if ($delete_sql_stmt) {
        $success = "Deleted" && header("refresh:1; url=meal_categories");
    } else {
        $err  = "Failed!, Please Try Again Later";
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
                <?php require_once('../partials/header.php'); ?>
                <!-- main header @e -->
                <!-- content @s -->
                <div class="nk-content ">
                    <div class="container-fluid">
                        <div class="nk-content-inner">
                            <div class="nk-content-body">
                                <div class="nk-block-head nk-block-head-sm">
                                    <div class="nk-block-between">
                                        <div class="nk-block-head-content">
                                            <h3 class="nk-block-title page-title">Meal Categories</h3>
                                            <div class="nk-block-des text-soft">
                                                <nav>
                                                    <ul class="breadcrumb breadcrumb-arrow">
                                                        <li class="breadcrumb-item"><a href="dashboard">Home</a></li>
                                                        <li class="breadcrumb-item active">Meal Categories</li>
                                                    </ul>
                                                </nav>
                                            </div>
                                        </div><!-- .nk-block-head-content -->

                                        <div class="nk-block-head-content">
                                            <div class="toggle-wrap nk-block-tools-toggle">
                                                <a href="#" class="btn btn-icon btn-trigger toggle-expand mr-n1" data-target="pageMenu"><em class="icon ni ni-menu-alt-r"></em></a>
                                                <div class="toggle-expand-content" data-content="pageMenu">
                                                    <ul class="nk-block-tools g-3">
                                                        <li><a href="#add_modal" data-toggle="modal" class="btn btn-white btn-outline-light"><em class="icon ni ni-plus"></em><span>Add Meal Category</span></a></li>
                                                    </ul>
                                                </div>
                                            </div><!-- .toggle-wrap -->
                                        </div><!-- .nk-block-head-content -->
                                        <!-- Add Modal -->
                                        <div class="modal fade" id="add_modal">
                                            <div class="modal-dialog  modal-lg">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h4 class="modal-title">Register New Meal Category</h4>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form method="post" enctype="multipart/form-data" role="form">
                                                            <div class="card-body">
                                                                <div class="row">
                                                                    <div class="form-group col-md-12">
                                                                        <label for="">Category Name</label>
                                                                        <input type="text" required name="category_name" class="form-control">
                                                                    </div>
                                                                    <div class="form-group col-md-12">
                                                                        <label for="">Category Details</label>
                                                                        <textarea type="text" rows="2" required name="category_details" class="form-control"></textarea>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="text-right">
                                                                <button type="submit" name="add_meal_category" class="btn btn-primary">Submit</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- End Modal -->
                                    </div><!-- .nk-block-between -->
                                </div><!-- .nk-block-head -->
                                <div class="nk-block">
                                    <div class="card-stretch">
                                        <div class="card-inner-group">
                                            <div class="card-preview">
                                                <div class="card-inner">
                                                    <div class="nk-block">
                                                        <div class="row g-gs">
                                                            <?php
                                                            /* Pop This Partial With All Meal Categories */
                                                            $ret = "SELECT * FROM meal_categories ORDER BY category_name ASC  ";
                                                            $stmt = $mysqli->prepare($ret);
                                                            $stmt->execute(); //ok
                                                            $res = $stmt->get_result();
                                                            while ($meal_categories = $res->fetch_object()) {
                                                            ?>
                                                                <div class="col-md-6">
                                                                    <div class="card card-bordered card-full">
                                                                        <div class="nk-wg1">
                                                                            <div class="nk-wg1-block">
                                                                                <div class="nk-wg1-img">
                                                                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 90 90">
                                                                                        <circle cx="82.03" cy="26.32" r="2.97" fill="none" stroke="#c4cefe" stroke-linecap="round" stroke-linejoin="round" />
                                                                                        <circle cx="79.41" cy="68.42" r="2.87" fill="#e3e7fe" />
                                                                                        <circle cx="14.98" cy="82.08" r="1.72" fill="#e3e7fe" />
                                                                                        <rect x="5" y="34.77" width="6.88" height="2.29" fill="#e3e7fe" />
                                                                                        <rect x="5" y="34.77" width="6.88" height="2.29" transform="translate(44.36 27.47) rotate(90)" fill="#e3e7fe" />
                                                                                        <polygon points="39.21 4.22 42.89 5.55 45.92 3.33 45.97 7.18 49.17 9.48 45.52 10.53 44.47 14.18 42.16 10.98 38.31 10.94 40.54 7.91 39.21 4.22" fill="#6576ff" />
                                                                                        <polygon points="29.41 10.28 31.41 11 33.06 9.79 33.08 11.88 34.82 13.14 32.84 13.71 32.27 15.69 31.02 13.95 28.92 13.93 30.13 12.28 29.41 10.28" fill="#b3c2ff" />
                                                                                        <polygon points="51.56 9.67 53.89 10.52 55.81 9.11 55.84 11.54 57.87 13 55.56 13.67 54.89 15.98 53.44 13.95 51 13.92 52.41 12 51.56 9.67" fill="#b3c2ff" />
                                                                                        <path d="M49.89,64.32a.77.77,0,0,0-1-.78,21.57,21.57,0,0,1-4.44.67A21.73,21.73,0,0,1,40,63.54a.77.77,0,0,0-1,.78v3a1,1,0,0,0,1,1h8.81a1,1,0,0,0,1-1Zm.46-.17" fill="#fff" stroke="#6576ff" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" />
                                                                                        <path d="M58.43,20.88H30.92A2.08,2.08,0,0,0,28.84,23V40.1a21.46,21.46,0,0,0,4.51,13.75,14.46,14.46,0,0,0,22.65,0A21.46,21.46,0,0,0,60.51,40.1V23A2.08,2.08,0,0,0,58.43,20.88Z" fill="#fff" stroke="#6576ff" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" />
                                                                                        <path d="M24.15,44.87c-2.71-1-4-3.51-4-7.59V27.8h4V23.85H18.31a2.07,2.07,0,0,0-2.07,2.07V37.28c0,6.93,3.16,11.15,9,12.14a26.26,26.26,0,0,1-1.06-4.55Z" fill="#fff" stroke="#6576ff" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" />
                                                                                        <path d="M28.5,72.92v2.16c0,.23.94.42,2.1.42H58.4c1.16,0,2.1-.19,2.1-.42V72.92c0-.23-.94-.42-2.1-.42H30.6C29.44,72.5,28.5,72.69,28.5,72.92Zm2.85.12" fill="#6576ff" stroke="#6576ff" stroke-linecap="round" stroke-linejoin="round" />
                                                                                        <path d="M28.5,78.92v2.16c0,.23.94.42,2.1.42H58.4c1.16,0,2.1-.19,2.1-.42V78.92c0-.23-.94-.42-2.1-.42H30.6C29.44,78.5,28.5,78.69,28.5,78.92Zm2.85.12" fill="#6576ff" stroke="#6576ff" stroke-linecap="round" stroke-linejoin="round" />
                                                                                        <path d="M71.64,23.85H65.71V27.8h4v8.84c0,3.89-1.24,6.22-4,7.23a24,24,0,0,1-1.06,4.33c5.81-.94,9-5,9-11.56V25.82a2,2,0,0,0-2-2Z" fill="#fff" stroke="#6576ff" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" />
                                                                                        <path d="M50.56,33.64v9a1.45,1.45,0,0,1-.72,1.26L45.4,46.48a1.45,1.45,0,0,1-1.45,0l-4.43-2.56a1.44,1.44,0,0,1-.73-1.26V37.54a1.44,1.44,0,0,1,.73-1.26L44,33.72a1.43,1.43,0,0,1,1.32-.07v1.52l0,0a1.14,1.14,0,0,0-1.13,0l-3.44,2a1.11,1.11,0,0,0-.56,1v4a1.12,1.12,0,0,0,.56,1l3.44,2a1.14,1.14,0,0,0,1.13,0l3.44-2a1.12,1.12,0,0,0,.56-1v-9.2Z" fill="#6576ff" />
                                                                                        <path d="M47.92,32.11l-1.33-.76v1.53h0v8a.49.49,0,0,1-.24.42l-1.43.83a.51.51,0,0,1-.48,0L43,41.35a.49.49,0,0,1-.24-.42V39.27a.49.49,0,0,1,.24-.42L44.44,38a.46.46,0,0,1,.48,0l.35.21V36.7l-.19-.11a.79.79,0,0,0-.81,0L41.84,38a.82.82,0,0,0-.41.7V41.5a.81.81,0,0,0,.41.7l2.43,1.41a.83.83,0,0,0,.81,0l2.43-1.41a.81.81,0,0,0,.41-.7V33.64h0Z" fill="#6576ff" />
                                                                                        <path d="M51.89,34.41v8.82a1.78,1.78,0,0,1-.9,1.55l-5.42,3.13a1.79,1.79,0,0,1-1.79,0l-5.42-3.13a1.78,1.78,0,0,1-.9-1.55V37a1.78,1.78,0,0,1,.9-1.55l5.42-3.13a1.79,1.79,0,0,1,1.79,0l1,.59V31.35l-.86-.5a2.1,2.1,0,0,0-2.11,0L37.2,34.56a2.11,2.11,0,0,0-1.06,1.83v7.42a2.11,2.11,0,0,0,1.06,1.83l6.42,3.7a2.1,2.1,0,0,0,2.11,0l6.42-3.7a2.11,2.11,0,0,0,1.06-1.83V35.19Z" fill="#6576ff" />
                                                                                    </svg>
                                                                                </div>
                                                                                <div class="nk-wg1-text">
                                                                                    <h5 class="title"><?php echo $meal_categories->category_name; ?></h5>
                                                                                    <p>
                                                                                        <?php echo $meal_categories->category_details; ?>
                                                                                    </p>
                                                                                </div>
                                                                            </div>
                                                                            <div class="nk-wg1-action">
                                                                                <br>
                                                                                <div class="text-center">
                                                                                    <a class="btn btn-primary" data-toggle="modal" href="#update-<?php echo $meal_categories->category_id; ?>"><em class="icon ni ni-edit"></em><span> Edit </span></a>
                                                                                    <a class="btn btn-danger" data-toggle="modal" href="#delete-<?php echo $meal_categories->category_id; ?>"><em class="icon ni ni-trash"></em><span> Delete </span></a>
                                                                                </div>
                                                                                <br>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div><!-- .col -->
                                                                <!-- Edit Modals -->
                                                                <div class="modal fade" id="update-<?php echo $meal_categories->category_id; ?>">
                                                                    <div class="modal-dialog  modal-lg">
                                                                        <div class="modal-content">
                                                                            <div class="modal-header">
                                                                                <h4 class="modal-title">Update <?php echo $meal_categories->category_name; ?> Details</h4>
                                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                    <span aria-hidden="true">&times;</span>
                                                                                </button>
                                                                            </div>
                                                                            <div class="modal-body">
                                                                                <form method="post" enctype="multipart/form-data" role="form">
                                                                                    <div class="card-body">
                                                                                        <div class="row">
                                                                                            <div class="form-group col-md-12">
                                                                                                <label for="">Category Name</label>
                                                                                                <input type="text" value="<?php echo $meal_categories->category_name; ?>" required name="category_name" class="form-control">
                                                                                                <input type="hidden" value="<?php echo $meal_categories->category_id; ?>" required name="category_id" class="form-control">
                                                                                            </div>
                                                                                            <div class="form-group col-md-12">
                                                                                                <label for="">Category Details</label>
                                                                                                <textarea type="text" rows="2" required name="category_details" class="form-control"><?php echo $meal_categories->category_details; ?></textarea>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="text-right">
                                                                                        <button type="submit" name="update_meal_category" class="btn btn-primary">Submit</button>
                                                                                    </div>
                                                                                </form>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <!-- End Edit Modal -->


                                                                <!-- Delete Modals -->
                                                                <div class="modal fade" id="delete-<?php echo $meal_categories->category_id; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                                                        <div class="modal-content">
                                                                            <div class="modal-header">
                                                                                <h5 class="modal-title" id="exampleModalLabel">CONFIRM DELETION</h5>
                                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                    <span aria-hidden="true">&times;</span>
                                                                                </button>
                                                                            </div>
                                                                            <div class="modal-body text-center text-danger">
                                                                                <h4>Delete <?php echo $meal_categories->category_name; ?> Meal Category ?</h4>
                                                                                <br>
                                                                                <p>Heads Up, You are about to delete <?php echo $meal_categories->category_name; ?> Meal Category. This action is irrevisble.</p>
                                                                                <button type="button" class="text-center btn btn-success" data-dismiss="modal">No</button>
                                                                                <a href="meal_categories?delete=<?php echo $meal_categories->category_id; ?>" class="text-center btn btn-danger"> Delete </a>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <!-- End Modals -->
                                                            <?php } ?>
                                                        </div><!-- .row -->
                                                    </div>
                                                </div>
                                            </div>
                                        </div><!-- .card -->
                                    </div><!-- .nk-block -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- content @e -->
                <!-- footer @s -->
                <?php require_once('../partials/footer.php'); ?>
                <!-- footer @e -->
            </div>
            <!-- wrap @e -->
        </div>
        <!-- main @e -->
    </div>
    <!-- Js -->
    <?php require_once('../partials/scripts.php'); ?>
</body>

</html>