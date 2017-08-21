<!DOCTYPE HTML>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>jQuery UI - Ventana Modal</title>
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.1/jquery.min.js" type="text/javascript"></script>
    <script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.23/jquery-ui.min.js" type="text/javascript"></script>
    <link rel="stylesheet" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.13/themes/cupertino/jquery-ui.css" />
</head>
<body>
    <h1>jQuery UI - Ventana Modal</h1>
    <p>Levantar una <a class="modal" href="#">ventana modal</a>.</p> 
    <script type="text/javascript">
        $('.modal').on('click',function(e){
          e.preventDefault();
          $('body').append('<div id="modal">un lindo bla bla bla</div>');
          $('#modal').dialog({
                modal : true,
                title : 'Titulo de la modal',
                close : function() {
                    $('#modal').remove();
                },
                buttons: {
                    "OK":function(){
                        $(this).dialog('close');
                    }
                }
          }); // dialog
        }); // on
    </script>
</body>
</html>