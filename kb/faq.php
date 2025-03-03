<?php
/*********************************************************************
    faq.php

    FAQs Clients' interface..

    Peter Rotich <peter@osticket.com>
    Copyright (c)  2006-2013 osTicket
    http://www.osticket.com

    Released under the GNU General Public License WITHOUT ANY WARRANTY.
    See LICENSE.TXT for details.

    vim: expandtab sw=4 ts=4 sts=4:
**********************************************************************/
require('kb.inc.php');
require_once(INCLUDE_DIR.'class.faq.php');

$nofind='Not finding what you\'re looking for?';
$faq=$category=null;
if($_REQUEST['id'] && !($faq=FAQ::lookup($_REQUEST['id'])))
   $errors['err']=sprintf(__('%s: Unknown or invalid'), __('FAQ article'));

if(!$faq && $_REQUEST['cid'] && !($category=Category::lookup($_REQUEST['cid'])))
    $errors['err']=sprintf(__('%s: Unknown or invalid'), __('FAQ category'));


$inc='knowledgebase.inc.php'; //FAQs landing page.
if($faq && $faq->isPublished()) {
    $nofind='What else can we help you find?';
    $inc='faq.inc.php';
} elseif($category && $category->isPublic() && $_REQUEST['a']!='search') {
    $inc='faq-category.inc.php';
}
require_once(CLIENTINC_DIR.'header.inc.php'); ?>
    <div class="container light page default-page">
        <div class="row">
            <?php require_once(CLIENTINC_DIR.$inc); ?>
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
<?php require_once(CLIENTINC_DIR.'footer.inc.php'); ?>
