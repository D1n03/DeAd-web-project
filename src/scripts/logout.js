function logout() {
    fetch('../logout_script.php', {
        method: 'POST',
    })
    .then(response => response.json())
    .then(data => {
        if (data.message) {
            // Redirect to the index page upon successful logout
            window.location.href = '../Index/index.php';
        } else if (data.error) {
            console.error(data.error);
        }
    })
    .catch(error => console.error('Error:', error));
}