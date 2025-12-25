<?php

if (isset($_POST['submit'])) {
	
    $service = field_protect($_POST['service']);
	$details = field_protect($_POST['account_no']);
    $taka = floatval($_POST['taka']);
    $comment = field_protect($_POST['comment']);
$cash_in = 0;
$cash_out = 0; 
// deposit goes to cash out for reduce taka from business 
// withdraw goes to cash in to increase taka in business     
if ($service == 'ব্যাংক জমা') {
    $cash_out = $taka;	
} elseif ($service == 'ব্যাংক থেকে উত্তোলন') {
    $cash_in = $taka;
	
}

    // Prepare data for insertion
    $transections_add = array(
        "created_by" => $uid,
        "created_at" => $datetime,
        "created_ip" => $ip,
        "entry_date" => $today,
        "category" => "Bank",
		"service" => $service,
        "details" => $details,
        "comment" => $comment,
        "cash_in" => $cash_in,
        "cash_out" => $cash_out,
        "status" => "Paid",
    );

    // Insert into database
    $transections_submit = insert_data($conn, $transections_add, "transections");
    $submited_id = $transections_submit['last_id'];

    if ($submited_id) {
        echo "<div align='center' class='alert alert-success' style='width:40%; font-size:20px; margin:0 auto;'>Amount Inserted Successfully</div>";

        $redirect_url = "index.php?action=bank_list&id=$submited_id";
        echo '<meta http-equiv="refresh" content="0;url=' . $redirect_url . '" />';
    }
}
?>

 <h2>bank</h2>
 <form method="post" action="">
         <div class="row mb-3">
            <div class="col-3">
                
                <label for="category Item" class="form-label">Service Name</label>
                <select class="form-control" name="service" id="service" required>
                    <option value="">-- SELECT --</option>
           <?php $option_collection2 = option_collect("Bank Action", "");
                     
					  echo $option_collection2['options'];
                       
                        ?>
                    </select>
            </div>
			
			
			<div class="col-3">
                
                <label for="category Item" class="form-label">Account NO</label>
                <select class="form-control" name="account_no" id="account_no" required>
                    <option value="">-- SELECT --</option>
           <?php $option_collection = option_collect("Bank", "");
                     
					  echo $option_collection['options'];
                       
                        ?>
                    </select>
            </div>
			
			
            

            <div class="col-3">
                <label for="taka" class="form-label">Taka</label>
                <input type="number" name="taka" id="taka" class="form-control" required>
            </div>
             <div class="col-4">
                <label for="details" class="form-label">Comment</label>
                <input type="text" name="comment" id="comment" class="form-control">
            </div>
      </div>

       

        <button type="submit" name="submit" class="btn btn-primary">Submit</button>
</form>