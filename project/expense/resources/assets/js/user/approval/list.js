$(document).ready(function() {
    $('.user-action-btn').on('click', function() {
        let actionUrl = $(this).data('action');
        let userId = $(this).data('user-id');
        $('#userActionForm').attr('action', actionUrl);
        $('#user_id').val(userId);
        $('#userActionForm').submit();
    });
});