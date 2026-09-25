<footer class="de-footer">
	<div class="de-foot-grid">
		<div>
			<img class="de-foot-logo" src="assets/images/logo.png" alt="Delta Engineering Services">
			<p style="max-width:280px">Structural &amp; civil engineering across the Greater Toronto Area since 1985.</p>
		</div>
		<div>
			<h5>Canada</h5>
			<a class="de-foot-addr" href="https://www.google.com/maps/search/?api=1&amp;query=<?= urlencode('204-4211 Sheppard Ave. E., Scarborough, ON M1S 5H5') ?>" target="_blank" rel="noopener">
				<svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M12 3s6 6.8 6 11a6 6 0 1 1-12 0c0-4.2 6-11 6-11Z" stroke="currentColor" stroke-width="1.5"/></svg>
				<span>204-4211 Sheppard Ave. E.<br>Scarborough, ON M1S 5H5</span>
			</a>
			<a class="de-foot-addr" href="https://www.google.com/maps/search/?api=1&amp;query=<?= urlencode('2482 Kentucky Derby Way, Oshawa, ON L1L 0R7') ?>" target="_blank" rel="noopener">
				<svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M12 3s6 6.8 6 11a6 6 0 1 1-12 0c0-4.2 6-11 6-11Z" stroke="currentColor" stroke-width="1.5"/></svg>
				<span>2482 Kentucky Derby Way<br>Oshawa, ON L1L 0R7</span>
			</a>
		</div>
		<div>
			<h5><a href="india_engineering_services.php" style="color:inherit">India</a></h5>
			<a class="de-foot-addr" href="https://www.google.com/maps/search/?api=1&amp;query=<?= urlencode('Dev Arced, New Shahibaug, Nana Chiloda, Ahmedabad, Gujarat') ?>" target="_blank" rel="noopener">
				<svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M12 3s6 6.8 6 11a6 6 0 1 1-12 0c0-4.2 6-11 6-11Z" stroke="currentColor" stroke-width="1.5"/></svg>
				<span>Dev Arced, New Shahibaug<br>Nana Chiloda, Ahmedabad<br>Gujarat</span>
			</a>
			<a class="de-foot-addr" href="https://www.google.com/maps/search/?api=1&amp;query=<?= urlencode('JP 1 C/31, 2nd Floor, 6th Cross, LIC Colony, 3rd Block East, Jayanagar, Bengaluru 560011') ?>" target="_blank" rel="noopener">
				<svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M12 3s6 6.8 6 11a6 6 0 1 1-12 0c0-4.2 6-11 6-11Z" stroke="currentColor" stroke-width="1.5"/></svg>
				<span>JP 1 C/31, 2nd Floor, 6th Cross<br>LIC Colony, 3rd Block East, Jayanagar<br>Bengaluru 560011</span>
			</a>
		</div>
		<div>
			<h5>Contact<?= $deLoc === 'india' ? ' &mdash; India' : ' &mdash; Canada' ?></h5>
			<?php if ($deLoc === 'india'): ?>
			<p><a href="tel:+917259405511">+91 72594 05511</a><br><a href="mailto:info@delta-engineering.ca">info@delta-engineering.ca</a></p>
			<?php else: ?>
			<p><a href="tel:+14165731573">(416) 573-1573</a><br><a href="tel:+14379863858">(437) 986-3858</a><br><a href="mailto:info@delta-engineering.ca">info@delta-engineering.ca</a></p>
			<?php endif; ?>
		</div>
	</div>
	<div class="de-foot-bottom">
		<span>&copy; 2026 Delta Engineering Services. All rights reserved.</span>
		<span><a href="privacy_policy.php">Privacy Policy</a><a href="terms_and_conditions.php">Terms &amp; Conditions</a></span>
	</div>
</footer>
</div><!-- /.de-root -->

	<!-- JQuery v1.12.4 -->
	<script src="assets/js/jquery.min.js"></script>
	
	<!-- Library - Js -->
	<script src="assets/js/lib.js"></script>
	
	<!-- RS5.0 Core JS Files -->
	<script type="text/javascript" src="assets/revolution/js/jquery.themepunch.tools.min.js?rev=5.0"></script>
	<script type="text/javascript" src="assets/revolution/js/jquery.themepunch.revolution.min.js?rev=5.0"></script>
	<script type="text/javascript" src="assets/revolution/js/extensions/revolution.extension.video.min.js"></script>
	<script type="text/javascript" src="assets/revolution/js/extensions/revolution.extension.slideanims.min.js"></script>
	<script type="text/javascript" src="assets/revolution/js/extensions/revolution.extension.layeranimation.min.js"></script>
	<script type="text/javascript" src="assets/revolution/js/extensions/revolution.extension.navigation.min.js"></script>
	
	<script src="assets/js/slick/slick.min.js"></script>
	
	<!-- Library - Theme JS -->
	<script src="assets/js/functions.js"></script>
	
	
	
</body>
</html>