<?php

namespace Bazo\Trustpay\Api;

use Bazo\Trustpay\Currency;
use Bazo\Trustpay\Language;
use Bazo\Trustpay\RuntimeException;



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
	 * Signing key
	 * @var type @var string
	 */
	private $key;

	/**
	 * Currency of the payment same as currency of merchant account
	 * @var string
	 */
	private $currency;

	/** @var string */
	private $baseUri;



	public function __construct($aid, $key, $currency = Currency::EUR, $mode = self::MODE_PRODUCTION)
	{
		$this->aid = $aid;
		$this->key = $key;
		$this->currency = $currency;

		if ($mode === self::MODE_PRODUCTION) {
			$this->baseUri = self::PRODUCTION_URI;
		} else {
			$this->baseUri = self::TEST_URI;
		}
	}


	public function generatePaymentURI($amount, $lang = Language::SLOVAK, $ref = '', $description = '', $successUrl = NULL, $cancelUrl = NULL, $errorUrl = NULL)
	{
		$amount = number_format($amount, 2, '.', '');

		$message = $this->aid . (string) $amount . $this->currency . $ref;

		$query = array(
			'AID' => $this->aid,
			'AMT' => $amount,
			'CUR' => $this->currency,
			'REF' => $ref,
			'SIG' => $this->signRequest($message),
			'LNG' => $lang,
			'DSC' => $description
		);

		return $this->baseUri . '?' . http_build_query($query);
	}


	public function verifyNotificationParameters($aid, $typ, $amt, $cur, $ref, $res, $tid, $oid, $tss, $sig)
	{
		if ($aid !== $this->aid) {
			throw new RuntimeException(sprintf('Given AID not correct. %s !== %s', $this->aid, $aid));
		}

		if ($cur !== $this->currency) {
			throw new RuntimeException(sprintf('Given CUR not correct. %s !== %s', $this->currency, $cur));
		}

		$message = $aid . $typ . $amt . $cur . $ref . $res . $tid . $oid . $tss;

		$signature = $this->signRequest($message);

		if ($sig !== $signature) {
			throw new RuntimeException(sprintf('Signature mismatch. %s !== %s', $this->currency, $cur));
		}
	}


	private function signRequest($message)
	{
		return strtoupper(hash_hmac('sha256', pack('A*', $message), pack('A*', $this->key)));
	}


}
