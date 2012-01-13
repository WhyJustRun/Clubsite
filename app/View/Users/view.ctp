<?php
$this->Html->script('highstock-1.0.2', array('inline' => false));
$name = $user["User"]["name"];
$show_results = true;
?>
<header>
	<h1><?= $user["User"]["name"] ?></h1>
</header>

<div class="column span-12">
<?php
/////////////
// Results //
/////////////
if(!empty($results)) {?>
        <div class="column-box" >
            <?php echo $this->element('Users/results', array('user' => $user, 'results' => $results)); ?>
        </div>
<? } ?>
    <?php
    /////////////////
    // Information //
    /////////////////
    if($show_info) {?>
        <div class="column-box">
            <h2>Information</h2>
            <? if($user["User"]["si_number"] != NULL) echo "Sport Ident: " . $user["User"]["si_number"]. "<br>"; ?>
            <? if($user["User"]["year_of_birth"] != NULL) echo "Year of birth: " . $user["User"]["year_of_birth"]."<br>"; ?>
            <? if($user["User"]["email"] != NULL) echo "Email: " . $user["User"]["email"]."<br>"; ?>
        </div>
        <div class="column-box">
            <h2>Groups</h2>
            <?php
                foreach($groups as $group) {
                    echo $group["Groups"]["id"] . "<br>";
                }
            ?>
        </div>
    <?php } ?>
    <?php 
    ////////////////
    // Membership //
    ////////////////
    if(!empty($memberships)) {?>
        <div class="column-box">
            <h2>Membership</h2>
        </div>
         <?
        foreach($memberships as $membership) {
            echo $membership["Membership"]["id"] . "<br>";
        }
        ?>
    <?php } ?>
</div>

<?php
//////////////
// Settings //
//////////////
if($show_settings) {?>
<div class="column span-12 last">
    <?php echo $this->element('Users/settings');?>
</div>
<?php } ?>
<? if(!empty($results)) { ?>
<div class="column span-24">
    <div class="column-box">
        <h2>Ranking Points over time</h2>
        <div id='results-chart'>
         
        </div>
    </div>
</div>
<? } ?>
<? $highstocks = "
$(function() {
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