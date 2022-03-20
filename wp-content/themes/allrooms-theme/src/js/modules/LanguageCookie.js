class LanguageCookie {
  constructor() {
    this.activeLanguage;
    this.langSelectors = document.querySelectorAll(".navbar-lang-choice");
    // this.getLanguage = getLanguage;
    this.events();
  }

  events() {
    document.addEventListener(
      "DOMContentLoaded",
      this.initiateCookie.bind(this)
    );
    this.langSelectors.forEach((language) => this.chooseLanguage(language));
  }

  getLanguage() {
    return document.cookie
      .split("; ")
      .find((row) => row.startsWith("lang="))
      .split("=")[1];
  }

  initiateCookie() {
    let language = !document.cookie ? "de" : this.getLanguage();
    let cookie = `lang=${language};max-age=2419200;path=/`;
    document.cookie = cookie;
    this.activeLanguage = this.getLanguage();
  }

  chooseLanguage(language) {
    language.addEventListener("click", (e) => {
      if (language.dataset.lang === this.activeLanguage) {
        return;
      } else {
        let cookie = `lang=${language.dataset.lang};max-age=2419200;path=/`;
        document.cookie = cookie;
        location.reload();
      }
    });
  }
}

export default LanguageCookie;
