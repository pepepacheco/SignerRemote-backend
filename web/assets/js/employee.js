$(document).ready(function () {

    $('.modal').modal({
            dismissible: true,
            opacity: .5,
            inDuration: 300,
            outDuration: 200,
            startingTop: '2%',
            endingTop: '2%'
        }
    );

    $('#delete-employee-button').click(function () {
        var id = [];

        $('input[name="delete"]:checked').each(function () {
            id.push($(this).attr('id'))
        });

        var path = Routing.generate('employee_delete');

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
                console.log(err);
            }
        });

    });

    $('.edit-employee-button').click(function () {
        var id = $(this).data('id');
        var path = Routing.generate('employee_show_edit');

        $.ajax({
            'url' : path,
            'type' : 'GET',
            'data' : {
                'id' : id
            },
            'success' : function (result) {
                $('#modal-employee-edit-content').empty();
                $('#modal-employee-edit-content').append(result.form);
                $('#modal-employee-edit-content').modal('open');

                startDatepicker();
                implementButtonSendListener();

                Materialize.updateTextFields();
            },
            'error' : function (err) {
                console.log(err)   ;
            }
        });
    });

    function implementButtonSendListener() {
        $('#button-edit-send').click(function () {
            var formData = new FormData($('form[name="corebundle_employee"]')[0]);
            var path = Routing.generate('employee_edit');

            $.ajax({
                url: path,
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                cache: false,

                success: function(result) {
                    console.log(result);
                    location.reload();
                },
                error: function (err){
                    console.log(err);
                }
            });
        });
    }

    function startDatepicker() {
        $('.datepicker').pickadate({
            format: 'yyyy-mm-dd',
            showOn: 'button',
            selectMonths: true,
            selectYears: 15,
            labelMonthNext: 'Mes siguiente',
            labelMonthPrev: 'Mes anterior',
            labelMonthSelect: 'Selecciona un mes',
            labelYearSelect: 'Selecciona un año',
            monthsFull: [ 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre' ],
            monthsShort: [ 'Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic' ],
            weekdaysFull: [ 'Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado' ],
            weekdaysShort: [ 'Dom', 'Lun', 'Mar', 'Mie', 'Jue', 'Vie', 'Sab' ],
            weekdaysLetter: [ 'D', 'L', 'M', 'X', 'J', 'V', 'S' ],
            today: 'Hoy',
            clear: 'Limpiar',
            close: 'Cerrar',
        });
    }
});

