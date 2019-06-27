<?php
    if(!defined('OSTCLIENTINC') || !$faq  || !$faq->isPublished()) die('Access Denied');
    $category=$faq->getCategory();
?>

<div id="breadcrumbs" style="padding-top:2px;">
    <a href="index.php"><?php echo __('Categories');?></a>
    &raquo; <a href="faq.php?cid=<?php echo $category->getId(); ?>"><?php
    echo $category->getFullName(); ?></a>
</div>
<h1><?php echo $faq->getLocalQuestion() ?></h1>
<div class="timestamp">
    <?php
        echo sprintf(__('Last Updated %s'),
        Format::relativeTime(Misc::db2gmtime($faq->getUpdateDate())));
    ?>
</div>
<br/>
<div id="faq-content">
    <?php echo $faq->getLocalAnswerWithImages(); ?>
</div>
<div class="content">
    <?php if ($attachments = $faq->getLocalAttachments()->all()) { ?>
        <section>
            <strong><?php echo __('Attachments');?>:</strong>
            <?php foreach ($attachments as $att) { ?>
                <div>
                    <a href="<?php echo $att->file->getDownloadUrl(['id' => $att->getId()]); ?>" class="no-pjax">
                        <i class="icon-file"></i>
                        <?php echo Format::htmlchars($att->getFilename()); ?>
                    </a>
                </div>
            <?php } ?>
        </section>
    <?php } ?>
</div>
