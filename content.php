<?php

?>
<!-- START PAGE CONTENT-->

<div class="page-content fade-in-up">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="ibox bg-success color-white widget-stat">
                <a href="index.php?action=savings_all" style="text-decoration: none; color: inherit;">
                    <div class="ibox-body text-center">

                        <h3 class="m-b-5 font-strong">স্বাগতম</h3>
                        <!-- <h4 class="m-b-5"></h4> -->
                        <!-- <i class="ti-money widget-stat-icon"></i> -->
                        <hr>
                        <!-- <div class="h6"><i class="fa fa-check-circle-o m-r-5"></i>বর্তমান জমা <span class="float-right"> টাকা</span></div> -->
                    </div>
                </a>
            </div>
        </div>
        <!-- <div class="col-lg-3 col-md-6">
            <div class="ibox bg-success color-white widget-stat">
                <a href="index.php?action=savings_all" style="text-decoration: none; color: inherit;">
                    <div class="ibox-body">
                        <?php
                        // $select_branch = "";
                        // if ($user_other_info['branch_id'] > 0) {
                        //     $select_branch = "AND branch='{$user_other_info['branch_id']}'";
                        // }
                        // $active_member = array();
                        // $savings_sql = manual_query("SELECT `account_create`.*, SUM((`transections`.`total_tk`-`transections`.`fine`)-`transections`.`withdraw`) as total_savings FROM `account_create` LEFT JOIN `transections` ON `account_create`.`id`=`transections`.`account_no` WHERE `account_create`.`account_type`='savings' AND `account_create`.`status`='active' $select_branch GROUP BY `account_create`.`id`");
                        // $total_savings = 0;
                        // while ($savings_res = mysqli_fetch_assoc($savings_sql['query'])) {
                        //     $total_savings += $savings_res['total_savings'];
                        //     if (!in_array($savings_res['member_id'], $active_member)) {
                        //         array_push($active_member, $savings_res['member_id']);
                        //     }
                        // };
                        ?>
                        <h3 class="m-b-5 font-strong">সঞ্চয়ী একাউন্ট</h3>
                        <h4 class="m-b-5"><?php //echo $savings_sql['count'] ?> টি</h4>
                        <! -- <i class="ti-money widget-stat-icon"></i> -- >
                        <hr>
                        <div class="h6"><i class="fa fa-check-circle-o m-r-5"></i>বর্তমান জমা <span class="float-right"> <?php //echo $total_savings ?> টাকা</span></div>
                    </div>
                </a>
            </div>
        </div> -->
        <!-- <div class="col-lg-3 col-md-6">
            <div class="ibox bg-info color-white widget-stat">
                <a href="index.php?action=deposit_all" style="text-decoration: none; color: inherit;">
                    <div class="ibox-body">
                        <?php
                        // $deposit_sql = manual_query("SELECT `account_create`.* FROM `account_create` WHERE `account_create`.`account_type`='deposit' AND `account_create`.`status`='active' $select_branch GROUP BY `account_create`.`id`");
                        // $total_deposit = 0;
                        // while ($deposit_res = mysqli_fetch_assoc($deposit_sql['query'])) {
                        //     $total_deposit += $deposit_res['basic_amount'];
                        //     if (!in_array($deposit_res['member_id'], $active_member)) {
                        //         array_push($active_member, $deposit_res['member_id']);
                        //     }
                        // };
                        ?>
                        <h3 class="m-b-5 font-strong">মেয়াদী আমানত</h3>
                        <h4 class="m-b-5"><?php //echo $deposit_sql['count'] ?> টি</h4>
                        <hr>
                        <div class="h6"><i class="fa fa-check-circle-o m-r-5"></i>বর্তমান জমা <span class="float-right"> <?php //echo $total_deposit ?> টাকা</span></div>
                    </div>
                </a>
            </div>
        </div> -->
        <!-- <div class="col-lg-3 col-md-6">
            <div class="ibox bg-purple-800 color-white widget-stat">
                <a href="index.php?action=loan_all" style="text-decoration: none; color: inherit;">
                    <div class="ibox-body">
                        <?php
                        // $loan_sql = manual_query("SELECT `account_create`.*, SUM(`transections`.`total_tk`-`transections`.`fine`) as total_paid FROM `account_create` LEFT JOIN `transections` ON `account_create`.`id`=`transections`.`account_no` WHERE `account_create`.`account_type`='loan' AND `account_create`.`status`='active' $select_branch GROUP BY `account_create`.`id`");
                        // $total_loan = 0;
                        // $loan_paid = 0;
                        // while ($loan_res = mysqli_fetch_assoc($loan_sql['query'])) {
                        //     $total_loan += $loan_res['basic_amount'];
                        //     $loan_paid += $loan_res['total_paid'];
                        //     if (!in_array($loan_res['member_id'], $active_member)) {
                        //         array_push($active_member, $loan_res['member_id']);
                        //     }
                        // };
                        ?>
                        <h3 class="m-b-5 font-strong">বাকীতে বিক্রয়</h3>
                        <h4 class="m-b-5"><?php //echo $loan_sql['count'] ?> টি</h4>
                        <hr>
                        <div class="h6"><i class="fa fa-check-circle-o m-r-5"></i>অনাদায়ী <span class="float-right"> <?php //echo $total_loan - $loan_paid; ?> টাকা</span></div>
                    </div>
            </div>
        </div> -->
        <!-- Members -->
        <!-- <div class="col-lg-3 col-md-6">
            <div class="ibox bg-yellow-800 color-white widget-stat">
                <a href="index.php?action=members_view" style="text-decoration: none; color: inherit;">
                    <div class="ibox-body">
                        <?php
                        // $member_sql = manual_query("SELECT COUNT(*) as active_member FROM `members` WHERE status='active' $select_branch");
                        // $member_res = mysqli_fetch_assoc($member_sql['query']);
                        ?>
                        <h3 class="m-b-5 font-strong">সদস্য</h3>
                        <h4 class="m-b-5"><?php //echo $member_res['active_member'] ?> জন</h4>
                        <hr>
                        <div class="h6"><i class="fa fa-check-circle-o m-r-5"></i>সক্রিয় <span class="float-right"> <?php //echo count($active_member); ?> জন</span></div>
                    </div>
                </a>
            </div>
        </div> -->
        <!-- Staff Balance Information -->
        <!-- <div class="col-lg-3 col-md-6">
            <div class="ibox bg-teal color-white widget-stat">
                <div class="ibox-body">
                    <h3 class="m-b-5 font-strong">স্টাফ</h3>
                    <h4 class="m-b-5">ব্যালেন্স  টাকা</h4>
                    <hr>
                    <div class="h6"><i class="fa fa-check-circle-o m-r-5"></i>স্টাফ সংখ্যা <span class="float-right"> জন</span></div>
                </div>
            </div>
        </div> -->
        <!-- Branch -->
        <!-- <div class="col-lg-3 col-md-6">
            <div class="ibox bg-grey color-white widget-stat">
                <a href="index.php?action=expenses_list" style="text-decoration: none; color: inherit;">
                    <div class="ibox-body">
                        <?php

                        ?>
                        <h3 class="m-b-5 font-strong"> ব্যয়যোগ্য ব্যালেন্স</h3>
                        <h4 class="m-b-5">&nbsp;
                             টাকা
                        </h4>
                        <hr>
                        <div class="h6">&nbsp;</div>
                    </div>
                </a>
            </div>
        </div> -->
        <!-- Last Month Profit -->
        <!-- <div class="col-lg-3 col-md-6">
            <div class="ibox bg-blue color-white widget-stat">
                <div class="ibox-body">
                    
                    <h3 class="m-b-5 font-strong">গত মাসের মুনাফা</h3>
                    <h4 class="m-b-5">টাকা</h4>
                    <hr>
                    <div class="h6">&nbsp;</div>
                </div>
            </div>
        </div> -->
        <!-- Last Month Expense -->
        <!-- <div class="col-lg-3 col-md-6">
            <div class="ibox bg-purple color-white widget-stat">
                <div class="ibox-body">
                    
                    <h3 class="m-b-5 font-strong">মোট খরচ</h3>
                    <h4 class="m-b-5"> টাকা</h4>
                    <hr>
                    <div class="h6">&nbsp;</div>
                </div>
            </div>
        </div> -->
        <!-- Last Month Withdraw -->
        <!-- <div class="col-lg-3 col-md-6">
            <div class="ibox bg-blue-light-600 color-white widget-stat">
                <div class="ibox-body">
                    <?php

                    ?>
                    <h3 class="m-b-5 font-strong">মোট উত্তোলন</h3>
                    <h4 class="m-b-5"> টাকা</h4>
                    <hr>
                    <div class="h6">&nbsp;</div>
                </div>
            </div>
        </div> -->
        <!-- Last Month Withdraw -->
        <!-- <div class="col-lg-3 col-md-6">
            <div class="ibox bg-orange color-white widget-stat">
                <a href="index.php?action=fine_list_view" style="text-decoration: none; color: inherit;">
                    <div class="ibox-body">
                        <?php

                        ?>
                        <h3 class="m-b-5 font-strong">মোট জরিমানা</h3>
                        <h4 class="m-b-5"> টাকা</h4>
                        <hr>
                        <div class="h6">&nbsp;</div>
                    </div>
                </a>
            </div>
        </div> -->
        <!-- Total Profit -->
        <!-- <div class="col-lg-3 col-md-6">
            <div class="ibox bg-success color-white widget-stat">
                <?php

                ?>
                <div class="ibox-body">
                    <h3 class="m-b-5 font-strong">মোট মুনাফা</h3>
                    <h4 class="m-b-5"><?php ?> টাকা</h4>
                    <hr>
                    <div class="h6">&nbsp;</div>
                </div>
            </div>
        </div> -->
        <!-- Total Taka -->
        <!-- <div class="col-lg-3 col-md-6">
            <div class="ibox bg-ebony color-white widget-stat">
                <div class="ibox-body">
                    <h3 class="m-b-5 font-strong">মোট টাকা</h3>
                    <h4 class="m-b-5"> টাকা</h4>
                    <hr>
                    <div class="h6">&nbsp;</div>
                </div>
            </div>
        </div> -->
    </div>
    <script src="assets/js/scripts/dashboard_1_demo.js" type="text/javascript"></script>
    <!-- END PAGE CONTENT-->