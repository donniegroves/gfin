<?php
use TomorrowIdeas\Plaid\Plaid;
ini_set('display_errors', 1);

require __DIR__.'/../vendor/autoload.php';

const PLAID_CLIENT_ID = 'xxx';
const PLAID_SECRET_KEY = 'xxx'; // sandbox
const SUPPORTED_BANKS = [
    // 'ins_127991', // Wells Fargo
    // 'ins_56',     // Chase Bank
    'ins_130347', // Chiphone Credit Union
    'ins_130358'  // Equitable Bank
];

$plaid = new Plaid(PLAID_CLIENT_ID, PLAID_SECRET_KEY, "sandbox");

$all_institutions = $plaid->institutions->list(10,0,['US','CA']);
$all_institutions_arr = $all_institutions->institutions;
$institution = $all_institutions_arr[array_rand($all_institutions_arr)];
$inst_id = $institution->institution_id;

foreach (SUPPORTED_BANKS as $inst_id){
    $response = $plaid->sandbox->createPublicToken($inst_id,['transactions']);
    $public_token = $response->public_token;
    $request_id = $response->request_id;
}



$response = $plaid->items->exchangeToken($public_token);
$access_token = $response->access_token;
$item_id = $response->item_id;
$request_id = $response->request_id;

$start_date = new \DateTime('1 month ago');
$end_date = new \DateTime('yesterday');
$response = $plaid->transactions->list($access_token,$start_date,$end_date);
$transactions = $response->transactions;

phpinfo();


