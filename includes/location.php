<?php
// Site-wide Canada/India context, available to every page via header.php.
//
// ?loc=canada|india on the current URL is authoritative for this render
// (so a single link can be dropped into WhatsApp or a campaign and land a
// visitor on the right office's content). When the URL carries no ?loc=,
// we fall back to the de_loc cookie -- written client-side in header.php
// whenever a page *does* load with ?loc= present -- so the choice a
// visitor makes once (via the header switcher, or by following a location
// link) sticks across ordinary navigation. Canada is the default when
// neither is set.
$deLoc = $_GET['loc'] ?? ($_COOKIE['de_loc'] ?? 'canada');
if ($deLoc !== 'india') {
	$deLoc = 'canada';
}
