<?php
/**
 *
 * PHP versions 4 and 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2010, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2010, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       cake
 * @subpackage    cake.cake.libs.view.templates.pages
 * @since         CakePHP(tm) v 0.10.0.1076
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
if (Configure::read() == 0):
	$this->cakeError('error404');
endif;
?>
<table>
   <tr>
       <th>&nbsp</th>
       <th>Navigation!</th>
       <th>Terrain running!</th>
       <th>Checkpoints!</th>
    </tr>
   <tr class="text">
      <?php echo $this->element('root_sidebar'); ?>
      <td width=200px>
         <?php echo $this->Html->image('root2.gif', array('width'=>'175px'));?>
         Orienteering involves navigating between checkpoints using a detailed map and compass.
      </td>
      <td width=200px>
         <?php echo $this->Html->image('root1.jpg', array('width'=>'175px'));?>
         Pick your own route between the checkpoints: Along a path, over a hill, or straight through the forest!<br>
      </td>
      <td width=200px>
         <?php echo $this->Html->image('root3.jpg', array('width'=>'175px'));?>
         <br>Checkpoints are marked with red and white flags, and are indicated by a circle on the map.
      </td>
   </tr>
</table>
<?php echo $this->element('root_bottom'); ?>
