class Slider {
	constructor() {
		if (document.querySelector(".swiper-container")) {
			this.swipers = document.querySelectorAll(".swiper-container");
			this.events();
		}
	}

	// EVENTS
	events() {
		document.addEventListener("DOMContentLoaded", () => {
			this.initSwipers(this.swipers);
		});
	}

	// METHODS

	initSwipers(swipers) {
		swipers.forEach((swiper) => {
			if (swiper.classList.contains("swiper-news")) {
				const newsSwiper = new Swiper(".swiper-news", newsConfig);
				// FAILSAFE for showing one slide
				const foundIndexes = newsSwiper.slides.map(
					(s) => s.dataset.swiperSlideIndex
				);
				const numberOfSlides = new Set(foundIndexes);
				if (numberOfSlides.size === 1) {
					newsSwiper.autoplay.stop();
					newsSwiper.disable();
					newsSwiper.loopDestroy();
					newsSwiper.update();
				}
			}

			if (swiper.classList.contains("swiper-artists")) {
				const artistSwiper = new Swiper(
					".swiper-artists",
					landingArtistsConfig
				);
			}

			if (swiper.classList.contains("swiper-media")) {
				const singleArtistSwiper = new Swiper(
					".swiper-media",
					singleArtistsConfig
				);
			}
		});
	}
}

const newsConfig = {
	direction: "horizontal",
	loop: true,
	navigation: {
		nextEl: ".swiper-button-next.news",
		prevEl: ".swiper-button-prev.news",
	},
	slidesPerView: 1,
	// slidesPerGroup: 1,
	initialSlide: 0,
	loopAdditionalSlides: 4,
	speed: 800,
	preloadImages: true,
	breakpoints: {
		992: {
			spaceBetween: 24,
		},
	},
	updateOnWindowResize: true,
	resizeObserver: true,
	grabCursor: true,
	pagination: {
		el: ".nav-rects",
		type: "bullets",
		clickable: true,
	},
	autoplay: {
		delay: 4000,
		// delay: 500,
		disableOnInteraction: false,
	},
};

const landingArtistsConfig = {
	direction: "horizontal",
	loop: false,
	navigation: {
		nextEl: ".swiper-button-next.artists",
		prevEl: ".swiper-button-prev.artists",
	},
	speed: 600,
	grabCursor: true,
	slidesPerView: 1,
	initialSlide: 0,
	breakpoints: {
		450: {
			slidesPerView: 2,
			slidesPerGroup: 2,
			spaceBetween: 30,
		},
	},
};

const singleArtistsConfig = {
	direction: "horizontal",
	loop: true,
	navigation: {
		nextEl: ".swiper-button-next.media-artist",
		prevEl: ".swiper-button-prev.media-artist",
	},
	slidesPerView: 1,
	slidesPerGroup: 1,
	initialSlide: 0,
	// centeredSlides: true,
	loopAdditionalSlides: 1,
	speed: 800,
	preloadImages: true,
	updateOnWindowResize: true,
	resizeObserver: true,
	grabCursor: true,
	pagination: {
		el: ".media-pagination",
		type: "bullets",
		clickable: true,
	},
};

export default Slider;
