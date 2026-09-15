<?php
// HTML allowlist sanitizer for admin-authored rich text (currently just
// the project Description field). This is the actual security boundary --
// the admin UI's rich text editor is just a convenience; a request could
// always POST directly to project-edit.php with a raw payload bypassing
// it entirely, so sanitizing here (not just in JS) is what actually keeps
// stored XSS out. Descriptions are echoed unescaped on the public site
// once sanitized, so everything not on this allowlist must be stripped
// before it ever reaches the database.

const DE_RTE_ALLOWED_TAGS = ['p', 'br', 'b', 'strong', 'i', 'em', 'u', 'h1', 'h2', 'h3', 'ul', 'ol', 'li', 'blockquote', 'a'];
const DE_RTE_REMOVE_ENTIRELY = ['script', 'style', 'iframe', 'object', 'embed', 'noscript', 'form'];

function de_sanitize_html(string $html): string {
	if (trim($html) === '') {
		return '';
	}

	$doc = new DOMDocument();
	libxml_use_internal_errors(true);
	$doc->loadHTML(
		'<?xml encoding="utf-8"?><div id="de-sanitize-root">' . $html . '</div>',
		LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD
	);
	libxml_clear_errors();

	$root = $doc->getElementById('de-sanitize-root');
	if (!$root) {
		return '';
	}

	de_sanitize_node($root);

	$out = '';
	foreach (iterator_to_array($root->childNodes) as $child) {
		$out .= $doc->saveHTML($child);
	}
	return trim($out);
}

function de_sanitize_node(DOMNode $node): void {
	foreach (iterator_to_array($node->childNodes) as $child) {
		if ($child->nodeType === XML_COMMENT_NODE) {
			$node->removeChild($child);
			continue;
		}
		if ($child->nodeType !== XML_ELEMENT_NODE) {
			continue; // text nodes pass through untouched
		}

		$tag = strtolower($child->nodeName);

		if (in_array($tag, DE_RTE_REMOVE_ENTIRELY, true)) {
			$node->removeChild($child);
			continue;
		}

		// Recurse first so nested disallowed tags are cleaned up before we
		// decide whether to keep, unwrap, or drop this element.
		de_sanitize_node($child);

		if (!in_array($tag, DE_RTE_ALLOWED_TAGS, true)) {
			de_unwrap($node, $child);
			continue;
		}

		$href = $tag === 'a' ? $child->getAttribute('href') : null;
		foreach (iterator_to_array($child->attributes ?? []) as $attr) {
			$child->removeAttribute($attr->name);
		}

		if ($tag === 'a') {
			if ($href !== null && $href !== '' && de_is_safe_url($href)) {
				$child->setAttribute('href', $href);
				$child->setAttribute('rel', 'noopener');
				$child->setAttribute('target', '_blank');
			} else {
				// No safe href left -- a dead/dangerous link isn't worth
				// keeping as a link, so drop the tag and keep its text.
				de_unwrap($node, $child);
			}
		}
	}
}

function de_unwrap(DOMNode $parent, DOMElement $child): void {
	while ($child->firstChild) {
		$parent->insertBefore($child->firstChild, $child);
	}
	$parent->removeChild($child);
}

function de_is_safe_url(string $url): bool {
	$url = trim($url);
	return $url !== '' && preg_match('#^(https?://|mailto:|/|\#)#i', $url) === 1;
}
