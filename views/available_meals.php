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
            $err = 'A Meal Category With This Name Already Exists';
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
                <?php require_once('../partials/header.php');
                $view = $_GET['view'];
                $ret = "SELECT * FROM meal_categories WHERE category_id = '$view' ";
                $stmt = $mysqli->prepare($ret);
                $stmt->execute(); //ok
                $res = $stmt->get_result();
                while ($meal_category = $res->fetch_object()) {
                ?>
                    <!-- main header @e -->
                    <!-- content @s -->
                    <div class="nk-content ">
                        <div class="container-fluid">
                            <div class="nk-content-inner">
                                <div class="nk-content-body">
                                    <div class="nk-block-head nk-block-head-sm">
                                        <div class="nk-block-between">
                                            <div class="nk-block-head-content">
                                                <h3 class="nk-block-title page-title">Available Meals In : <?php echo $meal_category->category_name; ?></h3>
                                                <div class="nk-block-des text-soft">
                                                    <nav>
                                                        <ul class="breadcrumb breadcrumb-arrow">
                                                            <li class="breadcrumb-item"><a href="dashboard">Home</a></li>
                                                            <li class="breadcrumb-item"><a href="dashboard">Meal Categories</a></li>
                                                            <li class="breadcrumb-item active"><?php echo $meal_category->category_name; ?></li>
                                                        </ul>
                                                    </nav>
                                                </div>
                                            </div><!-- .nk-block-head-content -->
                                        </div><!-- .nk-block-between -->
                                    </div><!-- .nk-block-head -->
                                    <div class="nk-block">
                                        <div class="card-stretch">
                                            <div class="card-inner-group">
                                                <div class="card-preview">
                                                    <div class="card-inner">
                                                        <div class="nk-block">

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
                <?php } ?>
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