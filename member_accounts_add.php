<?php
$memberid = $_GET['member_id'];
$memberinfo = single_condition_select("members", "id", $memberid);
if ($memberinfo['count'] ==1 ) {
            $member_info_res = mysqli_fetch_assoc($memberinfo['query']);
            
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['new_account'])) {
    // Collecting values
    $account_type = $_POST['account_type'];
    $account_number = $_POST['account_number'];
    $opening_date = $_POST['opening_date'];
    $installment = $_POST['installment'];
    $last_paid_month = $_POST['last_paid_month'];
    $last_balance = $_POST['last_balance'];

// Check existing account
$check_account = double_condition_select("accounts", "member_id", $memberid, "account_type", $account_type);
//print_r($check_account);
if ($check_account['count'] > 0 ) {
            echo "Sorry! Account Already Exist.";
            exit();
            
}else{
// new account create


$array = array(
                "account_title" =>  $member_info_res['name'],
                "created_by" =>  $uid,
                "created_at" =>  $datetime,
                "created_ip" => $ip,
                "member_id" => $memberid,
                "status" => "Active",
                "account_type" => $account_type,
                "account_no" => $account_number,
                "installment" => $installment,
                "opening_date" => $opening_date,
                
            );
            $new_account = insert_data($conn, $array, "accounts");
            $account_id = $new_account['last_id'];
            if ($account_id) {
                
                echo "<div align='center' class='alert alert-success' style='width:40%; font-size:20px; margin:0 auto;'>$account_type Account Created Successfully</div>";
            }
            
    // create transaction
    // if use Last Balance
    if(isset($last_balance)){
        $transaction = array(
                            "created_by" =>  $uid,
                            "created_at" =>   $datetime,
                            "created_ip" => $ip,
                            "entry_date" =>   date("Y-m-d", strtotime($datetime)),
                            "member_id" => $memberid,
                            "category" => $account_type,
                            "details" => "New Account Created",
                            "cash_in" => $last_balance,
                            "status" => "paid"
                
            );
            $new_transaction = insert_data($conn, $transaction, "transections");
            $new_transaction_id = $new_transaction['last_id'];
    }
    
    // create transaction details
    if ($new_transaction_id) {
             $transections_array = array(
                            "created_at" => $datetime,
                            "created_by" => $uid,
                            "created_ip" => $ip,
                            "transaction_id" => $new_transaction['last_id'],
                            "account_id" => $account_id,
                            "account_no" => $account_number,
                            "member_id" => $memberid,
                            "account_title" =>  $member_info_res['name'],
                            "category" => $account_type,
                            "details" => "New Account Created",
                            "cash_in" => $last_balance,
                            "status" => "Paid",
                        );
        $insert_transaction_details = insert_data($conn, $transections_array, "transections_details");
         $transaction_details_id = $insert_transaction_details['last_id'];
                
            }
    if(isset($new_transaction_id, $transaction_details_id)){
        echo "<div align='center' class='alert alert-success' style='width:40%; font-size:20px; margin:0 auto;'>Opening Balance Added.</div>";
           $redirect_url = "index.php?action=member_accounts_add&member_id=$memberid";
    echo '<meta http-equiv="refresh" content="0;url='.$redirect_url.'" />';    
            }
    
}
    
}

//view Accounts
$accounts = manual_query("SELECT * FROM `accounts` WHERE status = 'Active' AND member_id='$memberid'");

        while ($row = mysqli_fetch_array($accounts['query'])) {

$transections_sql = manual_query("SELECT `transections_details`.*, COALESCE(SUM(`transections_details`.`cash_in` - `transections_details`.`cash_out`), 0) AS total_deposite FROM `transections_details` WHERE `transections_details`.`account_id`='{$row['id']}' AND `transections_details`.`category`='{$row['account_type']}'  GROUP BY `account_id`");
    while ($transectons_res = mysqli_fetch_assoc($transections_sql['query'])) {                        
                $this_ac_this_month = $transectons_res['this_month'];
                $balance = $transectons_res['total_deposite'];
   


   $table_tbody_cont .=  "<tr>
                                <td>{$row['account_type']}</td>
                                <td>{$row['account_title']}</td>
                                <td>{$row['opening_date']}</td>
                                <td>{$balance}</td>
                                <td>{$row['status']}</td>
                                <td class='hiddenp text-center p-2'>
                                    <a target='_blank' class='btn btn-sm btn-primary mb-1' href='index.php?action=account_info&id={$row['id']}'>View</a>
                                </td>
                            </tr>"; 
}


            
                           
                            
        } 
        
?>

 <h2>Add Accounts</h2>
 <form action="" method="post" >
        <div class="row">
            
        </div>
        <div class="row">
            <div class="col-md-8 calculation_area">
                <div class="row">
                    <div class="col-md-6">
    <label for="account_type" class="form-label">একাউন্টের ধরণ</label>
    
    <select name="account_type" id="account_type" class="form-control" required>
        <option value="">-- SELECT --</option>
        <option value="Savings">Savings</option>
        <option value="Share">Share</option>
    </select>
</div>

 <div class="col-md-6">
                <label for="account_number_search" class="form-label">একাউন্ট নম্বর</label>
                <input type="text" name="account_number" class="form-control" required>
            </div>
            
        <div class="col-md-6">
                <label for="start_date" class="form-label">শুরুর তারিখ</label>
                <input type="date" name="opening_date" id="taka" class="form-control" value="0">
            </div>
            
<div class="col-md-6">
      <label for="savings_installment" class="form-label">কিস্তির পরিমাণ</label>
                <input type="number" name="installment" id="taka" class="form-control" value="0">
            </div>
            
<div class="col-md-6">
     <label for="last_paid" class="form-label">সর্বশেষ পরিশোধিত মাস</label>
                <input type="month" name="last_paid_month" class="form-control" value="0">
            </div>
            
<div class="col-md-6">
            <label for="last_balance">সর্বশেষ ব্যালেন্স</label>
            <input class="form-control" type="number" name="last_balance"  class="form-group" value="0">
            </div>
    <div class="text-center">
                    <?php //echo isset($csrf_input) ? $csrf_input : ""; 
                    ?>
                    <input type="submit" value="Submit" name="new_account" class="btn btn-success" id="new_account">
                </div>
                    </div>
                   
                    </div>
        <div class="col-md-4">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <ul id="memberDetails" class="list-group">
                                <li class="list-group-item">
                                    <!-- Media top -->
                                    <div class="media">
                                        <img src="assets/images/img/demo.jpg" id="member_image" class="align-self-start mr-3" height="150px">
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="card">
                            <ul id="accountinfo" class="list-group">
                                <li class="list-group-item">
                                    <!-- Media top -->
                                    <div class="media">
                                        <div class="media-body">
                                            <p>
                                                <strong><span id="member_name"></span></strong><br>
                                                <span>সদস্য নং : </span> <span id="member_id2"></span><br>
                                                <span>মোবাইল : </span> <span id="member_mobile"></span><br>
                                            </p>
                                            <p>
                                                <span>সঞ্চয় কিস্তি: </span> <span id="savings_installment_text2"></span><br>
                                                <span>সঞ্চয় ব্যালেন্স: </span> <span id="savings_balance"></span><br>
                                                <span>সঞ্চয় সর্ব শেষ : </span> <span id="savings_last_paid"></span><br>
                                                <span>সঞ্চয় বকেয়া : </span> <span id="savings_due"></span> মাস<br>
                                            </p>
                                            <p>
                                                <span>শেয়ার কিস্তি: </span> <span id="share_installment_text2"></span><br>
                                                <span>শেয়ার মোট: </span> <span id="share_balance"></span><br>
                                                <span>শেয়ার সর্ব শেষ : </span> <span id="share_last_paid"></span><br>
                                                <span>শেয়ার বকেয়া : </span> <span id="share_due"></span> মাস<br>
                                            </p>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
                </div>

    </form>
    

<h3 class="text-center" id="header_first">Accounts List</h3>
                <div class="table-responsive">
                    <table class="table table-light" id="savings_list_table" class="display">
                        <thead class="thead-light">
                            <tr>
                                <th>একাউন্ট ধরণ</th>
                                <th>সদস্য নাম</th>
                                <th>তারিখ</th>
                                <th>মোট জমা</th>
                                <th>অবস্থা</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php echo $table_tbody_cont; ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>একাউন্ট ধরণ</th>
                                <th>সদস্য নাম</th>
                                <th>তারিখ</th>
                                <th>মোট জমা</th>
                                <th>অবস্থা</th>
                                <th>Action</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>    
    