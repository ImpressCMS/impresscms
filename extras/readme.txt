Incompatibility with some modules
---------------------------------

Beginning with XOOPS 2.0.14, a change in the core template methods resulted in some incompatibilities with some modules that do not have their templates under (dirname)/templates/ as many XOOPS modules do. Some example are: wraps, 3dforum etc..

If you use one of these modules, you may fix this problem by replacing the resource.db.php file located in the class/smarty/xoops_plugins/ folder by the one provided in the package "extras" folder. This file is the patch provided by Gijoe on peak.xoops.ne.jp.

This issue is one that will be addressed further in later core releases, to provide better compatibility.

We would like to thank GiJoe from peak.xoops.ne.jp for his work with this issue.
