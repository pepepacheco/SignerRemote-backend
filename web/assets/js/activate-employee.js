$(document).ready(function () {
   $('#activate-emplyee').click(function () {
       var id = $(this).data('id');
       var path = Routing.generate('activate_employee');

       $.ajax({
           'url' : path,
           'type' : 'PUT',
           'data' : {
               'id' : id
           },
           'success' : function (result) {
               location.reload();
           },
           'error' : function (err) {
               console.log(err);
           }
       });
   })
});