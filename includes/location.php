<?php
// Site-wide Canada/India context, available to every page via header.php.
//
// Resolution order, most to least authoritative:
//   1. ?loc=canada|india on the current URL -- so a single link can be
//      dropped into WhatsApp or a campaign and land a visitor on the right
//      office's content.
//   2. The de_loc cookie -- synced client-side on every page load (see the
//      inline script in header.php) to whatever this file resolves below,
//      so a visitor's location sticks across navigation without
//      re-running IP detection on every request.
//   3. IP-based geolocation, for a visitor's first page view when neither
//      of the above is set yet: a quick, short-timeout lookup against a
//      free geolocation API. Fails safe to Canada on any error, timeout,
//      unreachable host, or inconclusive result -- a slow or down third
//      party can delay this by at most ~2 seconds, never break the page.
//   4. Canada, if nothing else applies.
if (isset($_GET['loc'])) {
	$deLoc = $_GET['loc'];
} elseif (isset($_COOKIE['de_loc'])) {
	$deLoc = $_COOKIE['de_loc'];
} else {
	$deLoc = de_visitor_ip_is_india() ? 'india' : 'canada';
}
if ($deLoc !== 'india') {
	$deLoc = 'canada';
}

function de_visitor_ip_is_india(): bool {
	$ip = $_SERVER['REMOTE_ADDR'] ?? '';
	// FILTER_FLAG_NO_PRIV_RANGE/NO_RES_RANGE rejects local/private IPs
	// (localhost, LAN, dev environments) -- those can't be geolocated
	// meaningfully, so skip the lookup entirely rather than ask a public
	// API about a private address.
	if ($ip === '' || !filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
		return false;
	}

	$country = '';
	$url = 'https://ipapi.co/' . urlencode($ip) . '/country/';
	if (function_exists('curl_init')) {
		$ch = curl_init($url);
		curl_setopt_array($ch, [
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_CONNECTTIMEOUT => 1,
			CURLOPT_TIMEOUT => 2,
			CURLOPT_FAILONERROR => true,
		]);
		$result = curl_exec($ch);
		curl_close($ch);
		$country = is_string($result) ? trim($result) : '';
	} elseif (filter_var(ini_get('allow_url_fopen'), FILTER_VALIDATE_BOOLEAN)) {
		$ctx = stream_context_create(['http' => ['timeout' => 2]]);
		$result = @file_get_contents($url, false, $ctx);
		$country = is_string($result) ? trim($result) : '';
	}
	// else: neither curl nor allow_url_fopen available on this host --
	// no way to look it up, falls back to Canada below.

	return $country === 'IN';
}
