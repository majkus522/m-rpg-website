let error = document.querySelector("p.error");
let modeKick = true;

reloadButtons(kickPlayer, "Kick");

document.querySelectorAll("content div button").forEach(element =>
{
    element.addEventListener("click", (event) =>
    {
        switch(event.target.dataset.operation)
        {
            case "leave":
                request("PATCH", "../api/guilds/" + guild + "/kick", getCookie("username"));
                break;

            case "delete":
                request("DELETE", "../api/guilds/" + guild);
                break;

            case "leader":
                reloadButtons(newLeader, "New leader");
                createKick();
                break;

            case "vice":
                reloadButtons(newViceLeader, "New vice leader");
                createKick();
                break;
        }
    });
});

function createKick()
{
    let main = document.querySelector("content div");
    if(main.querySelector('button[data-operation="kick"]') != null)
        return;
    let button = document.createElement("button");
    button.textContent = "Kick player";
    button.dataset.operation = "kick";
    button.addEventListener("click", (event) =>
    {
        event.target.remove();
        reloadButtons(kickPlayer, "Kick");
    });
    main.insertBefore(button, main.querySelector("p.error"));
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
    request.setRequestHeader("Session-ID", getCookie("session-id"));
    request.setRequestHeader("Session-Key", getCookie("session-key"));
    request.send(body);
}

function reloadButtons(action, text)
{
    document.querySelectorAll("table tr").forEach((element) =>
    {
        let button = element.querySelector("button");
        if(button == null)
            return;
        button.style.display = "block";
        if(text == "New vice leader" && element.querySelector("td:nth-child(2)").textContent.includes("Vice Leader"))
            button.style.display = "none";
        button.removeEventListener("click", kickPlayer);
        button.removeEventListener("click", newLeader);
        button.removeEventListener("click", newViceLeader);
        button.addEventListener("click", action);
        button.textContent = text;
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

function newViceLeader(event)
{
    request("PATCH", "../api/guilds/" + guild, JSON.stringify({ vice_leader: event.target.dataset.player}));
}