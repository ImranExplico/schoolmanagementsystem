(function($,undefined){
    $.fn.serializeObject = function(){
      var obj = {};
      
      $.each( this.serializeArray(), function(i,o){
        var n = o.name,
          v = o.value;
          
          obj[n] = obj[n] === undefined ? v
            : $.isArray( obj[n] ) ? obj[n].concat( v )
            : [ obj[n], v ];
      });
      
      return obj;
    };
    
  })(jQuery);
  
  function callAjax(options, callback, callbackException) {
      options.data._token = $('meta[name="csrf-token"]').attr('content');
      $.ajax({
          'async': true,
          'type': options.method,
          'global': false,
          data : options.data,
          url: options.path,
          dataType: "json",
          success: function(response) 
          {
              if (callback && typeof callback == "function"){
                  callback(response);
              }else {
                  handleResponse(response);  
              }
          },
          error:function(err){
              if (callbackException && typeof callbackException == "function"){
                  callbackException(err);
              }else {
                  handleException(err);
              }
          }
      });
  };
  
  function handleException(err){
      var response = JSON.parse(err.responseText);
      if(err.status == '422'){
          $(".form-error").remove();
          $.each( response.errors, function( key, value) {
              var error_string = "";
              $.each( value, function( key, message) {
                  error_string+= message;
              })
              $("#"+key).after('<div class="form-error">'+error_string+'</div>');
          });
      }
      $(".custom-spinner").fadeOut("fast");
      window.processing = false;
  }