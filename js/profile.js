function enableNomEditing() {
    document.getElementById('nom').removeAttribute('readonly');
    document.getElementById('nom-edit-buttons').style.display = 'inline';
    document.getElementById('nom-modify-button').style.display = 'none';
}

function cancelNomEditing() {
    document.getElementById('nom').setAttribute('readonly', true);
    document.getElementById('nom-edit-buttons').style.display = 'none';
    document.getElementById('nom-modify-button').style.display = 'inline';
    document.getElementById('nom').value = nomInitial;
}

function enableEmailEditing() {
    document.getElementById('email').removeAttribute('readonly');
    document.getElementById('email-edit-buttons').style.display = 'inline';
    document.getElementById('email-modify-button').style.display = 'none';
}

function cancelEmailEditing() {
    document.getElementById('email').setAttribute('readonly', true);
    document.getElementById('email-edit-buttons').style.display = 'none';
    document.getElementById('email-modify-button').style.display = 'inline';
    document.getElementById('email').value = emailInitial;
}