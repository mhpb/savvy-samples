<?php
define('SAVVY_SECRET', 'secAPIKEY'); //get your key at www.savvy.io

function getAddress($orderId, $token = 'ETH') {
	$callbackUrl = 'http://CHANGEME.com/callback.php?id='.$orderId;
    $lockAddressTimeout = 3600;

	$token = strtolower($token);
	$url = sprintf('https://api.savvy.io/v3/%s/payment/%s?token=%s&lock_address=%s', $token, urlencode($callbackUrl), SAVVY_SECRET, $lockAddressTimeout);
	if ($response = file_get_contents($url)) {
		$response = json_decode($response);
		if (isset($response->data->address)) {
			return $response->data->address;
		}
	}

	return null;
}

function getCurrencies()
{
	static $currencies = false;

	if (!$currencies) {
		$url = sprintf( "https://api.savvy.io/v3/currencies?token=%s", SAVVY_SECRET );
		if ( $response = @file_get_contents( $url ) ) {
			$response = json_decode( $response, true );
			if ( isset( $response ) && $response['success'] ) {
				$currencies = $response['data'];
			}
		}
	}

	return $currencies;
}

function getCurrency($token, $orderId, $getAddress = false) {
	$rate = getRate($token);

	if ($rate) {
		$fiatValue = 19.99; //get from $orderId
		$coinsValue = round($fiatValue / $rate, 8);

		$token = strtolower($token);

		$currencies = getCurrencies();
		if (isset($currencies[$token])) {
			$currency               = $currencies[ $token ];
			$currency['coinsValue'] = $coinsValue;
			$currency['rate'] = $rate;

			if ( $getAddress ) {
				$currency['address'] = getAddress( $orderId, $token );
			} else {
				$currency['currencyUrl'] = sprintf( 'currencies.php?order=%s&token=%s', $orderId, $token );
			}

			return $currency;
		}

	}

	echo 'can\'t get rate for '.$token;
	return null;
}


function getRate($curCode) {
	$rates = getRates();
	$curCode = strtolower($curCode);

	return isset($rates->$curCode) ? $rates->$curCode->mid : false;
}

function getRates() {
	static $rates = null;

	if (empty($rates)) {
		$url = "https://api.savvy.io/v3/exchange/usd/rate";

		if ( $response = file_get_contents( $url ) ) {
			$response = json_decode( $response );
			if ( $response->success ) {
				$rates = $response->data;
			}
		}
	}
	return $rates;
}