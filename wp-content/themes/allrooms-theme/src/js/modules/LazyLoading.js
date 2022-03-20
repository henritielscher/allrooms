class LazyLoading {
  constructor() {
    this.images;
    this.bgs;
    this.imgObserver;
    this.bgObserver;
    this.imageOptions = {
      threshold: 0.33,
    };

    // SELECT ALL IMAGES
    if (document.querySelector("img[data-src]")) {
      this.images = document.querySelectorAll("img[data-src]");
      // INITIATE OBSERVER FOR IMAGES
      this.imgObserver = new IntersectionObserver((entries, observer) => {
        entries.forEach((entry) => {
          if (!entry.isIntersecting) return;
          const image = entry.target;
          const newURL = image.getAttribute("data-src");
          image.src = newURL;
          observer.unobserve(image);
        });
      }, this.imageOptions);
    }

    if (document.querySelector(".lazy")) {
      this.bgs = document.querySelectorAll(".lazy");
      // INITIATE OBSERVER FOR CSS BACKGROUND PROPERTIES
      this.bgObserver = new IntersectionObserver((entries, observer) => {
        entries.forEach((entry) => {
          if (!entry.isIntersecting) return;
          const bg = entry.target;
          bg.classList.remove("lazy");
          if (bg.classList.contains("artist-hero")) {
            const newURL = bg.getAttribute("data-src");
            bg.style.backgroundImage = `url(${newURL})`;
          }

          observer.unobserve(bg);
        });
      });
    }
    this.events();
  }

  // EVENTS
  events() {
    this.observe();
  }

  // METHODS
  observe() {
    if (this.images) {
      this.images.forEach((image) => {
        this.imgObserver.observe(image);
      });
    }

    if (this.bgs) {
      this.bgs.forEach((bg) => {
        this.bgObserver.observe(bg);
      });
    }
  }
}

export default LazyLoading;
