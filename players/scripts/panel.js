let svg = document.querySelector("info svg");
let rect = svg.getBoundingClientRect();
let parent = document.querySelector("info > div");
let size = parent.offsetHeight;
parent.style.gridTemplateColumns = "1fr " + size + "px";
size -= 20;
let radius = size / 2;
let linePoints = [];
let max = Math.max(...stats);
for(let index = 0; index < stats.length; index++)
{
    let degree = 360 / stats.length * index;
    createLine(circleX(degree, radius), circleY(degree, radius));
    createText(labels[index], degree);
}
let points = "";
for(let index = 0; index < stats.length; index++)
{
    let x = radius + (linePoints[index].x - radius) * (stats[index] / max);
    let y = radius + (linePoints[index].y - radius) * (stats[index] / max);
    points += x + "," + y + " ";
    createDot(x, y);
}
svg.querySelector("polygon").setAttribute("points", points);

function createLine(x, y)
{
    let line = document.createElementNS('http://www.w3.org/2000/svg', "line");
    line.setAttribute("x1", size / 2);
    line.setAttribute("y1", size / 2);
    line.setAttribute("x2", x);
    line.setAttribute("y2", y);
    svg.appendChild(line);
    linePoints.push({x: x, y: y});
}

function createText(content, angle)
{
    let text = document.createElementNS('http://www.w3.org/2000/svg', "text");
    text.textContent = content;
    text.setAttribute("x", circleX(angle, radius / 2.25));
    text.setAttribute("y", circleY(angle, radius / 2.25));
    text.setAttribute("text-anchor", "middle");
    text.setAttribute("fill", "#121212");
    svg.appendChild(text);
}

function createDot(x, y)
{
    let dot = document.createElementNS('http://www.w3.org/2000/svg', "circle");
    dot.setAttribute("cx", x);
    dot.setAttribute("cy", y);
    dot.setAttribute("r", 2);
    svg.appendChild(dot);
}

function circleX(angle, radius)
{
    return (size / 2) + radius * Math.cos(angle * (Math.PI / 180.0));
}

function circleY(angle, radius)
{
    return (size / 2) + radius * Math.sin(angle * (Math.PI / 180.0));
}

let items = document.querySelectorAll("items img");
items.forEach(element =>
{
    if(element.src == "")
        return;
    element.addEventListener("click", async (event) =>
    {
        let dialog = document.createElement("div");
        document.querySelector("items").appendChild(dialog);
        let rect = event.target.getBoundingClientRect();
        dialog.style.top = (rect.top + 40) + "px";
        dialog.style.left = (rect.left + 80) + "px";
        let part = event.target.src.split("/");
        let equipment = (await (await fetch("../api/data/equipment/" + part[part.length - 1].split(".")[0] + ".json")).json());
        let p = document.createElement("p");
        p.textContent = equipment.label;
        dialog.appendChild(p);
        Object.entries(equipment.bonusStats).forEach((pair) => 
        {
            let p = document.createElement("p");
            p.textContent = statsData.filter(element => element.short == pair[0])[0].label + ": " + pair[1];
            dialog.appendChild(p);
        });
    });
});

document.addEventListener("click", () => document.querySelectorAll("items div").forEach(element => element.remove()), true);