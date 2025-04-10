<?php
// Configuration
$hmailserver_admin = 'Administrator';
$hmailserver_password = 'admin_password';
$domain_name = 'yourdomain.com';
$new_user_email = 'newuser@yourdomain.com';
$new_user_password = 'user_password';

// Create user function
function createHMailServerUser($admin, $password, $domain, $email, $user_password) {
    $hmailserver = new COM("hMailServer.Application");
    $hmailserver->Authenticate($admin, $password);

    $domain_obj = $hmailserver->Domains->ItemByName($domain);
    $accounts = $domain_obj->Accounts;
    $account = $accounts->Add();
    $account->Address = $email;
    $account->Password = $user_password;
    $account->Save();

    echo "User created successfully.";
}

// Create user
createHMailServerUser($hmailserver_admin, $hmailserver_password, $domain_name, $new_user_email, $new_user_password);
?>
