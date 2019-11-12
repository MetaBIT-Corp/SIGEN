$(document).ready(function() {
    
    $('#datetimepicker1').datetimepicker({
        format: 'DD/MM/YYYY h:mm A',
        minDate: moment(),
    });
    
    $('#datetimepicker2').datetimepicker({
        format: 'DD/MM/YYYY h:mm A'
    });
    
 });