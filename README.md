# ShareMyLink
ShareMyLink.ca (formerly ShareNews.ca) was launched in August 2023 in response to Canada's Bill C-18, the Online News Act. This bill prompted Facebook to voluntarily and prematurely block news content shared by Canadians and news publishers even before the law would come into effect, currently slated for late 2024.

Regardless of one's stance on Bill C-18, Facebook's decision to restrict news sharing has had a significant impact on smaller, independent news outlets and has hindered the dissemination of critical information, including urgent news and missing person reports.

**I'm proud of the modest impact it had, enabling 54K Canadians to share news over just a few short weeks.**

On September 19, 2023, Facebook added ShareMyLink.ca and previously ShareNews.ca to their 'news content' blacklist, preventing further use of the tool on its existing domain.

In an effort to support the continued sharing of news links, I am open-sourcing the code, encouraging others to host this service on their own domains. Smaller independent news publishers may also choose to host this tool on an alternate domain, customizing the code to restrict it to their own content URLs, thereby limiting its broader utility. This approach may allow them to continue using the tool until the issues between the Canadian Government and social media platforms are resolved.

**🇨🇦 Please share it widely.**

## Requirements

- A PHP capable web server.

## Installation and Configuration

- Modify the RewriteRule on line (14) of [htaccess](https://github.com/jordanwan/ShareMyLink/blob/main/htaccess) to use your own domain.
- Rename htaccess to "**.htaccess**" (as a hidden file) and upload it to the root of your domain. The .htaccess file will not work correctily without the dot prefixed.
- Configure settings in site-config.php
- Place index.php, site-config.php and og-image.jpg on the root of your domain.

## Screenshot

![ShareMyLink Interactive Tool](https://raw.githubusercontent.com/jordanwan/ShareMyLink/main/ShareMyLink.png)
