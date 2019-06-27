<?php
if(!defined('OSTCLIENTINC') || !$category || !$category->isPublic()) die('Access Denied');
?>

<h1><?php echo $category->getLocalName(); ?></h1>
<p><?php echo Format::safe_html($category->getLocalDescriptionWithImages()); ?></p>

<?php
$faqs = FAQ::objects()
    ->filter(array('category'=>$category))
    ->exclude(array('ispublished'=>FAQ::VISIBILITY_PRIVATE))
    ->annotate(array('has_attachments' => SqlAggregate::COUNT(SqlCase::N()
        ->when(array('attachments__inline'=>0), 1)
        ->otherwise(null)
    )))
    ->order_by('-ispublished', 'question');

if ($faqs->exists(true)) {
    echo '
        <div id="faq">
            <ul class="faq-category feature-list">';
foreach ($faqs as $F) {
        $attachments=$F->has_attachments?'<span class="Icon file"></span>':'';
        echo sprintf('
            <li class="feature link"><a href="faq.php?id=%d" >%s &nbsp;%s</a></li>',
            $F->getId(),Format::htmlchars($F->question), $attachments);
    }
    echo '  </ul>
         </div>';
} else {
    echo '<p class="timestamp">This category does not have any content yet. Please check our other <a href="index.php">categories</a>.</p>';
}
?>

<?php if ($faq->getLocalAttachments && $faq->getLocalAttachments->all && $attachments = $faq->getLocalAttachments()->all()) { ?>
    <div class="content">
        <section>
            <strong><?php echo __('Attachments'); ?>:</strong>
            <?php foreach ($attachments as $att) { ?>
                <div>
                    <a href="<?php echo $att->file->getDownloadUrl(['id' => $att->getId()]); ?>" class="no-pjax">
                        <i class="icon-file"></i>
                        <?php echo Format::htmlchars($att->getFilename()); ?>
                    </a>
                </div>
            <?php } ?>
        </section>
    </div>
<?php } ?>
