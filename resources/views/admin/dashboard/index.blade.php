<div class="row row-cols-1 row-cols-lg-4 row-cols-xl-4 row-cols-xxl-4">
    <div class="col">
        <div class="card overflow-hidden radius-10">
            <div class="card-body">
                <p>{{ __( 'dashboard.all' ) }}</p>
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
                <p>{{ __( 'dashboard.enquiried' ) }}</p>
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
                <p>{{ __( 'dashboard.done' ) }}</p>
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
                <p>{{ __( 'dashboard.complaint' ) }}</p>
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
            <div class="card-body">
                <canvas id="myChart" style="max-width:88%; padding-left:6%; max-height: 550px;"></canvas>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {

        const xValues = [ 'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'July', 'Aug', 'Sep', 'Oct', 'Noc', 'Dec'];

        new Chart("myChart", {
            type: "line",
            data: {
                labels: xValues,
                datasets: [
                    {
                        label: 'sales',
                        data: [860,1140,1060,1060,1070,1110,1330,2210,7830,2478,7830,2478],
                        borderColor: "blue",
                        fill: true
                    },
                ]
            },
            options: {
                plugins: {
                    legend: {
                        display: true,
                        align: 'end',
                        labels:{
                            useBorderRadius: true,
                            borderRadius: 5,
                            boxWidth: 50,
                            boxHeight:20,
                        }
                    },
                    title: {
                        display: true,
                        text: '{{ __( 'dashboard.monthly_report' ) }}',
                    }
                },
            }
        });

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
                }
            } );
        }
    } );
</script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>