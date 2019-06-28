<?php
$desc = $event->getDescription(ThreadEvent::MODE_CLIENT);
if (!$desc)
    return;
?>
<div class="thread-event <?php if ($event->uid) echo 'action'; ?> head-room foot-room">
  <span class="faded description"><?php echo $desc; ?></span>
</div>
