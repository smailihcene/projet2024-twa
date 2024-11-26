$(document).ready(function () {
    let selectedOrder = 0; // Compteur pour les positions
    const selectedImages = new Map(); // Stocker les images déjà sélectionnées

    // Écouter les clics sur les images
    $(".selectable-image").on("click", function () {
        const imageId = $(this).data("id");
        const inputField = $(this).siblings(".image-order"); // Champ input caché associé
        const positionLabel = $(this).siblings(".position-label"); // Label pour afficher la position

        if (selectedImages.has(imageId)) {
            // Déselectionner une image
            $(this).removeClass("selected"); // Retirer le style de sélection
            inputField.val("").prop("disabled", true); // Désactiver le champ
            positionLabel.text(""); // Effacer la position

            selectedImages.delete(imageId); // Retirer l'image de la liste
            reorderImages(); // Réorganiser les positions
        } else {
            // Sélectionner une nouvelle image
            selectedOrder++;
            $(this).addClass("selected"); // Ajouter un style de sélection
            inputField.val(selectedOrder).prop("disabled", false); // Activer le champ avec la position
            positionLabel.text(`Position : ${selectedOrder}`); // Afficher la position

            selectedImages.set(imageId, selectedOrder); // Ajouter l'image à la liste
        }
    });

    // Réorganiser les positions après une déselection
    function reorderImages() {
        let order = 1; // Réinitialiser l'ordre
        selectedImages.forEach((value, key) => {
            const imageElement = $(`.selectable-image[data-id='${key}']`);
            const inputField = imageElement.siblings(".image-order");
            const positionLabel = imageElement.siblings(".position-label");

            inputField.val(order); // Mettre à jour la position dans l'input
            positionLabel.text(`Position : ${order}`); // Mettre à jour le label
            selectedImages.set(key, order); // Mettre à jour l'ordre dans la Map

            order++;
        });
        selectedOrder = order - 1; // Mettre à jour le compteur global
    }
});