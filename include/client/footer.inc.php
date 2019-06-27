    </div> <!-- div from header.inc... -->
    <div id="footer" class="container">
        <div class="row">
            <p><?php echo __('Copyright &copy;'); ?> <?php echo date('Y'); ?> <?php
            echo Format::htmlchars((string) $ost->company ?: 'osTicket.com'); ?> - <?php echo __('All rights reserved.'); ?></p>
            <a id="poweredBy" href="http://osticket.com" target="_blank"><?php echo __('Helpdesk software - powered by osTicket'); ?></a>
        </div>
    </div>

    <div id="overlay"></div>
    <div id="loading">
        <i class="fas fa-sync-alt fa-spin fa-lg"></i> &nbsp; <?php echo __('Please Wait!');?>
    </div>

    <?php
    if (($lang = Internationalization::getCurrentLanguage()) && $lang != 'en_US') { ?>
        <script type="text/javascript" src="ajax.php/i18n/<?php
            echo $lang; ?>/js"></script>
    <?php } ?>
    <script type="text/javascript">
        getConfig().resolve(<?php
            include INCLUDE_DIR . 'ajax.config.php';
            $api = new ConfigAjaxAPI();
            print $api->client(false);
        ?>);
    </script>
</body>
</html>
