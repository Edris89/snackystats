var objXMLHttpRequest = new XMLHttpRequest();
        var ctx = document.getElementById('myChart').getContext('2d');
        objXMLHttpRequest.onreadystatechange = function() {
        if(objXMLHttpRequest.readyState === 4) {
            if(objXMLHttpRequest.status === 200) {
                //console.log(objXMLHttpRequest.responseText);
                var data = JSON.parse(objXMLHttpRequest.responseText);
                //console.log(data);
                let all_timestamps      = [];
                let all_open_or_closed  = [];
                data.forEach(element => {
                    all_timestamps.push(element["timestamp"]);
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
                
                if(open_or_closed == 1){
                    var open_or_closed_div = document.getElementById('open_or_closed_div');
                    //open_or_closed_div.innerHTML += "Open";
                    open_or_closed_div.insertAdjacentHTML('afterend', '<button type="button" class="btn btn-success btn-lg btn-block bigButton" style="font-size: 30px;">Snacky is Open</button>');
                }

                if(open_or_closed == 0 ) {
                    var open_or_closed_div = document.getElementById('open_or_closed_div');
                    //open_or_closed_div.innerHTML += "Closed";
                    open_or_closed_div.insertAdjacentHTML('afterend', '<button type="button" class="btn btn-danger btn-lg btn-block bigButton" style="font-size: 30px;">Snacky is Gesloten</button>');

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
        objXMLHttpRequest.open('GET', 'ajaxfile.php');
        objXMLHttpRequest.send();