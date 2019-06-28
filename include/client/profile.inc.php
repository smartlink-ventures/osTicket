<h1><?php echo __('Manage Your Profile Information'); ?></h1>
<p><?php echo __(
'Use the forms below to update the information we have on file for your account'
); ?>
</p>
<form action="profile.php" method="post">
  <?php csrf_token(); ?>
  <div class="pad-box">
    <?php foreach ($user->getForms() as $f) { ?>
        <?php $f->render(['staff' => false]); ?>
    <?php } ?>
  </div>
<?php
if ($acct = $thisclient->getAccount()) {
    $info=$acct->getInfo();
    $info=Format::htmlchars(($errors && $_POST)?$_POST:$info);
?>

    <div class="pad-box">
        <h3><?php echo __('Preferences'); ?></h3>
        <?php echo __('Time Zone');?>:
        <?php
            $TZ_NAME = 'timezone';
            $TZ_TIMEZONE = $info['timezone'];
            include INCLUDE_DIR.'staff/templates/timezone.tmpl.php';
        ?>
        <div class="error"><?php echo $errors['timezone']; ?></div>
    </div>
<?php if ($cfg->getSecondaryLanguages()) { ?>
    <?php echo __('Preferred Language'); ?>:
    <?php
        $langs = Internationalization::getConfiguredSystemLanguages();
    ?>
    <select name="lang">
        <option value="">&mdash; <?php echo __('Use Browser Preference'); ?> &mdash;</option>
<?php foreach($langs as $l) {
$selected = ($info['lang'] == $l['code']) ? 'selected="selected"' : ''; ?>
        <option value="<?php echo $l['code']; ?>" <?php echo $selected;
            ?>><?php echo Internationalization::getLanguageDescription($l['code']); ?></option>
<?php } ?>
    </select>
    <span class="error">&nbsp;<?php echo $errors['lang']; ?></span>
<?php }
      if ($acct->isPasswdResetEnabled()) { ?>
        <div class="pad-box render-field"><h3><?php echo __('Access Credentials'); ?></h3>
<?php if (!isset($_SESSION['_client']['reset-token'])) { ?>
            <?php echo __('Current Password'); ?>:
            <input type="password" size="18" name="cpasswd" value="<?php echo $info['cpasswd']; ?>">
            &nbsp;<span class="error">&nbsp;<?php echo $errors['cpasswd']; ?></span>
        </div>
<?php } ?>
    <div class="pad-box render-field">
    <?php echo __('New Password'); ?>:
    <input type="password" size="18" name="passwd1" value="<?php echo $info['passwd1']; ?>">
    &nbsp;<span class="error">&nbsp;<?php echo $errors['passwd1']; ?></span>
    </div>
    <div class="pad-box render-field">
    <?php echo __('Confirm New Password'); ?>:
    <input type="password" size="18" name="passwd2" value="<?php echo $info['passwd2']; ?>">
    &nbsp;<span class="error">&nbsp;<?php echo $errors['passwd2']; ?></span>
    </div>
<?php } ?>
<?php } ?>
<p style="text-align: center;">
    <input type="submit" value="Update"/>
    <input type="reset" value="Reset"/>
    <input type="button" value="Cancel" onclick="javascript:
        window.location.href='index.php';"/>
</p>
</form>
