import * as Validator from "validatorjs";

class Newsletter {
  constructor() {
    // LANGUAGE
    this.cookieLanguage;
    // CONTAINER ELEMENTS
    this.form = document.querySelector(".newsletter-form");
    this.container = document.querySelector(".modal-container");
    this.newsletter = document.querySelector(".footer-newsletter");
    // CONTROLS
    this.openButton = document.querySelector(".open-newsletter-modal");
    this.closeButton = document.querySelector(".close-modal");
    // INPUTS
    this.typeInputs = document.querySelectorAll(
      ".newsletter-form .newstype input"
    );
    this.emailInput = document.querySelector(".newsletter-form .email input");
    this.cityInput = document.querySelector(".newsletter-form .city input");
    this.companyInput = document.querySelector(
      ".newsletter-form .company input"
    );
    this.submitBtn = document.querySelector(".newsletter-form-submit");
    // FIELDS TO HIDE
    this.city = document.querySelector(".form-group.city");
    this.company = document.querySelector(".form-group.company");
    // ERRORS
    this.errorMessages = document.querySelectorAll(
      ".newsletter-form .error-msg"
    );
    this.events();
  }

  events() {
    this.container.addEventListener("click", (e) => {
      this.hideAdditionalFields(e);
    });
    this.openButton.addEventListener("click", this.openModal.bind(this));
    this.closeButton.addEventListener("click", this.closeModal.bind(this));
    this.form.addEventListener("submit", (e) => this.handleSubmit(e));
    document.addEventListener("DOMContentLoaded", this.getLanguage.bind(this));
  }

  // METHODS

  hideAdditionalFields(e) {
    const type = e.target.value;
    if (type === "privat") {
      this.company.style.display = "none";
      this.city.style.display = "flex";
    }
    if (type === "business") {
      this.city.style.display = "none";
      this.company.style.display = "flex";
    }
  }

  handleSubmit(e) {
    e.preventDefault();

    // RESET STYLING AND ERROR MESSAGES
    this.errorMessages.forEach((msg) => (msg.innerText = ""));
    const formGroupInputs = this.form.querySelectorAll(
      ".newsletter-form .form-group input"
    );

    formGroupInputs.forEach((input) => {
      input.style.borderColor = "#f29530ff";
      input.style.borderWidth = "1px";
    });

    // DETERMINE CHOSEN NEWSLETTER TYPE
    let activeNewsType;
    this.typeInputs.forEach((input) => {
      if (input.checked) {
        activeNewsType = input.value;
      }
    });

    // CREATE OBJECT FROM INPUTS
    let submitObject = {
      newstype: activeNewsType,
      email: this.emailInput.value,
    };

    if (activeNewsType === "privat") {
      submitObject.city = this.cityInput.value;
    } else if (activeNewsType === "business") {
      submitObject.company = this.companyInput.value;
    }

    // VALIDATION
    const clientValidation = this.handleClientValidation(submitObject);
    if (clientValidation === true) {
      this.handleServerValidation(submitObject);
    } else {
      // HANDLE ERRORS PASSING IN RETURNED ERROR OBJECT
      this.handleClientErrors(clientValidation);
    }
  }

  handleClientValidation(obj) {
    // SET DEFAULT LANGUAGE
    Validator.useLang(this.cookieLanguage);

    // SET VALIDATION RULES & ATTRIBUTES FOR VALIDATORJS
    let validationRules = {
      email: "required|email",
      newstype: "required|string|alpha",
    };

    if ("city" in obj) validationRules.city = "string";
    if ("company" in obj) validationRules.company = "string";

    const validationAttributes = {
      email: "E-Mail",
      newstype:
        this.cookieLanguage === "de" ? "Newsletter-Typ" : "newsletter type",
      city: this.cookieLanguage === "de" ? "Stadt" : "city",
      company: this.cookieLanguage === "de" ? "Unternehmen" : "company",
    };

    let validation = new Validator(obj, validationRules);
    validation.setAttributeNames(validationAttributes);

    // RETURN VALUE ERRORS WHEN FAIL OR TRUE WHEN PASSING VALIDATION
    if (validation.fails()) {
      const errors = validation.errors.all();
      return errors;
    } else if (validation.passes()) {
      return true;
    }
  }

  handleClientErrors(err) {
    // ITERATE AND SPREAD ERROR MESSAGES OVER SPECIFIC SPANS
    for (const type in err) {
      if (typeof err[type] === Array) {
        // FOR CLIENT ERROR OBJECT
        const msg = err[type].join(" ");
        document.querySelector(`.newsletter-form .${type}-error`).innerText =
          msg;
      } else {
        // FOR SERVER ERROR OBJECT
        const msg = err[type];
        document.querySelector(`.newsletter-form .${type}-error`).innerText =
          msg;
      }

      // CHANGE STYLE OF INPUTS WITH ERRORS
      const input = document.querySelector(`.newsletter-form .${type} input`);
      input.style.borderColor = "red";
      input.style.borderWidth = "2px";

      const errorMsg =
        this.cookieLanguage === "de" ? "Falsche Eingabe" : "Invalid Input";

      this.buttonDisplayError(errorMsg, false);
    }
  }

  handleServerValidation(obj) {
    let url = `${urlInfo.theme_url}/newsletterSub.php`;
    fetch(url, {
      method: "POST",
      body: JSON.stringify(obj),
      headers: { "Content-type": "application/json; charset=UTF-8" },
    })
      .then((res) => {
        return res.json();
      })
      .then((data) => {
        if (!data.success) {
          this.handleClientErrors(data.errors);
        }
        if (data.success) {
          this.handleFormSuccess(data.successMsg);
        }
      })
      .catch((err) => {
        let errorMsg =
          this.cookieLanguage === "de" ? "Server-Fehler" : "Server Error";
        this.buttonDisplayError(errorMsg, false);
      });
  }

  handleFormSuccess(msg) {
    let previousBtnText = this.submitBtn.innerText;
    this.submitBtn.innerHTML = `<i class="fas fa-check" aria-hidden="true"></i> ${msg}`;
    this.form.classList.add("form-success");

    setTimeout(() => {
      this.form.classList.remove("form-success");
      this.submitBtn.innerHTML = previousBtnText;
      this.closeModal();
      this.form.reset();
    }, 2000);
  }

  buttonDisplayError(msg, reset) {
    if (reset) this.form.reset();

    let previousBtnText = this.submitBtn.innerText;
    this.submitBtn.innerHTML = `<i class="fas fa-times" aria-hidden="true"></i> ${msg}`;
    this.form.classList.add("form-error");

    setTimeout(() => {
      this.form.classList.remove("form-error");
      this.submitBtn.innerHTML = previousBtnText;
    }, 2000);
  }

  // CONTROL METHODS
  openModal() {
    this.container.style.marginTop = `${
      document.querySelector(".nav").clientHeight
    }px`;
    this.newsletter.classList.toggle("active-modal");
    document.body.classList.add("no-overflow");
    // this.emailInput.focus();
  }

  closeModal() {
    document.body.classList.remove("no-overflow");
    this.newsletter.classList.toggle("active-modal");
  }

  // LANGUAGE SETTING
  getLanguage() {
    if (!document.cookie) {
      return "de";
    } else {
      this.cookieLanguage = document.cookie
        .split("; ")
        .find((row) => row.startsWith("lang="))
        .split("=")[1];
    }
  }
}

export default Newsletter;
