const currentTask = process.env.npm_lifecycle_event;

const screens = {
  "xs": "414px",
  "sm": "576px",
  "md": "768px",
  "lg": "1024px",
  "xl": "1280px",
  "xxl": "1536px",
  "hd": "1920px",
};

let config = {
  darkMode: false, // or 'media' or 'class'
  theme: {
    screens,
    extend: {},
  },
  variants: {
    extend: {},
  },
  plugins: [],
};

if (currentTask == "build") {
  config.purge = {
    enabled: true,
    content: ["./**/*.php", "./src/**/*.js"],
  };
}

module.exports = config;
