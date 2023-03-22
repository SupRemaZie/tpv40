function updateShoppingCart(){
    var panier = document.forms['panier'];
    $.ajax({
        body: '/recalculerPanier',
        data: $(panier).serialize(),
        type: 'GET',
        success: function(data){
            console.log(data);
        }
    });
}