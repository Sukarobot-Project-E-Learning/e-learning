document.getElementById('cancelEditProfile').addEventListener('click', function () {
    const route = this.getAttribute('data-route');
    window.location.href = route + "?cancel=true";
});