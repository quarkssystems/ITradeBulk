@extends('admin.layouts.main')
@section('header')
    <div class="row">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card card-stats mb-4 mb-xl-4">
                <div class="card-body">
            <!-- <h5 class="card-title text-uppercase text-muted mb-0"><i class="fa fa-exclamation-circle" aria-hidden="true"></i> Content in dashboard is not real. Its for demo purpose.</h5> -->
                </div></div>
        </div>
        <div class="col-xl-3 col-lg-6">
            <div class="card card-stats mb-4 mb-xl-0">
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <h5 class="card-title text-uppercase text-muted mb-0">{{__('SUPPLIER')}}</h5>
                            <span class="h2 font-weight-bold mb-0">{{$supplier}}</span>
                        </div>
                        <div class="col-auto">
                            <div class="icon icon-shape bg-danger text-white rounded-circle shadow">
                                <i class="fas fa-chart-bar"></i>
                            </div>
                        </div>
                    </div>
                    <!-- <p class="mt-3 mb-0 text-muted text-sm">
                        <span class="text-success mr-2"><i class="fa fa-arrow-up"></i> 3.48%</span>
                        <span class="text-nowrap">Since last month</span>
                    </p> -->
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-6">
            <div class="card card-stats mb-4 mb-xl-0">
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <h5 class="card-title text-uppercase text-muted mb-0">{{__('Trader')}}</h5>
                            <span class="h2 font-weight-bold mb-0">{{$vendor}}</span>
                        </div>
                        <div class="col-auto">
                            <div class="icon icon-shape bg-warning text-white rounded-circle shadow">
                                <i class="fas fa-chart-pie"></i>
                            </div>
                        </div>
                    </div>
                    <!-- <p class="mt-3 mb-0 text-muted text-sm">
                        <span class="text-danger mr-2"><i class="fas fa-arrow-down"></i> 3.48%</span>
                        <span class="text-nowrap">Since last week</span>
                    </p> -->
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-6">
            <div class="card card-stats mb-4 mb-xl-0">
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <h5 class="card-title text-uppercase text-muted mb-0">{{__('TRANSPORTER')}}</h5>
                            <span class="h2 font-weight-bold mb-0">{{$driver}}</span>
                        </div>
                        <div class="col-auto">
                            <div class="icon icon-shape bg-yellow text-white rounded-circle shadow">
                                <i class="fas fa-users"></i>
                            </div>
                        </div>
                    </div>
                    <!-- <p class="mt-3 mb-0 text-muted text-sm">
                        <span class="text-warning mr-2"><i class="fas fa-arrow-down"></i> 1.10%</span>
                        <span class="text-nowrap">Since yesterday</span>
                    </p> -->
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-6">
            <div class="card card-stats mb-4 mb-xl-0">
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <h5 class="card-title text-uppercase text-muted mb-0">{{__('SALES')}} (R)</h5>
                            <span class="h2 font-weight-bold mb-0">{{$salesOrder}}</span>
                        </div>
                        <div class="col-auto">
                            <div class="icon icon-shape bg-info text-white rounded-circle shadow">
                                <i class="fas fa-percent"></i>
                            </div>
                        </div>
                    </div>
                    <!-- <p class="mt-3 mb-0 text-muted text-sm">
                        <span class="text-success mr-2"><i class="fas fa-arrow-up"></i> 12%</span>
                        <span class="text-nowrap">Since last month</span>
                    </p> -->
                </div>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-xl-12 mb-5 mb-xl-0">
            <div class="card bg-gradient-default shadow">
                <div class="card-header bg-transparent">
                    <div class="row align-items-center">
                        <div class="col">
                            <h6 class="text-uppercase text-light ls-1 mb-1">Overview</h6>
                            <h2 class="text-white mb-0">Sales value</h2>
                        </div>
                        <div class="col">
                            
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Chart -->
                    <div class="chart">
                        <!-- Chart wrapper -->
                        <canvas id="lineChart" class="chart-canvas"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <!-- <div class="col-xl-4">
            <div class="card shadow">
                <div class="card-header bg-transparent">
                    <div class="row align-items-center">
                        <div class="col">
                            <h6 class="text-uppercase text-muted ls-1 mb-1">Performance</h6>
                            <h2 class="mb-0">Total orders</h2>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    
                     <div class="chart">
                        <canvas id="chart-orders" class="chart-canvas"></canvas>
                    </div>
                </div>
            </div>
        </div> -->
    </div>
    {{--<div class="row mt-5">--}}
        {{--<div class="col-xl-8 mb-5 mb-xl-0">--}}
            {{--<div class="card shadow">--}}
                {{--<div class="card-header border-0">--}}
                    {{--<div class="row align-items-center">--}}
                        {{--<div class="col">--}}
                            {{--<h3 class="mb-0">Page visits</h3>--}}
                        {{--</div>--}}
                        {{--<div class="col text-right">--}}
                            {{--<a href="#!" class="btn btn-sm btn-primary">See all</a>--}}
                        {{--</div>--}}
                    {{--</div>--}}
                {{--</div>--}}
                {{--<div class="table-responsive">--}}
                    {{--<!-- Projects table -->--}}
                    {{--<table class="table align-items-center table-flush">--}}
                        {{--<thead class="thead-light">--}}
                        {{--<tr>--}}
                            {{--<th scope="col">Page name</th>--}}
                            {{--<th scope="col">Visitors</th>--}}
                            {{--<th scope="col">Unique users</th>--}}
                            {{--<th scope="col">Bounce rate</th>--}}
                        {{--</tr>--}}
                        {{--</thead>--}}
                        {{--<tbody>--}}
                        {{--<tr>--}}
                            {{--<th scope="row">--}}
                                {{--/argon/--}}
                            {{--</th>--}}
                            {{--<td>--}}
                                {{--4,569--}}
                            {{--</td>--}}
                            {{--<td>--}}
                                {{--340--}}
                            {{--</td>--}}
                            {{--<td>--}}
                                {{--<i class="fas fa-arrow-up text-success mr-3"></i> 46,53%--}}
                            {{--</td>--}}
                        {{--</tr>--}}
                        {{--<tr>--}}
                            {{--<th scope="row">--}}
                                {{--/argon/index.html--}}
                            {{--</th>--}}
                            {{--<td>--}}
                                {{--3,985--}}
                            {{--</td>--}}
                            {{--<td>--}}
                                {{--319--}}
                            {{--</td>--}}
                            {{--<td>--}}
                                {{--<i class="fas fa-arrow-down text-warning mr-3"></i> 46,53%--}}
                            {{--</td>--}}
                        {{--</tr>--}}
                        {{--<tr>--}}
                            {{--<th scope="row">--}}
                                {{--/argon/charts.html--}}
                            {{--</th>--}}
                            {{--<td>--}}
                                {{--3,513--}}
                            {{--</td>--}}
                            {{--<td>--}}
                                {{--294--}}
                            {{--</td>--}}
                            {{--<td>--}}
                                {{--<i class="fas fa-arrow-down text-warning mr-3"></i> 36,49%--}}
                            {{--</td>--}}
                        {{--</tr>--}}
                        {{--<tr>--}}
                            {{--<th scope="row">--}}
                                {{--/argon/tables.html--}}
                            {{--</th>--}}
                            {{--<td>--}}
                                {{--2,050--}}
                            {{--</td>--}}
                            {{--<td>--}}
                                {{--147--}}
                            {{--</td>--}}
                            {{--<td>--}}
                                {{--<i class="fas fa-arrow-up text-success mr-3"></i> 50,87%--}}
                            {{--</td>--}}
                        {{--</tr>--}}
                        {{--<tr>--}}
                            {{--<th scope="row">--}}
                                {{--/argon/profile.html--}}
                            {{--</th>--}}
                            {{--<td>--}}
                                {{--1,795--}}
                            {{--</td>--}}
                            {{--<td>--}}
                                {{--190--}}
                            {{--</td>--}}
                            {{--<td>--}}
                                {{--<i class="fas fa-arrow-down text-danger mr-3"></i> 46,53%--}}
                            {{--</td>--}}
                        {{--</tr>--}}
                        {{--</tbody>--}}
                    {{--</table>--}}
                {{--</div>--}}
            {{--</div>--}}
        {{--</div>--}}
        {{--<div class="col-xl-4">--}}
            {{--<div class="card shadow">--}}
                {{--<div class="card-header border-0">--}}
                    {{--<div class="row align-items-center">--}}
                        {{--<div class="col">--}}
                            {{--<h3 class="mb-0">Social traffic</h3>--}}
                        {{--</div>--}}
                        {{--<div class="col text-right">--}}
                            {{--<a href="#!" class="btn btn-sm btn-primary">See all</a>--}}
                        {{--</div>--}}
                    {{--</div>--}}
                {{--</div>--}}
                {{--<div class="table-responsive">--}}
                    {{--<!-- Projects table -->--}}
                    {{--<table class="table align-items-center table-flush">--}}
                        {{--<thead class="thead-light">--}}
                        {{--<tr>--}}
                            {{--<th scope="col">Referral</th>--}}
                            {{--<th scope="col">Visitors</th>--}}
                            {{--<th scope="col"></th>--}}
                        {{--</tr>--}}
                        {{--</thead>--}}
                        {{--<tbody>--}}
                        {{--<tr>--}}
                            {{--<th scope="row">--}}
                                {{--Facebook--}}
                            {{--</th>--}}
                            {{--<td>--}}
                                {{--1,480--}}
                            {{--</td>--}}
                            {{--<td>--}}
                                {{--<div class="d-flex align-items-center">--}}
                                    {{--<span class="mr-2">60%</span>--}}
                                    {{--<div>--}}
                                        {{--<div class="progress">--}}
                                            {{--<div class="progress-bar bg-gradient-danger" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 60%;"></div>--}}
                                        {{--</div>--}}
                                    {{--</div>--}}
                                {{--</div>--}}
                            {{--</td>--}}
                        {{--</tr>--}}
                        {{--<tr>--}}
                            {{--<th scope="row">--}}
                                {{--Facebook--}}
                            {{--</th>--}}
                            {{--<td>--}}
                                {{--5,480--}}
                            {{--</td>--}}
                            {{--<td>--}}
                                {{--<div class="d-flex align-items-center">--}}
                                    {{--<span class="mr-2">70%</span>--}}
                                    {{--<div>--}}
                                        {{--<div class="progress">--}}
                                            {{--<div class="progress-bar bg-gradient-success" role="progressbar" aria-valuenow="70" aria-valuemin="0" aria-valuemax="100" style="width: 70%;"></div>--}}
                                        {{--</div>--}}
                                    {{--</div>--}}
                                {{--</div>--}}
                            {{--</td>--}}
                        {{--</tr>--}}
                        {{--<tr>--}}
                            {{--<th scope="row">--}}
                                {{--Google--}}
                            {{--</th>--}}
                            {{--<td>--}}
                                {{--4,807--}}
                            {{--</td>--}}
                            {{--<td>--}}
                                {{--<div class="d-flex align-items-center">--}}
                                    {{--<span class="mr-2">80%</span>--}}
                                    {{--<div>--}}
                                        {{--<div class="progress">--}}
                                            {{--<div class="progress-bar bg-gradient-primary" role="progressbar" aria-valuenow="80" aria-valuemin="0" aria-valuemax="100" style="width: 80%;"></div>--}}
                                        {{--</div>--}}
                                    {{--</div>--}}
                                {{--</div>--}}
                            {{--</td>--}}
                        {{--</tr>--}}
                        {{--<tr>--}}
                            {{--<th scope="row">--}}
                                {{--Instagram--}}
                            {{--</th>--}}
                            {{--<td>--}}
                                {{--3,678--}}
                            {{--</td>--}}
                            {{--<td>--}}
                                {{--<div class="d-flex align-items-center">--}}
                                    {{--<span class="mr-2">75%</span>--}}
                                    {{--<div>--}}
                                        {{--<div class="progress">--}}
                                            {{--<div class="progress-bar bg-gradient-info" role="progressbar" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100" style="width: 75%;"></div>--}}
                                        {{--</div>--}}
                                    {{--</div>--}}
                                {{--</div>--}}
                            {{--</td>--}}
                        {{--</tr>--}}
                        {{--<tr>--}}
                            {{--<th scope="row">--}}
                                {{--twitter--}}
                            {{--</th>--}}
                            {{--<td>--}}
                                {{--2,645--}}
                            {{--</td>--}}
                            {{--<td>--}}
                                {{--<div class="d-flex align-items-center">--}}
                                    {{--<span class="mr-2">30%</span>--}}
                                    {{--<div>--}}
                                        {{--<div class="progress">--}}
                                            {{--<div class="progress-bar bg-gradient-warning" role="progressbar" aria-valuenow="30" aria-valuemin="0" aria-valuemax="100" style="width: 30%;"></div>--}}
                                        {{--</div>--}}
                                    {{--</div>--}}
                                {{--</div>--}}
                            {{--</td>--}}
                        {{--</tr>--}}


                        {{--</tbody>--}}
                    {{--</table>--}}
                {{--</div>--}}
            {{--</div>--}}
        {{--</div>--}}
    {{--</div>--}}
@endsection



@section('scripts')
    <script type="text/javascript">

$(document).ready(function(){
    
//line
    var ctxL = document.getElementById("lineChart").getContext('2d');
    var myLineChart = new Chart(ctxL, {
        type: 'line',
        data: {
        labels: <?php echo json_encode($x_axies); ?>,
        datasets: [{
        label: "My First dataset",
        data: <?php echo json_encode($y_axies); ?>,
        backgroundColor: [
        'rgba(105, 0, 132, .2)',
        ],
        borderColor: [
        'rgba(200, 99, 132, .7)',
        ],
        borderWidth: 2
        }
        ]
        },
        options: {
        responsive: true
        }
    });
});
</script>
@endsection
