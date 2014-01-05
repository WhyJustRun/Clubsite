<!DOCTYPE html>
<html lang=en>
<head>
    <?php echo $this->Html->charset(); ?>
    <title>
        <?php echo $title_for_layout; ?>
    </title>

    <?php
    echo $this->Html->meta('icon');
    echo $this->Html->css('printable.css');
    
    echo $scripts_for_layout;
    ?>
</head>
<body>
    <?php
    echo $content_for_layout;
    echo $this->element('layout_bottom_dependencies');
    ?>
</body>
</html>

