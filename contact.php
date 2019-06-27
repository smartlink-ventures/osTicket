<?php
/*********************************************************************
    contact.php

    Helpdesk contact page. Please customize it to fit your needs.

    Peter Rotich <peter@osticket.com>
    Copyright (c)  2006-2013 osTicket
    http://www.osticket.com

    Released under the GNU General Public License WITHOUT ANY WARRANTY.
    See LICENSE.TXT for details.

    vim: expandtab sw=4 ts=4 sts=4:
**********************************************************************/
require('client.inc.php');

require_once INCLUDE_DIR . 'class.page.php';

$section = 'home';
require(CLIENTINC_DIR.'header.inc.php');
?>
<div id="contact_page" class="container light page default-page">
    <div class="row">
        <h1>Contact</h1>
        <p>For more information or help using SkyOS, please contact us via email: <a href="mailto: support@smartlink.city">support@smartlink.city</a></p>
        <hr />
        <h3>Is something wrong?</h3>
        <p><a href="/open.php">Open a ticket</a></p>
    </div>
</div>


<?php require(CLIENTINC_DIR.'footer.inc.php'); ?>
