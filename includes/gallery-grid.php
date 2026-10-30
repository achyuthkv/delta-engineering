<?php
// Shared by the three gallery pages (Canada / India / Oman). Photos with
// the same project_id (set when an admin adds a photo from the Projects
// screen rather than the standalone Gallery screen) are grouped into one
// card that cycles through them on hover, using the same .de-proj-photo-stack
// markup/CSS/JS as the multi-photo cards on projects.php. A photo with no
// project_id (most of the historic/bulk-imported ones) stays its own tile,
// unchanged from before.
function de_render_gallery_grid(array $photos): void {
	$groups = [];
	$order = [];
	foreach ($photos as $photo) {
		$key = $photo['project_id'] ?? ('_' . count($groups));
		if (!isset($groups[$key])) {
			$order[] = $key;
		}
		$groups[$key][] = $photo;
	}

	foreach ($order as $key) {
		$group = $groups[$key];
		$first = $group[0];
		$isMulti = count($group) > 1;
		$title = htmlspecialchars($first['title'], ENT_QUOTES, 'UTF-8');
		$location = htmlspecialchars($first['location'], ENT_QUOTES, 'UTF-8');

		if (!$isMulti) {
			$path = htmlspecialchars($first['image_path'], ENT_QUOTES, 'UTF-8');
			$alt = htmlspecialchars($first['alt_text'], ENT_QUOTES, 'UTF-8');
			echo <<<HTML
			<div class="de-photo-item">
				<div class="content-image-block">
					<img src="{$path}" alt="{$alt}">
					<div class="content-block-hover">
						<a class="zoom-in" href="{$path}"><i class="fa fa-search" style="font-size:30px;margin-left:25px"></i></a>
					</div>
				</div>
				<div class="de-photo-cap">{$title}<span>{$location}</span></div>
			</div>
			HTML;
			continue;
		}

		echo '<div class="de-photo-item de-photo-item-multi" data-multi-photo>';
		echo '<div class="de-proj-photo-stack content-image-block">';
		foreach ($group as $pi => $photo) {
			$path = htmlspecialchars($photo['image_path'], ENT_QUOTES, 'UTF-8');
			$alt = htmlspecialchars($photo['alt_text'], ENT_QUOTES, 'UTF-8');
			$activeClass = $pi === 0 ? ' is-active' : '';
			echo "<img src=\"{$path}\" alt=\"{$alt}\" loading=\"lazy\" class=\"{$activeClass}\">";
		}
		$firstPath = htmlspecialchars($group[0]['image_path'], ENT_QUOTES, 'UTF-8');
		echo "<div class=\"content-block-hover\"><a class=\"zoom-in\" href=\"{$firstPath}\"><i class=\"fa fa-search\" style=\"font-size:30px;margin-left:25px\"></i></a></div>";
		echo '</div>';
		echo '<span class="de-proj-photo-count">' . count($group) . ' photos</span>';
		echo "<div class=\"de-photo-cap\">{$title}<span>{$location}</span></div>";
		echo '</div>';
	}
}
