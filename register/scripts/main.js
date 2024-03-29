let button = document.querySelector("form input[type='button']");
let loading = document.querySelector("div.loading");
let error = document.querySelector("form p");
document.querySelector("form input[type='button']").addEventListener("click", () =>
{
    loading.style.display = "block";
    button.style.display = "none";
    let passwordField = document.querySelectorAll("form label.password input");
    if(passwordField[0].value != passwordField[1].value)
        return;
    let username = document.querySelector("form label.username input").value;
    let email = document.querySelector("form input[type='email']").value;
    let password = btoa(passwordField[0].value);
    let remember = document.querySelector("mrpg-checkbox.remember").value;
    let request = new XMLHttpRequest();
    request.open("POST", "../api/players", true);
    request.onload = function ()
    {
        loading.style.display = "none";
        button.style.display = "block";
        if(this.status >= 200 && this.status < 300)
        {
            error.textContent = "";
            let response = JSON.parse(this.responseText);
            document.cookie = "session-key=" + response.key + ";path=/" + (!remember ? (";max-age=" + (60 * 60 * 24)) : "") + ";secure";
            document.cookie = "session-id=" + response.id + ";path=/" + (!remember ? (";max-age=" + (60 * 60 * 24)) : "") + ";secure";
            document.cookie = "username=" + username + ";path=/" + (!remember ? (";max-age=" + (60 * 60 * 24)) : "") + ";secure";
            location.reload();
        }
        else
            error.textContent = JSON.parse(this.responseText).message;
    }
    request.send(JSON.stringify({
        username: username,
        email: email,
        password: password
    }));
});