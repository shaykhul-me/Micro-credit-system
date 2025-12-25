<?php
if(isset($_POST['update_account_title'])){
    $account_select = manual_query("SELECT * FROM accounts WHERE member_id > 0");
    while ($account_res = mysqli_fetch_assoc($account_select['query'])) {
        $member_id = $account_res['member_id'];
        $account_id = $account_res['id'];
        $get_member_info = manual_query("SELECT * FROM members WHERE id='$member_id' LIMIT 1");
        $member_res = mysqli_fetch_assoc($get_member_info['query']);

        $update_ac_array = array(
            "account_title" => $member_res['name']
        );
        updatethis(array("id"=>$account_id), $update_ac_array, "accounts");
    }
}
if(isset($_POST['active_admin'])){
    if($_POST['active_admin'] == "01741316063"){
        $_SESSION['admin_access'] = true;
    }
}
print_r($_SESSION);
?>
<hr class="mr-4">
<form method="POST">
    <input type="text" name="active_admin" class="form-control">
    <input type="submit" value="Submit" class="btn btn-danger">
</form>

<form action="" method="post">
    <input type="submit" value="Update Account Title" name="update_account_title" class="btn btn-info">
</form>