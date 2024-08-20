<script type="text/javascript">
    document.addEventListener('DOMContentLoaded', function() {
        var indexSelect = document.getElementById('INDEX');
        var coatingSelect = document.getElementById('COATING');
        var photoSelect = document.getElementById('PHOTO');
        var polarSelect = document.getElementById('POLAR');

        indexSelect.addEventListener('change', function() {
            var indexValue = indexSelect.value;
            
            // Envoyer une requête AJAX pour obtenir les options mises à jour
            var xhr = new XMLHttpRequest();
            xhr.open('GET', 'get_options.php?index=' + indexValue, true);
            xhr.onreadystatechange = function() {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    var options = JSON.parse(xhr.responseText);
                    
                    // Mettre à jour les options du menu COATING
                    coatingSelect.innerHTML = '';
                    for (var i = 0; i < options.coating.length; i++) {
                        var option = document.createElement('option');
                        option.value = options.coating[i].value;
                        option.textContent = options.coating[i].name;
                        coatingSelect.appendChild(option);
                    }
                    
                    // Mettre à jour les options du menu PHOTO
                    photoSelect.innerHTML = '';
                    for (var j = 0; j < options.photo.length; j++) {
                        var option = document.createElement('option');
                        option.value = options.photo[j].value;
                        option.textContent = options.photo[j].name;
                        photoSelect.appendChild(option);
                    }
                    
                    // Mettre à jour les options du menu POLAR
                    polarSelect.innerHTML = '';
                    for (var k = 0; k < options.polar.length; k++) {
                        var option = document.createElement('option');
                        option.value = options.polar[k].value;
                        option.textContent = options.polar[k].name;
                        polarSelect.appendChild(option);
                    }
                }
            };
            xhr.send();
        });
    });
</script>
