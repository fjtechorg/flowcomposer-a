$(document).ready(function () {
    var urlParams = new URLSearchParams(window.location.search);
    if(urlParams.has('template_installed')){
        if(urlParams.get('template_installed')==='true'){
            jQuery('#template_installed_modal').modal();
            confettiInitiate();
        }
    }

    <!-- tool tips initialize-->
    $('[data-toggle="tooltip"]').tooltip({
        'container': 'body'
    });
    <!-- datatables initialize-->
    $("span.pie").peity("pie");

    $('.latest_sub_table').dataTable({
        searching: false,
        "ordering": false,
        paging: false,
        "language": {
            "emptyTable": "You do not have any subscribers yet.",
            "info": "",
            "infoEmpty": ""
        }
    });

    $('.latest_chat_table').dataTable({
        searching: false,
        "ordering": false,
        paging: false,
        "language": {
            "emptyTable": "You did not receive any messages yet.",
            "info": "",
            "infoEmpty": ""
        }
    });

    $('.latest_broadcast_table').dataTable({
        searching: false,
        "ordering": false,
        paging: false,
        "language": {
            "emptyTable": "You did not send any broadcast yet.",
            "info": "",
            "infoEmpty": ""
        }
    });


    var ajax_url = 'includes/admin-ajax.php';
    var data = {'action': 'get_subs'};
    jQuery.post(ajax_url, data, function (response) {
        if (response !== "") {
            var response_arr = response.split("|", 3);
            jQuery('#active_subscribers').html(response_arr['0']);
            jQuery('#total_subscribers').html(response_arr['1']);
            jQuery('#unsubscribers').html(response_arr['2']);
        }

    });

    var fromdate = moment().utc().subtract(6, 'days').unix();
    var todate = moment().add(1, 'days').unix();
    chart1n2graph(fromdate, todate);
});

function chart1n2graph(fromdate, todate) {
    if (typeof chartjsdemo !== 'undefined' && typeof chartjsdemo2 !== 'undefined') {
        chartjsdemo.destroy();
        chartjsdemo2.destroy();
    }
    var ajax_url = 'includes/admin-ajax.php';
    var data = {
        'action': 'get_analytics_data',
        'date_start': fromdate,
        'date_end': todate
    };
    jQuery.post(ajax_url, data, function (res) {
        var parsedData = JSON.parse(res);
        var xtype = "time";
        var offsetValue = false;
        var xAxes = [{
            type: xtype,
            offset: offsetValue,
            time:
                {
                    format: "YYYY-MM-DD HH:mm:ss",
                    displayFormats:
                        {
                            year: 'Y MMM D',
                            month: 'Y MMM D',
                            day: 'MMM D',
                            hour: 'MMM D',
                            minute: 'MMM D',
                            second: 'MMM D',
                            millisecond: 'MMM D',
                        },
                    /*unitStepSize: 1,*/
                    unit: 'day',
                    tooltipFormat: 'Y MMM D',
                    labelFormat: 'Y MMM D'
                }
        }];
        if (parsedData.arr1.length == 1) {
            //offsetValue =true;
            xtype = "category";
            var obj = {};
            obj.x = '';
            obj.y = null;
            var obj1 = {};
            obj1.x = '';
            obj1.y = null;
            var arrDate = moment(parsedData.arr1[0].x).format('MMM D');
            parsedData.arr0.push(obj1);
            parsedData.arr0.unshift(obj);
            parsedData.arr1.push(obj1);
            parsedData.arr1.unshift(obj);
            parsedData.arr2.push(obj1);
            parsedData.arr2.unshift(obj);
            parsedData.arr3.push(obj1);
            parsedData.arr3.unshift(obj);
            parsedData.arr4.push(obj1);
            parsedData.arr4.unshift(obj);
            parsedData.arr5.push(obj1);
            parsedData.arr5.unshift(obj);
            parsedData.arr6.push(obj1);
            parsedData.arr6.unshift(obj);
            parsedData.arr7.push(obj1);
            parsedData.arr7.unshift(obj);
            xAxes = [{
                type: 'category',
                labels: ['', arrDate, '']
            }];
        }

        var options = {
            type: 'line',
            data: {
                datasets: [
                    {
                        label: 'Current Subscribers',
                        /*steppedLine: 'before',*/
                        data: parsedData.arr0,
                        backgroundColor: '#ffa500',
                        borderColor: '#ffa500',
                        fill: false
                    },
                    {
                        label: 'Reachable Subscribers',
                        /*steppedLine: 'before',*/
                        data: parsedData.arr1,
                        backgroundColor: '#e55977',
                        borderColor: '#e55977',
                        fill: false
                    },
                    {
                        label: 'Daily Active Subscribers',
                        /*steppedLine: 'before',*/
                        data: parsedData.arr2,
                        backgroundColor: '#66c9c9',
                        borderColor: '#66c9c9',
                        fill: false
                    },
                    {
                        label: 'New Subscribers',
                        /*steppedLine: 'before',*/
                        data: parsedData.arr3,
                        backgroundColor: '#04D392',
                        borderColor: '#04D392',
                        fill: false
                    }
                ]
            },
            options: {
                tooltips: {
                    mode: 'index',
                    intersect: false
                },
                hover: {
                    mode: 'point',
                    intersect: true
                },
                scales: {
                    xAxes: xAxes,
                    yAxes: [{
                        ticks: {
                            beginAtZero: true,
                            autoSkip: true,
                            maxTicksLimit: 5
                        }
                    }]
                },
                responsive: true,
                maintainAspectRatio: false,
                legend: {position: 'bottom'},
            }
        };
        var ctx = document.getElementById('chartJSContainer').getContext('2d');
        chartjsdemo = new Chart(ctx, options);
    });
}

function confettiInitiate(){
    for (i = 0; i < 200; i++) {
        // Random rotation
        var randomRotation = Math.floor(Math.random() * 360);
        // Random width & height between 0 and viewport
        var randomWidth = Math.floor(
            Math.random() *
            Math.max(document.documentElement.clientWidth, window.innerWidth || 0)
        );
        var randomHeight = Math.floor(
            Math.random() *
            Math.max(document.documentElement.clientHeight, window.innerHeight || 0)
        );

        // Random animation-delay
        var randomAnimationDelay = Math.floor(Math.random() * 10);

        // Random colors
        var colors = [
            "#0CD977",
            "#FF1C1C",
            "#FF93DE",
            "#5767ED",
            "#FFC61C",
            "#8497B0"
        ];
        var randomColor = colors[Math.floor(Math.random() * colors.length)];

        // Create confetti piece
        var confetti = document.createElement("div");
        confetti.className = "confetti";
        confetti.style.top = randomHeight + "px";
        confetti.style.left = randomWidth + "px";
        confetti.style.backgroundColor = randomColor;
        confetti.style.transform = "skew(15deg) rotate(" + randomRotation + "deg)";
        confetti.style.animationDelay = randomAnimationDelay + "s";
        document.getElementById("confetti-wrapper").appendChild(confetti);
    }
}