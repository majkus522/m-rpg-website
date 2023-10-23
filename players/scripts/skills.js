let inspector = document.querySelector("content inspector");

document.querySelectorAll("content filters rarities div").forEach(element => element.addEventListener("click", (event) =>
{
    if(event.target.classList.contains("active"))
        event.target.classList.remove("active");
    else
        event.target.classList.add("active");
}));

let container = document.querySelector("content skills");
async function getSkills(url)
{
    let request = new XMLHttpRequest();
    request.open("GET", url, true);
    request.onload = function ()
    {
        container.innerHTML = "";
        if(this.status >= 200 && this.status < 300)
        {
            let skills = JSON.parse(this.responseText);
            skills.forEach(async element =>
            {
                let skill = document.createElement("skill");
                skill.dataset.skill = element.skill;
                let data = (await (await fetch("../api/data/skills/" + element.skill + ".json")).json());
                skill.dataset.rarity = data.rarity;
                if(data.toggle)
                    skill.dataset.toggle = element.toggle;
                let img = document.createElement("img");
                img.src = "../img/skills/" + element.skill + ".png";
                skill.appendChild(img);
                container.appendChild(skill);
                skill.addEventListener("click", async (event) =>
                {
                    let target = event.target;
                    if(target.tagName == "IMG")
                        target = target.parentElement;
                    let data = await(await fetch("../api/data/skills/" + target.dataset.skill + ".json")).json();
                    inspector.querySelector("img").src = "../img/skills/" + target.dataset.skill + ".png";
                    inspector.querySelector("h2").textContent = data.label;
                    inspector.querySelector("p.desc").textContent = data.description;
                    inspector.querySelector("p.rarity").textContent = "Rarity: " + toPrettyString(target.dataset.rarity);
                    inspector.querySelector("button").style.visibility = "hidden";
                    inspector.style.display = "flex";
                    if("toggle" in target.dataset)
                        skillToggle(target);
                })
            });
        }
        else
        {
            container.innerHTML = "<p>You don't have any skills</p>";
        }
    }
    request.setRequestHeader("Session-Key", getCookie("session"));
    request.setRequestHeader("Session-Type", "website");
    request.send();
}
getSkills("../api/skills/" + getCookie("username"));

document.querySelector("content filters .search").addEventListener("click", function ()
{
    let url = "../api/skills/" + getCookie("username") + "?";
    let order = document.querySelector("content filters select").value;
    if(order != "default")
        url += "order=" + order;
    url += "&rarity[]=unknown";
    document.querySelectorAll("content filters rarities div.active").forEach(element =>
    {
        url += "&rarity[]=" + element.textContent.toLowerCase();
    });
    getSkills(url);
});

function skillToggle(target)
{
    let toggle = target.dataset.toggle == 1 ? true : false;
    let button = inspector.querySelector("button");
    button.dataset.status = toggle ? "enabled" : "disabled";
    button.textContent = (toggle ? "Enabled" : "Disabled");
    button.style.visibility = "visible";
    button.onclick = () =>
    {
        button.style.visibility = "hidden";
        let request = new XMLHttpRequest();
        request.open("PATCH", "../api/skills/" + getCookie("username") + "/" + target.dataset.skill, true);
        request.onload = function ()
        {
            if(this.status >= 200 && this.status < 300)
            {
                target.dataset.toggle = ((!toggle) ? 1 : 0);
                skillToggle(target, button);
            }
        };
        request.setRequestHeader("Session-Key", getCookie("session"));
        request.setRequestHeader("Session-Type", "website");
        request.send((!toggle) ? "true" : "false");
    }
}