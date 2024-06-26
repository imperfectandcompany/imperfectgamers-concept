<?php

$json = prometheus::updateCheck();

$array = json_decode($json, true);

if (isset($array['uptodate'])) {
  if ($array['uptodate'] == 0 && !isset($array['error'])) {
    $update = 1;
  } else {
    $update = 0;
  }
}

?>

<?php if (permissions::has("stats")) { ?>
  <div class="row">
    <div class="col-md-4">
      <div class="dashboard-widget-small-box">
        <div class="float-left">
          <h2 class="element"><?php echo dashboard::getTotalCurrency(getSetting('dashboard_main_cc', 'value2'), 'total'); ?></h2>

          <p class="caption"><?= lang('earned_total', 'TOTAL EARNED'); ?></p>
        </div>
        <i class="fas fa-money-bill fa-4x float-right"></i>
      </div>
    </div>
    <div class="col-md-4">
      <div class="dashboard-widget-small-box">
        <div class="float-left">
          <h2 class="element"><?php echo dashboard::getTotalCurrency(getSetting('dashboard_main_cc', 'value2'), 'week'); ?></h2>

          <p class="caption"><?= lang('earned_week', 'EARNED THIS WEEK'); ?></p>
        </div>
        <i class="fas fa-clock fa-4x float-right"></i>
      </div>
    </div>
    <div class="col-md-4">
      <div class="dashboard-widget-small-box">
        <div class="float-left">
          <h2 class="element"><?php echo dashboard::getTotalCurrency(getSetting('dashboard_main_cc', 'value2'), 'month'); ?></h2>

          <p class="caption"><?= lang('earned_month', 'EARNED THIS MONTH'); ?></p>
        </div>
        <i class="fas fa-check-circle fa-4x float-right"></i>
      </div>
    </div>
  </div>
<?php } ?>

<div class="row">
  <div class="col-12">
    <?php if ($update == 1) { ?>
      <p class="bs-callout bs-callout-info alert" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
              aria-hidden="true">&times;</span></button>
        <b>Update available!(<?php echo $array['latest']; ?>) <?php echo $array['updatemsg'] ?>. <a
              href="https://nmscripts.com/prometheus/updates">Read more</a><br>
      </p>
    <?php } ?>

    <?php if (gateways::enabled('stripe') && getSetting('stripe_webhookSecret', 'value') === '') { ?>
      <p class="bs-callout bs-callout-danger alert" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
              aria-hidden="true">&times;</span></button>
        <strong>We have just released an important update for Stripe</strong>. Please update your
        <a href="/admin.php?a=gen&p=stripe" target="_blank">Stripe settings</a>.<br><br>
        <a href="http://wiki.prometheusipn.com/index.php?title=Integration:gateways#Stripe" target="_blank">Find out
          how</a>.
      </p>
    <?php } ?>

    <div class="row">
      <div class="col-12">
        <div class="panel-body">
          <div class="panel-header">
            <div class="float-left"><?= lang('dashboard'); ?></div>
          </div>
          <div class="panel-inner">
            <?= lang('dashboard_text'); ?>
          </div>
        </div>
      </div>
    </div>

    <?php if (permissions::has("stats")) { ?>
      <?php if (dashboard::transactionAmount() != 0) { ?>
      <!--
			<form method="POST">
				<div class="row">
					<div class="col-xs-5">
						<input type="text" class="form-control" id="datepicker" placeholder="<?php //echo lang('between', 'Between');?>">
					</div>
					<div class="col-xs-5">
						<input type="text" class="form-control" id="datepicker2" placeholder="<?php //echo lang('and', 'And');?>">
					</div>
					<div class="col-xs-2">
						<input type="submit" value="<?php //echo lang('submit');?>" class="btn btn-prom float-right">
					</div>
				</div>
			</form>
			-->

      <div class="row">
        <div class="col-12">
          <div class="panel-body">
            <div class="panel-header">
              <div class="float-left"><?= lang('dashboard_revenuegraph', 'Revenue Graph'); ?></div>
            </div>
            <div class="panel-inner">
              <?= lang('dashboard_revenuecurrency', 'Shown in your main currency'); ?>
              [<?= $db->getOne("SELECT cc FROM currencies WHERE id = ?", [getSetting('dashboard_main_cc', 'value2')])['cc']; ?>]. <?= lang('dashboard_creditsrevenue', 'This graph also compares credits spent to money spent. Although credits are aquired with money. (Credits = <font color="#9c9c9c"><b>Gray</b></font>, Money = <font color="#c10000"><b>Red</b></font>)'); ?>
              <br><br>
              <canvas id="adminChart" style="width: 100%; height: 400px;"></canvas>
            </div>
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col-md-12">
          <div class="panel-body">
            <div class="panel-header">
              <div class="float-left"><?= lang('dashboard_packagesgraph', 'Package Sales'); ?></div>
            </div>
            <div class="panel-inner">
              <div id="packagesChartInfo" style="display: none;">
                <div id="json"><?php echo dashboard::getPackageSales(); ?></div>
              </div>
              <canvas id="packagesChart" style="width: 100%; height: 400px;"></canvas>
            </div>
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col-md-12">
          <div class="panel-body">
            <div class="panel-header">
              <div class="float-left"><?= lang('dashboard_serversgraph', 'Server Sales'); ?></div>
            </div>
            <div class="panel-inner">
              <div id="serversChartInfo" style="display: none;">
                <div id="json"><?php echo dashboard::getServerSales(); ?></div>
              </div>
              <canvas id="serversChart" style="width: 100%; height: 400px;"></canvas>
            </div>
          </div>
        </div>
      </div>

      <div id="adminChart_money" style="display: none;">
        <?php
        echo dashboard::getRevenue('money');
        ?>
      </div>

      <div id="adminChart_credits" style="display: none;">
        <?php
        echo dashboard::getRevenue('credits');
        ?>
      </div>

      <script>
        function getDates(startDate /*moment.js date object*/) {
          nowNormalized = moment().startOf("month"),
              startDateNormalized = startDate.clone().startOf("month").add(1, "M"),
              months = [];


          while (startDateNormalized.isBefore(nowNormalized)) {
            months.push(startDateNormalized.format("MMMM"));
            startDateNormalized.add(1, "M");
          }

          return months;
        }

        var thisMonth = moment();
        thisMonth = thisMonth.format('MMMM');

        var earlierMonths = moment();
        earlierMonths.subtract(5, 'months');
        earlierMonths = String(getDates(earlierMonths));
        earlierMonths = earlierMonths.split(',');

        var money = $('#adminChart_money').text();
        var moneyJson = $.parseJSON(money);

        var credits = $('#adminChart_credits').text();
        var creditsJson = $.parseJSON(credits);

        /**
         * Do code to get money this month and previous months, grab from a json not from fucking divs like before rofl
         */

        var adminChartData = {
          labels: [earlierMonths[0], earlierMonths[1], earlierMonths[2], earlierMonths[3], thisMonth],
          datasets: [
            {
              label: "Money revenue",
              fillColor: "rgba(60,60,60,0.2)",
              strokeColor: "rgba(193,0,0,1)",
              pointBackgroundColor: "rgba(193,0,0,1)",
              pointBorderColor: "#c10000",
              pointHoverBackgroundColor: "#c10000",
              pointHoverBorderColor: "rgba(193,0,0,1)",
              data: [moneyJson[4], moneyJson[3], moneyJson[2], moneyJson[1], moneyJson[0]],
              backgroundColor: [
                'rgba(190,0,0,0.2)'
              ],
              borderColor: [
                'rgba(190,0,0,1)'
              ],
            },
            {
              label: "Credits revenue",
              fillColor: "rgba(60,60,60,0.2)",
              strokeColor: "rgba(156,156,156,1)",
              pointBackgroundColor: "rgba(156,156,156,1)",
              pointBorderColor: "#9c9c9c",
              pointHoverBackgroundColor: "#9c9c9c",
              pointHoverBorderColor: "rgba(156,156,156,1)",
              data: [creditsJson[4], creditsJson[3], creditsJson[2], creditsJson[1], creditsJson[0]],
              backgroundColor: [
                'rgba(60,60,60,0.2)'
              ],
              borderColor: [
                'rgba(60,60,60,1)'
              ],
            }
          ]
        };

        var packagesChartInfo_json = $("#packagesChartInfo").find("#json").text();
        packages_json = JSON.parse(packagesChartInfo_json);
        var packagesChartData = packages_json;

        var serversChartInfo_json = $("#serversChartInfo").find("#json").text();
        servers_json = JSON.parse(serversChartInfo_json);
        var serversChartData = servers_json;

        window.onload = function () {
          var ctx = document.getElementById("adminChart").getContext("2d");
          window.myLine = new Chart(ctx, {
            type: "line",
            data: adminChartData,
            responsive: true
          });

          var ctx2 = document.getElementById("packagesChart").getContext("2d");

          new Chart.Bar(ctx2, {
            type: 'bar',
            data: packagesChartData,
            responsive: true,
            options: {
              scales: {
                yAxes: [{
                  ticks: {
                    beginAtZero: true,
                    stepSize: 1
                  }
                }]
              }
            }
          });

          var ctx3 = document.getElementById("serversChart").getContext("2d");

          new Chart(ctx3, {
            type: 'bar',
            data: serversChartData,
            responsive: true,
            options: {
              scales: {
                yAxes: [{
                  ticks: {
                    beginAtZero: true,
                    stepSize: 1
                  }
                }]
              }
            }
          });
        }
      </script>
    <?php } ?>

    <?php } else { ?>
      <h2><?= lang('no_graph_info', 'No graph info'); ?></h2>
      <?= lang('no_graph_info_text', 'Not showing graph info due to there not having been any transactions!'); ?>
    <?php } ?>
  </div>
