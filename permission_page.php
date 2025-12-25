<?php
if (isset($_GET['action'])) {
    $page = $_GET['action'];
    $file_name = basename($_SERVER['PHP_SELF']);
    $page_name = "$file_name?action=$page";
} else {
    $page_name = basename($_SERVER['PHP_SELF']);
}

$staff_array  = array(
    "index.php",
    "logout.php",
    "content.php",
    "index.php?action=saving_receive",
    "index.php?action=show_accounts",
    "index.php?action=savings_all",
    "index.php?action=deposit_all",
    "index.php?action=account_info",
    "index.php?action=deposit_status",
    "index.php?action=payment",
    "index.php?action=transection_list",
    "index.php?action=loan_application",
    "index.php?action=loan_application_edit",
    "index.php?action=loan_application_person",
    "index.php?action=loan_given_form",
    "index.php?action=loan_all",
    "index.php?action=loan_status",
    "index.php?action=member_insert",
    "index.php?action=member_edit",
    "index.php?action=members_view",
    "index.php?action=member_view_single",
    "index.php?action=profile",
    "index.php?action=daily_transection",
    "index.php?action=expense",
    "index.php?action=head_office",
    "index.php?action=staff_transections_view",
    "index.php?action=search_account",
    "index.php?action=profit_earns",
    "index.php?action=expenses_list",
    "index.php?action=close_loan_list",
    "index.php?action=change_password",
);
$uddokta_array  = array(
    "index.php",
    "logout.php",
    "content.php",
    "index.php?action=members_view",
    "index.php?action=member_view_single",
    "index.php?action=staff_view",
    "index.php?action=staff_view_single",
);



if ($ac_type == "staff") {
    $per_array = $staff_array;
} elseif ($ac_type == "Librarian") {
    $per_array = $librarian_array;
} else {
    $per_array = array();
}



if ($uname != "" && $uid != "" && $ac_type != "" && $ac_type != "admin") {
    if (!in_array($page_name, $per_array)) {
        header("Location:error_500.php");
    }
}
