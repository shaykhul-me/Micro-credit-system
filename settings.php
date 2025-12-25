<?php
$readonly = "";
if (isset($_GET['edit_setting'])) {
    $id = $_GET['edit_setting'];
    $cat_sql = single_condition_select("settings", "id", $id);
    $category = "";
    $name = "";
    $value = "";
    $status = "";
    if ($cat_sql['count'] > 0) {
        $category_ck = mysqli_fetch_assoc($cat_sql['query']);
        $category = $category_ck['s_category'];
        $name = $category_ck['s_name'];
        $value = $category_ck['s_value'];
        $status = $category_ck['status'];
    }
}
if (isset($_GET['delete_setting'])) {
    $id = $_GET['delete_setting'];
    $cat_sql = deletedata("settings", $id);
    if ($cat_sql['deleted']) {
        $message = "<div class='alert alert-success'>Category Deleted Successfully</div>";
    }
}
if (isset($_POST['category'])) {
    $category = $_POST['category'];
    $name = $_POST['name'];
    $value = $_POST['value'];

    if ($_POST['category'] == "" || $_POST['name'] == "" || $_POST['value'] === "") {
        $message = "<div class='alert alert-danger'>All Field Required</div>";
    } else {

        if (isset($id) && $id != "") {
            if ($status == "readonly") {
                $message = "<div class='alert alert-danger'>Sorry You Can't Edit This Settings</div>";
            } else {
                $ck_exist = single_not_double_check("settings", "s_name", $name, "s_category", $category, "id", $id);
                if ($ck_exist['count'] > 0) {
                    $message = "<div class='alert alert-danger'>Category Already Exist</div>";
                } else {
                    $insertarray = array(
                        "updated_at" => $datetime,
                        "updated_by" => $uid,
                        "s_name" => $name,
                        "s_value" => $value,
                        "s_category" => $category
                    );
                    $insertvalu = updatethis(array("id" => $id), $insertarray, "settings");
                    if ($insertvalu['edited_id']) {
                        $actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
                        $url = explode('&', $actual_link);
                        array_pop($url);
                        $redirec_url =  implode('/', $url);
                        $_SESSION['msg'] = "<div class='alert alert-success'>Category Updated successfully</div>";
                        echo "<script type='text/javascript'>location.href = '$redirec_url';</script>";
                        exit;
                        
                    }
                }
            }
        } else {
            $exist = double_condition_select("settings", "s_category", $_POST['category'], "s_name", $_POST['name']);
            if ($exist['count'] > 0) {
                $message = "<div class='alert alert-danger'>Name Already Exist in {$_POST['category']}</div>";
            } else {
                $insertarray = array(
                    "created_at" => $datetime,
                    "created_ip" => $ip,
                    "created_by" => $uid,
                    "s_name" => $name,
                    "s_value" => $value,
                    "s_category" => $category
                );
                $insertvalu = insert_data($conn, $insertarray, "settings");
                if ($insertvalu['last_id']) {
                    $message = "<div class='alert alert-success'>Category Created successfully</div>";
                }
            }
        }
    }
}
$settings_sql = get_posts("settings");
?>
<div class="mt-4">
    <h3 class="text-center">Settings Page (Admin Only)</h3>
    <div id="showallmsg">
        <?php
        if (isset($message)) {
            echo $message;
        }
        if(isset($_SESSION['msg'])){
            echo $_SESSION['msg'];
            unset($_SESSION['msg']);
        }
        ?>
    </div>

    <ul class="nav nav-tabs nav-fill" id="myTab" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Add</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">View</a>
        </li>
    </ul>
    <div class="tab-content" id="myTabContent">

        <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
            <form action="" method="POST">
                <?php echo isset($csrf_input)?$csrf_input:"";?>
                <input type="hidden" id="not_reset_form" value="true">
                <div class="row">

                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="category" class="control-label">Category <span class="text-danger">**</span></label>
                            <input class="form-control member_autocomplete" type="text" name="category" id="category" placeholder="Settings Category" required value="<?php echo isset($category) ? $category : ""; ?>">
                            <input type="hidden" name="editable_id" value="<?php echo isset($id) ? $id : ""; ?>">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="name" class="control-label">Name <span class="text-danger">**</span></label>
                            <input class="form-control member_autocomplete" type="text" name="name" id="name" placeholder="Settings Name" required value="<?php echo isset($name) ? $name : ""; ?>">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="value" class="control-label">Value <span class="text-danger">**</span></label>
                            <input class="form-control member_autocomplete" type="text" name="value" id="value" placeholder="Settings Value" required value="<?php echo isset($value) ? $value : ""; ?>">
                        </div>
                    </div>

                </div>
                <input type="submit" class="btn btn-info" value="Submit">
            </form>
        </div>

        <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
            <div class="table-responsive">
            <table class="table table-light table-hover" id="data_table">
                <thead class="thead-dark">
                    <tr class="text-center">
                        <th>Sl No</th>
                        <th>Category</th>
                        <th>Sub Category</th>
                        <th>Value</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $i = 1;
                    while ($settings_res = mysqli_fetch_assoc($settings_sql['query'])) {
                    ?>
                        <tr class="text-center">
                            <td><?php echo $settings_res['id']; ?></td>
                            <td><?php echo $settings_res['s_category']; ?></td>
                            <td><?php echo $settings_res['s_name']; ?></td>
                            <td><?php echo $settings_res['s_value']; ?></td>
                            <td><a href="index.php?action=settings&edit_setting=<?php echo $settings_res['id']; ?>" class="btn btn-info btn-sm">Edit</a>&nbsp; &nbsp;<a href="index.php?action=settings&delete_setting=<?php echo $settings_res['id']; ?>" class="btn btn-danger btn-sm">Delete</a></td>
                        </tr>
                    <?php
                        $i++;
                    }
                    ?>
                </tbody>
            </table>
        </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        $("#category").autocomplete({
            source: function(request, response) {
                $.ajax({
                    url: "controllers/index.php",
                    type: 'post',
                    dataType: "json",
                    data: {
                        search_setting_type: request.term
                    },
                    success: function(data) {
                        console.log(data);
                        response(data);
                    },
                    error: function(d) {
                        console.log(d);
                    }
                });
            },
            select: function(event, ui) {
                $(this).val(ui.item.value);
                return false;
            }
        });
        $("#data_table").dataTable();
    });
</script>