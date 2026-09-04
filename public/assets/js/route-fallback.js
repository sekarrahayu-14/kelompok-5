document.querySelectorAll('form[action]').forEach(function (form) {
    var action = new URL(form.action, window.location.origin);
    var prefix = '/kelompok-5/';
    if (action.pathname.indexOf(prefix) === 0 && action.pathname.indexOf('/public/index.php') === -1 && action.pathname.indexOf('/app/Views/') === -1) {
        var route = action.pathname.slice(prefix.length - 1);
        action.pathname = prefix + 'public/index.php';
        action.search = '?route=' + encodeURIComponent(route);
        form.action = action.toString();
    }
});
