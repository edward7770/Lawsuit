getSessionData();
var defaultEvents=[];
var setSessionDetail=[];
function getSessionData()
{
	$.ajax({
		type:"POST",
		url: "getSessionDetailDasbhoard.php",
		success: function (data) {
			sessionData=data;
			////console.log(data);
			/////alert(JSON.stringify(data));
			const jsonObject = JSON.parse(data);
			if(jsonObject.status)
			{
				data_array = jsonObject['data'];
				var i=1;
				jQuery.each(data_array, function() {
					/////var dateStr = this.sessionDate+' '+this.sessionTime // Your date string
					var dateStr = this.sessionDate; // Your date string
				  // Parse the date string using moment.js
					  var date = moment(dateStr, "YYYY-MM-DD HH:mm:ss");

					  // Format the date to the desired format
					var formattedDate = date.format("ddd MMM DD YYYY HH:mm:ss [GMT]ZZ (zz)");
					item = {};
					if(this.tableData=="session")
					{
						item['title']="Session "+i;
						item['start']=formattedDate;
						item['className']='bg-success';
						
					}
					else 
					{
						item['title']="Task "+i;
						item['start']=formattedDate;
						item['className']='bg-danger';
						
					}
					defaultEvents.push(item);
					
					itemSDetail = {};
					if(this.tableData=="session")
						itemSDetail['name']="Session "+i;
					else
						itemSDetail['name']="Task "+i;
					itemSDetail['id']=this.id;
					itemSDetail['lsDId']=this.lsDId;
					itemSDetail['lsMId']=this.lsMId;
					itemSDetail['data']=this.tableData;
					setSessionDetail.push(itemSDetail);
					i++;
				});
			}
		},
		complete: function (data) {
			//////alert(JSON.stringify(defaultEvents));
			//////alert(JSON.stringify(setSessionDetail));
			setCalender();
		}
	});
}




$(document).ajaxStart(function() {
	$("#ajax_loader").show();
})

.ajaxStop(function() {
	$("#ajax_loader").hide();
	$('#calendar').fullCalendar('option', 'contentHeight', 'auto');
	
});

function setCalender()
{
!function($) {
    "use strict";
	var CalendarApp = function() {
        this.$body = $("body")
        this.$calendar = $('#calendar'),
        this.$event = ('#calendar-events div.calendar-events'),
        this.$categoryForm = $('#add_new_event form'),
        this.$extEvents = $('#calendar-events'),
        this.$modal = $('#my_event'),
        this.$saveCategoryBtn = $('.save-category'),
        this.$calendarObj = null
    };


    /* on drop */
    CalendarApp.prototype.onDrop = function (eventObj, date) { 
        var $this = this;
            // retrieve the dropped element's stored Event Object
            var originalEventObject = eventObj.data('eventObject');
            var $categoryClass = eventObj.attr('data-class');
            // we need to copy it, so that multiple events don't have a reference to the same object
            var copiedEventObject = $.extend({}, originalEventObject);
            // assign it the date that was reported
            copiedEventObject.start = date;
            if ($categoryClass)
                copiedEventObject['className'] = [$categoryClass];
            // render the event on the calendar
            $this.$calendar.fullCalendar('renderEvent', copiedEventObject, true);
            // is the "remove after drop" checkbox checked?
            if ($('#drop-remove').is(':checked')) {
                // if so, remove the element from the "Draggable Events" list
                eventObj.remove();
            }
    },
    /* on click on event */
    CalendarApp.prototype.onEventClick =  function (calEvent, jsEvent, view) {
        var $this = this;
            var form = $("<form></form>");
            form.append("<label>Change event name</label>");
            form.append("<div class='input-group'><input class='form-control' type=text value='" + calEvent.title + "' /><span class='input-group-append'><button type='submit' class='btn btn-success'><i class='fas fa-check'></i> Save</button></span></div>");
            $this.$modal.modal({
                backdrop: 'static'
            });
            $this.$modal.find('.delete-event').show().end().find('.save-event').hide().end().find('.modal-body').empty().prepend(form).end().find('.delete-event').unbind('click').click(function () {
                $this.$calendarObj.fullCalendar('removeEvents', function (ev) {
                    return (ev._id == calEvent._id);
                });
                $this.$modal.modal('hide');
            });
            $this.$modal.find('form').on('submit', function () {
                calEvent.title = form.find("input[type=text]").val();
                $this.$calendarObj.fullCalendar('updateEvent', calEvent);
                $this.$modal.modal('hide');
                return false;
            });
    },
    /* on select */
    CalendarApp.prototype.onSelect = function (start, end, allDay) {
        var $this = this;
            $this.$modal.modal({
                backdrop: 'static'
            });
            var form = $("<form></form>");
            form.append("<div class='event-inputs'></div>");
            form.find(".event-inputs")
                .append("<div class='form-group'><label class='control-label'>Event Name</label><input class='form-control' placeholder='Insert Event Name' type='text' name='title'/></div>")
                .append("<div class='form-group'><label class='control-label'>Category</label><select class='form-control' name='category'></select></div>")
                .find("select[name='category']")
                .append("<option value='bg-danger'>Danger</option>")
                .append("<option value='bg-success'>Success</option>")
                .append("<option value='bg-purple'>Purple</option>")
                .append("<option value='bg-primary'>Primary</option>")
                .append("<option value='bg-info'>Info</option>")
                .append("<option value='bg-warning'>Warning</option></div></div>");
            $this.$modal.find('.delete-event').hide().end().find('.save-event').show().end().find('.modal-body').empty().prepend(form).end().find('.save-event').unbind('click').click(function () {
                form.submit();
            });
            $this.$modal.find('form').on('submit', function () {
                var title = form.find("input[name='title']").val();
                var beginning = form.find("input[name='beginning']").val();
                var ending = form.find("input[name='ending']").val();
                var categoryClass = form.find("select[name='category'] option:checked").val();
                if (title !== null && title.length != 0) {
                    $this.$calendarObj.fullCalendar('renderEvent', {
                        title: title,
                        start:start,
                        end: end,
                        allDay: false,
                        className: categoryClass
                    }, true);  
                    $this.$modal.modal('hide');
                }
                else{
                    alert('You have to give a title to your event');
                }
                return false;
                
            });
            $this.$calendarObj.fullCalendar('unselect');
    },
    CalendarApp.prototype.enableDrag = function() {
        //init events
        $(this.$event).each(function () {
            // it doesn't need to have a start or end
            var eventObject = {
                title: $.trim($(this).text()) // use the element's text as the event title
            };
            // store the Event Object in the DOM element so we can get to it later
            $(this).data('eventObject', eventObject);
            // make the event draggable using jQuery UI
            $(this).draggable({
                zIndex: 999,
                revert: true,      // will cause the event to go back to its
                revertDuration: 0  //  original position after the drag
            });
        });
    }
	
    /* Initializing */
	
    CalendarApp.prototype.init = function() {
        this.enableDrag();
        //  Initialize the calendar 
        var date = new Date();
        var d = date.getDate();
        var m = date.getMonth();
        var y = date.getFullYear();
        var form = '';
        var today = new Date($.now());
		/*
        var defaultEvents =  [{
                title: 'Event Name 4',
                start: new Date($.now() + 148000000),
                className: 'bg-purple'
            },
            {
                title: 'Test Event 1',
                start: today,
                end: today,
                className: 'bg-success'
            },
            {
                title: 'Test Event 2',
                start: new Date($.now() + 168000000),
                className: 'bg-info'
            },
            {
                title: 'Test Event 3',
                start: new Date($.now() + 338000000),
                className: 'bg-primary'
            }];
		*/
        var $this = this;
        $this.$calendarObj = $this.$calendar.fullCalendar({
            slotDuration: '00:15:00', // If we want to split day time each 15minutes //
            minTime: '08:00:00',
            maxTime: '19:00:00',  
            defaultView: 'month',  
            handleWindowResize: true,   
            displayEventTime: false, // Remove the time display 
            header: {
                left: 'prev,next today',
                center: 'title',
                right: 'month,agendaWeek,agendaDay'
            },
            events: defaultEvents,
            editable: true,
            droppable: true, // this allows things to be dropped onto the calendar !!!
            eventLimit: true, // allow "more" link when too many events
            selectable: true,
            drop: function(date) { $this.onDrop($(this), date); },
            select: function (start, end, allDay) { $this.onSelect(start, end, allDay); },
            eventClick: function(calEvent, jsEvent, view) {
				
				$.each(setSessionDetail, function(index, item) {
					if(item.name==calEvent.title)
					{
						viewLawsuitDetail(item.lsMId,item.lsDId,item.id,item.data);
						return;
					}
				});
			}
			
        });

        //on new event
        this.$saveCategoryBtn.on('click', function(){
            var categoryName = $this.$categoryForm.find("input[name='category-name']").val();
            var categoryColor = $this.$categoryForm.find("select[name='category-color']").val();
            if (categoryName !== null && categoryName.length != 0) {
                $this.$extEvents.append('<div class="calendar-events" data-class="bg-' + categoryColor + '" style="position: relative;"><i class="fas fa-circle text-' + categoryColor + '"></i>' + categoryName + '</div>')
                $this.enableDrag();
            }

        });
		
		
		

    },
	
	
	

   //init CalendarApp
    $.CalendarApp = new CalendarApp, $.CalendarApp.Constructor = CalendarApp
   
}(window.jQuery),
	function($) {
	    "use strict";
	    $.CalendarApp.init()
	}(window.jQuery);
}
/*
//initializing CalendarApp
function($) {
    "use strict";
    $.CalendarApp.init()
}(window.jQuery);	
*/

///fc-event-container

function viewLawsuitDetail(lsMId, lsDId,id,data)
{
	var form = document.createElement("form");
	form.setAttribute("method", "post");
	form.setAttribute("action", "LawsuitDetail.php");

	// Generate a unique name for the window
	var windowName = "formresult_" + id;
	// setting form target to a window named 'formresult'
	form.setAttribute("target", windowName);

	var hiddenField = document.createElement("input");
	hiddenField.setAttribute("name", "lsMId");
	hiddenField.setAttribute("value", lsMId);
	hiddenField.setAttribute("hidden", "hidden");
	form.appendChild(hiddenField);
	document.body.appendChild(form);

	var hiddenField = document.createElement("input");
	hiddenField.setAttribute("name", "id");
	hiddenField.setAttribute("value", id);
	hiddenField.setAttribute("hidden", "hidden");
	form.appendChild(hiddenField);
	document.body.appendChild(form);
	
	var hiddenField = document.createElement("input");
	hiddenField.setAttribute("name", "lsDId");
	hiddenField.setAttribute("value", lsDId);
	hiddenField.setAttribute("hidden", "hidden");
	form.appendChild(hiddenField);
	
	var hiddenField = document.createElement("input");
	hiddenField.setAttribute("name", "data");
	hiddenField.setAttribute("value", data);
	hiddenField.setAttribute("hidden", "hidden");
	form.appendChild(hiddenField);
	
	document.body.appendChild(form);
	
	// creating the 'formresult' window with custom features prior to submitting the form
	//window.open('test.html', 'formresult', 'scrollbars=no,menubar=no,height=600,width=800,resizable=yes,toolbar=no,status=no');
	window.open('test.html', windowName);

	form.submit();
}


