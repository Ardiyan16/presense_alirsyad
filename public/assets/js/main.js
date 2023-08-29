function set_cookie(key, value) {
    return $.cookie(key, value, {
        path: "/",
    });
}

function get_cookie(key) {
    return $.cookie(key);
}

function remove_cookie(key) {
    return $.removeCookie(key, {
        path: "/",
    });
}
