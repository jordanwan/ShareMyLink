<!DOCTYPE html>
<html lang="en">

	<head>

		<?php include("site-config.php"); ?>

		<?php

			function fixSourceURL() {

				global $sourceURL;

				if (strpos($sourceURL, ":/") === false) {

					// The sourceUrl parameter does not have ":/" or "://", assume the destination server will support https and prepend "https://" to this URL.

					$sourceURL = "https://" . $sourceURL;

				} else {

					// Apache 2.4.39 and higher have a directive called MergeSlashes with its default value set to on, which collapses any '//' to '/' when the 302 redirect from the .htaccess occurs. This would make the destinations URL protocol invalid and so the issue is addressed here by replacing the existing ':/' with '://'.

					if (strpos($sourceURL, "://") === false) {

						// The URL param has only ":/", replace it with "://"

						$sourceURL = str_replace(":/", "://", $sourceURL);

					} else {

						// The sourceUrl parameter already has "://" do nothing.

					}

				}

			}

			function fetchOpenGraphTags($sourceURL) {

				// Some servers may disallow the allow_url_fopen directive, resulting in a file_get_contents() failure for external domains. Instead, use curl_get_file_contents() as a more reliable alternative in order to fetch file contents from an external server.

				function curl_get_file_contents($URL)
				{
				    $c = curl_init();
				    curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
				    curl_setopt($c, CURLOPT_URL, $URL);
				    curl_setopt($c, CURLOPT_USERAGENT, "Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/117.0.0.0 Safari/537.36");
				    $contents = curl_exec($c);
				    curl_close($c);

				    if ($contents) return $contents;
				    else return FALSE;
				}

			    $html = curl_get_file_contents($sourceURL);

			    $dom = new DOMDocument();
			    @$dom->loadHTML($html);

			    $metaTags = $dom->getElementsByTagName('meta');
			    $openGraphTags = array();

				function stripSubdomains($url){

				    // Credits to https://github.com/gavingmiller of second-level domains
				    $second_level_domains = curl_get_file_contents("https://raw.githubusercontent.com/gavingmiller/second-level-domains/master/SLDs.csv");

				    // Presume second-level domains first...
				    $possible_sld = implode('.', array_slice(explode('.', $url), -2));

				    // Return the domain with subdomains stripped.
				    if (strpos($second_level_domains, $possible_sld)){
				        return  implode('.', array_slice(explode('.', $url), -3));
				    } else {
				        return  implode('.', array_slice(explode('.', $url), -2));
				    }

				}

				global $sourceDomain;

				$sourceDomain = stripSubdomains(parse_url($sourceURL, PHP_URL_HOST));

				// Extract the Open Graph data from the page at the sourceUrl

			    foreach ($metaTags as $metaTag) {

			        $property = $metaTag->getAttribute('property');
			        if (strpos($property, 'og:') !== false || strpos($property, 'fb:') !== false) {

			        	if ($property == "og:title") {

			        		// Add a link emoji and repurpose the og:title for the pages title.

			        		echo "<title>🔗 " . strtoupper($sourceDomain) . " | " . $metaTag->getAttribute('content') . "</title>";

			        		echo "<meta content=\"🔗 " . strtoupper($sourceDomain) . " | " . $metaTag->getAttribute('content') . "\" property=\"og:title\">";

			        		echo "<meta content=\"" . $metaTag->getAttribute('content') . "\" property=\"og:original-title\">";

			        		
			        	} else {

							// Include the entire meta tag
				            
				            $openGraphTags[] = $metaTag->C14N();

			        	}


			        }
			    }

			    return $openGraphTags;

			}

			// Rewrite the extracted Open Graph tags to the page on this domain for Meta to parse for the preview.

			function writeOpenGraphTags($openGraphTags) {
			    foreach ($openGraphTags as $tag) {
					echo "\t" . $tag . "\n";
			    }
			}

			// For debugging needs &debugMode=true can be added to a sharable URL to suppress the redirect that occurs after the countdown completes.

			if (isset($_GET['debugMode'])) {

				$debugMode = $_GET['debugMode'];

			} else {

				$debugMode = $_GET['debugMode'];

			}

			if (isset($_GET['sourceURL'])) {

				// If the sourceUrl parameter exists then scrape the destination pages Open Graph data and write it here.

				$sourceURLExists = "true";
				$sourceURL = $_GET['sourceURL'];

				fixSourceURL();
				$openGraphTags = fetchOpenGraphTags($sourceURL);
				writeOpenGraphTags($openGraphTags);

			} else {

				// If the sourceUrl parameter does not exist then someone may be sharing the link creator tool itself and custom Open Graph data is defined for this situation.

				$sourceURLExists = "false";

				echo "<title>" . $pageTitle . "</title>";
				echo "\n\t\t<meta property=\"og:title\" content=\"" . $pageTitle . "\" />";
				echo "\n\t\t<meta property=\"og:image\" content=\"" .$siteOgImageUrl . "\" />";

				echo "\n\t\t<meta property=\"og:url\" content=\"" . $siteConicalUrl . "\" />";
				echo "\n\t\t<meta property=\"og:type\" content=\"website\" />";
				echo "\n\t\t<meta property=\"og:description\" content=\"Simply copy and paste your news URL into the field below, and instantly receive an unrestricted link that you can confidently share.\" />";

			}

		?>

		<meta name="viewport" content="width=device-width, user-scalable=no">

		<style type="text/css">

			html {
				font-size: 18px;
			}

			a:link, a:visited {
				color: skyblue;
			}

			form a:link, form a:visited {
				color: slategray;
			}

			body {
				font-size: 1rem;
				font-family: sans-serif;
				margin: 20px;
				background-color: slategray;
				color: white;
			}

			p {
				font-size: 1rem;
			}

			form {
				color: darkslategray;
				background-color: #D0D7D8;
				padding: 40px;
				border-radius: 3px;
				text-align: center;
				margin: 1em auto;
			}

			#interactive-sharing form div {
				margin-bottom: 1rem;
			}

			#interactive-sharing form div > * {
				display: block;
				margin-bottom: 0.25rem;
			}

			#interactive-sharing form input {
				width: 100%;
				height: 2rem;
				border: 1px gray;
				border-radius: 3px;
				font-size: 1rem;
				padding-left: 1rem;
				box-sizing: border-box;
				color: steelblue;
			}

			::placeholder {
			  color: lightgray;
			}
			
			#redirection-message .source-domain, #counter, #skip-delay .source-domain {
				font-weight: bold;
			}

			#redirection-message {
				display: none;
			}

			#interactive-sharing {
				display: none;
				text-align: center;
			}

			#share-link {
				opacity: 0;
				transition: opacity 500ms;
			}

			#copyShareableURL {
				opacity: 0;
				transition: opacity 500ms;
			}

			#preview {
				display: none;
				text-align: center;
			}

			#preview h1 a {
				text-decoration: none;
				color: inherit;
			}

			#preview img {
				width: 100%;
				height: auto;

				
			}

			@media (min-width: 768px) {
				#preview img {
					max-width: 800px;
				}
			}

			label {
				margin-right: 0.5em;
			}

			h1 {
				font-size: 1.5rem;
			}


			h2 {
				font-size: 1.25rem;
			}

			h3 {
				font-size: 1rem;
			}

			h1, h2, h3 {
				font-weight: 400;
			}

			button {
				padding: 0.5rem 1rem;
				font-weight: 300;
				border: none;
				font-size: 1rem;
				cursor: pointer;
				border-radius: 30px;
				box-shadow: 0px 3px rgba(0,0,0,0.30);
				color: white;
				background-color: slateblue;
				margin: 1em auto;
				display: block;

			}

			form button {
				box-shadow: 0px 3px rgba(0,0,0,0.15);
			}

			#example-link {
				font-size: 0.75rem;
			}

			#disclaimer {
				color: lightgray;
				font-size: 0.75rem;
			}

			#disclaimer strong {
				color: white;
				font-weight: 400;
			}


		</style>

	</head>

	<body>

		<div id="preview">

			<h1><a href="<?php echo $siteConicalUrl ?>">🗞️ <?php echo $siteName ?></a></h1>

			<p id="redirection-message">Your web browser will be redirected to the content on <span class="source-domain"></span> will open in <span id="counter">#</span> <strong>seconds</strong>&hellip;</p>

			<button id="skip-delay">Go to <span class="source-domain"></span> now!</button>

			<a id="cancel-redirect" href="javascript:void(0);" onclick="cancelCountdown()">Cancel Redirect</a>

			<p>This news content is made sharable by <?php echo $siteName ?>. <a href="<?php echo $siteConicalUrl ?>">Click here</a> to share your own news stories.</p>

			<hr>

		</div>

		<div id="interactive-sharing">

			<h1>🗞️ <?php echo $siteName ?></h1>
		
			<h2>Effortlessly Share Links!</h2>

			<p>Simply copy and paste your news URL into the field below, and instantly receive an unrestricted link that you can confidently share.</p>

			<form>

				<div>
					<label for="userInputURL"><strong>Step 1:</strong> Paste Original URL</label>
					<input type="text" id="userInputURL" placeholder="Paste original news URL&hellip;" />
					<a href="javascript:void(null)" id="example-link">Show me an example.</a>
				</div>

				<div>
					<label for="sharableURL"><strong>Step 2:</strong> Copy Sharable URL</label>
					<input type="text" id="sharableURL" readonly />
				</div>

				<div>
					<button id="copyShareableURL">🔗 Copy Sharable URL</button>
				</div>

				<strong><a id="share-link" href="">Tap here to it share now!</a></strong>

			</form>

		</div>

		<button id="tell-others">📢 Tell others about <?php echo $siteName ?>!</button>

		<p id="disclaimer"><?php echo $siteName ?> is a platform that facilitates the sharing of content links on social media platforms. <strong><?php echo $siteName ?> does not store or retain any content from the source URLs provided by users.</strong> This service operates by relaying information solely from the Open Graph metadata of the provided source URLs. This metadata, including titles, images, and descriptions, is explicitly provided by publishers for the purpose of sharing their content on social media platforms. <?php echo $siteName ?> does not alter, modify, or store any content, and content shared using this tool simply redirects the visitor to the original source. <strong>Send media inquiries to <?php echo $emailAddress ?>.</strong></p>

	</body>

	<script type="text/javascript">

		const sourceURLExists = <?php echo "\"$sourceURLExists\"" ?> === "true";
		const debugMode = <?php echo "\"$debugMode\"" ?> === "true";

		console.log ("$debugMode?:",debugMode);

		function getBaseUrl() {
			return "https://<?php echo $siteDomain ?>/";
		}

		const exampleArticleURl = <?php echo "\"$exampleArticleURl\"" ?>;

		const facebookSharePrefix = <?php echo "\"$facebookSharePrefix\"" ?>;

		if (sourceURLExists == true) {

			// Show the content preview and redirect the user to the original sourceUrl

			const sourceURL = <?php echo "\"$sourceURL\"" ?>;

			function parseDomain(url) {
				const domainMatch = url.match(/^(?:https?:\/\/)?(?:[^@\n]+@)?(?:www\.)?([^:/\n?]+)/im);

				if (domainMatch) {
				    const domainParts = domainMatch[1].split('.');
				    const topLevelDomain = domainParts[domainParts.length - 2];
				    const extension = domainParts[domainParts.length - 1];
				    return topLevelDomain + '.' + extension;
				} else {
				    return null;
				}
			}

			
			const sourceDomain = <?php echo "\"$sourceDomain\"" ?>;

			document.querySelector("#redirection-message .source-domain").textContent = sourceDomain.toUpperCase();
			document.querySelector("#skip-delay .source-domain").textContent = sourceDomain.toUpperCase();

			document.querySelector("#skip-delay").addEventListener("click", function(event){
				event.preventDefault();
				window.location = sourceURL;
			});

			var countdownInterval;

			function cancelCountdown() {
				clearInterval(countdownInterval);
				document.querySelector('#redirection-message').style.display = "none";
				document.querySelector('#cancel-redirect').style.display = "none";
			}

			document.addEventListener("DOMContentLoaded", function() {

			    var openGraphTitle = null;
			    var openGraphImage = null;
			    var openGraphType = null;

			    const metaTags = document.getElementsByTagName("meta");

			    for (const metaTag of metaTags) {
			        const property = metaTag.getAttribute("property");
			        const content = metaTag.getAttribute("content");

			        console.log('prop:', property, 'cont:', content);

			        if (property === "og:original-title") {
			            openGraphTitle = content;
			        } else if (property === "og:image") {
			            openGraphImage = content;
			        } else if (property === "og:type") {
			            openGraphType = content;
			        }
			    }

			    const previewElement = document.getElementById("preview");

				if (openGraphTitle !== null) {
				    // Create an <a> tag and set its href attribute to the sourceURL variable
				    const aElement = document.createElement("a");
				    aElement.href = sourceURL;

				    // Create an <h3> element and set its innerHTML to the openGraphTitle
				    const h3Element = document.createElement("h3");
				    h3Element.innerHTML = openGraphTitle;

				    // Append the <h3> element to the <a> tag
				    aElement.appendChild(h3Element);

				    // Append the <a> tag to the preview element
				    previewElement.appendChild(aElement);
				}

			    if (openGraphImage !== null) {
				    const imgElement = document.createElement("img");
				    imgElement.src = openGraphImage;
				    
				    // Create an <a> tag and set its href attribute to the sourceURL variable
				    const aElement = document.createElement("a");
				    aElement.href = sourceURL;
				    
				    // Append the image element to the <a> tag
				    aElement.appendChild(imgElement);
				    
				    // Append the <a> tag to the preview element
				    previewElement.appendChild(aElement);
				}

			    document.getElementById("preview").style.display = "block";

			    var counterElement = document.getElementById("counter");
			    
			    function countdown() {
			        var count = 5;
			        counterElement.innerHTML = count;
	       
					document.getElementById("redirection-message").style.display = "block";

			        countdownInterval = setInterval(function() {
			            count--;
			            counterElement.innerHTML = count;
			            
			            if (count <= 1) {
			                clearInterval(countdownInterval);
			                setTimeout(function() {

			                    console.log('Redirecting to: ', sourceURL);

			                    if (debugMode !== true) {
			                    	window.location = sourceURL;
			               		} else {
			               			console.log('Redirection was suppressed.')
			               		}

			                }, 500);
			            }
			        }, 1000);
			    }

			    countdown();		    

			});

		} else {

			// Show the main page with copy/paste fields and Facebook submit widget

			const userInputURL = document.getElementById("userInputURL");
			const sharableURL = document.getElementById("sharableURL");
			const copyButton = document.getElementById("copyShareableURL");
			const shareLink = document.getElementById("share-link");

			document.querySelector("#example-link").addEventListener("click", function(event){
				event.preventDefault();

				userInputURL.value = exampleArticleURl;

				var inputEvent = new Event('input');
				userInputURL.dispatchEvent(inputEvent);
				
			});

			document.getElementById("interactive-sharing").style.display = "block";

			var userInputURLVal = "";
			var sharableURLVal = "";

			userInputURL.addEventListener("input", function () {

			  userInputURLVal = userInputURL.value;

			  if (userInputURL.value !== '') {

				sharableURLVal = getBaseUrl() + userInputURLVal;
				sharableURL.value = sharableURLVal;

				shareLink.href= facebookSharePrefix + sharableURLVal;

				copyButton.style.opacity = "1";
				shareLink.style.opacity = "1";

			  } else {

			  	sharableURLVal = '';
			  	sharableURL.value = '';

				copyButton.style.opacity = "0";
			  	shareLink.style.opacity = "0";

			  }

			});

			document.querySelector("#sharableURL").addEventListener("click", function () {

				var clickEvent = new Event('click',{cancelable: true});
				copyButton.dispatchEvent(clickEvent);

			});

			copyButton.addEventListener("click", function (event) {

				event.preventDefault();

				if (sharableURL.value !== "") {

					sharableURL.select();

					document.execCommand("copy");

			        document.querySelector("#copyShareableURL").innerHTML = '👍 URL copied to clipboard!';

					setTimeout(function () {

						document.querySelector("#copyShareableURL").innerHTML = '🔗 Copy Sharable URL';
					}, 2000);

				}


			});

		}

	
		document.querySelector("#tell-others").addEventListener("click", function(event){
			event.preventDefault();
			window.location = facebookSharePrefix + getBaseUrl();
		});


	</script>

</html>