<?php
    $categories = Category::objects()
        ->exclude(Q::any(array(
            'ispublic'=>Category::VISIBILITY_PRIVATE,
            'faqs__ispublished'=>FAQ::VISIBILITY_PRIVATE,
        )))
        ->annotate(array('faq_count'=>SqlAggregate::COUNT('faqs')));
        // ->filter(array('faq_count__gt'=>0));
    if ($categories->exists(true)) { ?>
        <div><?php echo __('Click on the category to browse FAQs.'); ?></div>
        <ul class="feature-list">
        <?php foreach ($categories as $C) { ?>
            <li>
                <h4>
                    <?php
                        echo sprintf('<a href="faq.php?cid=%d">%s %s</a>',
                            $C->getId(), Format::htmlchars($C->getLocalName()),
                            ''
                            // $count ? "({$count})": ''
                        );
                    ?>
                </h4>
            </li>
<?php   } ?>
       </ul>
<?php
    } else {
        echo __('No articles found');
    }
?>
