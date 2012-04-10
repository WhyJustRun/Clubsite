<? $this->layout = null; ?>
<?= $useMarkdown ? $this->Markdown->render($content) : $content ?>
