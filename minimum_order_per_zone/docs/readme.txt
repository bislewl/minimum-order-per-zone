Name
====
Minimum Order


Version
=======
v1.2


Author(s)
======
Andrew Berezin - ORIGINAL AUTHOR - andrew@ecommerce-service.com http://eCommerce-Service.com 
Chinchilla2661, charlow - SQL and Readme.txt changes
JT Website Design - first order addition


Description
===========
This contribution will add a configuration option that will allow you to specify a minimum order total that 
must be met before allowing checkout.

See Admin-->Configuration-->Minimum Values. A default of 10 has been added for ease of location (and it seemed like a good figure to stick in the blank) - charlow aka Chinchilla2661


Support forum
=============
Support topic on forum: http://www.zen-cart.com/forum/showthread.php?p=380484


Affected files
==============
None


Affects DB
==========
Yes (creates new record into configuration table)


DISCLAIMER
==========
Installation of this contribution is done at your own risk.
Backup your ZenCart database and any and all applicable files before proceeding.


Install:
========

0. BACKUP! BACKUP! BACKUP! BACKUP! BACKUP! BACKUP!
1. Unzip and upload all files to your store directory;
2. Go to Admin->Tools->Install SQL Patches and, using a text editor, copy & paste code from install.sql
3. Go to Configuration > Minimum Values, and change your order amounts. Done!


Upgrade:
========

0. BACKUP! BACKUP! BACKUP! BACKUP! BACKUP! BACKUP!
1. Unzip and upload all files to your store directory;
2. Go to Admin->Tools->Install SQL Patches and, using a text editor, copy & paste code from install_UPGRADE.sql
3. Go to Configuration > Minimum Values, and change your order amounts. Done!


Tips
====

IF you have any problems with your install/upgrade, then run uninstall.sql, then run install.sql. Re-upload all files to your store directory.


History
=======
v 1.0 13.05.2007 6:33 - Andrew Berezin
Initial version

v 1.0.1 20.09.2007 0:06 - Andrew Berezin
Bug fix.

v 1.0.1 01.02.2008 12:45pm - Fred Schenk, Chinchilla2661, charlow
Added where to find the configuration setting in the description area of this readme file. Updated sql install file to move configuration location from Admin-->Configuration-->My Store to Admin-->Configuration-->Minimum Values and include a default minimum order amount. NO CHANGES TO FILE CODING MADE

v 1.2 04/28/11 - JT Website Design
Added "first" order amount, so you may specify higher/lower amounts on subsequent orders.


DISCLAIMER
=======
This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
(LICENSE) along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.