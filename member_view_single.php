<?php
$id = $_GET['id'];

$data = single_post_view("members", $id);
if($data['count'] != 1){
    echo '<div class="alert alert-info" role="alert">Sorry! Member Not Found</div>';
    return;
}
$row = mysqli_fetch_assoc($data['query']);
// if($ac_type != "admin"){
//     if($ac_type =="staff" && $row['branch'] != $user_other_info['branch_id']){
//         echo '<div class="alert alert-info" role="alert">Sorry! You Have no Parmission to see this information</div>';
//     return;
//     }
// }
// $staffinfo = single_post_view("staffs", $row['reference']);
// $row2 = mysqli_fetch_assoc($staffinfo['query']);
$created_user = single_post_view("users", $row['created_by']);
$row3 = mysqli_fetch_assoc($created_user['query']);
//Office info
// $office_sql = single_condition_select("office", "id", $row['office']);
// $office_res = mysqli_fetch_assoc($office_sql['query']);
// $office_name = $office_res['name'];
//Branch info
// $branch_sql = single_condition_select("branch", 'id', $row['branch']);
// $branch_res = mysqli_fetch_assoc($branch_sql['query']);
// $branch_name = $branch_res['branch_name'];
//Account_info
// $member_accounts_sql = single_condition_select("account_create", "member_id", $id);

// $member_accounts_sql = manual_query("SELECT * FROM account_create WHERE member_id='{$id}' AND status='active'");
// $savings = "";
// $deposit = "";
// $loan = "";
// while ($account_res = mysqli_fetch_assoc($member_accounts_sql['query'])) {
//     if($account_res['account_type'] =="savings"){
//         $savings .= "<li class='list-group-item'><a href='index.php?action=account_info&id={$account_res['id']}'>{$account_res['account_no']}</a></li>";
//     }
//     if($account_res['account_type'] =="deposit"){
//         $deposit .= "<li class='list-group-item'><a href='index.php?action=deposit_status&deposit_id={$account_res['id']}'>{$account_res['account_no']}</a></li>";
//     }
//     if($account_res['account_type'] =="loan"){
//         $loan .= "<li class='list-group-item'><a href='index.php?action=loan_status&loan_id={$account_res['id']}'>{$account_res['account_no']}</a></li>";
//     }
// }


?>
<style>
    hr {
        margin-top: 5px;
        margin-bottom: 5px;
    }
</style>

<div class="page-content fade-in-up">
    <div class="row">
        <div class="col-lg-3 col-md-4">
            <div class="ibox">
                <div class="ibox-body text-center">
                    <div class="m-t-20">
                        <img class="img-circle" src="<?php echo isset($row['member_photo']) && file_exists($row['member_photo']) ? $row['member_photo'] : 'assets/images/img/demo.jpg'; ?>" height="150px" />
                    </div>
                    <h5 class="font-strong m-b-10 m-t-10"><?php echo isset($row['name']) ? $row['name'] : "";
                                                            echo isset($row['name_bn']) ? "<br>" . $row['name_bn'] : ""; ?></h5>
                    <div class="m-b-20 text-muted">Member</div>
                    <!-- <div class="profile-social m-b-20">
                        <a href="javascript:;"><i class="fa fa-twitter"></i></a>
                        <a href="javascript:;"><i class="fa fa-facebook"></i></a>
                        <a href="javascript:;"><i class="fa fa-pinterest"></i></a>
                        <a href="javascript:;"><i class="fa fa-dribbble"></i></a>
                    </div>                    
                    <div>
                        <button class="btn btn-info btn-rounded m-b-5"><i class="fa fa-plus"></i> Follow</button>
                        <button class="btn btn-default btn-rounded m-b-5">Message</button>
                    </div> 
                    <hr>-->
                    <div class="text-left">
                        <div class="text-danger font-weight-bold h4">Member's ID : <?php echo isset($row['id']) ? $row['id'] : ""; ?></div>
                    </div>
                    <hr>
                    <div class="text-left">
                        <div class="font-bold">Status : <?php echo isset($row['status']) ? $row['status'] : ""; ?></div>
                    </div>
                    <hr>
                    <!-- <div class="text-left">
                        <div class="font-bold">Office : <?php //echo isset($office_name) ? $office_name: ""; ?></div>
                    </div>
                    <hr>
                    <div class="text-left">
                        <div class="font-bold">Branch : <?php //echo isset($branch_name) ? $branch_name : ""; ?></div>
                    </div>
                    <hr>
                    <div class="text-left">
                        <div class="font-bold">Reference : <a href="index.php?action=staff_view_single&id=<?php echo isset($row['reference']) ? $row['reference'] : ""; ?>"><?php //echo isset($row2['name']) ? $row2['name'] : ""; ?></a></div>
                    </div>
                    <hr> -->
                    <div class="text-left">
                        <div class="font-bold">Created at : <?php echo isset($row['created_at']) ? $row['created_at'] : "";?></div>
                    </div>
                    <hr>
                    <div class="text-left">
                        <div class="font-bold">Created by : <a href="index.php?action=user_view_single&id=<?php echo isset($row['created_by']) ? $row['created_by'] : ""; ?>"><?php echo isset($row3['username']) ? $row3['username'] : ""; ?></a></div>
                    </div>
                </div>
            </div>
            <!-- <div class="ibox">
                <div class="ibox-body">
                    <div class="row m-b-20">
                        <div class="col-12">
                            <div class="font-24 profile-stat-count"></div>
                            <div class="text-muted">Savings</div>
                        </div>
                        <div class="col-12">
                            <ul class="list-group">
                                <?php //echo $savings;?>
                            </ul>
                        </div>
                        <div class="col-12">
                            <div class="font-24 profile-stat-count"></div>
                            <div class="text-muted">Deposit</div>
                        </div>                        
                        <div class="col-12">
                            <ul class="list-group">
                                <?php //echo $deposit;?>
                            </ul>
                        </div>
                        <div class="col-12">
                            <div class="font-24 profile-stat-count"></div>
                            <div class="text-muted">Loan</div>
                        </div>                        
                        <div class="col-12">
                            <ul class="list-group">
                                <?php //echo $loan;?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div> -->
        </div>
        <div class="col-lg-9 col-md-8">
            <div class="ibox">
                <div class="ibox-body">
                    <ul class="nav nav-tabs tabs-line">
                        <li class="nav-item">
                            <a class="nav-link active" href="#tab-2" data-toggle="tab"><i class="fa fa-cog" aria-hidden="true"></i> Information</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#member_files" data-toggle="tab"><i class="fa fa-file-image-o" aria-hidden="true"></i> Documents</a>
                        </li>
                        <!--<li class="nav-item">-->
                        <!--    <a class="nav-link" href="#tab-1" data-toggle="tab"><i class="fa fa-bar-chart" aria-hidden="true"></i> Overview</a>-->
                        <!--</li>-->
                        <!--<li class="nav-item">-->
                        <!--    <a class="nav-link" href="#tab-3" data-toggle="tab"><i class="fa fa-bullhorn" aria-hidden="true"></i> Feeds</a>-->
                        <!--</li>-->
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane fade show active" id="tab-2">
                            <form action="javascript:void(0)">
                                <div class="row">
                                    <div class="col-md-3 font-bold">Father's Name</div>
                                    <div class="col-md-3"><?php echo isset($row['father_name']) ? $row['father_name'] : ""; ?></div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-md-3 font-bold">Mother's Name</div>
                                    <div class="col-md-3"><?php echo isset($row['mother_name']) ? $row['mother_name'] : ""; ?></div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-sm-3 font-bold">Spouse's Name</div>
                                    <div class="col-sm-9"><?php echo isset($row['spouse_name']) ? $row['spouse_name'] : ""; ?></div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-sm-3 font-bold">Email</div>
                                    <div class="col-sm-9"><?php echo isset($row['email']) ? $row['email'] : ""; ?></div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-sm-3 font-bold">Mobile</div>
                                    <div class="col-sm-9"><?php echo isset($row['mobile']) ? $row['mobile'] : ""; ?></div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-sm-3 font-bold">NID</div>
                                    <div class="col-sm-9"><?php echo isset($row['nid']) ? $row['nid'] : ""; ?></div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-sm-3 font-bold">Gender</div>
                                    <div class="col-sm-9"><?php echo isset($row['gender']) ? $row['gender'] : ""; ?></div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-sm-3 font-bold">Date Of Birth</div>
                                    <div class="col-sm-9"><?php echo isset($row['date_of_birth']) ? $row['date_of_birth'] : ""; ?></div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-sm-3 font-bold">Blood Group</div>
                                    <div class="col-sm-9"><?php echo isset($row['blood_group']) ? $row['blood_group'] : ""; ?></div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-sm-3 font-bold">Religion</div>
                                    <div class="col-sm-9"><?php echo isset($row['religion']) ? $row['religion'] : ""; ?></div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-sm-3 font-bold">Education</div>
                                    <div class="col-sm-9"><?php echo isset($row['educational_qualification']) ? $row['educational_qualification'] : ""; ?></div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-sm-3 font-bold">Present Address</div>
                                    <div class="col-sm-9"><?php echo isset($row['present_address']) ? $row['present_address'] . ',&nbsp;&nbsp;' : "";
                                                            echo isset($row['present_thana']) ? $row['present_thana'] . ',&nbsp;&nbsp;' : "";
                                                            echo isset($row['present_district']) ? $row['present_district'] : ""; ?></div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-sm-3 font-bold">Permanent Address</div>
                                    <div class="col-sm-9"><?php echo isset($row['permanent_address']) ? $row['permanent_address'] . ',&nbsp;&nbsp;' : "";
                                                            echo isset($row['permanent_thana']) ? $row['permanent_thana'] . ',&nbsp;&nbsp;' : "";
                                                            echo isset($row['permanent_district']) ? $row['permanent_district'] : ""; ?></div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-sm-3 font-bold">Nominee Name</div>
                                    <div class="col-sm-9"><?php echo isset($row['nominee_name']) ? $row['nominee_name'] : ""; ?></div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-sm-3 font-bold">Nominee NID</div>
                                    <div class="col-sm-9"><?php echo isset($row['nominee_nid_no']) ? $row['nominee_nid_no'] : ""; ?></div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-sm-3 font-bold">Relation With Member</div>
                                    <div class="col-sm-9"><?php echo isset($row['nominee_relation']) ? $row['nominee_relation'] : ""; ?></div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-sm-3 font-bold">Nominee Mobile</div>
                                    <div class="col-sm-9"><?php echo isset($row['nominee_mobile']) ? $row['nominee_mobile'] : ""; ?></div>
                                </div>
                                <hr>
                            </form>
                        </div>
                        <div class="tab-pane fade" id="member_files">
                            <div class="row">
                                <div class="col-sm-3 font-bold">Member NID</div>
                                <div class="col-sm-9">
                                    <img height="100px" class="mb-2" src="<?php echo isset($row['member_nid_front']) && file_exists($row['member_nid_front']) ? $row['member_nid_front'] : "assets/img/nid_front.jpg"; ?>" alt="Member NID Front" srcset="">&nbsp;&nbsp;<img height="100px" class="mb-2" src="<?php echo isset($row['member_nid_back']) && file_exists($row['member_nid_back']) ? $row['member_nid_back'] : "assets/img/nid_back.jpg"; ?>" alt="Member NID Back" srcset="">
                            </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-sm-3 font-bold">Member Signature</div>
                                <div class="col-sm-9">
                                    <img height="100px" class="mb-2" src="<?php echo isset($row['member_signature']) && file_exists($row['member_signature']) ? $row['member_signature'] : "assets/images/img/sign.png"; ?>" alt="Member NID Back" srcset="">
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-sm-3 font-bold">Nominee Image</div>
                                <div class="col-sm-9"><img height="100px" class="mb-2" src="<?php echo isset($row['m_nominee_img']) && file_exists($row['m_nominee_img']) ? $row['m_nominee_img'] : "assets/images/img/demo.jpg"; ?>" alt="Member NID Back" srcset=""></div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-sm-3 font-bold">Nominee NID Front, Back</div>
                                <div class="col-sm-9"><img height="100px" class="mb-2" src="<?php echo isset($row['m_nominee_nid_front']) && file_exists($row['m_nominee_nid_front']) ? $row['m_nominee_nid_front'] : "assets/img/nid_front.jpg"; ?>" alt="Nominee NID Front" srcset="">&nbsp;&nbsp;<img height="100px" class="mb-2" src="<?php echo isset($row['m_nominee_nid_back']) && file_exists($row['m_nominee_nid_back']) ? $row['m_nominee_nid_back'] : "assets/img/nid_back.jpg"; ?>" alt="Member NID Front" srcset=""></div>
                            </div>
                            <hr>
                        </div>
                        <div class="tab-pane fade" id="tab-1">
                            <div class="row">
                                <div class="col-md-6" style="border-right: 1px solid #eee;">
                                    <h5 class="text-info m-b-20 m-t-10"><i class="fa fa-bar-chart"></i> Month Statistics</h5>
                                    <div class="h2 m-0">$12,400<sup>.60</sup></div>
                                    <div><small>Month income</small></div>
                                    <div class="m-t-20 m-b-20">
                                        <div class="h4 m-0">120</div>
                                        <div class="d-flex justify-content-between"><small>Month income</small>
                                            <span class="text-success font-12"><i class="fa fa-level-up"></i> +24%</span>
                                        </div>
                                        <div class="progress m-t-5">
                                            <div class="progress-bar progress-bar-success" role="progressbar" style="width:50%; height:5px;" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </div>
                                    <div class="m-b-20">
                                        <div class="h4 m-0">86</div>
                                        <div class="d-flex justify-content-between"><small>Month income</small>
                                            <span class="text-warning font-12"><i class="fa fa-level-down"></i> -12%</span>
                                        </div>
                                        <div class="progress m-t-5">
                                            <div class="progress-bar progress-bar-warning" role="progressbar" style="width:50%; height:5px;" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </div>
                                    <ul class="list-group list-group-full list-group-divider">
                                        <li class="list-group-item">Projects
                                            <span class="pull-right color-orange">15</span>
                                        </li>
                                        <li class="list-group-item">Tasks
                                            <span class="pull-right color-orange">148</span>
                                        </li>
                                        <li class="list-group-item">Articles
                                            <span class="pull-right color-orange">72</span>
                                        </li>
                                        <li class="list-group-item">Friends
                                            <span class="pull-right color-orange">44</span>
                                        </li>
                                    </ul>
                                </div>
                                <div class="col-md-6">
                                    <h5 class="text-info m-b-20 m-t-10"><i class="fa fa-user-plus"></i> Latest Followers</h5>
                                    <ul class="media-list media-list-divider m-0">
                                        <li class="media">
                                            <a class="media-img" href="javascript:;">
                                                <img class="img-circle" src="./assets/img/users/u1.jpg" width="40" />
                                            </a>
                                            <div class="media-body">
                                                <div class="media-heading">Jeanne Gonzalez <small class="float-right text-muted">12:05</small></div>
                                                <div class="font-13">Lorem Ipsum is simply dummy text of the printing and typesetting.</div>
                                            </div>
                                        </li>
                                        <li class="media">
                                            <a class="media-img" href="javascript:;">
                                                <img class="img-circle" src="./assets/img/users/u2.jpg" width="40" />
                                            </a>
                                            <div class="media-body">
                                                <div class="media-heading">Becky Brooks <small class="float-right text-muted">1 hrs ago</small></div>
                                                <div class="font-13">Lorem Ipsum is simply dummy text of the printing and typesetting.</div>
                                            </div>
                                        </li>
                                        <li class="media">
                                            <a class="media-img" href="javascript:;">
                                                <img class="img-circle" src="./assets/img/users/u3.jpg" width="40" />
                                            </a>
                                            <div class="media-body">
                                                <div class="media-heading">Frank Cruz <small class="float-right text-muted">3 hrs ago</small></div>
                                                <div class="font-13">Lorem Ipsum is simply dummy.</div>
                                            </div>
                                        </li>
                                        <li class="media">
                                            <a class="media-img" href="javascript:;">
                                                <img class="img-circle" src="./assets/img/users/u6.jpg" width="40" />
                                            </a>
                                            <div class="media-body">
                                                <div class="media-heading">Connor Perez <small class="float-right text-muted">Today</small></div>
                                                <div class="font-13">Lorem Ipsum is simply dummy text of the printing and typesetting.</div>
                                            </div>
                                        </li>
                                        <li class="media">
                                            <a class="media-img" href="javascript:;">
                                                <img class="img-circle" src="./assets/img/users/u5.jpg" width="40" />
                                            </a>
                                            <div class="media-body">
                                                <div class="media-heading">Bob Gonzalez <small class="float-right text-muted">Today</small></div>
                                                <div class="font-13">Lorem Ipsum is simply dummy.</div>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="tab-3">
                            <h5 class="text-info m-b-20 m-t-20"><i class="fa fa-bullhorn"></i> Latest Feeds</h5>
                            <ul class="media-list media-list-divider m-0">
                                <li class="media">
                                    <div class="media-img"><i class="ti-user font-18 text-muted"></i></div>
                                    <div class="media-body">
                                        <div class="media-heading">New customer <small class="float-right text-muted">12:05</small></div>
                                        <div class="font-13">Lorem Ipsum is simply dummy text.</div>
                                    </div>
                                </li>
                                <li class="media">
                                    <div class="media-img"><i class="ti-info-alt font-18 text-muted"></i></div>
                                    <div class="media-body">
                                        <div class="media-heading text-warning">Server Warning <small class="float-right text-muted">12:05</small></div>
                                        <div class="font-13">Lorem Ipsum is simply dummy text.</div>
                                    </div>
                                </li>
                                <li class="media">
                                    <div class="media-img"><i class="ti-announcement font-18 text-muted"></i></div>
                                    <div class="media-body">
                                        <div class="media-heading">7 new feedback <small class="float-right text-muted">Today</small></div>
                                        <div class="font-13">Lorem Ipsum is simply dummy text.</div>
                                    </div>
                                </li>
                                <li class="media">
                                    <div class="media-img"><i class="ti-check font-18 text-muted"></i></div>
                                    <div class="media-body">
                                        <div class="media-heading text-success">Issue fixed <small class="float-right text-muted">12:05</small></div>
                                        <div class="font-13">Lorem Ipsum is simply dummy text.</div>
                                    </div>
                                </li>
                                <li class="media">
                                    <div class="media-img"><i class="ti-shopping-cart font-18 text-muted"></i></div>
                                    <div class="media-body">
                                        <div class="media-heading">7 New orders <small class="float-right text-muted">12:05</small></div>
                                        <div class="font-13">Lorem Ipsum is simply dummy text.</div>
                                    </div>
                                </li>
                                <li class="media">
                                    <div class="media-img"><i class="ti-reload font-18 text-muted"></i></div>
                                    <div class="media-body">
                                        <div class="media-heading text-danger">Server warning <small class="float-right text-muted">12:05</small></div>
                                        <div class="font-13">Lorem Ipsum is simply dummy text.</div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>