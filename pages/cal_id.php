<?php
session_start();
require '../bootstrap.php';
echo head("Test");

?>
<body>
    <div class="container">
        <div class="row">
            <div class="col-12">
                    <input type="text" name="subject" id="search_subject">

            </div>
        </div>
    </div>
</body>
<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script>
        $(document).ready(function() {
    $('#search_subject').autocomplete({
        source: function(request, response) {
            $.ajax({
                type: 'POST',
                url: './choose_trigramme.php',
                data: {
                    subject: request.term
                },
                success: function(data) {
                    var subjectData = JSON.parse(data); // Parsez les données JSON renvoyées par PHP
                    response(subjectData.map(function(item) {
                        return {
                            label: item.name_prof, // Utilisez "name_prof" comme label
                            value: item.tri_prof  // Utilisez "tri_prof" comme valeur
                        };
                    }));
                }
            });
        },
        minLength: 2,
        select: function(event, ui) {
            // Lorsque l'utilisateur sélectionne une suggestion
            var selectedValue = ui.item.value; // Récupérez la valeur (tri_prof) de la suggestion sélectionnée
            console.log(selectedValue);

            // Définissez la valeur de l'input avec le tri_prof
            $('#subject').val(selectedValue);
        }
    });
});

    </script>