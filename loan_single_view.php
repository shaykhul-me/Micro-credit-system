<?php
$loan_id = $_GET['loansoftid'];
// view code
$data = single_condition_select("loan_account", "id", "$loan_id");

$row = mysqli_fetch_array($data['query']);
             $name =$row['name'];
               $name_bn = $row['name_bn'];
               $fname = $row['father_name'];
               $mname = $row['mother_name'];
               $spause_name = $row['spouse_name'];
               $email = $row['email'];
               $mobile = $row['mobile'];
               $nid = $row['nid'];
                $gender = $row['gender'];
            	$dob = $row['date_of_birth'];
               $nominee_name = $row['nominee_name'];
                 $nominee_nid_no = $row['nominee_nid_no'];
                 $relation = $row['nominee_relation'];
                 $nominee_mobile = $row['nominee_mobile'];
                 $status = $row['status'];
                 $present_address = $row['present_address'];
                 $present_upazila = $row['present_thana'];
                $present_district= $row['present_district'];
                 $permanent_address= $row['permanent_address'];
                 $permanent_upazila = $row['permanent_thana'];
                 $permanent_district = $row['permanent_district'];
                 $member_img = $row['member_photo'];
                $member_nid= $row['member_nid'];
                 $member_signature = $row['member_signature'];
                 $nominee_photo = $row['nominee_img'];
                 $nominee_nid = $row['nominee_nid'];
				 $loan_type = $row['loan_type'];
				 $product_price = $row['product_price'];
				 $profit_percent = $row['profit_percent'];
				 $profit = $row['profit'];
				 $net_amount = $row['net_amount'];
				 $total_installment = $row['total_installment'];
				 $taka_per_installment = $row['taka_per_installment'];
				$actual_per_installment = $row['actual_per_installment'];
				$profit_per_inatallment = $row['profit_per_inatallment'];
				 $comment = $row['comment'];

 
        
    if (isset($_POST['submit']) && $_POST['taka'] > 0) {
    $category = "Loan";
    $status = $_POST['status'];
    $taka = isset($_POST['taka']) ? (int)$_POST['taka'] : 0;
    $cash_paid = isset($_POST['cash_paid']) ? (int)$_POST['cash_paid'] : 0;
    $bank_paid = $_POST['bank_paid'];
    $total = $cash_paid + $bank_paid;
	 if($total != $taka){
       echo "<div align='center' class='alert alert-warning' style='width:40%; font-size:20px; margin:0 auto;'>Sorry! Amount Not Match!! Try Again.</div>";
       exit();
    }	 	    
		 	    
	 $transections_add = array(
        "created_by" =>  $uid,
        "created_at" =>  $datetime,
        "created_ip" => $ip,
        "entry_date" => $today,
        "category" => $category,
        "details" => $loan_type,
        "cash_out" => $taka,
        "status" => "Paid",
    );
    
        $transections_submit = insert_data($conn, $transections_add, "transections");
        $submited_id = $transections_submit['last_id'];
        
    if($submited_id){
    
        
        
     $cash_id = 0; 
    $bank_id = 0;
   $update = "";
    
    // Cash Decrease
     if($cash_paid > 0){
    $cash_decrease = array(
        "transection_id" => $submited_id,
        "entry_date" => $today,
        "category" => $category,
        "title" => $loan_type,
        "decrease" => $cash_paid
        
    );
    
        $cash_submit = insert_data($conn, $cash_decrease, "cash");
    $cash_id = $cash_submit['last_id'];
     }
    //Bank Decreas
    if($bank_paid > 0){
        $bank_decrease = array(
        "transection_id" => $submited_id,
        "entry_date" => $today,
        "category" => $category,
        "title" => $loan_type,
        "decrease" => $bank_paid
        
    );
    
        $bank_submit = insert_data($conn, $bank_decrease, "bank");
    $bank_id = $bank_submit['last_id'];
    
    // update loadn status
    $sql = "UPDATE loan_account SET status='$status' WHERE id=$loan_id";

    $update = mysqli_query($conn, $sql);
    }            
  if(isset($update, $cash_id, $bank_id)){
        echo "<div align='center' class='alert alert-success' style='width:40%; font-size:20px; margin:0 auto;'>Loan Approved Successfully</div>";       }      
}
}
?>

<div class="row">
    <!-- <div class="col-1"></div> -->
    <div class="col-12">
       
            <div class="text-center font-weight-bold font-weight-bolder">
                <h3 class="font-weight-bold font-weight-bolder text-capitalize">লোন একাউন্ট এর বিস্তারিত   </h3>
            </div><br>
            <div class="col-12">
                <h3 class="text-center">ব্যক্তিগত তথ্য</h3>
                <hr>
            </div>
            <!-- Name And Name (Bangla) -->
            <div class="form-row">
               
                <div class="form-group col-md-6">
                    <label for="staffname">নাম (ইংরেজী)<span style="color:red;">**</span></label>
                    <?php echo $name; ?>
                </div>
                <div class="form-group col-md-6">
                    <label for="name_bangla">নাম (বাংলা)</label>
                    <?php echo $name_bn; ?>
                </div>
            </div>
            <!-- Father's Name and Mother's Name -->
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="class">পিতার নাম</label>
                   <?php echo $fname; ?> 
                </div>
                <div class="form-group col-md-6">
                    <label for="name">মাতার নাম</label>
                    <?php echo $mname; ?> 
                </div>
            </div>
            <!-- Spouse's Name And NID -->
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="spause_name">স্বামী/স্ত্রীর নাম</label>
 <?php echo $spause_name; ?>
                </div>
                <div class="form-group col-md-6">
                    <label for="class">এন,আই,ডি/জন্ম নিবন্ধন:<span style="color:red;">**</span></label>
                     <?php echo $nid; ?>
                </div>
            </div>
            <!-- Email And Cell Phone -->
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="name">ইমেইল ঃ</label>
                     <?php echo $email; ?>
                </div>
                <div class="form-group col-md-6">
                    <label for="name">মোবাইল ঃ</label>
                    <div class="form-inline">
                        
                         <?php echo $mobile; ?>
                    </div>
                </div>
            </div>
      
            <!-- Date of Birth And Educational Qualification -->
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="name">জন্ম তারিখ</label>
                    <?php echo $dob; ?>
                </div>
                <div class="form-group col-md-6">
                    <label for="gender">লিঙ্গ<span style="color:red;">**</span></label>
                     <?php echo $gender; ?>
                </div>
            </div>
            <!-- Nominee Name && Nominee NID Number -->
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="nominee_name">নমিনীর নাম</label><span style="color:red;">**</span>
                    <?php echo $nominee_name; ?>
                </div>
                <div class="form-group col-md-6">
                    <label for="nominee_nid">নমিনীর এন,আই,ডি</label><span style="color:red;">**</span>
                   <?php echo $nominee_nid_no; ?>
                </div>
            </div>
            <!-- Relationship with Nominee && status-->
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="nominee_relation">নমিনীর সাথে সম্পর্ক</label><span style="color:red;">**</span>
                    <?php echo $relation; ?>
                </div>
                <div class="form-group col-md-6">
                    <label for="nominee_mobile">নমিনীর মোবাইল</label><span style="color:red;">**</span>
                   <?php echo $nominee_mobile; ?>
                </div>
            </div>


            <div class="col-12">
                <h3 class="text-center">ঠিকানা</h3>
                <hr>
            </div>
            <div class="col-12 pl-0">
                <h5 class="text-leff ml-0 pl-0">বার্তমান</h5>
            </div>
            <!-- Present Address -->
            <div class="form-row">
                <div class="form-group col-sm-4">
                    <label for="name">পাড়া/গ্রাম/পোষ্ট</label>
                     <?php echo $present_address; ?>
                </div>
                <div class="form-group col-sm-4">
                    <label for="name">উপজেলা</label>
                    <?php echo $present_upazila; ?>
                </div>
                <div class="form-group col-sm-4">
                    <label for="name">জেলা</label>
                   <?php echo $present_district; ?> 
                </div>
            </div>
<div class="col-12 pl-0">
                <h5 class="text-leff ml-0 pl-0">স্থায়ী</h5>
            </div>
            <!-- Permanent Address -->
            <div class="form-row">
                <div class="form-group col-sm-4">
                    <label for="name">পাড়া/গ্রাম/পোষ্ট</label>
                   <?php echo $permanent_address; ?>
                </div>
                <div class="form-group col-sm-4">
                    <label for="name">উপজেলা</label>
                    <?php echo $permanent_upazila; ?>
                </div>
                <div class="form-group col-sm-4">
                    <label for="name">জেলা</label>
                    <?php echo $permanent_district; ?>
                </div>
            </div>
            <hr>
            
            <div class="col-12">
                <h3 class="text-center">লোন/ক্রয় সংক্রান্ত</h3>
                <hr>
            </div>
            <div class="form-row">
                <div class="form-group col-sm-4">
                    <label for="name">ঋনের ধরন: </label>
                    <?php echo $loan_type; ?>
                </div>
                
                <div class="form-group col-sm-4">
                    <label for="name">পন্যের মূল্য/ঋনের পরিমান: </label>
                    <?php echo $product_price; ?>
                </div>
                <div class="form-group col-sm-4">
                    <label for="name">লাভের % </label>
                   <?php echo $profit_percent; ?>
                </div>
                <div class="form-group col-sm-4">
                    <label for="name">লাভ </label>
                     <?php echo $profit; ?>
                </div>
                <div class="form-group col-sm-4">
                    <label for="name">মোট মূল্য </label>
                    <?php echo $net_amount; ?>
                </div>
                <div class="form-group col-sm-4">
                    <label for="name">কিস্তির পরিমান </label>
                     <?php echo $total_installment; ?>
                </div>
                <div class="form-group col-sm-4">
                    <label for="name">কিস্তি প্রতি টাকা </label>
                       <?php echo $taka_per_installment; ?>
                </div>
                <div class="form-group col-sm-4">
                    <label for="name">কিস্তি প্রতি আসল </label>
                     <?php echo $actual_per_installment; ?>
                </div>
                <div class="form-group col-sm-4">
                    <label for="name">কিস্তি প্রতি লাভ </label>
                    <?php echo $profit_per_inatallment; ?>
                </div>
                
                
                </div>
            <hr>
            <div class="col-12">
                <h3 class="text-center">ডকুমেন্ট </h3>
                <hr>
            </div>
            <!-- Photo & NID & Nominee Photo & Nominee NID -->
            <div class="form-row">
                <div class="form-group col-md-4 mb-2 border">
                    <label for="member_img">মেম্বার ফটো</label>
                    <div class="text-center"><img height='100px' class="photo_show" src="<?php echo $member_img; ?>" alt="your image" id="member_img_show"></div>
                    
                </div>
                <div class="form-group col-md-4 mb-2 border">
                    <label for="m_nid">মেম্বার এন,আই,ডি  </label>
                    <div class="text-center">
                        <img height='100px' class="photo_show rounded" src="<?php echo $member_nid; ?>" alt="Member NID Front" id="m_nid_show">
                    </div>
                    
                </div>
               
                <div class="form-group col-md-4 mb-2 border">
                    <label for="m_signature">মেম্বার স্বাক্ষর</label>
                    <div class="text-center">
                        <img height='100px' class="photo_show rounded" width='100px' src="<?php echo $member_signature; ?>" alt="Member NID Front" id="m_signature_show">
                    </div>
                   
                </div>
                <div class="form-group col-md-4 mb-2 mt-3 border">
                    <label for="nominee_photo">নমিনী ছবি</label>
                    <div class="text-center">
                        <img height='100px' class="photo_show rounded" src="<?php echo $nominee_photo; ?>" alt="Nominee image" id="nominee_photo_show">
                    </div>
                    
                </div>
                <div class="form-group col-md-4 mb-2 mt-3 border">
                    <label for="nominee_nid">নমিনী এন,আই,ডি  </label>
                    <div class="text-center">
                        <img class="photo_show rounded" height='100px' src="<?php echo $nominee_nid; ?>" alt="Member form" id="nominee_nid_img_show">
                    </div>
                    
                </div>
                
                <div class="form-group col-md-4 mb-2 mt-3 border">
                    <label>মন্তব্য</label>
                    <?php echo $comment; ?>
                    
                </div>    
                
            </div>
            
          
          <h2 class="text-center">Loan Approved</h2>
 <form method="post" action="">
     
  
         <div class="row mb-3">
            <div class="col-4">
                    <label for="name">Total Amount </label>
                   
                <input type="number" name="taka" id="taka" class="form-control" value="<?php echo $product_price; ?>" readonly  required>
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
                <lebel>Change Status</lebel>
                    <select name="status">
                        <option value="">-- Select --</option>
                        <option value="Approved">Approved</option>
                        <option value="Reject">Reject</option>
                        
                    </select>
            </div>   
          
            
      </div>

       

        <button type="submit" name="submit" class="btn btn-primary">Submit</button>
</form>
          
             