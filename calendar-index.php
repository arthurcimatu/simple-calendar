<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <style> 
            .required{color: #FF0000;}
            .error-message{font-size: 12px;color: red;display: none;}
    </style>
</head>
<body>
    <hr>
    <div class="container">
        <div class="row">
            <div class="col-md-4">
                <h1>Calendar</h1>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-md-4"><hr>
               <form id="calendarForm">
                    <div class="form-label-group">
                        <label for="inputEmail">Name<span class="required">*</span></label>
                        <input type="text" name="event_name" id="event_name" class="form-control"  placeholder="Event Name" autocomplete="off">
                        <div id="event_name_error" class="error-message">event name error</div>
                    </div> <br/>
                    <div class="form-label-group">
                        <label for="inputStartDate">From<span class="required">*</span></label>
                        <input type="date" name="start_date" id="start_date" class="form-control">
                        <div id="start_date_error" class="error-message">start date error</div>
                    </div> <br/>                
                     <div class="form-label-group">
                        <label for="inputEndDate">To<span class="required">*</span></label>
                        <input type="date" name="end_date" id="end_date" class="form-control">
                        <div id="end_date_error" class="error-message">end date error</div>
                    </div> <br/>
                    <div class="checkbox">
                        <label>    
                            <input type="checkbox" name="monday"     id="monday"  value="1"    /> Mon
                            <input type="checkbox" name="tuesday"    id="tuesday" value="2"    /> Tue
                            <input type="checkbox" name="wednesday"  id="wednesday" value="3"  /> Wed
                            <input type="checkbox" name="thursday"   id="thursday" value="4"   /> Thu
                            <input type="checkbox" name="friday"     id="friday" value="5"     /> Fri
                            <input type="checkbox" name="saturday"   id="saturday" value="6"  />  Sat
                            <input type="checkbox" name="sunday"     id="sunday" value="0"    />  Sun
                            <div id="day_of_week_error" class="error-message">day of week error</div>
                        </label>
                    </div>
                    <button type="submit" name="submitBtn" id="submitBtn" class="btn btn-md btn-primary" >Save</button>
                    <center><img src="green-dots.gif" id="loader" style="display: none"/></center>
                </form>
                  
            </div>
            <div class="col-md-7">
                <div id="calendar"></div>
            </div>
        </div>
    </div>


<script type="text/javascript">
  function getDayOfWeek(day) {
      //alert($(day).is(':checked'));
      return ( ($(day).is(':checked')) ? $(day).val() : "");
  }

  $("form#calendarForm").on("submit",function (e) {
      e.preventDefault();
      
      var event_name = $("#event_name").val();
      var start_date = $("#start_date").val();
      var end_date   = $("#end_date").val();
      var monday     = getDayOfWeek("#monday");
      var tuesday    = getDayOfWeek("#tuesday");
      var wednesday  = getDayOfWeek("#wednesday");
      var thursday   = getDayOfWeek("#thursday");
      var friday     = getDayOfWeek("#friday");
      var saturday   = getDayOfWeek("#saturday");
      var sunday     = getDayOfWeek("#sunday");
    
      
      if (event_name == ""){
          $("#event_name_error").show();
          $("#event_name_error").html("Please enter an Event Name");
          $("#event_name_error").fadeOut(4000);
          $("#event_name").focus();
          return;
          
      } 
      
      if (start_date == "") {
          $("#start_date_error").show();
          $("#start_date_error").html("Please enter a FROM date");
          $("#start_date_error").fadeOut(4000);
          $("#start_date").focus();
          return;
          
      }
      
      if (end_date == "") {
          $("#end_date_error").show();
          $("#end_date_error").html("Please enter a TO date");
          $("#end_date_error").fadeOut(4000);
          $("#end_date").focus();
          return;
          
      } 
      
      if (start_date != "" && end_date != "") {
          var from = new Date(start_date);
          var to   = new Date(end_date);
          
          if (from > to) {
              $("#end_date_error").show();
              $("#end_date_error").html("FROM date should NOT be greater than TO date");
              $("#end_date_error").fadeOut(4000);
              $("#start_date").focus();
              return;
          }
          
      } 
      
      if (monday == "" &&
          tuesday == "" &&
          wednesday == "" &&
          thursday == "" &&
          friday == "" &&
          saturday == "" &&
          sunday == "") {
          $("#day_of_week_error").show();
          $("#day_of_week_error").html("Please select at least ONE day");
          $("#day_of_week_error").fadeOut(4000);
          $("#monday").focus();  
      
      }else{
         
          $.ajax({
              url:"calendar.php",
              data:{
                  submit:"submit",
                  event_name:event_name,
                  start_date:start_date,
                  end_date:end_date,
                  monday:monday,
                  tuesday:tuesday,
                  wednesday:wednesday,
                  thursday:thursday,
                  friday:friday,
                  saturday:saturday,
                  sunday:sunday
              },
              method:"POST",
              success:function (response) {
                  
                  $("#calendar").html(response);
              }
          });
      }
  });
  
    $(document).ready(function(){
        $.ajax({ 
            url: "calendar.php",
            success:function (response){
               $("#calendar").html(response);
            }
            
        });
    });

  
</script>
</body>
</html>

