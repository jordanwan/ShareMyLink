# ShareMyLink
ShareMyLink.ca (formerly ShareNews.ca) was launched in August 2023 in response to Canada's Bill C-18, the Online News Act. This bill prompted Facebook to voluntarily and prematurely block news content shared by Canadians and news publishers even before the law would come into effect, currently slated for late 2024.
Regardless of one's stance on Bill C-18, Facebook's move to restrict news sharing has drastically impacted smaller, independent news outlets and hindered the dissemination of urgent and important news, missing person reports and more.

On September 19, 2023, Facebook added ShareMyLink.ca and previously ShareNews.ca to their 'news content' blacklist, preventing further use of the tool on its existing domain.

I'm open-sourcing the code to encourage others to host this service on their own domains. Smaller independent news publishers may also consider hosting this tool on an alternate domain and modifying the code to restrict it to their own content URLs in order to limit the utility of their custom link sharer. This may allow them to continue using the tool until the issue between the Canadian Government and social media platforms may be resolved.

**🇨🇦 Please share it widely.**

## Requirements

- A PHP capable web server.

## Installation and Configuration

- Modify the RewriteRule in line for of [htaccess](https://github.com/jordanwan/ShareMyLink/blob/main/htaccess) to use your own domain.
- Configure settings in site-config.php
- Place index.php, site-config.php and og-image.jpg on the root of your domain.
- Rename htaccess to "**.htaccess**" (as a hidden file) and upload it to the root of your domain. The .htaccess file will not work correctily without the dot prefixed.

## Demo

While [ShareMyLink.ca](https://www.sharemylink.ca) may no longer be a viable option for Canadians to share content on Facebook, as it has been added to Facebook's  'news content' blacklist, it will continue to function for demonstration purposes.
