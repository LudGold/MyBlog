// Sélectionnez le formulaire de réinitialisation du mot de passe
const newPasswordForm = document.getElementById("new-password-form");
// Écoutez l'événement de soumission du formulaire
newPasswordForm.addEventListener("submit", function (event) {
    // Sélectionnez les champs de mot de passe et de vérification
    const passwordField = document.getElementById("password");
    const passwordVerifyField = document.getElementById("password-verify");

    // Récupérez les valeurs des champs
    const password = passwordField.value;
    const passwordVerify = passwordVerifyField.value;
// Définissez des règles de validation pour le mot de passe
const minLength = 8; // Minimum de 8 caractères
const minDigits = 1; // Au moins 1 chiffre
const minSpecialChars = 1; // Au moins 1 caractère spécial (par exemple, !, @, #, etc.)

const hasMinimumLength = password.length >= minLength;
const hasMinimumDigits = (password.match(/\d/g) || []).length >= minDigits;
const hasMinimumSpecialChars = (password.match(/[!@#\$%\^&\*\(\)_\+{}\[\]:;<>,\.?~]/g) || []).length >= minSpecialChars;

// Vérifiez si le mot de passe satisfait toutes les règles
if (!hasMinimumLength || !hasMinimumDigits || !hasMinimumSpecialChars) {
    // Affichez un message d'erreur
    alert("Le mot de passe doit contenir au moins 8 caractères, 1 chiffre et 1 caractère spécial.");
    // Empêchez le formulaire d'être soumis
    event.preventDefault();
    return; // Arrêtez la vérification, car le mot de passe n'est pas conforme
}
        //  validation du mot de passe
    if (password !== passwordVerify) {
        // Affichez un message d'erreur
        alert("Les mots de passe ne correspondent pas.");
        // Empêchez le formulaire d'être soumis
        event.preventDefault();
    }
})







