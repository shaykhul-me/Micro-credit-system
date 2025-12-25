<?php
if (isset($_POST['submit']) && $_POST['taka'] > 0) {
    $category = field_protect($_POST['category']);
    $category_item = field_protect($_POST['category_item']);
    $entry_date = field_protect($_POST['entry_date']);
    $taka = isset($_POST['taka']) ? (int)$_POST['taka'] : 0;
    $accounts_profit = $_POST['accounts_profit'];
    $member_id = $_POST['member_id'];
    $name = $_POST['name'];
    $ac_id = $_POST['ac_id'];
   
    $comment = field_protect($_POST['comment']);
 
    
     
      //profit INCREASE
    
        $accounts_profit = array(
        "entry_date" => $today,
        "category" => $category_item,
        "profit" => $taka,
        "comment"=> $comment,
    );
    
        $accounts_profit_submit = insert_data($conn, $accounts_profit, "accounts_profit");
    $accounts_profit_id = $accounts_profit_submit['last_id'];
  
    
    
    if(isset($accounts_profit_id)){
        echo "<div align='center' class='alert alert-success' style='width:40%; font-size:20px; margin:0 auto;'> Inserted Successfully</div>";
        
       $redirect_url = "index.php?action=profit_view&id=$accounts_profit_id";
    
    echo '<meta http-equiv="refresh" content="0;url='.$redirect_url.'" />';
        }
        
      else{
       echo "<div align='center' class='alert alert-warning' style='width:40%; font-size:20px; margin:0 auto;'>Sorry! Asset Not Inserted</div>";
    }  
        
    }
    
    ?>
 <h2>Accounts Profit</h2>
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
                   
           <?php $option_collection = option_collect("account_type", "");
                        echo $option_collection['options'];
                       // $category = $option_collection['category'];
                        ?>
                    </select>
                    
                    
            </div>

            <div class="col-4">
                <label for="taka" class="form-label">Total Taka</label>
                <input type="number" name="taka" id="taka" class="form-control"  required>
            </div>
            
           
             <div class="col-4">
                <label for="details" class="form-label">Comment</label>
                <input type="text" name="comment" id="comment" class="form-control">
            </div>
            
      </div>

       

        <button type="submit" name="submit" class="btn btn-primary">Submit</button>
</form>