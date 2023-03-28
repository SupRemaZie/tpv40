function updateShoppingCart() {

    $.ajax({
        url: $('form').attr('action'), // Récupère l'URL de l'action du formulaire
        method: $('form').attr('method'), // Récupère la méthode HTTP du formulaire (GET ou POST)
        data: $('form').serialize(), // Récupère les données du formulaire sous forme de chaîne de requête
        success: function(response) {
            // Code à exécuter en cas de succès de la requête Ajax
           // met à jour le panier
              document.querySelector('body').innerHTML = response;

        },
        error: function(jqXHR, textStatus, errorThrown) {
            // Code à exécuter en cas d'erreur de la requête Ajax
            console.log(textStatus + ": " + errorThrown);
        }
    });
}




