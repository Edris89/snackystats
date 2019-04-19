@extends('layouts.default')


@section('content')

    <div class="container">
        <div class="row">
            <div class="col-lg-12 text-center">
                <!-- <h2 class="mt-5">Open Or Closed</h2> -->
                    <br>
            </div>
        </div>
    </div>
   <!-- Page Content -->
    <div id="container" class="container">
        <div class="row">
            <div class="col-lg-12 text-center">
                    {{-- <div id="open_or_closed_div" class="bigButton"> --}}
                    <button r id="open_or_closed_div" type="button"  style="font-size: 30px;"></button>
                </div>
            </div>
        </div>
    </div>

    <br>
    
    <div class="container">
        <div class="row text-center">
            <div class="col-lg-2">
                {{-- <a name="" id="todayButton" class="btn btn-secondary" href="/getDataToday" role="button">Today</a> --}}
                <button id="todayButton" type="button" class="btn btn-secondary">Today</button>
            </div>
            <div class="col-lg-2">
                {{-- <a name="" id="" class="btn btn-secondary" href="/yesterday" role="button">Yesterday</a> --}}
                <button id="yesterdayButton" type="button" class="btn btn-secondary">Yesterday</button>
            </div>
            <div class="col-lg-2">
                {{-- <a name="" id="" class="btn btn-secondary" href="/lastsevendays" role="button">Last 7 Days</a> --}}
                <button id="lastsevendaysButton" type="button" class="btn btn-secondary">Last 7 Days</button>
            </div>
            <div class="col-lg-2">
                <button id="last30daysButton" type="button" class="btn btn-secondary">Last 30 Days</button>
            </div>
            <div class="col-lg-2">
                <button id="thismonthButton" type="button" class="btn btn-secondary">This Month</button>
            </div>
            <div class="col-lg-2">
                <button id="lastmonthButton" type="button" class="btn btn-secondary">Last Month</button>
            </div>
        </div>
    </div>


    <div class="container">
        <div class="row">
            <div class="col-lg-12" id="charts">
                <canvas id="todayChart"></canvas>
                <canvas id="yesterdayChart"></canvas>
                <canvas id="lastsevendaysChart"></canvas>
                <canvas id="last30daysChart"></canvas>
                <canvas id="thismonthChart"></canvas>
                <canvas id="lastmonthChart"></canvas>
            </div>
        </div>
    </div>
@stop


@section("scripts")


{{-- Check If Snacky is Open or Closed --}}

<script type="text/javascript">
    $(document).ready(function(){

            
        $.ajax({
            type: 'get',
            url: 'checkOpenOrClosed',
            contentType: 'application/json',
            // data: {
            //     "benaan":"benaan"
            // },
            dataType: 'json',
            success: function(data) {
                if(data == 0){
                    console.log("Snacky is dicht");
                    var open_or_closed_div = document.getElementById('open_or_closed_div');
                    open_or_closed_div.className = "btn btn-danger btn-lg btn-block bigButton";
                    open_or_closed_div.innerHTML = "Snacky is Closed"

                }
                if(data == 1){
                    console.log("Snacky is open");
                    var open_or_closed_div = document.getElementById('open_or_closed_div');
                    open_or_closed_div.className = "btn btn-success btn-lg btn-block bigButton";
                    open_or_closed_div.innerHTML = "Snacky is Open"

                }
            },
            error: function(data) {
                // console.log("ajax error" + data);
                // console.log(data);
            }
        });
        
       

    })
</script>


{{-- Todays Data --}}
<script type="text/javascript">
    $(document).ready(function(){
      
        var ctx = document.getElementById('todayChart').getContext('2d');

        var token = document.querySelector('meta[name="csrf-token"]').content;
        $("#todayButton").click(function(){
            $("#charts").empty();
            $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: 'get',
                url: 'getDataToday',
                contentType: 'application/json',
                // data: {
                //     "benaan":"benaan"
                // },
                dataType: 'json',
                success: function(data) {
                    //console.log(data);

                    var canvas_html = '<canvas id="todayChart"></canvas>';
                    $("#charts").append(canvas_html);
                    var ctx = document.getElementById('todayChart').getContext('2d');

                    let all_timestamps      = [];
                    let all_open_or_closed  = [];
                    data.forEach(element => {
                        all_timestamps.push(element["datumpje"]);
                        all_open_or_closed.push(element["open_or_closed"]);
                    });
                    var n = all_timestamps.length;
                    let better_all_time_stamps = [];
                    all_timestamps.forEach(element => {
                        better_all_time_stamps.push(element.slice(11)) ;
                    });

                    var chart = new Chart(ctx, {
                        // The type of chart we want to create
                        type: 'line',
                        // The data for our dataset
                        data: {
                            labels: better_all_time_stamps,
                            datasets: [{
                                label: 'Open Or Closed',
                                backgroundColor: 'rgba(0, 99, 132, 0.6)',
                                borderColor: 'rgb(255, 99, 132)',
                                data: all_open_or_closed,
                                yAxisID: 'openorclosed',
                                hoverBorderColor: 'rgb(80, 81, 81',
                                hoverBorderWidth: 5
                                
                            }],
                            
                        },
                        // Configuration options go here
                        options: {

                            layout: {
                                padding: {
                                    left: 0,
                                    right: 0,
                                    top: 0,
                                    bottom: 0
                                }
                            },


                            tooltips: {
                                titleFontSize:18,
                                borderColor:'rgb(0,0,0)',
                                displayColors: true,
                                
                            },
                            
                            title: {
                                display: false,
                                text: 'Snacky Stats'
                            },

                            legend: {
                                display: true,
                                position: 'top',
                                labels: {
                                    boxWidth: 40,
                                    fontSize: 20,
                                    fontColor: 'rgb(0, 0, 0)'
                                },
                                
                            },
                            
                            
                            animation: {
                                
                                onProgress: function(animation) {
                                    all_open_or_closed.value = animation.animationObject.currentStep / animation.animationObject.numSteps;
                                }
                                },
                            scales: {
                                
                                xAxes: [{
                                    barPercentage: 1,
                                    barThickness: 45,
                                    minBarLength: 500,
                                    maxBarThickness: 100,
                                    gridLines: {
                                        offsetGridLines: true
                                    },
                                    scaleLabel: {
                                        display: true,
                                        labelString: 'Time',
                                        lineHeight: 0
                                    },
                                }],
                                yAxes: [{
                                    id: "openorclosed",
                                    ticks: {
                                        beginAtZero: true,
                                    },
                                    barPercentage: 1,
                                    minBarLength: 243,
                                    maxBarThickness: 100,
                                    scaleLabel: {
                                        display: true,
                                        labelString: 'Open or Closed',
                                        lineHeight: 0
                                    },
                                    gridLines: {
                                        offsetGridLines: false
                                    }
                                }] 
                            },
                            
                            }
                        })
                    
                    
                },
                error: function(data) {
                    // console.log("ajax error" + data);
                    // console.log(data);
                }
            });
        });
    });    
</script>



{{-- Yesterdays Data --}}
<script>
    $(document).ready(function(){
        var ctx = document.getElementById('yesterdayChart').getContext('2d');
        var token = document.querySelector('meta[name="csrf-token"]').content;
        $("#yesterdayButton").click(function(){
            // $("#todayChart").hide();
            // $("#yesterdayChart").show();
            $("#charts").empty();
            $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: 'get',
                url: 'getDataYesterday',
                contentType: 'application/json',
                // data: {
                //     "benaan":"benaan"
                // },
                dataType: 'json',
                success: function(data) {
                    //console.log(data);
                    var canvas_html = '<canvas id="yesterdayChart"></canvas>';
                    $("#charts").append(canvas_html)
                    var ctx = document.getElementById('yesterdayChart').getContext('2d');

                    let all_timestamps      = [];
                    let all_open_or_closed  = [];
                    data.forEach(element => {
                        all_timestamps.push(element["datumpje"]);
                        all_open_or_closed.push(element["open_or_closed"]);
                    });

                    
                    //console.log(all_timestamps);
                    var n = all_timestamps.length;
                    //console.log(n);

                    let better_all_time_stamps = [];
                    
                    all_timestamps.forEach(element => {
                        // console.log(element.slice(11));
                        better_all_time_stamps.push(element.slice(11)) ;
                    });
                    var chart = new Chart(ctx, {
                        // The type of chart we want to create
                        type: 'line',
                        
                        // The data for our dataset
                        data: {
                            labels: all_timestamps,
                            datasets: [{
                                label: 'Open Or Closed',
                                backgroundColor: 'rgba(0, 99, 132, 0.6)',
                                // backgroundColor: function(context) {
                                //     var index = context.dataIndex;
                                //     var value = context.dataset.data[index];
                                //     return value == 0 ? 'rgb(130, 2, 2)' :  // draw negative values in red
                                //         index % 2 ? 'rgba(0, 99, 132, 0.6)' :    // else, alternate values in blue and green
                                //         'rgba(0, 99, 132, 0.6)';
                                // },
                                borderColor: 'rgb(255, 99, 132)',
                                data: all_open_or_closed,
                                yAxisID: 'openorclosed',
                                hoverBorderColor: 'rgb(80, 81, 81',
                                hoverBorderWidth: 5
                                
                            }],
                            
                        },


                        // Configuration options go here
                        options: {

                            layout: {
                                padding: {
                                    left: 0,
                                    right: 0,
                                    top: 0,
                                    bottom: 0
                                }
                            },


                            tooltips: {
                                titleFontSize:18,
                                borderColor:'rgb(0,0,0)',
                                displayColors: true,
                                
                            },
                            
                            title: {
                                display: false,
                                text: 'Snacky Stats'
                            },

                            legend: {
                                display: true,
                                position: 'top',
                                labels: {
                                    boxWidth: 40,
                                    fontSize: 20,
                                    fontColor: 'rgb(0, 0, 0)'
                                },
                                
                            },
                            
                            
                            animation: {
                                
                                onProgress: function(animation) {
                                    all_open_or_closed.value = animation.animationObject.currentStep / animation.animationObject.numSteps;
                                }
                                },
                            scales: {
                                
                                xAxes: [{
                                    barPercentage: 1,
                                    barThickness: 45,
                                    minBarLength: 500,
                                    maxBarThickness: 100,
                                    gridLines: {
                                        offsetGridLines: true
                                    },
                                    scaleLabel: {
                                        display: true,
                                        labelString: 'Time',
                                        lineHeight: 0
                                    },
                                }],
                                yAxes: [{
                                    id: "openorclosed",
                                    ticks: {
                                        beginAtZero: true,
                                    },
                                    barPercentage: 1,
                                    minBarLength: 243,
                                    maxBarThickness: 100,
                                    scaleLabel: {
                                        display: true,
                                        labelString: 'Open or Closed',
                                        lineHeight: 0
                                    },
                                    gridLines: {
                                        offsetGridLines: false
                                    }
                                }] 
                            },
                            
                            }
                        })
                    
                    
                },
                error: function(data) {
                    console.log("ajax error" + data);
                    console.log(data);
                }
            });
        });
    });    
</script>



{{-- Last 7 Days Data --}}
<script>
    $(document).ready(function(){
        var ctx = document.getElementById('lastsevendaysChart').getContext('2d');
        var token = document.querySelector('meta[name="csrf-token"]').content;
        $("#lastsevendaysButton").click(function(){
            // $("#todayChart").hide();
            // $("#yesterdayChart").show();
            $("#charts").empty();
            $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: 'get',
                url: 'lastsevendaysData',
                contentType: 'application/json',
                // data: {
                //     "benaan":"benaan"
                // },
                dataType: 'json',
                success: function(data) {
                    //console.log(data);
                    
                    var canvas_html = '<canvas id="lastsevendaysChart"></canvas>';
                    $("#charts").append(canvas_html);
                    var ctx = document.getElementById('lastsevendaysChart').getContext('2d');

                    let all_timestamps      = [];
                    let all_open_or_closed  = [];
                    data.forEach(element => {
                        all_timestamps.push(element["datumpje"]);
                        all_open_or_closed.push(element["open_or_closed"]);
                    });
                    
                    //console.log(all_timestamps);
                    var n = all_timestamps.length;
                    //console.log(n);

                    let better_all_time_stamps = [];
                    
                    all_timestamps.forEach(element => {
                        // console.log(element.slice(11));
                        better_all_time_stamps.push(element.slice(11)) ;
                    });

                    var chart = new Chart(ctx, {
                        // The type of chart we want to create
                        type: 'line',
                        
                        // The data for our dataset
                        data: {
                            labels: all_timestamps,
                            datasets: [{
                                label: 'Open Or Closed',
                                backgroundColor: 'rgba(0, 99, 132, 0.6)',
                                // backgroundColor: function(context) {
                                //     var index = context.dataIndex;
                                //     var value = context.dataset.data[index];
                                //     return value == 0 ? 'rgb(130, 2, 2)' :  // draw negative values in red
                                //         index % 2 ? 'rgba(0, 99, 132, 0.6)' :    // else, alternate values in blue and green
                                //         'rgba(0, 99, 132, 0.6)';
                                // },
                                borderColor: 'rgb(255, 99, 132)',
                                data: all_open_or_closed,
                                yAxisID: 'openorclosed',
                                hoverBorderColor: 'rgb(80, 81, 81',
                                hoverBorderWidth: 5
                                
                            }],
                            
                        },


                        // Configuration options go here
                        options: {

                            layout: {
                                padding: {
                                    left: 0,
                                    right: 0,
                                    top: 0,
                                    bottom: 0
                                }
                            },


                            tooltips: {
                                titleFontSize:18,
                                borderColor:'rgb(0,0,0)',
                                displayColors: true,
                                
                            },
                            
                            title: {
                                display: false,
                                text: 'Snacky Stats'
                            },

                            legend: {
                                display: true,
                                position: 'top',
                                labels: {
                                    boxWidth: 40,
                                    fontSize: 20,
                                    fontColor: 'rgb(0, 0, 0)'
                                },
                                
                            },
                            
                            
                            animation: {
                                
                                onProgress: function(animation) {
                                    all_open_or_closed.value = animation.animationObject.currentStep / animation.animationObject.numSteps;
                                }
                                },
                            scales: {
                                
                                xAxes: [{
                                    barPercentage: 1,
                                    barThickness: 45,
                                    minBarLength: 500,
                                    maxBarThickness: 100,
                                    gridLines: {
                                        offsetGridLines: true
                                    },
                                    scaleLabel: {
                                        display: true,
                                        labelString: 'Time',
                                        lineHeight: 0
                                    },
                                }],
                                yAxes: [{
                                    id: "openorclosed",
                                    ticks: {
                                        beginAtZero: true,
                                    },
                                    barPercentage: 1,
                                    minBarLength: 243,
                                    maxBarThickness: 100,
                                    scaleLabel: {
                                        display: true,
                                        labelString: 'Open or Closed',
                                        lineHeight: 0
                                    },
                                    gridLines: {
                                        offsetGridLines: false
                                    }
                                }] 
                            },
                            
                            }
                        })
                    
                    
                },
                error: function(data) {
                    console.log("ajax error" + data);
                    console.log(data);
                }
            });
        });
    });    
</script>



{{-- Last 30 Days Data --}}
<script>
    $(document).ready(function(){
        var ctx = document.getElementById('last30daysChart').getContext('2d');
        var token = document.querySelector('meta[name="csrf-token"]').content;
        $("#last30daysButton").click(function(){
            $("#charts").empty();
            $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: 'get',
                url: 'last30days',
                contentType: 'application/json',
                // data: {
                //     "benaan":"benaan"
                // },
                dataType: 'json',
                success: function(data) {
                    //console.log(data);
                    var canvas_html = '<canvas id="last30daysChart"></canvas>';
                    $("#charts").append(canvas_html);
                    var ctx = document.getElementById('last30daysChart').getContext('2d');

                    let all_timestamps      = [];
                    let all_open_or_closed  = [];
                    data.forEach(element => {
                        all_timestamps.push(element["datumpje"]);
                        all_open_or_closed.push(element["open_or_closed"]);
                    });

                    
                    //console.log(all_timestamps);
                    var n = all_timestamps.length;
                    //console.log(n);

                    let better_all_time_stamps = [];
                    
                    all_timestamps.forEach(element => {
                        // console.log(element.slice(11));
                        better_all_time_stamps.push(element.slice(11)) ;
                    });

                    var chart = new Chart(ctx, {
                        // The type of chart we want to create
                        type: 'line',
                        
                        // The data for our dataset
                        data: {
                            labels: all_timestamps,
                            datasets: [{
                                label: 'Open Or Closed',
                                backgroundColor: 'rgba(0, 99, 132, 0.6)',
                                // backgroundColor: function(context) {
                                //     var index = context.dataIndex;
                                //     var value = context.dataset.data[index];
                                //     return value == 0 ? 'rgb(130, 2, 2)' :  // draw negative values in red
                                //         index % 2 ? 'rgba(0, 99, 132, 0.6)' :    // else, alternate values in blue and green
                                //         'rgba(0, 99, 132, 0.6)';
                                // },
                                borderColor: 'rgb(255, 99, 132)',
                                data: all_open_or_closed,
                                yAxisID: 'openorclosed',
                                hoverBorderColor: 'rgb(80, 81, 81',
                                hoverBorderWidth: 5
                                
                            }],
                            
                        },


                        // Configuration options go here
                        options: {

                            layout: {
                                padding: {
                                    left: 0,
                                    right: 0,
                                    top: 0,
                                    bottom: 0
                                }
                            },


                            tooltips: {
                                titleFontSize:18,
                                borderColor:'rgb(0,0,0)',
                                displayColors: true,
                                
                            },
                            
                            title: {
                                display: false,
                                text: 'Snacky Stats'
                            },

                            legend: {
                                display: true,
                                position: 'top',
                                labels: {
                                    boxWidth: 40,
                                    fontSize: 20,
                                    fontColor: 'rgb(0, 0, 0)'
                                },
                                
                            },
                            
                            
                            animation: {
                                
                                onProgress: function(animation) {
                                    all_open_or_closed.value = animation.animationObject.currentStep / animation.animationObject.numSteps;
                                }
                                },
                            scales: {
                                
                                xAxes: [{
                                    barPercentage: 1,
                                    barThickness: 45,
                                    minBarLength: 500,
                                    maxBarThickness: 100,
                                    gridLines: {
                                        offsetGridLines: true
                                    },
                                    scaleLabel: {
                                        display: true,
                                        labelString: 'Time',
                                        lineHeight: 0
                                    },
                                }],
                                yAxes: [{
                                    id: "openorclosed",
                                    ticks: {
                                        beginAtZero: true,
                                    },
                                    barPercentage: 1,
                                    minBarLength: 243,
                                    maxBarThickness: 100,
                                    scaleLabel: {
                                        display: true,
                                        labelString: 'Open or Closed',
                                        lineHeight: 0
                                    },
                                    gridLines: {
                                        offsetGridLines: false
                                    }
                                }] 
                            },
                            
                            }
                        })
                    
                    
                },
                error: function(data) {
                    // console.log("ajax error" + data);
                    // console.log(data);
                }
            });
        });
    });    
</script>



{{-- Thismonth Data --}}
<script>
    $(document).ready(function(){
        var ctx = document.getElementById('thismonthChart').getContext('2d');
        var token = document.querySelector('meta[name="csrf-token"]').content;
        $("#thismonthButton").click(function(){
            $("#charts").empty();
            $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: 'get',
                url: 'thismonth',
                contentType: 'application/json',
                // data: {
                //     "benaan":"benaan"
                // },
                dataType: 'json',
                success: function(data) {
                    //console.log(data);
                    var canvas_html = '<canvas id="thismonthChart"></canvas>';
                    $("#charts").append(canvas_html);
                    var ctx = document.getElementById('thismonthChart').getContext('2d');

                    let all_timestamps      = [];
                    let all_open_or_closed  = [];
                    data.forEach(element => {
                        all_timestamps.push(element["datumpje"]);
                        all_open_or_closed.push(element["open_or_closed"]);
                    });

                    
                    //console.log(all_timestamps);
                    var n = all_timestamps.length;
                    //console.log(n);

                    let better_all_time_stamps = [];
                    
                    all_timestamps.forEach(element => {
                        //console.log(element.slice(0,10));
                        better_all_time_stamps.push(element.slice(0,10)) ;
                    });
                    

                    var chart = new Chart(ctx, {
                        // The type of chart we want to create
                        type: 'line',
                        
                        // The data for our dataset
                        data: {
                            labels: better_all_time_stamps,
                            datasets: [{
                                label: 'Open Or Closed',
                                backgroundColor: 'rgba(0, 99, 132, 0.6)',
                                // backgroundColor: function(context) {
                                //     var index = context.dataIndex;
                                //     var value = context.dataset.data[index];
                                //     return value == 0 ? 'rgb(130, 2, 2)' :  // draw negative values in red
                                //         index % 2 ? 'rgba(0, 99, 132, 0.6)' :    // else, alternate values in blue and green
                                //         'rgba(0, 99, 132, 0.6)';
                                // },
                                borderColor: 'rgb(255, 99, 132)',
                                data: all_open_or_closed,
                                yAxisID: 'openorclosed',
                                hoverBorderColor: 'rgb(80, 81, 81',
                                hoverBorderWidth: 5
                                
                            }],
                            
                        },


                        // Configuration options go here
                        options: {

                            layout: {
                                padding: {
                                    left: 0,
                                    right: 0,
                                    top: 0,
                                    bottom: 0
                                }
                            },


                            tooltips: {
                                titleFontSize:18,
                                borderColor:'rgb(0,0,0)',
                                displayColors: true,
                                
                            },
                            
                            title: {
                                display: false,
                                text: 'Snacky Stats'
                            },

                            legend: {
                                display: true,
                                position: 'top',
                                labels: {
                                    boxWidth: 40,
                                    fontSize: 20,
                                    fontColor: 'rgb(0, 0, 0)'
                                },
                                
                            },
                            
                            
                            animation: {
                                
                                onProgress: function(animation) {
                                    all_open_or_closed.value = animation.animationObject.currentStep / animation.animationObject.numSteps;
                                }
                                },
                            scales: {
                                
                                xAxes: [{
                                    barPercentage: 1,
                                    barThickness: 45,
                                    minBarLength: 500,
                                    maxBarThickness: 100,
                                    gridLines: {
                                        offsetGridLines: true
                                    },
                                    scaleLabel: {
                                        display: true,
                                        labelString: 'Time',
                                        lineHeight: 0
                                    },
                                }],
                                yAxes: [{
                                    id: "openorclosed",
                                    ticks: {
                                        beginAtZero: true,
                                    },
                                    barPercentage: 1,
                                    minBarLength: 243,
                                    maxBarThickness: 100,
                                    scaleLabel: {
                                        display: true,
                                        labelString: 'Open or Closed',
                                        lineHeight: 0
                                    },
                                    gridLines: {
                                        offsetGridLines: false
                                    }
                                }] 
                            },
                            
                            }
                        })
                    
                    
                },
                error: function(data) {
                    // console.log("ajax error" + data);
                    // console.log(data);
                }
            });
        });
    });    
</script>



{{-- Lastmonth Data --}}
<script>
    $(document).ready(function(){
        var ctx = document.getElementById('lastmonthChart').getContext('2d');
        var token = document.querySelector('meta[name="csrf-token"]').content;
        $("#lastmonthButton").click(function(){
            $("#charts").empty();
            $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: 'get',
                url: 'thismonth',
                contentType: 'application/json',
                // data: {
                //     "benaan":"benaan"
                // },
                dataType: 'json',
                success: function(data) {
                    //console.log(data);
                    var canvas_html = '<canvas id="lastmonthChart"></canvas>';
                    $("#charts").append(canvas_html);
                    var ctx = document.getElementById('lastmonthChart').getContext('2d');

                    let all_timestamps      = [];
                    let all_open_or_closed  = [];
                    data.forEach(element => {
                        all_timestamps.push(element["datumpje"]);
                        all_open_or_closed.push(element["open_or_closed"]);
                    });

                    
                    //console.log(all_timestamps);
                    var n = all_timestamps.length;
                    //console.log(n);

                    let better_all_time_stamps = [];
                    
                    all_timestamps.forEach(element => {
                        // console.log(element.slice(11));
                        better_all_time_stamps.push(element.slice(0,10)) ;
                    });

                    var chart = new Chart(ctx, {
                        // The type of chart we want to create
                        type: 'line',
                        
                        // The data for our dataset
                        data: {
                            labels: better_all_time_stamps,
                            datasets: [{
                                label: 'Open Or Closed',
                                backgroundColor: 'rgba(0, 99, 132, 0.6)',
                                // backgroundColor: function(context) {
                                //     var index = context.dataIndex;
                                //     var value = context.dataset.data[index];
                                //     return value == 0 ? 'rgb(130, 2, 2)' :  // draw negative values in red
                                //         index % 2 ? 'rgba(0, 99, 132, 0.6)' :    // else, alternate values in blue and green
                                //         'rgba(0, 99, 132, 0.6)';
                                //},
                                borderColor: 'rgb(255, 99, 132)',
                                data: all_open_or_closed,
                                yAxisID: 'openorclosed',
                                hoverBorderColor: 'rgb(80, 81, 81',
                                hoverBorderWidth: 5
                                
                            }],
                            
                        },


                        // Configuration options go here
                        options: {

                            layout: {
                                padding: {
                                    left: 0,
                                    right: 0,
                                    top: 0,
                                    bottom: 0
                                }
                            },


                            tooltips: {
                                titleFontSize:18,
                                borderColor:'rgb(0,0,0)',
                                displayColors: true,
                                
                            },
                            
                            title: {
                                display: false,
                                text: 'Snacky Stats'
                            },

                            legend: {
                                display: true,
                                position: 'top',
                                labels: {
                                    boxWidth: 40,
                                    fontSize: 20,
                                    fontColor: 'rgb(0, 0, 0)'
                                },
                                
                            },
                            
                            
                            animation: {
                                
                                onProgress: function(animation) {
                                    all_open_or_closed.value = animation.animationObject.currentStep / animation.animationObject.numSteps;
                                }
                                },
                            scales: {
                                
                                xAxes: [{
                                    barPercentage: 1,
                                    barThickness: 45,
                                    minBarLength: 0,
                                    maxBarThickness: 100,
                                    gridLines: {
                                        offsetGridLines: true
                                    },
                                    scaleLabel: {
                                        display: true,
                                        labelString: 'Time',
                                        lineHeight: 0
                                    },
                                }],
                                yAxes: [{
                                    id: "openorclosed",
                                    ticks: {
                                        beginAtZero: true,
                                    },
                                    barPercentage: 1,
                                    minBarLength: 0,
                                    maxBarThickness: 100,
                                    scaleLabel: {
                                        display: true,
                                        labelString: 'Open or Closed',
                                        lineHeight: 0
                                    },
                                    gridLines: {
                                        offsetGridLines: false
                                    }
                                }] 
                            },
                            
                            }
                        })
                    
                    
                },
                error: function(data) {
                    // console.log("ajax error" + data);
                    // console.log(data);
                }
            });
        });
    });    
</script>


@stop
