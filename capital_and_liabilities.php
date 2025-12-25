<?php

if (isset($_POST['submit'])) {
    $category = field_protect($_POST['category']);
    $category_item = field_protect($_POST['category_item']);
    $taka = $_POST['taka'];
    $comment = field_protect($_POST['comment']);

 //New Transection Create

    $transections_add = array(
        "created_by" =>  $uid,
        "created_at" =>  $datetime,
        "created_ip" => $ip,
        "entry_date" => $today,
        "category" => $category,
        "details" => $category_item,
        "comment" => $comment,
        "cash_in" => $taka,
        "status" => "Paid",
    );
    
        $transections_submit = insert_data($conn, $transections_add, "transections");
        $submited_id = $transections_submit['last_id'];
        
    if($submited_id){
     echo "<div align='center' class='alert alert-success' style='width:40%; font-size:20px; margin:0 auto;'>Expense Inserted Successfully</div>";
     
    $redirect_url = "index.php?action=capital_liabilities_list&id=$submited_id";
    
    echo '<meta http-equiv="refresh" content="0;url='.$redirect_url.'" />';
}
}
?>
 <h2>capital and liabilities</h2>
 <form method="post" action="">
         <div class="row mb-3">
            <div class="col-4">
                
                <label for="category Item" class="form-label">Category Item</label>
                <select class="form-control" name="category_item" id="category_item" required>
                    <option value="">-- SELECT --</option>
           <?php $option_collection = option_collect("capital investments", "");
                        echo $option_collection['options'];
                       // $category = $option_collection['category'];
                        ?>
                    </select>
                    
                    <input type="hidden" name="category" id="category" class="form-control" value="<?php echo $option_collection['category'];?>" required>
            </div>

            <div class="col-4">
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