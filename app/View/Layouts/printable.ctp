<!DOCTYPE html>
<html lang=en>
<head>
    <?php echo $this->Html->charset(); ?>
    <title>
        <?php echo $title_for_layout; ?>
    </title>

    <?php
    echo $this->Html->meta('icon');
    
    //echo $this->Html->css("blueprint/screen", null, array("media"=> "screen, projection"));
    //echo $this->Html->css("blueprint/print", null, array("media"=> "print"));
    //echo $this->Html->css('gvoc.css');
    echo $this->Html->css('printable.css');
    
    echo $scripts_for_layout;
    ?>
</head>
<body>
    <?php echo $content_for_layout; ?>
</body>
</html>

