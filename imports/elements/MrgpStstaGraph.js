class MrgpStstaGraph extends HTMLElement
{
    size = 0;
    svg;
    linePoints = [];
    radius = 0;

    constructor()
    {
        super();
        if(!this.hasAttribute("stats"))
            return;
        this.style = "display: block; aspect-ratio: 1/1;";
        const shadow = this.attachShadow({mode: "closed"});
        this.svg = document.createElementNS('http://www.w3.org/2000/svg', "svg");
        shadow.appendChild(this.svg);
        let link = document.createElement("link");
        link.href = "http://127.0.0.1/m-rpg/imports/elements/MrgpStstaGraph.css";
        link.rel = "stylesheet";
        link.type = "text/css";
        shadow.appendChild(link);
        this.svg.appendChild(document.createElementNS('http://www.w3.org/2000/svg', "polygon"));
        let stats = JSON.parse(this.getAttribute("stats"));
        this.size = this.offsetWidth;
        this.radius = this.size / 2;
        let max = Math.max(...stats);
        let min = this.hasAttribute("min") ? this.getAttribute("min") : 0.08;
        let labels = this.hasAttribute("labels") ? JSON.parse(this.getAttribute("labels")) : [];
        for(let index = 0; index < stats.length; index++)
        {
            let degree = 360 / stats.length * index;
            this.createLine(this.circleX(degree, this.radius), this.circleY(degree, this.radius));
            if(labels.length != 0)
                this.createText(labels[index], degree);
        }
        let points = "";
        let markersCount = this.hasAttribute("markers-count") ? this.getAttribute("markers-count") : 3;
        let markerPoints = [];
        for(let index = 0; index < stats.length; index++)
        {
            let x = this.radius + (this.linePoints[index].x - this.radius) * (stats[index] / max * (1 - min) + min);
            let y = this.radius + (this.linePoints[index].y - this.radius) * (stats[index] / max * (1 - min) + min);
            points += x + "," + y + " ";
            for(let markerIndex = 0; markerIndex < markersCount; markerIndex++)
            {
                if(markerPoints[markerIndex] == null)
                    markerPoints[markerIndex] = "";
                markerPoints[markerIndex] += (this.radius + (this.linePoints[index].x - this.radius) * (markerIndex + 1) / markersCount) + "," + (this.radius + (this.linePoints[index].y - this.radius) * (markerIndex + 1) / markersCount) + " ";
            }
            this.createDot(x, y);
        }
        this.svg.querySelector("polygon:not(.marker)").setAttribute("points", points);
        for(let index = 0; index < markersCount; index++)
        {
            let polygon = document.createElementNS('http://www.w3.org/2000/svg', "polygon");
            polygon.classList.add("marker");
            polygon.setAttribute("points", markerPoints[index]);
            this.svg.insertBefore(polygon, this.svg.firstChild);
        }
    }

    createLine(x, y)
    {
        let line = document.createElementNS('http://www.w3.org/2000/svg', "line");
        line.setAttribute("x1", this.size / 2);
        line.setAttribute("y1", this.size / 2);
        line.setAttribute("x2", x);
        line.setAttribute("y2", y);
        this.svg.appendChild(line);
        this.linePoints.push({x: x, y: y});
    }

    createText(content, angle)
    {
        let text = document.createElementNS('http://www.w3.org/2000/svg', "text");
        text.textContent = content;
        text.setAttribute("x", this.circleX(angle, this.radius / 2.25));
        text.setAttribute("y", this.circleY(angle, this.radius / 2.25));
        text.setAttribute("text-anchor", "middle");
        text.setAttribute("fill", "#121212");
        this.svg.appendChild(text);
    }

    createDot(x, y)
    {
        let dot = document.createElementNS('http://www.w3.org/2000/svg', "circle");
        dot.setAttribute("cx", x);
        dot.setAttribute("cy", y);
        dot.setAttribute("r", 2);
        this.svg.appendChild(dot);
    }

    circleX(angle, radius)
    {
        return (this.size / 2) + radius * Math.cos(angle * (Math.PI / 180.0));
    }

    circleY(angle, radius)
    {
        return (this.size / 2) + radius * Math.sin(angle * (Math.PI / 180.0));
    }
}

customElements.define("mrpg-stats-graph", MrgpStstaGraph);