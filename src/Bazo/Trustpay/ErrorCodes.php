<?php

namespace Bazo\Trustpay;

/**
 * @author Martin Bažík <martin@bazik.sk>
 */
abstract class ErrorCodes
{

	const SUCCESS = '0';
	const PENDING = '1';
	const AUTHORIZED = '3';
	const PROCESSING = '4';
	const INVALID_REQUEST = '1001';
	const UNKNOWN_ACCONNT = '1002';
	const MERCHANT_DISABLED = '1003';
	const INVALID_SIGN = '1004';
	const CANCEL = '1005';
	const INVALID_AUTHENTICATION = '1006';
	const DISPOSABLE_BALANCE = '1007';
	const SERVICE_NOT_ALLOWED = '1008';
	const PAYSAFECARD_TIMEOUT = '1009';
	const GENERAL_ERROR = '1100';
	const UNSUPPORTED_CURRENCY_CONVERSION = '1101';
	const UNKOWN_ERROR = -1;
	const NOTIFICATION_ERROR = -2;



}
