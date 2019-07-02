<?php
/*********************************************************************
    index.php

    Helpdesk landing page. Please customize it to fit your needs.

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

<div id="landing_page">
<div class="main-content">
    <div id="here-to-help" class="container">
        <div class="row">
            <form method="get" action="kb/faq.php">
                <label for="search-text">
                    <h1>How can we help?</h1>
                </label>
                <div class="stretch">
                    <input type="hidden" name="a" value="search"/>
                    <input type="text" name="q" placeholder="What do you need help with?" />
                    <input type="submit" value="Search">
                </div>
            </form> 
        </div>
    </div>
</div>
</div>
 <div id="features" class="container light">
   <div class="row flex space">
<?php
    $cats = Category::getFeatured();
    if ($cats->all()) {
?>
    <?php
    foreach ($cats as $C) { ?>
    <div class="flex-item-3 feature link" onClick="window.location.href='http://support.smartlink.ai/kb/faq.php?cid=<?php echo $C->getId(); ?>'">
        <div class="foot-room">
            <i class="fas fa-folder-open fa-4x"></i>
        </div>
        <span class="title"><?php echo $C->getName(); ?></span>
        <p><?php echo $C->getDescription(); ?></p>
    </div>

    <?php
        }
    }
?>
    </div>
    </div>

    <div id="support" class="container light highlight-topic row">
        <div class="row flex">
            <div class="flex-item">
                <h2>Discovering any issues?</h2>
                <p>Please help us improve our software.</p>
            </div>
            <div class="flex-item button">
                <a href="/open.php">Open Ticket</a>
            </div>
        </div>
    </div>

    <div id="no-find" class="container light highlight-topic row">
        <div class="row flex">
            <div class="flex-item">
                <h2>Can't find what you're looking for?</h2>
                <p>We'll help you find the answer.</p>
            </div>
            <div class="flex-item button">
                <a href="/contact.php">Contact Us</a>
            </div>
        </div>
    </div>

<?php require(CLIENTINC_DIR.'footer.inc.php'); ?>
