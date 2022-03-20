# Allrooms Agency Wordpress Theme :musical_note:

## Description

---

This is my second and definitely biggest commissioned work so far, which I built up entirely from scratch.

It's a fully responsive dynamic website for a Berlin Indie music artists agency which contains several features like a live show table, newsletter subscription, contact form, slider, lazy-loading, CMS for the client and more...

The website is not up and running in production to this day (March 20th 2022) since the database is not up to date yet.

## Additional Infos / Thoughts

---

Initially I was building this site with Express, NodeJS, EJS and MongoDB, which I discarded because of the CMS in Wordpress. At that point it seemed to be the better solution for integrating a nice Backend for the client. Nowadays I would probably go for a NextJS application with Strapi as CMS.

Since this build I found myself using classes for JavaScript functionality which I really like for separating everything into its own resusable modules. Since most of them are only used with one instance I did not implement Prototypes here.

For the build and development process I would rather go with Vite instead of Webpack.

## Features

---

-   Wordpress CMS and Theme Development
-   customized live show tables based on the JQuery DataTables library | [Link](https://datatables.net/)
-   customized sliders based on the SwiperJS library | [Link](https://swiperjs.com/)
-   contact and newsletter form with client validation through ValidatorJS | [Link](https://www.npmjs.com/package/validatorjs)
-   Webpack development and build configuration | [Link](https://webpack.js.org/)
-   lazy loading through the client built-in Intersection Observer API | [MDN-Link](https://developer.mozilla.org/en-US/docs/Web/API/Intersection_Observer_API)
-   two supported languages by a Cookie-Setup | [MDN-Link](https://developer.mozilla.org/en-US/docs/Web/API/Document/cookie)

## Project Explanation

---

The main focus is lying in the build of the Wordpress theme with all it's customized backend functionality. This is why the [functions.php](/wp-content/themes/allrooms-theme/functions.php) is the most important part to mention here.

Furthermore the various classes in the [modules Folder](/wp-content/themes/allrooms-theme/src/js/modules/) to handle the behaviour of the site are certainly a big chunk of the client-side functionality.
