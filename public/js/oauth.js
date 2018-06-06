var page = 1;
var current_page = 1;
var total_page = 0;
var is_ajax_fire = 0;

         $.ajaxSetup({
    headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
           // 'Authorization':'Bearer '+ sessionStorage.getItem('authtoken'),

            

        }
});


$("#loginform").on('submit',function(e){
    e.preventDefault();
    $('#loginerrmsg').hide();
    var form_action = $("#Login").find("form").attr("action");
    var username = $("#Login").find("input[name='username']").val();

 
    var password = $("#Login").find("input[name='password']").val();
    $.ajax({
        dataType: 'json',
        type:'POST',
        url: form_action,
        data:{username:username, password:password}
    }).done(function(data){
      window.sessionStorage.setItem('authtoken', data.accessToken.access_token)
            $.ajaxSetup({
    headers: {
             'Authorization':'Bearer '+ window.sessionStorage.getItem('authtoken'),
        }
});
            
        //    //setCookie('Authorization',"Bearer "+data.accessToken.access_token)
        //    url = "manage-item-ajax";
        // window.location.replace(url);
         $.ajax({
          url: "manage-item-ajax",
          data: {},
          type: "GET",
          
          success: function(data) { 
             //console.log(data);
            $('#container').html(data); 
            $("#authentication").hide();
             alert('Success!' + data.accessToken.access_token);


           }
       });
       // console.log(data.accessToken.access_token);
        //alert("Logged in successfully");
    }).fail(function(jqXHR, status, err) {
      $('#loginerrmsg').show();
      $('#loginerrmsg').text(jqXHR.responseJSON.error_description);
  });



});

function setCookie(key, value) {
            var expires = new Date();
            expires.setTime(expires.getTime() + (1 * 24 * 60 * 60 * 1000));
            document.cookie = key + '=' + value + ';expires=' + expires.toUTCString();
        }

        function getCookie(key) {
            var keyValue = document.cookie.match('(^|;) ?' + key + '=([^;]*)(;|$)');
            return keyValue ? keyValue[2] : null;
        }





