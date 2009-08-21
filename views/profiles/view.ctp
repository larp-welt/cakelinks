<?php $niceHead->css('profiles')?>

<h1><?php 
if (!empty($profile['Profile']['icon'])) {
    echo $html->image('upload/Profile/'.$profile['Profile']['id'].'/icon.'.$profile['Profile']['icon']);
    echo '&nbsp;';
}
echo $profile['User']['username'];
if (!empty($profile['Profile']['realname'])) {
    echo ' (aka '.$profile['Profile']['realname'].')';
} ?></h1>

<?php 
if (!empty($profile['Profile']['image'])) {
    echo '<p class="image">';
    echo $html->image('upload/Profile/'.$profile['Profile']['id'].'/image.'.$profile['Profile']['image']);
    echo '</p>';
} ?>

<div id="profile">
<?php echo $widgets->parse($profile['Profile']['description']); ?>

<h2>Kontaktdaten</h2>
<div class="row">
    <div class="label location">Wohnort</div>
    <div><?= $profile['Profile']['location'] ?></div>
</div>
<div class="row">
    <div class="label email">E-Mail</div>
    <div><?php e($mailto->encode($profile['Profile']['public_mail'])) ?></div>
</div>
<div class="row">
    <div class="label homepage">Homepage</div>
    <div><a href="<?= $profile['Profile']['homepage'] ?>" target="_blank"><?= $profile['Profile']['homepage'] ?></a></div>
</div>

<h2>Chat</h2>
<div class="row">
    <div class="label icq">ICQ</div>
    <div><?= $profile['Profile']['icq'] ?></div>
</div>
<div class="row">
    <div class="label icq">MSN</div>
    <div><?= $profile['Profile']['msn'] ?></div>
</div>
<div class="row">
    <div class="label icq">Yahoo</div>
    <div><?= $profile['Profile']['yahoo'] ?></div>
</div>

<h2>Links</h2>
<?php
    echo '<ul>';
    if (!empty($links)) {
        foreach ($links as $link) { ?>
    <li><a href="/links/links/view/<?= $link['Link']['id'] ?>"><?= $link['Link']['title'] ?></a></li>
<?php   }
    echo '</ul>';
    } else  { ?>
<p>Das Mitglied hat noch keine Links angemeldet.</p>
<?php
    } ?>













</div>