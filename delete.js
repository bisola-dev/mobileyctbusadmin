// Add event listener to delete buttons
document.querySelectorAll('.delete-btn').forEach(function(button) {
    button.addEventListener('click', function() {
        var adminId = this.getAttribute('data-id');
        if (confirm('Are you sure you want to delete the admin with ID ' + adminId + '?')) {
            // Send AJAX request to delete admin record
            var xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function() {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    if (xhr.status === 200) {
                        // Reload the page after successful deletion
                        window.location.reload();
                    } else {
                        // Handle error
                        alert('Error deleting admin: ' + xhr.responseText);
                    }
                }
            };
            xhr.open('POST', 'delete_admin.php', true);
            xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
            xhr.send('adminId=' + encodeURIComponent(adminId));
        }
    });
});
