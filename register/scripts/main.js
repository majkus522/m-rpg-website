let error = document.querySelector("form p");
document.querySelector("form input[type='button']").addEventListener("click", () =>
{
    let passwordField = document.querySelectorAll("form label.password input");
    if(passwordField[0].value != passwordField[1].value)
        return;
    let username = document.querySelector("form label.username input").value;
    let email = document.querySelector("form input[type='email']").value;
    let password = btoa(passwordField[0].value);
    let request = new XMLHttpRequest();
    request.open("POST", "../api/endpoints/players", true);
    request.onload = function ()
    {
        if(this.status == 201)
        {
            error.textContent = "";
            let remember = document.querySelector("form label.remember input").value;
            document.cookie = "username=" + username + ";path=/" + (!remember ? (";max-age=" + (60 * 60 * 24)) : "");
            document.cookie = "password=" + password + ";path=/" + (!remember ? (";max-age=" + (60 * 60 * 24)) : "") + ";secure";
            location.replace("../players/" + username + "");
        }
        else
        {
            error.textContent = JSON.parse(this.responseText).message;
        }
    }
    request.send(JSON.stringify({
        username: username,
        email: email,
        password: password
    }));
});