<?php
if (!isset($uid)) {
    header("Location: error_500.php");
}
if (isset($_POST['sent_msg'])) {
    $message = $_POST['message'];
    $numbers = $_POST['numbers'];
    $number_array = explode(",", $numbers);
    if (count($number_array) > 0 && $message != "") {
        $sent_msg = sms_to_member($number_array, $message);
        $actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

        if (isset($sent_msg['success'])) {
            $_SESSION['sms'] = "<div class='alert alert-success'>SMS Sent Successfully</div>";
        } else {
            $_SESSION['sms'] = "<div class='alert alert-danger'>{$sent_msg['error']}</div>";
        }


        echo "<script type='text/javascript'>location.href = '$actual_link';</script>";
        exit;
    }else{
        echo "<div class='alert alert-danger'>Receipnt Or MSG can't be empty</div>";
    }
}

if (isset($_SESSION['sms'])) {
    echo $_SESSION['sms'];
    unset($_SESSION['sms']);
}
$members_mobile_sql = manual_query("SELECT GROUP_CONCAT(mobile SEPARATOR ', ') AS mobiles FROM `members` WHERE mobile !='' GROUP BY NULL");
$members_mobile_res = mysqli_fetch_assoc($members_mobile_sql['query']);
// $mohari_mobile_sql = manual_query("SELECT GROUP_CONCAT(mobile SEPARATOR ', ') AS mobiles FROM `mohari` WHERE mobile !='' GROUP BY NULL");
// $mohari_mobile_res = mysqli_fetch_assoc($mohari_mobile_sql['query']);
$uname = "jhalakatibar";
$pass = "HJY3FP4G";
$sender = "NB";
//sms count
// $available_sms = file_get_contents("http://66.45.237.70/balancechk.php?username=$uname&password=$pass&type=sms");
$available_sms = file_get_contents("https://bulksmsbd.net/api/getBalanceApi?api_key=7v8QUuAmaySUFSykXngP");
$available_sms = json_decode($available_sms);
// print_r($available_sms);
// exit;
$available_sms = $available_sms->balance;
// $available_sms = 103;
?>
<div class="mt-5">

    <form action="" method="post">
        <div class="d-flex justify-content-center flex-row mt-4">
            <div class="col-md-5">
                <div class="form-group">
                    <label for="message ">Type Your Message</label>
                    <textarea id="message" class="form-control rounded" name="message" rows="3" placeholder="Type Your Message Here"></textarea>
                    <div id="smscount" class="text-right"></div>
                </div>
                <div id="sms-counter"></div>
                <div class="form-group">
                    <label for="gorup_number">Select Groups</label>
                    <select id="gorup_number" class="form-control" name="gorup_number">
                        <option value="">Select Numbers</option>
                        <option value="members">Members</option>
                    </select>
                </div>
                <div class="text-center">
                    <button type="submit" class="btn btn-adn rounded" name="sent_msg">Send</button>
                </div>
                <div class="form-group">
                    <label for="numbers">Input Number</label>
                    <textarea id="numbers" class="form-control" name="numbers" rows="1" placeholder="01911111111,01711111111"></textarea>
                    <!-- <input class="form-control" type="text" name="numbers"> -->
                    <div id="total_numbers"></div>
                </div>
                <div id="number_list" class='d-none'>
                </div>
            </div>
            <div class="col-md-3 mt-4">
                <table>
                    <tr>
                        <td width='150px'>Available SMS :</td>
                        <td><?php echo $available_sms; ?></td>
                    </tr>
                    <tr>
                        <td>Total Charecters :</td>
                        <td id="charecters"></td>
                    </tr>
                    <tr>
                        <td>Total Word :</td>
                        <td id="words"></td>
                    </tr>
                    <tr>
                        <td>Total Message :</td>
                        <td id="messeges"></td>
                    </tr>
                    <tr>
                        <td>Total Receipnt :</td>
                        <td id="show_receipnt"></td>
                    </tr>
                    <tr>
                        <td>Total SMS for sent :</td>
                        <td id="total_sms_4_sent"></td>
                    </tr>
                    <tr><td colspan="2" style="color:green;">If Bangla sms 70 char a Message</td></tr>
                </table>
            </div>
        </div>
    </form>
</div>
<script>
    $(document).ready(function() {
        var member_mobile = "<?php echo $members_mobile_res['mobiles']; ?>";
        // var mohari_mobile = "<?php //echo $mohari_mobile_res['mobiles']; ?>";
        $("#message").on("keyup change", function() {
            var text = $(this).val();
            $.ajax({
                type: "POST",
                url: "controllers/index.php",
                data: {
                    "message_count": text
                },
                success: function(response) {
                    response = JSON.parse(response)
                    $("#charecters").html(response.charecter)
                    $("#words").html(response.word)
                    $("#messeges").html(response.sms)
                    $("#smscount").html(response.char)
                    count_numbers()
                }
            });
        })
        $("#gorup_number").on("change", function() {
            if ($(this).val() == 'members') {
                $("#number_list").html(member_mobile);
                $("#numbers").val(member_mobile);
            } else {
                $("#number_list").html("");
                $("#numbers").val("");
            }
            count_numbers()
        })
        function count_numbers(){
            var all_numbers = $("#numbers").val();
            var count_num = all_numbers.split(", ");            
            var number_sms = $("#messeges").html() || 1;
            $("#show_receipnt").html(count_num.length);
            var receipnt = count_num.length || 0;
            $("#total_sms_4_sent").html(receipnt*number_sms);
        }
        $("#numbers").on("change keyup", function(){
            count_numbers()
        })
    });
</script>