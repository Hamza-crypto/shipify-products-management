

<script>
    document.addEventListener("DOMContentLoaded", function() {
        var options = {
            chart: {
                height: 350,
                type: "bar",
            },
            plotOptions: {
                bar: {
                    horizontal: false,
                    endingShape: "rounded",
                    columnWidth: "30%",
                },
            },
            dataLabels: {
                enabled: false
            },
            stroke: {
                show: true,
                width: 2,
                colors: ["transparent"]
            },
            colors:['#7db762', '#db6666', '#e0c70d'],
            series: [
                {
                    name: "Accepted",
                    data: [{{ implode(', ', $accepted ) }}]
                }
                ,
                {
                name: "Rejected",
                data: [{{ implode(', ', $rejected ) }}],

            },
                {
                name: "Pending",
                data: [ {{  implode(', ', $pending )  }} ],

            }],
            xaxis: {
                categories:  [ "<?php echo implode('","', $labels )?>"],
            },
            yaxis: {
                title: {
                    text: "$ (Amount)"
                }
            },
            fill: {
                opacity: 1
            },
            tooltip: {
                y: {
                    formatter: function(val) {
                        return "$" + val
                    }
                }
            }
        }
        var chart = new ApexCharts(
            document.querySelector("#apexcharts-column"),
            options
        );
        chart.render();
    });
</script>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Last 7 days report</h5>
                <h6 class="card-subtitle text-muted"> Total sales (accepted, rejected, pending) on last 7 days</h6>
            </div>
            <div class="card-body">
                <div class="chart w-100">
                    <div id="apexcharts-column"></div>
                </div>
            </div>
        </div>
    </div>

</div>
