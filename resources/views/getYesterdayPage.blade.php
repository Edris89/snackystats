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
                    <button r id="open_or_closed_div" type="button"  style="font-size: 30px;">Snacky is Open</button>
                </div>
            </div>
        </div>
    </div>

    <br>
    
    <div class="container">
        <div class="row">
            <div class="col-lg-4">
                <a name="" id="" class="btn btn-secondary" href="/" role="button">Back</a>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="row">
            <div class="col-lg-12 text-center">
            <h2 class="mt-5">Yesterday</h2>
                    
            </div>
        </div>
    </div>


    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <canvas id="yesterdayChart"></canvas>
            </div>
        </div>
    </div>
@stop


@section("scripts")



{{-- <script>
        $(document).ready(function(){
            var token = document.querySelector('meta[name="csrf-token"]').content;
            $("#reportrange").click(function(){
                $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    type: 'get',
                    url: 'getData',
                    contentType: 'application/json',
                    data: {
                        "benaan":"benaan"
                    },
                    dataType: 'json',
                    success: function(data) {
                        // $('#frmAddTask').trigger("reset");
                        // $("#frmAddTask .close").click();
                        // window.location.reload();
                        console.log("ajax success");
                        console.log(data);
                    },
                    error: function(data) {
                       console.log("ajax error" + data);
                       console.log(data);
                    }
                });
            });
       });    
    </script> --}}



<script type="text/javascript">
$( document ).ready(function() {
    var token = document.querySelector('meta[name="csrf-token"]').content;

    var objXMLHttpRequest = new XMLHttpRequest();
    var ctx = document.getElementById('yesterdayChart').getContext('2d');
    objXMLHttpRequest.onreadystatechange = function() {
    if(objXMLHttpRequest.readyState === 4) {
        if(objXMLHttpRequest.status === 200) {
            //console.log(objXMLHttpRequest.responseText);
            var data = JSON.parse(objXMLHttpRequest.responseText);
            console.log(data);
            
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
            //console.log(all_open_or_closed);
            var open_or_closed = all_open_or_closed[all_open_or_closed.length-1];
            //console.log(open_or_closed);
            
            if(open_or_closed == 1){
                var open_or_closed_div = document.getElementById('open_or_closed_div');
                //open_or_closed_div.innerHTML += "Open";
                open_or_closed_div.className = "btn btn-success btn-lg btn-block bigButton";
                //open_or_closed_div.insertAdjacentHTML('afterend', '<button type="button" class="btn btn-success btn-lg btn-block bigButton" style="font-size: 30px;">Snacky is Open</button>');
            }

            if(open_or_closed == 0 ) {
                var open_or_closed_div = document.getElementById('open_or_closed_div');
                open_or_closed_div.className = "btn btn-danger btn-lg btn-block bigButton";
                //open_or_closed_div.insertAdjacentHTML('afterend', '<button type="button" class="btn btn-danger btn-lg btn-block bigButton" style="font-size: 30px;">Snacky is Gesloten</button>');
            }

            var chart = new Chart(ctx, {
            // The type of chart we want to create
            type: 'bar',
            
            // The data for our dataset
            data: {
                labels: better_all_time_stamps,
                datasets: [{
                    label: 'Open Or Closed',
                    // backgroundColor: 'rgba(0, 99, 132, 0.6)',
                    backgroundColor: function(context) {
                        var index = context.dataIndex;
                        var value = context.dataset.data[index];
                        return value == 0 ? 'rgb(130, 2, 2)' :  // draw negative values in red
                            index % 2 ? 'rgba(0, 99, 132, 0.6)' :    // else, alternate values in blue and green
                            'rgba(0, 99, 132, 0.6)';
                    },
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

        } else {
            alert('Error Code: ' +  objXMLHttpRequest.status);
            alert('Error Message: ' + objXMLHttpRequest.statusText);
        }
    }
    }

    objXMLHttpRequest.open('get', '/getDataYesterday', true);

    objXMLHttpRequest.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    objXMLHttpRequest.setRequestHeader('X-CSRF-TOKEN', token); 

    objXMLHttpRequest.send();
})
</script>



@stop
