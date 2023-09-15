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
    points += (radius + (linePoints[index].x - radius) * (stats[index] / max * 0.95)) + "," + (radius + (linePoints[index].y - radius) * (stats[index] / max * 0.95)) + " ";
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
    svg.appendChild(text);
    text.setAttribute("x", circleX(angle, radius / 2.25));
    text.setAttribute("y", circleY(angle, radius / 2.25));
    text.setAttribute("text-anchor", "middle");
    text.setAttribute("fill", "#121212");
}

function circleX(angle, radius)
{
    return (size / 2) + radius * Math.cos(angle * (Math.PI / 180.0))
}

function circleY(angle, radius)
{
    return (size / 2) + radius * Math.sin(angle * (Math.PI / 180.0))
}