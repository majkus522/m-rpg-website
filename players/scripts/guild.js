let error = document.querySelector("p.error");
let modeKick = true;

reloadButtons(kickPlayer, "Kick");

let controls = document.querySelectorAll("content div button");
controls[0].addEventListener("click", (event) => request("PATCH", "../api/players/" + getCookie("username") + "/leave"));

if(controls.length > 1)
{
    controls[1].addEventListener("click", (event) => request("DELETE", "../api/guilds/" + guild));
    controls[2].addEventListener("click", (event) =>
    {
        modeKick = !modeKick;
        event.target.textContent = modeKick ? "Select new leader" : "Kick player";
        if(modeKick)
            reloadButtons(kickPlayer, "Kick");
        else
            reloadButtons(newLeader, "New leader");
    });
}

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

function reloadButtons(action, text)
{
    document.querySelectorAll("table button").forEach((element) =>
    {
        element.removeEventListener("click", kickPlayer);
        element.removeEventListener("click", newLeader);
        element.addEventListener("click", action);
        element.textContent = text;
    });
}

function kickPlayer(event)
{
    request("PATCH", "../api/guilds/" + guild + "/kick", event.target.dataset.player)
}

function newLeader(event)
{
    request("PATCH", "../api/guilds/" + guild, JSON.stringify({ leader: event.target.dataset.player}));
}