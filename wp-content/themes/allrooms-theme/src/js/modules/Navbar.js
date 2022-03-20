class Navbar {
  constructor() {
    this.navbar = document.querySelector(".nav");
    this.languageSelector = document.querySelector(".navbar-lang-selector");
    this.languageChoices = document.querySelectorAll(".navbar-lang-choice");
    this.burger = document.querySelector(".navbar-burger");
    this.wrapper = document.querySelector(".main-wrapper");
    this.scrollTimer;
    this.events();
  }

  events() {
    window.addEventListener("scroll", this.navbarScroll.bind(this));
    this.languageChoices.forEach((choice) => {
      choice.addEventListener("click", this.clickLanguageChoice.bind(this));
    });
    this.burger.addEventListener("click", this.toggleBurger.bind(this));
    document.addEventListener("DOMContentLoaded", () => {
      // orange background for the front page
      if (document.querySelector(".landing-container")) {
        document.querySelector("body").style.backgroundColor = "#f29530ff";
      }
      this.wrapper.style.marginTop = `${this.navbar.clientHeight}px`;
    });
  }

  navbarScroll() {
    clearTimeout(this.scrollTimer);
    this.scrollTimer = setTimeout(() => {
      if (window.scrollY > this.navbar.clientHeight) {
        document.body.classList.add("scroll-nav");
      } else {
        document.body.classList.remove("scroll-nav");
      }
    }, 100);
  }

  clickLanguageChoice() {
    this.languageChoices.forEach((choice) =>
      choice.classList.toggle("active-lang")
    );
    this.languageSelector.parentElement.classList.toggle("toggle-lang");
  }

  toggleBurger() {
    this.burger.classList.toggle("burger-toggle");
    this.navbar.classList.toggle("navbar-mobile-active");
    document.body.classList.toggle("no-overflow");
  }
}

export default Navbar;
