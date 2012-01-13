<h1>Resources</h1>

<? if(!$pages) { ?>
<h2>Sorry, no resources are posted yet.</h2>
<? } ?>
<? foreach($pages as $page) { ?>
<h2><?= $this->Html->link($page["Page"]["name"], '/pages/'.$page["Page"]["id"]) ?></h2>
<?
}
?>