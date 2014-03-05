<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Bazo\Trustpay\Api\Client;
use Tester\Assert;



$aid = '9876543210';
$ref = '1234567890';
$key = 'abcd1234';
$amount = 123.45;
$currency = Bazo\Trustpay\Currency::EUR;

$client = new Client($aid, $key, $mode = Client::MODE_TEST);

$url = $client->generatePaymentURI($amount, $currency, Bazo\Trustpay\Language::SLOVAK, $ref);

$expected = 'https://test.trustpay.eu/mapi/pay.aspx?AID=9876543210&AMT=123.45&CUR=EUR&REF=1234567890&SIG=DF174E635DABBFF7897A82822521DD739AE8CC2F83D65F6448DD2FF991481EA3&LNG=sk&DSC=';

Assert::same($expected, $url);
