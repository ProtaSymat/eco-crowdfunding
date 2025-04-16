import './bootstrap';
import feather from 'feather-icons';

document.addEventListener('DOMContentLoaded', () => {
    feather.replace();

    const replyButtons = document.querySelectorAll('.reply-button');
    const commentForm = document.getElementById('comment-form');
    const commentTextarea = document.getElementById('comment-content');
    const parentIdInput = document.getElementById('parent_id');
    const cancelReplyButton = document.getElementById('cancel-reply');
    
    replyButtons.forEach(button => {
        button.addEventListener('click', function() {
            const commentId = this.getAttribute('data-comment-id');
            const commentAuthor = this.closest('.card-body').querySelector('h6').innerText;
            parentIdInput.value = commentId;
            commentTextarea.value = `@${commentAuthor} `;
            commentTextarea.focus();
            commentForm.scrollIntoView({ behavior: 'smooth' });
            cancelReplyButton.classList.remove('d-none');
            const submitButton = commentForm.querySelector('button[type="submit"]');
            submitButton.innerHTML = '<i data-feather="edit-2" class="me-2"></i>Répondre';
        });
    });
    
    cancelReplyButton.addEventListener('click', function() {
        parentIdInput.value = '';
        commentTextarea.value = '';
        this.classList.add('d-none');
        const submitButton = commentForm.querySelector('button[type="submit"]');
        submitButton.innerHTML = '<i data-feather="send" class="me-2"></i>Publier';
    });
    
    const editButtons = document.querySelectorAll('.edit-comment');
    const editForm = document.getElementById('edit-comment-form');
    const editTextarea = document.getElementById('edit-comment-content');
    const editModal = new bootstrap.Modal(document.getElementById('editCommentModal'));
    
    editButtons.forEach(button => {
        button.addEventListener('click', function() {
            const commentId = this.getAttribute('data-comment-id');
            const commentContent = this.closest('.card-body').querySelector('.comment-content').innerText.trim();
            editForm.action = `/comments/${commentId}`;
            editTextarea.value = commentContent;
            editModal.show();
        });
    });
});
function copyShareLink() {
    var copyText = document.getElementById("shareLink");
    copyText.select();
    copyText.setSelectionRange(0, 99999);
    navigator.clipboard.writeText(copyText.value);
    var alert = document.getElementById("linkCopiedAlert");
    alert.classList.remove("d-none");
    setTimeout(function() {
        alert.classList.add("d-none");
    }, 2000);
}