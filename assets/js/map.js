const DECK_GL_KEY = 'pk.eyJ1IjoiZ25vdG9uZyIsImEiOiJjandrbmJxaTIwMGUzNDhwanZuNnh6cGgzIn0.XNWwQfuuyXoMzDqrW212bw';
const THEME = 'mapbox://styles/mapbox/streets-v11';

let initialPosition = greenSpaces.length > 0 ? greenSpaces[0].position : [0, 0]

const deckgl = new deck.DeckGL({
    container: 'container',
    mapboxApiAccessToken: DECK_GL_KEY,
    mapStyle: THEME,
    initialViewState: {
        longitude: initialPosition[0],
        latitude: initialPosition[1],
        zoom: 2
    },
    controller: true
});

const scatterplotLayer = new deck.ScatterplotLayer({
    data: greenSpaces,
    opacity: 0.3,
    getFillColor: d => d.color,
    radiusMinPixels: 11,
    pickable: true,
    onHover: info => setTooltip(info.x, info.y, info.object)
})

const ICON_MAPPING = {
    marker: {x: 0, y: 0, width: 128, height: 128, mask: true}
};

const iconLayer = new deck.IconLayer({
    id: 'icon-layer',
    data: greenSpaces,
    pickable: true,
    // iconAtlas and iconMapping are required
    // getIcon: return a string
    iconAtlas: 'https://raw.githubusercontent.com/visgl/deck.gl-data/master/website/icon-atlas.png',
    iconMapping: ICON_MAPPING,
    getIcon: d => 'marker',
    sizeScale: 10,
    getPosition: d => d.position,
    getSize: d => d.size,
    getColor: d => [Math.sqrt(d.exits), 140, 0]
});

deckgl.setProps({
    layers: [
        scatterplotLayer,
        iconLayer
    ]
});

function setTooltip(x, y, object) {
    const tooltip = document.getElementById('tooltip');
    if (object) {
        tooltip.style.display = 'block';
        tooltip.style.left = (x - 120) + 'px';
        tooltip.style.top = (y + 40) + 'px';
        tooltip.innerHTML = object.name;
    } else {
        tooltip.style.display = 'none';
    }
}
