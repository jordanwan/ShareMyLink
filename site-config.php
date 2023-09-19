<?php

	// Configure these global variables for your own domain.

	global $sourceURLExists;

	global $siteName;
	$siteName = "ShareMyLink"; // This is the sites name that will appear in text, buttons and elsewhere.

	global $siteDomain;
	$siteDomain = "www.sharemylink.ca"; // This is the sites base domain which is prefixed to sharable URLs.

	global $siteConicalUrl;
	$siteConicalUrl = "https://www.sharemylink.ca"; // This is the conical URL which is used in the Open Graph "og:url" property when someone shares shares the base URL.

	global $siteOgImageUrl;
	$siteOgImageUrl = "https://www.sharemylink.ca/og-image.jpg"; // This is the preview image which is used in the Open Graph "og:image" property when someone shares the tool on Facebook.

	global $pageTitle;
	$pageTitle = $siteName . " - Effortlessly Share Links!"; // This is the text that will appear in the pages <title> when someone visits the base URL.

	global $emailAddress;
	$emailAddress = "you[аt]gmail.com"; // This is your contact email address that will appear within the pages legalese.

	global $exampleArticleURl;
	$exampleArticleURl = "https://www.cbc.ca/news/canada/hamilton/indigenous-news-meta-block-1.6947924"; // This is the article URL that will be used when someone clicks the "Show me an example." link.

	global $facebookSharePrefix;
	$facebookSharePrefix = "https://www.facebook.com/sharer/sharer.php?u="; // This is the standard Facebook sharer URL that is prefixed to the shareable URL when someone clicks "Tap here to it share now!"

?>