<?php

if (isset($_POST['submit']) && $_POST['taka'] > 0) {
    $category = field_protect($_POST['category']);
    $category_item = field_protect($_POST['category_item']);
    $entry_date = field_protect($_POST['entry_date']);
    $taka = isset($_POST['taka']) ? (int)$_POST['taka'] : 0;
    $cash_paid = isset($_POST['cash_paid']) ? (int)$_POST['cash_paid'] : 0;
    $bank_paid = $_POST['bank_paid'];
    $due = $_POST['due'];
   $total = $cash_paid + $bank_paid + $due;
    if($total != $taka){
       echo "<div align='center' class='alert alert-warning' style='width:40%; font-size:20px; margin:0 auto;'>Sorry! Amount Not Match!! Try Again.</div>";
       exit();
    }
    $comment = field_protect($_POST['comment']);
 

 //New Transection Create

    $transections_add = array(
        "created_by" =>  $uid,
        "created_at" =>  $datetime,
        "created_ip" => $ip,
        "entry_date" => $entry_date,
        "category" => $category,
        "details" => $category_item,
        "comment" => $comment,
        "cash_in" => $taka,
        "status" => "Paid",
    );
    
        $transections_submit = insert_data($conn, $transections_add, "transections");
        $submited_id = $transections_submit['last_id'];
        if($submited_id){
           // Jabeda insert
          // Earning Increase
    $earningt_increase = array(
        "transection_id" => $submited_id,
        "entry_date" => $today,
        "title" => $category_item,
        "increase" => $taka
        
    );  
      $earning_submit = insert_data($conn, $earning_increase, "earning");
    $earning_id = $earning_submit['last_id'];
    
    // default
    $cash_id = 0; 
    $bank_id = 0;
    $due_id = 0;
    
     // Cash Increase
     if($cash_paid > 0){
    $cash_increase = array(
        "transection_id" => $submited_id,
        "entry_date" => $today,
        "title" => $category_item,
        "increase" => $cash_paid
        
    );
    
        $cash_submit = insert_data($conn, $cash_increase, "cash");
    $cash_id = $cash_submit['last_id'];
     }
     
    //Bank Increase
    if($bank_paid > 0){
        $bank_increase = array(
        "transection_id" => $submited_id,
        "entry_date" => $today,
        "title" => $category_item,
        "increase" => $bank_paid
        
    );
    
        $bank_submit = insert_data($conn, $bank_increase, "bank");
    $bank_id = $bank_submit['last_id'];
    }
    
    
    // due
    if($due > 0){
        $due_increase = array(
        "transection_id" => $submited_id,
        "entry_date" => $today,
        "title" => $category_item,
        "category" => "sell",
        "increase" => $due
        
    );
    
        $due_submit = insert_data($conn, $due_increase, "due");
    $due_id = $due_submit['last_id'];
    }
    
            
    if($transections_submit['last_id']){
     echo "<div align='center' class='alert alert-success' style='width:40%; font-size:20px; margin:0 auto;'>Earning Inserted Successfully</div>";
     
    $redirect_url = "index.php?action=others_earning_list&id=$submited_id";
    
    echo '<meta http-equiv="refresh" content="0;url='.$redirect_url.'" />';
}
}
}
?>

 <h2>Others Earning</h2>
 <form method="post" action="">
         <div class="row mb-3">
             <div class="col-4">
                <label for="date" class="form-label">Date</label>
                <input type="date" name="entry_date" class="form-control" required>
            </div>
            <div class="col-4">
                <label for="category Item" class="form-label">Category Item</label>
                <select class="form-control" name="category_item" id="category_item" required>
                    <option value="">-- SELECT --</option>
                   
           <?php $option_collection = option_collect("Others Earning", "");
                        echo $option_collection['options'];
                       // $category = $option_collection['category'];
                        ?>
                    </select>
                    
                    <input type="hidden" name="category" id="category" class="form-control" value="<?php echo $option_collection['category'];?>" required>
            </div>

            <div class="col-4">
                <label for="taka" class="form-label">Total Taka</label>
                <input type="number" name="taka" id="taka" class="form-control"  required>
            </div>
            <div class="col-4">
                <label for="taka" class="form-label">Paid BY Cash</label>
                <input type="number" name="cash_paid" id="taka" class="form-control" value="0">
            </div>
            <div class="col-4">
                <label for="taka" class="form-label">Paid By Bank</label>
                <input type="number" name="bank_paid" id="taka" class="form-control" value="0">
            </div>
            <div class="col-4">
                <label for="taka" class="form-label">Due</label>
                <input type="number" name="due" id="taka" class="form-control" value="0">
            </div>
             <div class="col-4">
                <label for="details" class="form-label">Comment</label>
                <input type="text" name="comment" id="comment" class="form-control">
            </div>
      </div>

       

        <button type="submit" name="submit" class="btn btn-primary">Submit</button>
</form>