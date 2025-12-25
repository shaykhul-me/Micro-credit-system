<?php
// Include database connection and session
include('seassion.php');

// Set header for JSON response
header('Content-Type: application/json');

// Initialize response array
$response = array('success' => false, 'message' => '');

try {
    // Check if request is POST
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        
        // Get and sanitize input
        $account_id = isset($_POST['account_id']) ? intval($_POST['account_id']) : 0;
        $installment = isset($_POST['installment']) ? floatval($_POST['installment']) : 0;
        
        // Validate inputs
        if ($account_id <= 0) {
            $response['message'] = 'Invalid account ID';
            echo json_encode($response);
            exit;
        }
        
        if ($installment <= 0) {
            $response['message'] = 'Invalid installment amount';
            echo json_encode($response);
            exit;
        }
        
        // Check if account exists
        $check_account = single_condition_select("accounts", "id", $account_id);
        
        if ($check_account['count'] > 0) {
            // Get current account data before update
            $current_account = mysqli_fetch_assoc($check_account['query']);
            $old_installment = $current_account['installment'];
            
            // Get current user info for logging
            $updated_by = isset($uid) ? $uid : '';
            $updated_ip = $ip;
            $updated_at = date('Y-m-d');
            
            // Update the installment amount
            $update_data = array(
                'installment' => $installment,
                'updated_at' => $updated_at,
                'updated_by' => $updated_by,
                'updated_ip' => $updated_ip
            );
            
            // Call updatethis function
            $update_result = updatethis(
                array('id' => $account_id),
                $update_data,
                'accounts'
            );
            
            // Check if update was successful
            // updatethis returns an array with 'edited_table', 'edited_id', 'successmsg' on success
            if (isset($update_result['edited_id']) && $update_result['edited_id'] == $account_id) {
                $response['success'] = true;
                $response['message'] = 'Installment updated successfully';
                
                // Log the update
                $log_file = '../update_log.txt';
                $log_message = date('Y-m-d H:i:s') . " - User: $updated_by - Account ID: $account_id - Old Installment: $old_installment - New Installment: $installment - IP: $updated_ip\n";
                @file_put_contents($log_file, $log_message, FILE_APPEND);
            } else {
                $response['message'] = 'Failed to update installment in database';
            }
        } else {
            $response['message'] = 'Account not found';
        }
        
    } else {
        $response['message'] = 'Invalid request method';
    }
} catch (Exception $e) {
    $response['message'] = 'Error: ' . $e->getMessage();
}

// Return JSON response
echo json_encode($response);
?>
