osTicket
========
#### Updating Support Site

1. Push all changes to this repo.
1. SSH into the support site server.
1. `cd /osticket`
1. `sudo git checkout develop`
1. `sudo git pull`
1. `sudo php manage.php deploy -v /var/www/osticket`

========
<a href="http://osticket.com"><img src="http://osticket.com/sites/default/files/osTicket.jpg"
align="left" hspace="10" vspace="6"></a>

**osTicket** is a widely-used open source support ticket system. It seamlessly
integrates inquiries created via email, phone and web-based forms into a
simple easy-to-use multi-user web interface. Manage, organize and archive
all your support requests and responses in one place while providing your
customers with accountability and responsiveness they deserve.

How osTicket works for you
--------------------------
  1. Users create tickets via your website, email, or phone
  1. Incoming tickets are saved and assigned to agents
  1. Agents help your users resolve their issues

osTicket is an attractive alternative to higher-cost and complex customer
support systems; simple, lightweight, reliable, open source, web-based and
easy to setup and use. The best part is, it's completely free.

Requirements
------------
  * HTTP server running Microsoft® IIS or Apache
  * PHP version 5.6 to 7.2, 7.2 is recommended
  * mysqli extension for PHP
  * MySQL database version 5.0 or greater

### Recommendations
  * gd, gettext, imap, json, mbstring, and xml extensions for PHP
  * APC module enabled and configured for PHP

Deployment
----------
osTicket now supports bleeding-edge installations. The easiest way to
install the software and track updates is to clone the public repository.
Create a folder on you web server (using whatever method makes sense for
you) and cd into it. Then clone the repository (the folder must be empty!):

    git clone https://github.com/osTicket/osTicket

And deploy the code into somewhere in your server's www root folder, for
instance

    cd osTicket
    php manage.php deploy --setup /var/www/htdocs/osticket/

Then you can configure your server if necessary to serve that folder, and
visit the page and install osTicket as usual. Go ahead and even delete
setup/ folder out of the deployment location when you’re finished. Then,
later, you can fetch updates and deploy them (from the folder where you
cloned the git repo into)

    git pull
    php manage.php deploy -v /var/www/htdocs/osticket/

Upgrading
---------
osTicket supports upgrading from 1.6-rc1 and later versions. As with any
upgrade, strongly consider a backup of your attachment files, database, and
osTicket codebase before embarking on an upgrade.

To trigger the update process, fetch the osTicket tarball from either
the osTicket [github](http://github.com/osTicket/osTicket/releases) page
or from the [osTicket website](http://osticket.com). Extract the tarball
into the folder of your osTicket codebase. This can also be accomplished
with the zip file, and a FTP client can of course be used to upload the new
source code to your server.

Any way you choose your adventure, when you have your codebase upgraded to
osTicket-1.7, visit the /scp page of you ticketing system. The upgrader will
be presented and will walk you through the rest of the process. (The couple
clicks needed to go through the process are pretty boring to describe).

### Upgrading from v1.6
**WARNING**: If you are upgrading from osTicket 1.6, please ensure that all
    your files in your upload folder are both readable and writable to your
    http server software. Unreadable files will not be migrated to the
    database during the upgrade and will be effectively lost.

After upgrading, we recommend migrating your attachments to the database or
to the new filesystem plugin. Use the `file` command-line applet to perform
the migration.

    php manage.php file migrate --backend=6 --to=D

View the UPGRADING.txt file for other todo items to complete your upgrade.

Help
----
Visit the [wiki](http://osticket.com/wiki/Home) or the
[forum](http://osticket.com/forums/). And if you'd like professional help
managing your osTicket installation,
[commercial support](http://osticket.com/support/) is available.

Contributing
------------
Create your own fork of the project and use
[git-flow](https://github.com/nvie/gitflow) to create a new feature. Once
the feature is published in your fork, send a pull request to begin the
conversation of integrating your new feature into osTicket.

### Localization
[![Crowdin](https://d322cqt584bo4o.cloudfront.net/osticket-official/localized.png)](http://i18n.osticket.com/project/osticket-official)

The interface for osTicket is now completely translatable. Language packs
are available on the [download page](http://osticket.com/download). If you
do not see your language there, join the [Crowdin](http://i18n.osticket.com)
project and request to have your language added. Languages which reach 100%
translated are are significantly reviewed will be made available on the
osTicket download page.

The software can also be translated in place in our [JIPT site]
(http://jipt.i18n.osticket.com). Once you have a Crowdin account, login and
translate the software in your browser!

Localizing strings in new code requires usage of a [few rules](setup/doc/i18n.md).

License
-------
osTicket is released under the GPL2 license. See the included LICENSE.txt
file for the gory details of the General Public License.

osTicket is supported by several magical open source projects including:

  * [Font-Awesome](http://fortawesome.github.com/Font-Awesome/)
  * [HTMLawed](http://www.bioinformatics.org/phplabware/internal_utilities/htmLawed)
  * [jQuery dropdown](http://labs.abeautifulsite.net/jquery-dropdown/)
  * [jsTimezoneDetect](http://pellepim.bitbucket.org/jstz/)
  * [mPDF](http://www.mpdf1.com/)
  * [PasswordHash](http://www.openwall.com/phpass/)
  * [PEAR](http://pear.php.net/package/PEAR)
  * [PEAR/Auth_SASL](http://pear.php.net/package/Auth_SASL)
  * [PEAR/Mail](http://pear.php.net/package/mail)
  * [PEAR/Net_SMTP](http://pear.php.net/package/Net_SMTP)
  * [PEAR/Net_Socket](http://pear.php.net/package/Net_Socket)
  * [PEAR/Serivces_JSON](http://pear.php.net/package/Services_JSON)
  * [php-gettext](https://launchpad.net/php-gettext/)
  * [phpseclib](http://phpseclib.sourceforge.net/)
  * [Spyc](http://github.com/mustangostang/spyc)
