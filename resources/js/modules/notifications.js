$(function () {
    const notificationsTable = $('.datatable').DataTable();

    $(".js-notifications-container").on("click", ".js-close-notification", function () {
        $id = $(this).data("id");
        $row = $(this).closest("tr");

        axios.post(`/notifications/${$id}/dismiss`)
            .then(function () {
                $(this).remove();
                $row.fadeOut(250);
                notificationsTable.row($row).draw();
                $(`.js-close-notification[data-id="${$id}"]`).parent(".notification").remove();
                window.refreshNotificationCount();
            })
            .catch(function (error) {
                console.log(error);
            });
    });
});
