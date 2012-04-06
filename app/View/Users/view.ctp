<?php
$this->Html->script('highstock', array('inline' => false));
$name = $user["User"]["name"];
$show_results = true;
?>
<header class="page-header">
	<h1><?= $user["User"]["name"] ?></h1>
</header>
<div class="row">
    <div class="span6">
        <?php if(!empty($results)) {?>
            <?php echo $this->element('Users/results', array('user' => $user, 'results' => $results)); ?>
        <? } ?>
        <?php if($show_info) { ?>
            <h2>Information</h2>
            <? if($user["User"]["si_number"] != NULL) echo "Sport Ident: " . $user["User"]["si_number"]. "<br>"; ?>
            <? if($user["User"]["year_of_birth"] != NULL) echo "Year of birth: " . $user["User"]["year_of_birth"]."<br>"; ?>
            <? if($user["User"]["email"] != NULL) echo "Email: " . $user["User"]["email"]."<br>"; ?>
            <h2>Groups</h2>
            <?php
                foreach($groups as $group) {
                    echo $group["Groups"]["id"] . "<br>";
                }
            ?>
        <?php }
        if(!empty($memberships)) {?>
            <h2>Membership</h2>
            <?
            foreach($memberships as $membership) {
                echo $membership["Membership"]["id"] . "<br>";
            }
        } ?>
    </div>
    <div class="span6">
    <?php
    // Send a message
    $email = $this->Session->read('Auth.User.email');
    if($this->Session->check('Auth.User.id') && $this->Session->read('Auth.User.id') != $user['User']['id'] && !empty($user['User']['email']) && !empty($email)) {
        // Logged in
        echo '<h2>Send message</h2>
        <p>Your email address will be revealed to the receiver so they can reply.</p>';
        echo $this->Form->create('User');
    	echo $this->Form->hidden('id', array('value' => $user['User']['id']));
    	echo $this->Form->textarea('message', array('class' => 'input-xlarge', 'rows' => 5, 'data-validate' => 'validate(required)'));
    	echo $this->Recaptcha->display();
    	echo $this->Form->end('Send');
    } else if(!empty($user['User']['email']) && $this->Session->read('Auth.User.id') != $user['User']['id']) {
        // Not logged in
        echo '<h2>Send message</h2>
        <p>'.$this->Html->link('Sign in', '/users/login', array('class' => 'btn btn-primary')).'</p>';
    }
    
    if($show_settings) {
        echo $this->element('Users/settings');
    } ?>
    </div>
</div>
<?php if(!empty($results)) { ?>
    <h2>Ranking Points over time</h2>
    <div id='results-chart'></div>
<?php }
$highstocks = "
$(function() {
    function zeroFill( number, width )
    {
      width -= number.toString().length;
      if ( width > 0 )
      {
        return new Array( width + (/\./.test( number ) ? 2 : 1) ).join( '0' ) + number;
      }
      return number;
    }

    // Create the chart  
    window.chart = new Highcharts.StockChart({
        chart: {
            renderTo: 'results-chart'
        },
        
        rangeSelector: {
            selected: 1
        },
        xAxis: {
            maxZoom: 14 * 24 * 3600000 // fourteen days
        },
        yAxis: {
            title: {
                text: 'Ranking Points'
            }
        },
        plotOptions: {
           spline: {
           marker: {
              radius: 4,
              lineColor: '#666666',
              lineWidth: 1,
              enabled: true
           }
         }
       },
       rangeSelector: {
         selected: 5,
         inputEnabled: false
       },
       tooltip: {
            yDecimals: 4,
            formatter: function() {
                var d = new Date(parseInt(this.x));
                return zeroFill(d.getFullYear(), 2) + \"-\" + zeroFill(d.getMonth() + 1, 2) + \"-\" + zeroFill(d.getDate(), 2) + \"<br/><b>\" + this.points[0].key + \"</b>\";
            }
        },
        series: [{
            name: 'Competition points',
            type: 'spline',
            data: ${jsonResults}
        }]
    });
});
";

echo $this->Html->scriptBlock($highstocks);
?>