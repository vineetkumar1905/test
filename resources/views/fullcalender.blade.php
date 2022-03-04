<!DOCTYPE html>
<html>
<head>
    <title>Event calender</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>  
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.9.0/fullcalendar.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.9.0/fullcalendar.js"></script>  
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" />
</head>
<body>
  
<div class="container">
    <h1>Event FullCalender</h1>
    <div id='calendar'></div>
</div>
   
<script>
$(document).ready(function () {
   
var SITEURL = "{{ url('/') }}";
  
$.ajaxSetup({
    headers: {
    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});
  
var calendar = $('#calendar').fullCalendar({
                    editable: true,
                    events: SITEURL + "/fullcalender",                                       
                    displayEventTime: false,
                    editable: true,
                    eventRender: function (event, element, view) {
                        //console.log(view.type);
                        if (event.allDay === 'true') {
                                event.allDay = true;
                        } else {
                                event.allDay = false;
                        }

                              element.bind('dblclick', function() {
                                 //alert('double click!');
                              });


                        if (view.type == 'month') {
                            element.find(".fc-content").prepend("<span class='closeon'>X</span>");                                   
                        } 
                        element.find(".closeon").on('click', function() {                
                            //console.log(event);
                            var deleteMsg = confirm("Do you really want to delete?");
                            if (deleteMsg) {
                                $.ajax({
                                    type: "POST",
                                    url: SITEURL + '/fullcalenderAjax',
                                    data: {
                                            id: event.id,
                                            type: 'delete'
                                    },
                                    success: function (response) {
                                        calendar.fullCalendar('removeEvents', event.id);
                                        displayMessage("Event Deleted Successfully");
                                    }
                                });
                            }
                            
                        });

                        element.find(".fc-content").on('dblclick', function() {
                            var current_title = event.title;
                            var title = prompt('Title',current_title);
                            if (title) {
                            var start = event.start.toISOString().slice(0, 10);                   
                            var end = event.end.toISOString().slice(0, 10);   
                                $.ajax({
                                    url: SITEURL + '/fullcalenderAjax',
                                    data: {
                                        title: title,
                                        start: start,
                                        end: end,
                                        id: event.id,
                                        type: 'update'
                                    },
                                    type: "POST",
                                    success: function (response) {                            
                                       calendar.fullCalendar('addEventSource', event);
                                        calendar.fullCalendar('refetchEvents');  
                                        displayMessage("Event Updated Successfully");             
                                    }
                                });
                            }
                        });
                    },
                    selectable: true,
                    selectHelper: true,
                    select: function (start, end, allDay) {
                        var title = prompt('Event Title:');
                        if (title) {
                            var start = $.fullCalendar.formatDate(start, "Y-MM-DD");
                            var end = $.fullCalendar.formatDate(end, "Y-MM-DD");
                            $.ajax({
                                url: SITEURL + "/fullcalenderAjax",
                                data: {
                                    title: title,
                                    start: start,
                                    end: end,
                                    type: 'add'
                                },
                                type: "POST",
                                success: function (data) {
                                    displayMessage("Event Created Successfully");
  
                                    calendar.fullCalendar('renderEvent',
                                        {
                                            id: data.id,
                                            title: title,
                                            start: start,
                                            end: end,
                                            //allDay: allDay
                                        },true);
  
                                    calendar.fullCalendar('unselect');
                                }
                            });
                        }
                    },
                    eventDrop: function (event, delta) {                        
                        var start = event.start.toISOString().slice(0, 10);                   
                        var end = event.end.toISOString().slice(0, 10);   
                        $.ajax({
                            url: SITEURL + '/fullcalenderAjax',
                            data: {
                                title: event.title,
                                start: start,
                                end: end,
                                id: event.id,
                                type: 'move'
                            },
                            type: "POST",
                            success: function (response) {
                                displayMessage("Event Updated Successfully");
                            }
                        });
                    },
                });

 
});
 
function displayMessage(message) {
    toastr.success(message, 'Event');
} 
  
</script>
  
</body>
</html>