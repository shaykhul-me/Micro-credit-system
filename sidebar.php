<!-- START SIDEBAR-->
<nav class="page-sidebar" id="sidebar">
    <div id="sidebar-collapse">
        <div class="admin-block d-flex">
            <div>
                <img src="./assets/img/admin-avatar.png" width="45px" />
            </div>
            <div class="admin-info">
                <div class="font-strong"><?php echo isset($uname) ? $uname : "Admin"; ?></div>
                <small><?php echo isset($ac_type) ? $staff_desig_array[$ac_type] : "Admin"; ?></small><br>
                <small><?php echo date("d-m-Y", strtotime($datetime)); ?></small>
            </div>

        </div>
        <div class="admin-block d-flex text-white">
            <?php echo isset($user_other_info['branch_name']) ? $user_other_info['branch_name'] : ""; ?> Branch
        </div>
        <ul class="side-menu metismenu">
            <li>
                <a class="active" href="index.php"><i class="sidebar-item-icon fa fa-th-large"></i>
                    <span class="nav-label">Dashboard</span>
                </a>
            </li>            
            <!-- <li>
                <a class="active" href="index.php?action=search_account"><i class="sidebar-item-icon fa fa-search"></i>
                    <span class="nav-label">Search Account</span>
                </a>
            </li> -->
            <!-- <li class="heading">FEATURES</li> -->
            <?php
            if ($ac_type == "admin") {
            ?>

                <!-- Earnings -->
                <li>
                    <a href="index.php?action=share_savings_collection"><i class="sidebar-item-icon fa fa-calculator"></i>
                        <span class="nav-label">Share Savings Collection</span></a>                
                </li>
                <!-- Withdraw -->
                <li>
                    <a href="index.php?action=withdraw"><i class="sidebar-item-icon fa fa-calculator"></i>
                        <span class="nav-label">Withdraw</span></a>
                </li>
                <li>
                    <a href="index.php?action=transections_short"><i class="sidebar-item-icon fa fa-calculator"></i>
                        <span class="nav-label">Transection Short</span></a>
                </li>
                <li>
                    <a href="index.php?action=transection_details"><i class="sidebar-item-icon fa fa-calculator"></i>
                        <span class="nav-label">Transections Details</span></a>
                </li>

                

                <!-- Account Open -->
                <li>
                    <a href="javascript:;"><i class="sidebar-item-icon fa fa-calculator"></i>
                        <span class="nav-label">Loan Account</span><i class="fa fa-angle-left arrow"></i></a>
                    <ul class="nav-2-level collapse">
                        <li>
                            <a href="index.php?action=loan_account">New Account</a>
                        </li>                        
                        <li>
                            <a href="index.php?action=loan_view">All Loan</a>
                        </li>
                        <li><a href="index.php?action=loan_installment_collection">Loan Installment</a></li>
                        <li><a href="index.php?action=special_loan_installment">Special Loan Installment</a></li>
                    </ul>
                </li>
                  <!-- Assest and Investment -->
                <li>
                    <a href="javascript:;"><i class="sidebar-item-icon fa fa-calculator"></i>
                        <span class="nav-label">Assest and Investment</span><i class="fa fa-angle-left arrow"></i></a>
                    <ul class="nav-2-level collapse">
                        <li>
                            <a href="index.php?action=assets">Assets</a>
                        </li>                        
                        <li>
                            <a href="index.php?action=assets_list">Assets List</a>
                        </li>
                        <li><a href="index.php?action=investment">Investment</a></li>
                        <li><a href="index.php?action=investment_list">Investment List</a></li>
                    </ul>
                </li>
                
                 <!-- Bank -->
                <li>
                    <a href="javascript:;"><i class="sidebar-item-icon fa fa-calculator"></i>
                        <span class="nav-label">Bank</span><i class="fa fa-angle-left arrow"></i></a>
                    <ul class="nav-2-level collapse">
                        <li>
                            <a href="index.php?action=bank">Bank</a>
                        </li>                        
                        <li>
                            <a href="index.php?action=bank_list.php">Bank Transaction List</a>
                        </li>
                        
                    </ul>
                </li>
                
                
                  <!-- Earning and Expense -->
                <li>
                    <a href="javascript:;"><i class="sidebar-item-icon fa fa-calculator"></i>
                        <span class="nav-label">Earning and Expense</span><i class="fa fa-angle-left arrow"></i></a>
                    <ul class="nav-2-level collapse">
                        <li>
                            <a href="index.php?action=others_earning">Others Earnings</a>
                        </li>                        
                        <li>
                            <a href="index.php?action=others_earning_list">Others Earning Listt</a>
                        </li>
                        <li><a href="index.php?action=administrative_expense">Administrative Expense</a></li>
                        <li><a href="index.php?action=administrative_expense_list">Adminis. Expense List</a></li>
                        <li><a href="index.php?action=operating_expense">Operating Expense</a></li>
                        <li><a href="index.php?action=operating_expense_list">Operating Expense List</a></li>
                    </ul>
                </li>
                
                <!-- Report -->
                <!-- <li>
                    <a href="index.php?action=daily_transection"><i class="sidebar-item-icon fa fa-money"></i>
                        <span class="nav-label">Daily Report</span></a>
                </li> -->

                <!-- Members -->
                <li>
                    <a href="javascript:;"><i class="sidebar-item-icon fa fa-address-card"></i>
                        <span class="nav-label">Members</span><i class="fa fa-angle-left arrow"></i></a>
                    <ul class="nav-2-level collapse">
                        <li>
                            <a href="index.php?action=member_add">New Member</a>
                        </li>
                        <li>
                            <a href="index.php?action=members_view">Members List</a>
                        </li>
                    </ul>
                </li>

                <!-- Staffs -->
                <!-- <li>
                    <a href="javascript:;"><i class="sidebar-item-icon fa fa-address-card"></i>
                        <span class="nav-label">Staffs</span><i class="fa fa-angle-left arrow"></i></a>
                    <ul class="nav-2-level collapse">
                        <li>
                            <a href="index.php?action=staff_insert">Staff Insert</a>
                        </li>
                        <li>
                            <a href="index.php?action=staff_view">Staff List</a>
                        </li>
                        <li>
                            <a href="index.php?action=staff_transections_view">Staff Transection</a>
                        </li>                        
                    </ul>
                </li> -->

                <li>
                    <a href="index.php?action=user_view"><i class="sidebar-item-icon fa fa-users"></i>
                        <span class="nav-label">Users</span></a>
                </li>                
            <?php
            }
            if ($ac_type == "staff") {
            ?>
                <li>
                    <a href="javascript:;"><i class="sidebar-item-icon fa fa-calculator"></i>
                        <span class="nav-label">Account Open</span><i class="fa fa-angle-left arrow"></i></a>
                    <ul class="nav-2-level collapse">
                        <li>
                            <a href="index.php?action=saving_receive">New Account</a>
                        </li>
                        <li>
                            <a href="index.php?action=savings_all">Savings List</a>
                        </li>
                        <li>
                            <a href="index.php?action=deposit_all">Deposit List</a>
                        </li>
                        <li>
                            <a href="index.php?action=loan_all">Loan List</a>
                        </li>
                    </ul>
                </li>
                
            <?php
            }
            ?>
            <li>
                            <a href="index.php?action=settings"><i class="sidebar-item-icon fa fa-users"></i>
                        <span class="nav-label">Settings</span></a>
                        </li>
        </ul>
    </div>
</nav>
<!-- END SIDEBAR-->
<!-- Content Start -->
<div class="content-wrapper">