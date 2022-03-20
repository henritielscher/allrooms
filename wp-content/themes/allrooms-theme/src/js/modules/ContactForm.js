import * as Validator from "validatorjs";

class ContactForm {
    constructor() {
        if (document.querySelector(".contact-form")) {
            this.cookieLanguage;
            this.form = document.querySelector(".contact-form");
            this.nameInput = document.querySelector(".contact-form #name");
            this.emailInput = document.querySelector(".contact-form #email");
            this.subjectInput = document.querySelector(
                ".contact-form #subject"
            );
            this.messageInput = document.querySelector(
                ".contact-form #message"
            );
            this.privacyInput = document.querySelector(
                ".contact-form #privacy"
            );
            this.errorMessages = document.querySelectorAll(
                ".contact-form .error-msg"
            );
            this.submitBtn = document.querySelector(".contact-form-submit");
            this.events();
        }
    }

    // EVENTS
    events() {
        this.form.addEventListener("submit", (e) => {
            this.handleSubmit(e);
        });
        document.addEventListener(
            "DOMContentLoaded",
            this.getLanguage.bind(this)
        );
    }

    // METHODS
    handleSubmit(e) {
        e.preventDefault();

        this.errorMessages.forEach((msg) => (msg.innerText = ""));
        const formGroupInputs = this.form.querySelectorAll(
            ".form-group input, select, textarea"
        );

        formGroupInputs.forEach((input) => {
            input.style.borderColor = "#f29530ff";
            input.style.borderWidth = "1px";
        });

        // CREATE OBJECT FROM INPUTS
        let submitObject = {
            name: this.nameInput.value,
            email: this.emailInput.value,
            subject: this.subjectInput.value,
            message: this.messageInput.value,
            privacy: this.privacyInput.checked ? true : false,
        };

        // VALIDATION
        const clientValidation = this.handleClientValidation(submitObject);
        if (clientValidation === true) {
            this.handleServerValidation(submitObject);
        } else {
            this.handleClientErrors(clientValidation);
        }
    }

    handleClientValidation(obj) {
        Validator.useLang(this.cookieLanguage);
        let validation = new Validator(obj, validationRules);
        validation.setAttributeNames(validationAttributes);

        if (validation.fails()) {
            const errors = validation.errors.all();
            return errors;
        } else if (validation.passes()) {
            return true;
        }
    }

    handleServerValidation(obj) {
        let url = `${urlInfo.theme_url}/sendMail.php`;
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
                    this.cookieLanguage === "de"
                        ? "Server-Fehler"
                        : "Server Error";
                this.buttonDisplayError(errorMsg, true);
            });
    }

    handleClientErrors(err) {
        // loop over all error-messages and inputs
        for (const type in err) {
            let msg;
            if (typeof err[type] === Array) {
                msg = err[type].join(" ");
            } else {
                msg = err[type];
            }
            document.querySelector(`.${type}-error`).innerText = msg;
            const input = document.querySelector(`.contact-form #${type}`);
            input.style.borderColor = "red";
            input.style.borderWidth = "2px";
        }

        const errMsg =
            this.cookieLanguage === "de" ? "Falsche Eingabe" : "Invalid Input";

        this.buttonDisplayError(errMsg, false);
    }

    handleFormSuccess(msg) {
        this.form.reset();

        let previousBtnText = this.submitBtn.innerText;
        this.submitBtn.innerHTML = `<i class="fas fa-check" aria-hidden="true"></i> ${msg}`;
        this.form.classList.add("form-success");

        setTimeout(() => {
            this.form.classList.remove("form-success");
            this.submitBtn.innerHTML = previousBtnText;
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

const validationRules = {
    name: "required|string",
    email: "required|email",
    subject: "required|string|alpha",
    message: "required|string",
    privacy: "required|accepted",
};

const validationAttributes = {
    name: "Name",
    email: "E-Mail",
    message: "Message",
    privacy: "Privacy Policy",
};

export default ContactForm;
