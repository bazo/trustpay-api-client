<?php

namespace Bazo\Trustpay\Api;

use Bazo\Trustpay\Currency;
use Bazo\Trustpay\Language;



/**
 * @author Martin Bažík <martin@bazik.sk>
 */
class Client
{

	const PRODUCTION_URI = 'https://ib.trustpay.eu/mapi/pay.aspx';
	const TEST_URI = 'https://test.trustpay.eu/mapi/pay.aspx';
	const MODE_TEST = 'mode.test';
	const MODE_PRODUCTION = 'mode.production';



	/**
	 * aid  Merchant account ID
	 * ID of account assigned by TrustPay
	 * @var string
	 */
	private $aid;

	/**
	 * Reference
	 * Merchant’s payment identification
	 * @var string
	 */
	private $ref;

	/**
	 * Signing key
	 * @var type @var string
	 */
	private $key;

	/** @var string */
	private $baseUri;



	public function __construct($aid, $key, $mode = self::MODE_PRODUCTION)
	{
		$this->aid = $aid;
		$this->key = $key;
		if ($mode === self::MODE_PRODUCTION) {
			$this->baseUri = self::PRODUCTION_URI;
		} else {
			$this->baseUri = self::TEST_URI;
		}
	}


	public function generatePaymentURI($amount, $currency = Currency::EUR, $lang = Language::SLOVAK, $ref = '', $description = '', $successUrl = NULL, $cancelUrl = NULL, $errorUrl = NULL)
	{
		$message = $this->aid . $amount . $currency . $ref;

		$query = array(
			'AID' => $this->aid,
			'AMT' => round($amount, 2),
			'CUR' => $currency,
			'REF' => $ref,
			'SIG' => $this->signRequest($message),
			'LNG' => $lang,
			'DSC' => $description
		);

		return $this->baseUri . '?' . http_build_query($query);
	}


	private function signRequest($message)
	{
		return strtoupper(hash_hmac('sha256', pack('A*', $message), pack('A*', $this->key)));
	}


}
