import LazyLoading from "./LazyLoading";

class ArtistFilter {
  constructor() {
    if (document.querySelector(".artists-container")) {
      this.fetchedArtists;
      this.allArtists = document.querySelector(".all-artists");
      this.artistGrid = document.querySelector(".artist-grid");
      this.filterTour = document.querySelector(".filter-btn");
      this.filterMode = document.querySelectorAll(".filter-mode");
      this.sortOnTour = false;
      this.isGrid = true;
      this.artistsResizeTimer;
      this.events();
    }
  }

  // EVENTS
  events() {
    document.addEventListener("DOMContentLoaded", this.fetchArtists.bind(this));
    window.addEventListener("resize", this.adjustResizeContent.bind(this));
    this.filterTour.addEventListener("click", this.filterByTour.bind(this));
    this.filterMode.forEach((mode) =>
      mode.addEventListener("click", () => {
        this.activateFilter(mode);
      })
    );
  }

  // METHODS

  fetchArtists() {
    let results;
    fetch(`${urlInfo.root_url}/wp-json/allrooms/artists`)
      .then((res) => res.json())
      .then((data) => {
        this.fetchedArtists = data;
        this.setGridContent(this.fetchedArtists);
      })
      .catch((e) => console.log(e));
  }

  filterByTour() {
    this.filterTour.parentElement.classList.toggle("filter-active");

    if (this.filterTour.parentElement.classList.contains("filter-active")) {
      // Tour Filter ON
      this.sortOnTour = true;
      const touringArtists = this.fetchedArtists.filter(
        (entry) => entry.isOnTour === true
      );

      if (this.isGrid) return this.setGridContent(touringArtists);
      if (!this.isGrid) return this.setListContent(touringArtists);
    } else {
      // Tour Filter OFF
      this.sortOnTour = false;
      const allArtists = this.fetchedArtists;

      if (this.isGrid) return this.setGridContent(allArtists);
      if (!this.isGrid) return this.setListContent(allArtists);
    }
  }

  activateFilter(mode) {
    this.filterMode.forEach((filter) =>
      filter.classList.remove("filter-active")
    );
    mode.classList.add("filter-active");

    let artistsMode = [];

    if (this.sortOnTour) {
      artistsMode = this.fetchedArtists.filter(
        (entry) => entry.isOnTour === true
      );
    } else {
      artistsMode = this.fetchedArtists;
    }

    if (mode.dataset.sort === "grid") {
      this.isGrid = true;
      return this.setGridContent(artistsMode);
    }
    if (mode.dataset.sort === "list") {
      this.isGrid = false;
      return this.setListContent(artistsMode);
    }
  }

  setGridContent(array) {
    const sortedArray = this.sortContentByName(array);

    const newArray = sortedArray
      .map((artist) => {
        return `
          <div class="artist-grid__item">
          ${artist.isOnTour ? "<div class='on-tour'>ON TOUR</div>" : ""}
        <h6>${artist.name}</h6>
        <a href="${artist.link}">
          <img
            src="${artist.lazyImage}" data-src="${artist.image}"
            alt="${artist.name} Bandfoto"
          />
        </a>
      </div>
          `;
      })
      .join("");

    this.artistGrid.innerHTML = newArray;
    let lazyArtists = new LazyLoading();
  }

  setListContent(array) {
    const sortedArray = this.sortContentByName(array);

    const newArray = sortedArray
      .map((artist) => {
        return `
            <li><a href="${artist.link}">${artist.name}</a>
            ${artist.isOnTour ? "<div class='on-tour'>ON TOUR</div>" : ""}
            </li>
            `;
      })
      .join("");

    this.artistGrid.innerHTML = `
          <ul class="artist-list">
          ${newArray}
          </ul>`;
  }

  sortContentByName(arr) {
    const newArray = arr
      .map((o) => [o.name.replace(/^The\s+/, ""), o])
      .sort(([a], [b]) => a.localeCompare(b))
      .map(([, o]) => o);

    return newArray;
  }

  adjustResizeContent() {
    clearTimeout(this.artistsResizeTimer);
    this.artistsResizeTimer = setTimeout(() => {
      if (window.innerWidth > 992 && !this.isGrid) {
        this.isGrid = true;
        let artists;
        if (this.sortOnTour) {
          artists = this.fetchedArtists.filter(
            (entry) => entry.isOnTour === true
          );
        } else {
          artists = this.fetchedArtists;
        }
        this.filterMode.forEach((filter) =>
          filter.classList.toggle("filter-active")
        );
        this.setGridContent(artists);
      }
    }, 100);
  }
}

export default ArtistFilter;
