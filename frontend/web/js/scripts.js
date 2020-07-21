$(document).ready(function() {  

  var buttonReg = document.getElementById('submit-button-registration'); 
  buttonReg.onclick = function(e) {
        e.preventDefault();
        var url = '/registration'; 
        var data = $('#form-signup').serialize(); 
 
        $.ajax({
            url: url,
            type: "POST", 
            dataType: "html", 
            data: data,  
            success: function(response) { 
              var result = $.parseJSON(response); 
              $('#error-registration').empty();
              if (result.status == 0) {
                  for (var key in result.message) {
                    var text = result.message[key];
                    $('#error-registration').append("<div>" + text + "</div>");
                  }
              }  else {
                  $('#sucsess-registration').text(result.message); 
              }             
            }
        });
  }  
  

});



   




   

    