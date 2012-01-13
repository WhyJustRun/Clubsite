<? 
if(!empty($onlyMarkdown) && $onlyMarkdown) {
    $this->layout = null;
    echo $page["Page"][$field];
} else { 
?>

<h1 id="page-resource-title-<?= $page['Page']['id'] ?>" class="page-resource-title"><?= $page['Page']['name']; ?></h1>

<div id="page-resource-<?= $page['Page']['id'] ?>" class="padded page-resource">
<?= $this->Markdown->render($page["Page"]["content"]) ?>
</div>
<? 
}
?>