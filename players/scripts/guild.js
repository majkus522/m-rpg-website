let error = document.querySelector("p.error");

document.querySelectorAll("table button").forEach((element) => element.addEventListener("click", (event) => request("PATCH", "../api/guilds/" + guild + "/kick", event.target.dataset.kick)));

let controls = document.querySelectorAll("content div button");
controls[0].addEventListener("click", (event) => request("PATCH", "../api/players/" + getCookie("username") + "/leave"));

if(controls.length > 1)
    controls[1].addEventListener("click", (event) => request("DELETE", "../api/guilds/" + guild));

function request(method, url, body = "")
{
    let request = new XMLHttpRequest();
    request.open(method, url, true);
    request.onload = function ()
    {
        if(this.status < 300)
            location.reload();
        else
            error.textContent = JSON.parse(this.responseText).message;
    }
    request.setRequestHeader("Session-Type", "website");
    request.setRequestHeader("Session-Key", getCookie("session"));
    request.send(body);
}