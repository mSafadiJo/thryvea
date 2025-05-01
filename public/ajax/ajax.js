$(document).ready(function(){

    function processSample(data) {
        // alert('La '+ data);
        $('#router').html(data['result']);
    }

   $('#formAjax').on('submit',function(e){
       // alert("vous alert lancer une requete en Ajax !");
       e.preventDefault();
       $.ajax({
           url          : '../testAjax/testAjax',
           type         : 'post',
           timeout      : 3000,
           data         : $(this).serialize(),
           dataType     : 'json',
           beforeSend   : function(hxr) {
               console.log('BEFORSEND : Requete on cours ...');
           },
           success      : function(data, status, xhr) {
                console.log('Success: data = ' + data + ' -- status = ' + status + ' -- xhr =  ' + xhr);
                processSample(data);
           },
           error        : function(xhr , status, error) {
                console.log('Error: Error execution requet Ajax !!!');
                console.log('jqXHR = ' + xhr
                        + ' -- textStatus = ' + status
                        + ' -- errorThrown = ' + error);
           },
           complete     : function(hxr, status) {
                console.log('Complete hxr = ' + hxr + ' -- Status = ' + status);
           },
           statusCode   : {
               404      : function() {
                   console.log('StatusConde : 404 : Page Not Found !!!');
               }
           }
       });
   });
});