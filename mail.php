<?php
declare(strict_types=1);
session_start();

require "./bootstrap.php";



require_once 'vendor/autoload.php';

use Zimbra\Admin\AdminApi;
use Zimbra\Common\Enum\AccountBy;
use Zimbra\Common\Struct\AccountSelector;

$username = 'rtiphone';
$password = '69Y7t5ps';

$api = new AdminApi('http://zimbra.univ-poitiers.fr/service/soap');
$api->auth($username, $password);
$account = $api->getAccountInfo(new AccountSelector(AccountBy::NAME, $accountName));