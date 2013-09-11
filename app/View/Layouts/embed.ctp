<!DOCTYPE html>
<html lang=en>
<head prefix="og: http://ogp.me/ns# fb: http://ogp.me/ns/fb# event: http://ogp.me/ns/event#">
      <?php echo $this->Html->charset(); ?>
      <title>
          <?php echo $title_for_layout; ?>
      </title>
  
    <?= $this->element('layout_dependencies') ?>
    <?= $scripts_for_layout ?>
</head>
<body>
    <?php echo $content_for_layout; ?>
</body>
</html>

