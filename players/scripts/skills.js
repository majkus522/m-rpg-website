let inspector = document.querySelector("content inspector");

document.querySelectorAll("content filters rarities div").forEach(element => element.addEventListener("click", (event) =>
{
    if(event.target.classList.contains("active"))
        event.target.classList.remove("active");
    else
        event.target.classList.add("active");
}));

let container = document.querySelector("content skills");
function getSkills(url)
{
    let request = new XMLHttpRequest();
    request.open("GET", url, true);
    request.onload = function ()
    {
        container.innerHTML = "";
        if(this.status == 200)
        {
            let skills = JSON.parse(this.responseText);
            skills.forEach(element =>
            {
                let skill = document.createElement("skill");
                skill.dataset.skill = element.skill;
                skill.classList.add(element.rarity);
                let img = document.createElement("img");
                img.src = "../img/skills/" + element.skill + ".png";
                skill.appendChild(img);
                container.appendChild(skill);
                skill.addEventListener("click", async (event) =>
                {
                    let target = event.target;
                    if(target.tagName == "IMG")
                        target = target.parentElement;
                    let data = await (await fetch("../api/data/skills/" + target.dataset.skill + ".json")).json();
                    inspector.querySelector("img").src = "../img/skills/" + target.dataset.skill + ".png";
                    inspector.querySelector("h2").textContent = data.label;
                    inspector.querySelector("p").textContent = data.description;
                    inspector.style.display = "flex";
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
getSkills("../api/endpoints/skills/" + getCookie("username"));

document.querySelector("content filters .search").addEventListener("click", function ()
{
    let url = "../api/endpoints/skills/" + getCookie("username") + "?";
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