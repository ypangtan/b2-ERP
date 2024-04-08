<div class="row row-cols-1 row-cols-lg-4 row-cols-xl-4 row-cols-xxl-4">
    <div class="col">
        <div class="card overflow-hidden radius-10">
            <div class="card-body">
                <p>{{ __( 'dashboard.total_all' ) }}</p>
                <h4 class="card-value" id="all">
                    <div class="spinner-border spinner-border-sm" role="status" style="width: 1.5rem; height: 1.5rem; border-width: .05em">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </h4>
            </div>
        </div>
    </div>
    <div class="col">
        <div class="card overflow-hidden radius-10">
            <div class="card-body">
                <p>{{ __( 'dashboard.total_leading' ) }}</p>
                <h4 class="card-value" id="enquiry">
                    <div class="spinner-border spinner-border-sm" role="status" style="width: 1.5rem; height: 1.5rem; border-width: .05em">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </h4>
            </div>
        </div>
    </div>
    <div class="col">
        <div class="card overflow-hidden radius-10">
            <div class="card-body">
                <p>{{ __( 'dashboard.total_done' ) }}</p>
                <h4 class="card-value" id="done">
                    <div class="spinner-border spinner-border-sm" role="status" style="width: 1.5rem; height: 1.5rem; border-width: .05em">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </h4>
            </div>
        </div>
    </div>
    <div class="col">
        <div class="card overflow-hidden radius-10">
            <div class="card-body">
                <p>{{ __( 'dashboard.total_complaint' ) }}</p>
                <h4 class="card-value" id="complaint">
                    <div class="spinner-border spinner-border-sm" role="status" style="width: 1.5rem; height: 1.5rem; border-width: .05em">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </h4>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-xxl-12">
        <div class="card overflow-hidden radius-10">
            <div class="card-body text-center">
                <div id="loading-div" class="spinner-border spinner-border-sm" role="status" style="width: 1.5rem; height: 1.5rem; border-width: .05em;">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <canvas id="salesChart" style="padding:3%; max-height: 550px; display:none;"></canvas>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {

        getDashboardData();

        function getDashboardData() {

            $.ajax( {
                url: '{{ route( 'admin.dashboard.totalDatas' ) }}',
                type: 'POST',
                data: { _token: '{{ csrf_token() }}' },
                success: function( response ) {

                    $( '#all' ).html( response.all );
                    $( '#enquiry' ).html( response.enquiry );
                    $( '#done' ).html( response.done );
                    $( '#complaint' ).html( response.complaint );
                    $( '#loading-div').hide(); 
                    $( '#salesChart' ).show(); 
                    $( '#inventoriesChart' ).show(); 
                    $data = {
                        'sales' : response.sale_report, 
                        'complaint' : response.complaint_report, 
                    };
                    showSaleChart( $data , response.years[0], response.years[11] );
                    
                }
            } );
        }
        
        function showSaleChart(data, startYear, endYear) {
            new Chart( "salesChart", {
                type: "line",
                data: {
                    datasets: [
                        {
                            label: '{{ __( 'dashboard.sales' ) }}',
                            data: data.sales,
                            borderColor: "rgba(52, 97, 255, 1)",
                            backgroundColor : "rgba(52, 97, 255, 0.2)",
                            fill: {
                                "above" : "rgba(52, 97, 255, 0.2)",
                                "below" : "rgba(52, 97, 255, 0.2)",
                                "target" : {
                                    "value" : 0,
                                }
                            },
                        },
                        {
                            label: '{{ __( 'dashboard.complaints' ) }}',
                            data: data.complaint,
                            borderColor: "rgba(255, 0, 0, 1)",
                            backgroundColor : "rgba(255, 0, 0, 0.2)",
                            fill: {
                                "above" : "rgba(255, 0, 0, 0.2)",
                                "target" : {
                                    "value" : 0
                                }
                            },
                        },
                    ],
                },
                options: {
                    elements: {
                        point: {
                            hoverRadius: 5,
                            hoverBorderWidth: 2,
                        },
                    },
                    plugins: {
                        legend: {
                            display: true,
                            align: 'end',
                            labels: {
                                useBorderRadius: true,
                                borderRadius: 5,
                                boxWidth: 50,
                                boxHeight: 20,
                                font: {
                                    size: 14,
                                    family:  "'Montserrat', sans-serif",
                                },
                            },
                        },
                        title: {
                            display: true,
                            text: '{{ __( 'dashboard.monthly_sales_report_bewteen' ) }} ' + startYear + ' {{ __( 'dashboard.to' ) }} ' + endYear,
                            font: {
                                size: 18,
                                family:  "'Montserrat', sans-serif",
                            },
                        }
                    },
                }
            });
        }

    } );
</script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>