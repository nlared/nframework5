<?php

class nextcloud{
// Configuration
	private $nextcloud_url = 'https://your-nextcloud-instance.com';
	private $admin_username = 'admin';
	private $admin_password = 'admin_password';
	
	// User details

	// Create user
	//createNextcloudUser($nextcloud_url, $admin_username, $admin_password, $new_username, $new_password);
	// Create user function
	function createNextcloudUser($url, $admin_user, $admin_pass, $user, $pass) {
	    $ch = curl_init();
	    curl_setopt($ch, CURLOPT_URL, $url . '/ocs/v1.php/cloud/users');
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	    curl_setopt($ch, CURLOPT_POST, 1);
	    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
	        'userid' => $user,
	        'password' => $pass,
	    ]));
	    curl_setopt($ch, CURLOPT_HTTPHEADER, [
	        'OCS-APIRequest: true',
	        'Content-Type: application/x-www-form-urlencoded',
	    ]);
	    curl_setopt($ch, CURLOPT_USERPWD, $admin_user . ':' . $admin_pass);
	
	    $response = curl_exec($ch);
	    if (curl_errno($ch)) {
	        echo 'Error: ' . curl_error($ch);
	    } else {
	        echo 'Response: ' . $response;
	    }
	    curl_close($ch);
	}

	//changeUserQuota($nextcloud_url, $admin_username, $admin_password, $user, $new_quota)
	function changeUserQuota($url, $admin_user, $admin_pass, $user, $quota) {
	    $ch = curl_init();
	    curl_setopt($ch, CURLOPT_URL, $url . '/ocs/v1.php/cloud/users/' . $user);
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
	    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
	        'key' => 'quota',
	        'value' => $quota,
	    ]));
	    curl_setopt($ch, CURLOPT_HTTPHEADER, [
	        'OCS-APIRequest: true',
	        'Content-Type: application/x-www-form-urlencoded',
	    ]);
	    curl_setopt($ch, CURLOPT_USERPWD, $admin_user . ':' . $admin_pass);
	
	    $response = curl_exec($ch);
	    if (curl_errno($ch)) {
	        echo 'Error: ' . curl_error($ch);
	    } else {
	        echo 'Response: ' . $response;
	    }
	    curl_close($ch);
	}
	function disableNextcloudUser($url, $admin_user, $admin_pass, $user) {
	    $ch = curl_init();
	    curl_setopt($ch, CURLOPT_URL, $url . '/ocs/v1.php/cloud/users/' . $user . '/disable');
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	    curl_setopt($ch, CURLOPT_POST, 1);
	    curl_setopt($ch, CURLOPT_HTTPHEADER, [
	        'OCS-APIRequest: true',
	        'Content-Type: application/x-www-form-urlencoded',
	    ]);
	    curl_setopt($ch, CURLOPT_USERPWD, $admin_user . ':' . $admin_pass);
	
	    $response = curl_exec($ch);
	    if (curl_errno($ch)) {
	        echo 'Error: ' . curl_error($ch);
	    } else {
	        echo 'Response: ' . $response;
	    }
	}
	function deleteNextcloudUser($url, $admin_user, $admin_pass, $user) {
	    $ch = curl_init();
	    curl_setopt($ch, CURLOPT_URL, $url . '/ocs/v1.php/cloud/users/' . $user);
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
	    curl_setopt($ch, CURLOPT_HTTPHEADER, [
	        'OCS-APIRequest: true',
	        'Content-Type: application/x-www-form-urlencoded',
	    ]);
	    curl_setopt($ch, CURLOPT_USERPWD, $admin_user . ':' . $admin_pass);
	
	    $response = curl_exec($ch);
	    if (curl_errno($ch)) {
	        echo 'Error: ' . curl_error($ch);
	    } else {
	        echo 'Response: ' . $response;
	    }
	}
	function backupUserData($url, $admin_user, $admin_pass, $user, $backup_location) {
    $user_data_url = $url . $user . '/';
    $backup_folder = $backup_location . $user . '_backup/';

    if (!is_dir($backup_folder)) {
        mkdir($backup_folder, 0777, true);
    }

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $user_data_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/x-www-form-urlencoded',
    ]);
    curl_setopt($ch, CURLOPT_USERPWD, $admin_user . ':' . $admin_pass);

    $response = curl_exec($ch);
    if (curl_errno($ch)) {
        echo 'Error: ' . curl_error($ch);
    } else {
        // Save user data to the backup location
        file_put_contents($backup_folder . 'user_data.zip', $response);
        echo 'User data backed up successfully.';
    }
    curl_close($ch);
}
}