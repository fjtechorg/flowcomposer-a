
$(document).ready(function(){

    flatpickrz = flatpickr("#date_start", {
        mode: "range",
        minDate: "",
        maxDate: "today",
        dateFormat: "Y-M-d",
        defaultDate: [moment().utc().subtract(6, 'days').format('Y-M-D'), moment().utc().format('Y-M-D')],
        onChange: function(selectedDates, dateStr, instance) {
            var dates = dateStr.split(" to ");
            if(dates[0]==dates[1]){
                $("#date_start").val(dates[0]);
            }
            var fromdate = moment(dates[0]+" 8:00:00").unix();
            var todate = moment(dates[1]+" 8:00:00").add(1, 'days').unix();
            if(!(isNaN(todate)) && !(isNaN(fromdate))) {
                chart1n2graph(fromdate, todate);
            }
        }
    });
    var fromdate = moment().utc().subtract(6, 'days').unix();
    var todate = moment().add(1, 'days').unix();
    chart1n2graph(fromdate,todate);
    $('#date_week').on("click", function(){
        //when chosen end_date, start can go just up until that point
        flatpickrz.setDate([moment().utc().subtract(6, 'days').format('Y-M-D'), moment().utc().format('Y-M-D')],'Y-M-D');
        var dateStr2 = $('#date_start').val();
        var dates = dateStr2.split(" to ");
        var fromdate = moment(dates[0]+" 8:00:00").unix();
        var todate = moment(dates[1]+" 8:00:00").add(1, 'days').unix();
        if(!(isNaN(todate)) && !(isNaN(fromdate))) {
            chart1n2graph(fromdate, todate);
        }
    });
    $('#date_month').on("click", function(){
        flatpickrz.setDate([moment().utc().subtract(30, 'days').format('Y-M-D'), moment().utc().format('Y-M-D')],'Y-M-D');
        var dateStr2 = $('#date_start').val();
        var dates = dateStr2.split(" to ");
        var fromdate = moment(dates[0]+" 8:00:00").unix();
        var todate = moment(dates[1]+" 8:00:00").add(1, 'days').unix();
        if(!(isNaN(todate)) && !(isNaN(fromdate))) {
            chart1n2graph(fromdate, todate);
        }
    });
    $('#date_quarter').on("click", function(){
        flatpickrz.setDate([moment().utc().subtract(90, 'days').format('Y-M-D'), moment().utc().format('Y-M-D')],'Y-M-D');
        var dateStr2 = $('#date_start').val();
        var dates = dateStr2.split(" to ");
        var fromdate = moment(dates[0]+" 8:00:00").unix();
        var todate = moment(dates[1]+" 8:00:00").add(1, 'days').unix();
        if(!(isNaN(todate)) && !(isNaN(fromdate))) {
            chart1n2graph(fromdate, todate);
        }
    });
});
function chart1n2graph(fromdate,todate){
    if (typeof chartjsdemo !== 'undefined' && typeof chartjsdemo2 !== 'undefined') {
        chartjsdemo.destroy();
        chartjsdemo2.destroy();
    }
    var ajax_url='includes/admin-ajax.php';
    var data = {
        'action': 'get_analytics_data',
        'date_start': fromdate,
        'date_end': todate
    };
    jQuery.post(ajax_url, data, function(res){
        var parsedData = JSON.parse(res);
        var xtype="time";
        let offsetValue = false;
        let xAxes = [{
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
                            millisecond:  'MMM D',
                        },
                    /*unitStepSize: 1,*/
                    unit: 'day',
                    tooltipFormat: 'Y MMM D',
                    labelFormat: 'Y MMM D'
                },
        }];
        if(parsedData.arr1.length == 1){
            //offsetValue =true;
            xtype="category";
            var obj = {};
            obj.x='';
            obj.y=null;
            var obj1 = {};
            obj1.x='';
            obj1.y=null;
            let arrDate = moment(parsedData.arr1[0].x).format('MMM D');
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

        var options2 = {
            type: 'line',
            data: {
                datasets: [
                    {
                        label: 'Spam',
                        /*steppedLine: 'before',*/
                        data: parsedData.arr4,
                        backgroundColor: '#e55977',
                        borderColor: '#e55977',
                        fill: false
                    },
                    {
                        label: 'Inappropriate',
                        /*steppedLine: 'before',*/
                        data: parsedData.arr5,
                        backgroundColor: '#66c9c9',
                        borderColor: '#66c9c9',
                        fill: false
                    },
                    {
                        label: 'Other',
                        /*steppedLine: 'before',*/
                        data: parsedData.arr6,
                        backgroundColor: '#04D392',
                        borderColor: '#04D392',
                        fill: false
                    },
                    {
                        label: 'Blocked Conversations Unique',
                        /*steppedLine: 'before',*/
                        data: parsedData.arr7,
                        backgroundColor: 'red',
                        borderColor: 'red',
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
                            min:0,
                            beginAtZero: true,
                            autoSkip: true,
                            maxTicksLimit: 5,
                            callback: function(value, index, values) {
                                if (Math.floor(value) === value) {
                                    return value;
                                }
                            }
                        }
                    }]
                },
                responsive: true,
                maintainAspectRatio: false,
                legend: {position: 'bottom'},
            }
        };

        var ctx2 = document.getElementById('chartJSContainer2').getContext('2d');
        chartjsdemo2 = new Chart(ctx2, options2);
    });
}