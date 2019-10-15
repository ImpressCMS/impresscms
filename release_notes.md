# ImpressCMS 1.4 Beta

ImpressCMS 1.4 is a intermediate release between the legacy 1.3 series and the upcoming 2.0 release. The focus is on providing compatibility with PHP 7 and code cleanup. Existing modules that are compatible with PHP 7 should still work.

## Installation

Unzip the contents of the 'htdocs' folder in the root folder of your site, and go in a browser to the address of your site. ImpressCMS will detect that no configuration is present, and will start the installation procedure to help you setup your site on your webserver.

## Update

When updating from a previous version in the 1.3 series, overwrite your existing installation and update the system module once you are logged in, that should bring you to the latest version.

## Known issues

The upgrade step that takes care of moving some templates from the system module to a new location is not yet present. These non-used files can be removed by hand. #508 takes care of this
