document.addEventListener('DOMContentLoaded', function() {
    var requiredInputs = document.querySelectorAll('input[required], select[required]');

    requiredInputs.forEach(function(input) {
        var label = document.querySelector('label[for="' + input.id + '"]');
        if (label) {
            label.classList.add('required');
        }
    });
});
function previewLogo(event) {
    var reader = new FileReader();
    reader.onload = function(){
        var output = document.getElementById('attachment-preview');
        output.src = reader.result;
        output.style.display = 'block';
        document.querySelector('.attachment-preview-container').style.display = 'inline-block'; // Show the container
        document.querySelector('.delete-btn').style.display = 'block'; // Show the delete button
    };
    reader.readAsDataURL(event.target.files[0]);
}

function deleteAttachmentPreview() {
    var preview = document.getElementById('attachment-preview');
    preview.src = '#';
    preview.style.display = 'none';
    document.getElementById('attachment').value = '';
    document.querySelector('.attachment-preview-container').style.display = 'none'; // Hide the container
    document.querySelector('.delete-btn').style.display = 'none'; // Hide the delete button
}
