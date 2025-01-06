# ImpressCMS 2.0.0

This release of ImpressCMS realizes the majority of the original scope of the 2.0 release, defined almost 10 years ago. The focus is on providing compatibility with newer version of PHP (PHP7.4 up until PHP 8.4) and code cleanup. Existing modules that are compatible with the newer PHP versions should still work, as long as they are not using any of the deprecated functions that have been removed in this release.  

This version, ImpressCMS 2.0.0, does require PHP versions 7.4 and above, and contains several corrections and security improvements compared to the previous release. Because of this requirement, the only database connection type is PDO, If your site is using the MySQL connection type, you will need to switch prior to moving to this version.

## Installation

Unzip the contents of the 'htdocs' folder in the root folder of your site, and go in a browser to the address of your site. ImpressCMS will detect that no configuration is present, and will start the installation procedure to help you setup your site on your webserver.

## Update
ImpressCMS 2.0 only has overlapping PHP version support with ImpressCMS 1.4.6. When updating from a previous version in the 1.4 or even 1.3 series, first do an upgrade to ImpressCMS 1.4.6 before continuing. When upgrading your PHP, please verify that the modules you use are compatible!

## Known issues

None so far. If you encounter any problems, please log a ticket at https://github.com/ImpressCMS/impresscms/issues/new/choose
