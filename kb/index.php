<?php
/*********************************************************************
    index.php

    Knowledgebase Index.

    Peter Rotich <peter@osticket.com>
    Copyright (c)  2006-2013 osTicket
    http://www.osticket.com

    Released under the GNU General Public License WITHOUT ANY WARRANTY.
    See LICENSE.TXT for details.

    vim: expandtab sw=4 ts=4 sts=4:
**********************************************************************/
require('kb.inc.php');
require_once(INCLUDE_DIR.'class.category.php');
$nofind='Not finding what you\'re looking for?';
$inc='knowledgebase.inc.php';

require(CLIENTINC_DIR.'header.inc.php'); ?>
    <div id="features" class="container light">
    <div class="row page">
            <?php require(CLIENTINC_DIR.$inc); ?>
            <div id="faq-search">
                <form method="get" action="faq.php">
                    <input type="hidden" name="a" value="search"/>
                    <label>
                        <?php echo $nofind; ?><br />
                        <input type="text" name="q" class="search" placeholder="<?php echo __('Search our knowledge base'); ?>"/>
                    </label>
                    <input type="submit" style="display:none" value="search"/>
                </form>
            </div>
        </div>
    </div>
<?php require(CLIENTINC_DIR.'footer.inc.php'); ?>
