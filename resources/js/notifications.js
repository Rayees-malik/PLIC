$(function () {
    $(".notifications-wrap").on("click", ".js-close-notification", function () {
        let $currentNotification = $(this).parent('.notification');

        axios.post(`/notifications/${$(this).data("id")}/dismiss`)
            .then(function (response) {
                $currentNotification.remove();
                window.refreshNotificationCount();
            })
            .catch(function (error) {
                console.log(error);
            });
    });
});

window.refreshNotificationCount = function () {
    var nCount = $(".notification").length;
    $(".notification-num").text(nCount > 9 ? "9+" : nCount);
}
