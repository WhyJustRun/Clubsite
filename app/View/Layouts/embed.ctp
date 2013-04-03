<!DOCTYPE html>
<html lang=en>
<head>
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

