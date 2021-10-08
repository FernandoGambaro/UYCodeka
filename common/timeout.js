   function timedCount(){         $.ajax({          type: 'POST',          url: "../common/check_time.php",          success: function(data){
            if (jQuery.trim(data) == "LOGOUT") {        //window.parent.dont_confirm_leave = 1;
        window.top.location.href='../index.php'; 
        //'index.php?action=timeout';            }          }        });        setTimeout("timedCount()",10000);     };
        setTimeout("timedCount()",10000);