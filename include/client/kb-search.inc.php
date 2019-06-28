<h3><?php echo __('Search Results'); ?></h3>
<?php
    if ($faqs->exists(true)) {
        echo '<div id="faq">'.sprintf(__('<b>%d</b> articles matched your search criteria.'),
            $faqs->count())
            .'<ol>';
        foreach ($faqs as $F) {
            echo sprintf(
                '<li><a href="faq.php?id=%d" class="previewfaq">%s</a></li>',
                $F->getId(), $F->getLocalQuestion(), $F->getVisibilityDescription());
        }
        echo '</ol></div>';
    } else {
        echo '<strong class="faded">'.__('The search did not match any FAQs.').'</strong>';
    }
?>