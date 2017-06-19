$(document).ready(function () {

    $('#notification-list').DataTable({
        "language": {
            "sProcessing": "Procesando...",
            "sLengthMenu": "Mostrar _MENU_ registros",
            "sZeroRecords": "No se encontraron resultados",
            "sEmptyTable": "Ningún dato disponible en esta tabla",
            "sInfo": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
            "sInfoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
            "sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
            "sInfoPostFix": "",
            "sSearch": "Buscar:",
            "sUrl": "",
            "sInfoThousands": ",",
            "sLoadingRecords": "Cargando...",
            "oPaginate": {
                "sFirst": "Primero",
                "sLast": "Último",
                "sNext": "Siguiente",
                "sPrevious": "Anterior"
            },
            "oAria": {
                "sSortAscending": ": Activar para ordenar la columna de manera ascendente",
                "sSortDescending": ": Activar para ordenar la columna de manera descendente"
            }
        },

        "initComplete": function(settings, json) {
            $('#notification-list_length').addClass('col m6 s12');
            $('#notification-list_filter').addClass('col m6 s12');
        }
    });

    $('#delete-notification-button').click(function () {
        var id = [];

        $('input[name="delete"]:checked').each(function () {
            id.push($(this).attr('id'))
        });

        var path = Routing.generate('notification_delete');

        $.ajax({
            'url' : path,
            'type' : 'DELETE',
            'data' : {
                'id' : id
            },
            'success' : function (result) {
                for (var i = 0; i < result.length; i++) {
                    $('#' + result[i]).parent().parent().hide('slow');
                }
            },
            'error' : function (err) {
                $('body').html(err.responseText);
                console.log(err);
            }
        });

    });
});