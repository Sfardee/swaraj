CONTENTS OF THIS FILE
----------------------

* Introduction
* Requirements
* Installation
* Configuration
* To-do
* Dependencies
* Maintainers


Introduction
============

This module allows easy UI access and redirect the path `/node` to home page, when /node not used or not disabled from Admin > Structure > Views.
Disable page /node is inspired from Node Page Disable,


* For more information you can check the module here:
  https://www.drupal.org/project/disable_page_slash_node

* Bug reports and feature suggestions are welcome, please track all changes in this link:
    https://www.drupal.org/project/issues/disable_page_slash_node


Requirements
============

* There are no requirements.



Installation
============

* To install a contributed Drupal please visit this page:
  https://www.drupal.org/node/1897420 for further information.


CONFIGURATION
=============

To redirect page /node, please check this path on the following administration menu:
1. Administration > Configuration > System > Basic site settings.
2. Move to FRONT PAGE tab.
3. You will find a new checkbox below Default front page called **"Retain /node as an active url?"**
4. Disable this checkbox when you frontpage is NOT set to node and it will no longer be available as a valid path
5. Save.

If your `site_frontpage` is not `/node` then `/node` will be deactivated when this module is enabled.

![UI Screenshot](https://www.drupal.org/files/project-images/Disable%20page%20node.png)


Similar Projects
=============
1. [Node Page Disable](https://www.drupal.org/project/node_page_disable)

TODO
=============
* Make the configuration user friendly and improve development for this module. Patches/issue/proposition are welcome :slightly_smiling_face:


Dependencies
=============
   - Module node


MAINTAINERS
===========

Current maintainers:
* fazni - https://www.drupal.org/u/fazni
