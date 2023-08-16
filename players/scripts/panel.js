window.addEventListener("resize", draw);

function draw()
{
    let svg = document.querySelector("info svg");
    let rect = svg.getBoundingClientRect();
    let heigth = rect.height;
    let width = heigth;
    svg.setAttribute("width", heigth);
    let max = Math.max(str, agl, chr, intl);
    svg.querySelector("polygon").setAttribute("points", (width / 2) + "," + ((heigth / 2) - (heigth / 2 * str / max) + 5) + " " + (width - ((width / 2) - (width / 2 * agl / max)) - 5) + "," + (heigth / 2) + " " + (width / 2) + "," + (heigth - ((heigth / 2) - (heigth / 2 * chr / max)) - 5) + " " + ((width / 2) - (width / 2 * intl / max) + 5) + "," + (heigth / 2));

    let line = svg.querySelectorAll("line")[0];
    line.setAttribute("x1", 0);
    line.setAttribute("y1", heigth / 2);
    line.setAttribute("x2", width);
    line.setAttribute("y2", heigth / 2);

    line = svg.querySelectorAll("line")[1];
    line.setAttribute("x1", width / 2);
    line.setAttribute("y1", 0);
    line.setAttribute("x2", width / 2);
    line.setAttribute("y2", heigth);
}

draw();