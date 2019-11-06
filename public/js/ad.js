$('#add-image').click(function () { // Recupère le numéro des futurs champs que je vais créer
    const index = + $('#widgets-counter').val();
    // Recupère le prototype des entrées
    const tmpl = $('#ad_images').data('prototype').replace(/__name__/g, index);

    // injection du code au sein de la DIV
    $('#ad_images').append(tmpl);

    $('#widgets-counter').val(index + 1)

    // gere le bouton supprimer
    handleDeleteButtons();

});
function handleDeleteButtons() {
    $('button[data-action="delete"]').click(function () {
        const target = this.dataset.target;
        $(target).remove()
    });
}

function updateCounter() {
    const count = +$('#ad_images div.form-group').length;

    $('#widgets-counter').val(count)
}

updateCounter();
// Permet de supprimer les images à la modification 'EDIT'
handleDeleteButtons();