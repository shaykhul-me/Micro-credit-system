<?php
include "seassion.php";

$id = mysqli_real_escape_string($conn, $_GET['id']);
// Get the logical transaction ID from the requested detail row
$initial_sql = manual_query("SELECT transaction_id FROM transections_details WHERE id = '$id'");
$initial_row = mysqli_fetch_assoc($initial_sql['query']);

if (!$initial_row) {
    die("Transaction not found");
}

$transaction_id = $initial_row['transaction_id'];

// If transaction_id is present, fetch all related details. Otherwise just fetch the one.
if (!empty($transaction_id) && $transaction_id > 0) {
    $sql = manual_query("SELECT * FROM transections_details WHERE transaction_id = '$transaction_id'");
} else {
    $sql = manual_query("SELECT * FROM transections_details WHERE id = '$id'");
}

$rows = [];
$member_name = "";
$date = "";
$t_id = $transaction_id;
if(empty($t_id)) $t_id = $id;

$total_in = 0;
$total_out = 0;

while($r = mysqli_fetch_assoc($sql['query'])){
    $rows[] = $r;
    if(!empty($r['account_title']) && empty($member_name)) $member_name = $r['account_title'];
    if(empty($date)) $date = $r['created_at'];
    $total_in += $r['cash_in'];
    $total_out += $r['cash_out'];
}

// Fallback to fetch member name from parent transaction if not found in details (e.g. only Fine/Others)
if(empty($member_name) && !empty($transaction_id)) {
    $parent_query = manual_query("SELECT member_id FROM transections WHERE id='$transaction_id'");
    $parent_res = mysqli_fetch_assoc($parent_query['query']);
    if($parent_res && !empty($parent_res['member_id'])) {
         $member_id = $parent_res['member_id'];
         $mem_q = manual_query("SELECT name FROM members WHERE id='$member_id'");
         $mem_r = mysqli_fetch_assoc($mem_q['query']);
         if($mem_r) $member_name = $mem_r['name'];
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transaction Memo</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f4f4;
            padding: 20px;
        }
        .memo-container {
            background: #fff;
            max-width: 800px;
            margin: 0 auto;
            padding: 40px;
            border: 1px solid #ddd;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #eee;
            padding-bottom: 20px;
        }
        .header h1 {
            margin: 0;
            color: #333;
            font-size: 24px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .header p {
            margin: 5px 0 0;
            color: #666;
        }
        .meta-info {
            margin-bottom: 20px;
        }
        .meta-info table {
            width: 100%;
        }
        .meta-info td {
            padding: 5px 0;
            vertical-align: top;
        }
        .transaction-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        .transaction-table th, .transaction-table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }
        .transaction-table th {
            background-color: #f8f9fa;
            font-weight: 600;
        }
        .text-right {
            text-align: right;
        }
        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 12px;
            color: #888;
        }
        .signature-section {
            margin-top: 60px;
            display: flex;
            justify-content: space-between;
        }
        .signature-box {
            text-align: center;
            width: 200px;
            border-top: 1px solid #333;
            padding-top: 5px;
        }
        .no-print {
            text-align: center;
            margin-top: 20px;
        }
        .btn {
            background: #007bff;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            margin: 0 5px;
        }
        .btn-close {
            background: #6c757d;
        }
        @media print {
            body {
                background: none;
                padding: 0;
            }
            .memo-container {
                box-shadow: none;
                border: none;
                width: 100%;
                max-width: none;
                padding: 0;
            }
            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body>

<div class="memo-container">
    <div class="header">
        <h1>Transaction Memo</h1>
        <p>Micro Credit Organization</p>
    </div>

    <div class="meta-info">
        <table>
            <tr>
                <td width="60%">
                    <!-- <strong>Name:</strong> <?php echo $member_name; ?> -->
                    <!-- In case we want to show Account Titles if different per row, we can skip here, but usually Member Name is Global -->
                    <strong>Member Name:</strong> <?php echo !empty($member_name) ? $member_name : "N/A"; ?>
                </td>
                <td width="40%" class="text-right">
                    <strong>Date:</strong> <?php echo date("d-M-Y h:i A", strtotime($date)); ?><br>
                    <strong>Transaction ID:</strong> <?php echo $t_id; ?>
                </td>
            </tr>
        </table>
    </div>

    <table class="transaction-table">
        <thead>
            <tr>
                <th>Type</th>
                <th>Account No/Details</th>
                <th class="text-right">Credit (In)</th>
                <th class="text-right">Debit (Out)</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($rows as $row): ?>
            <tr>
                <td><?php echo $row['category']; ?></td>
                <td>
                    <?php 
                        echo ($row['account_id'] > 0 ? "AC: " . $row['account_id'] . "<br>" : ""); 
                        echo $row['details']; 
                    ?>
                </td>
                <td class="text-right"><?php echo $row['cash_in'] > 0 ? number_format($row['cash_in'], 2) : "-"; ?></td>
                <td class="text-right"><?php echo $row['cash_out'] > 0 ? number_format($row['cash_out'], 2) : "-"; ?></td>
            </tr>
            <?php endforeach; ?>
            <tr style="background-color: #f9f9f9; font-weight: bold;">
                <td colspan="2" class="text-right">Total</td>
                <td class="text-right"><?php echo number_format($total_in, 2); ?></td>
                <td class="text-right"><?php echo number_format($total_out, 2); ?></td>
            </tr>
        </tbody>
    </table>

    <div class="signature-section">
        <div class="signature-box">
            Customer Signature
        </div>
        <div class="signature-box">
            Officer Signature
        </div>
    </div>

    <div class="footer">
        <p>Thank you for banking with us.</p>
        <p>Printed on: <?php echo date("d-M-Y h:i A"); ?></p>
    </div>
</div>

<div class="no-print">
    <button onclick="window.print()" class="btn">Print Memo</button>
    <button onclick="window.close()" class="btn btn-close">Close</button>
</div>

</body>
</html>
